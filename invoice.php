<?php
require_once('./helper.php');
require_once('./product.php');
require_once('./products-against-purchase.php');
require_once('./payment-against-purchase.php');
require_once('./customers.php');
require_once('./products_against_invoice.php');
class Invoice
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_invoice_order($data)
    {
        $billingCustomerID = "";
        $shippingCustomerID = "";
        try {
            $this->helper->start();
            $customers = new Customers($this->helper);
            $billingDetails = $data['customerDetails']['billing'];
            $shipping = $data['customerDetails']['shipping'];

            if (!$customers->create_customer($shipping)) {
                throw new Exception('Failed to add shipping details of the customer');
            }

            if ($data['billingAddressSameAsShipping'] === false) {
                if (!$customers->create_customer($billingDetails)) {
                    throw new Exception('Failed to add billing details of the customer');
                }
            }else{
                $shippingCustomerID = $billingCustomerID = $shipping['id'];
            }

            $this->helper->data = array(
                ':invoice_type'                     =>    $this->helper->clean_data($data['invoiceType']),
                ':invoice_number'                   =>    $this->helper->clean_data($data['invoiceNo']),
                ':invoice_date'                     =>    $this->helper->clean_data($data['invoiceDate']),
                ':dispatch_mode'                    =>    $this->helper->clean_data($data['dispatchMode']),
                ':reverse_charge'                   =>    $this->helper->clean_data($data['reverseCharge']),
                ':vehcile_no'                       =>    $this->helper->clean_data($data['vehicleNo']),
                ':due_date'                         =>    $this->helper->clean_data($data['dueDate']),
                ':customer_po_date'                 =>    $this->helper->clean_data($data['customerPOANumberAndDate']),
                ':taxable_amount'                   =>    $this->helper->clean_data($data['taxableAmount']),
                ':cgst'                             =>    $this->helper->clean_data($data['cgst']),
                ':sgst'                             =>    $this->helper->clean_data($data['sgst']),
                ':igst'                             =>    $this->helper->clean_data($data['igst']),
                ':total_tax'                        =>    $this->helper->clean_data($data['totalAmountAfterTax']),
                ':total_amount_after_tax'           =>    $this->helper->clean_data($data['taxableAmount']),
                ':total_discount'                   =>    $this->helper->clean_data($data['totalDiscount']),
                ':shipping_address'                 =>    $shippingCustomerID,
                ':billing_address'                  =>    $billingCustomerID,
                ':created_by'                       =>    @$_SESSION["admin_id"] || 1,
                ':date_created'                     =>    $this->helper->get_current_datetimestamp(),
                ':updated_by'                       =>    NULL,
                ':date_updated'                     =>    $this->helper->get_current_datetimestamp(),
            );
            $this->helper->query = "INSERT INTO invoice (
                invoice_type,
                invoice_number,
                invoice_date,
                dispatch_mode,
                reverse_charge,
                vehcile_no,
                due_date,
                customer_po_date,
                taxable_amount,
                cgst,
                sgst,
                igst,
                total_tax,
                total_amount_after_tax,
                total_discount,
                shipping_address,
                billing_address,
                created_by,
                date_created,
                updated_by,
                date_updated)   
            VALUES (
                :invoice_type,                    
                :invoice_number,                  
                :invoice_date,                    
                :dispatch_mode,                   
                :reverse_charge,                  
                :vehcile_no,                      
                :due_date,                        
                :customer_po_date,                
                :taxable_amount,                  
                :cgst,                            
                :sgst,                            
                :igst,                            
                :total_tax,                       
                :total_amount_after_tax,          
                :total_discount,                  
                :shipping_address,                
                :billing_address,                 
                :created_by,                      
                :date_created,                    
                :updated_by,                      
                :date_updated)";
            if ($this->helper->execute_query()) {
                $productAgaintsInvoice = new ProductAgainstInvoice($this->helper);
                for ($i = 0; $i < count($data['products']); $i++) {
                    if (!$productAgaintsInvoice->create_product_against_invoice($data['invoiceNo'],$data['products'][$i])) {
                        throw new Exception('Some products against purchase has not inserted');
                    }
                }
                $this->helper->commit();
            } else {
                throw new Exception('Unable to insert the record for invoice table');
            }
        } catch (\Throwable $th) {
            $this->helper->rollBack();
            return false;
        }
        return true;
    }

    function get_invoice($invoiceNumber)
    {
        $this->helper->query = "SELECT * FROM invoice WHERE invoice_number='$invoiceNumber'";
        if($this->helper->total_row() === 1){
            return formatInvoice($this->helper->query_result()[0]);
        }else{
            return null;
        }
    }

    function get_next_invoiceNumber() {
        $next_invoice = "BISS/";
        $this->helper->query = "SELECT * FROM invoice ORDER BY date_created DESC LIMIT 1";
        $row = $this->helper->query_result()[0];
        $db_invoice_number = $row['invoice_number'];
        $serialNo = explode("/",$db_invoice_number)[2];
        $dbFinancialYear = explode("/",$db_invoice_number)[1];

        $currentMonth = date('m');
        $currentYear = date('Y');
        $currentDate = date('d');
        if($currentMonth > 3) {
            $next_invoice_financial_year = $currentYear. '-' .($currentYear+1) ;
        }else{
            $next_invoice_financial_year = ($currentYear-1). '-' .$currentYear ;
        }
        if($dbFinancialYear !== $next_invoice_financial_year && $currentMonth === 4 && $currentDate === 1){
            $serialNo = 1;
        }else{
            $serialNo = $serialNo+1;
        }
        return array('nextInvoice' =>  $next_invoice. $next_invoice_financial_year .'/'. $serialNo);
    }

    function get_invoice_list()
    {
        $this->helper->query = "SELECT *FROM invoice "
        . $this->helper->getSortingQuery('invoice', t_Invoice(@$_GET['orderBy']) )
        . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT COUNT(*) as count FROM invoice";
        $total_Count = $this->helper->query_result();
        foreach ($total_rows as $row) {
            $pages_array[] = formatInvoice($row);
        }
        return array(
            "count" =>    (int)$total_Count[0]['count'],
            "rows"  =>    $pages_array,
        );
    }
}

function formatInvoice($row)
{
    return (object) array(
        "id"                                => $row['id'],
        "invoiceType"                       => $row['invoice_type'],
        "invoiceNumber"                     => $row['invoice_number'],
        "invoiceDate"                       => $row['invoice_date'],
        "dispatchMode"                      => $row['dispatch_mode'],
        "reverseCharge"                     => $row['reverse_charge'],
        "vehcileNo"                         => (int)$row['vehcile_no'],
        "dueDate"                           => $row['due_date'],
        "customerPoDate"                    => (float)$row['customer_po_date'],
        "taxableAmount"                     => (float)$row['taxable_amount'],
        "cgst"                              => (float)$row['cgst'],
        "sgst"                              => $row['sgst'],
        "igst"                              => $row['igst'],
        "totalTax"                          => $row['total_tax'],
        "gstTotalAmountAfterTaxNumber"      => $row['total_amount_after_tax'],
        "totalDiscount"                     => $row['total_discount'],
        "shippingAddress"                   => $row['shipping_address'],
        "billingAddress"                    => $row['billing_address'],
        "createdBy"                         => $row['created_by'],
        "dateCreated"                      => $row['date_created'],
        "updatedBy"                     => $row['updated_by'],
        "dateUpdated"                     => $row['date_updated']
    );
}

function t_Invoice($fieldName)
{
    switch ($fieldName) {
        case 'dateUpdated':
            return 'date_updated';
        case 'invoiceDate':
            return 'invoice_date';
        case 'invoiceNumber':
            return 'invoice_number';
        default:
            return '';
    }
}
