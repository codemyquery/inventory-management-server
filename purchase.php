<?php
require_once('./helper.php');
require_once('./product.php');
require_once('./products-against-purchase.php');
require_once('./payment-against-purchase.php');
class Purchase
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_purchase_order($data)
    {
        try {
            $this->helper->connect->beginTransaction();
            $this->helper->data = array(
                ':sold_by'                   =>    $this->helper->clean_data($data['vendorId']),
                ':cateogry'                  =>    $this->helper->clean_data($data['cateogry']),
                ':invoice_date'              =>    $this->helper->clean_data($data['invoiceDate']),
                ':invoice_number'            =>    $this->helper->clean_data($data['invoiceNumber']),
                ':tax_type'                  =>    $this->helper->clean_data($data['taxType']),
                ':cgst'                      =>    $this->helper->clean_data($data['cgst']),
                ':sgst'                      =>    $this->helper->clean_data($data['sgst']),
                ':igst'                      =>    $this->helper->clean_data($data['igst']),
                ':tax_amount'                =>    $this->helper->clean_data($data['taxAmount']),
                ':taxable_amount'            =>    $this->helper->clean_data($data['taxableAmount']),
                ':total_amount_after_tax'    =>    $this->helper->clean_data($data['amountAfterTax']),
                ':transport_charges'         =>    $this->helper->clean_data($data['transportCharges']),
                ':payment_status'            =>    $this->helper->clean_data($data['paymentStatus']),
                ':amount_paid'               =>    $this->helper->clean_data($data['amountPaid']),
                ':created_by'                =>    @$_SESSION["admin_id"] || 1
            );
            $this->helper->query = "INSERT INTO purchase (
                sold_by, 
                cateogry, 
                invoice_date, 
                invoice_number, 
                tax_type, 
                cgst, 
                sgst, 
                igst, 
                tax_amount, 
                taxable_amount, 
                total_amount_after_tax, 
                transport_charges, 
                payment_status, 
                amount_paid, 
                created_by) 
            VALUES (
                :sold_by,
                :cateogry,
                :invoice_date,
                :invoice_number,
                :tax_type,
                :cgst,
                :sgst,
                :igst,
                :tax_amount,
                :taxable_amount,
                :total_amount_after_tax,
                :transport_charges,
                :payment_status,
                :amount_paid,
                :created_by)";
            if($this->helper->execute_query()){
                @$product = new Product($this->helper);
                for ($i=0; $i < count($data['products']); $i++) { 
                    if(!$product->create_new_product($data['products'][$i])){
                        throw new Exception('Some product insertion failed due to some constrain');
                    }
                }
                for ($i=0; $i < count($data['products']); $i++) {
                    @$productAgainstPurchase = new ProductAgainstPurchase($this->helper);
                    if(!$productAgainstPurchase->create_product_against_purchase($data['products'][$i], $data['invoiceNumber'])){
                        throw new Exception('Some products against purchase has not inserted');
                        break;
                    }
                }
                $this->helper->connect->commit();
            }else{
                throw new Exception('Unable to insert the record for invoice table');
            }
        } catch (\Throwable $th) {
            $this->helper->connect->rollBack();
            return false;
        }
        return true;
    }

    function get_purchase($invoiceNumber){
        $this->helper->data = array( ':invoiceNumber' => $this->helper->clean_data($invoiceNumber) );
        $this->helper->query = "SELECT * FROM purchase INNER JOIN vendor ON purchase.sold_by=vendor.vendor_id WHERE invoice_number= :invoiceNumber";
        $purchase = $this->helper->query_result()[0];
        $paymentAgainstPurchase = new PaymentAgainstPurchase($this->helper);
        $paymentHistory = $paymentAgainstPurchase->get_payment_against_purchase($purchase['invoice_number']);
        $productAgainstPurchase = new ProductAgainstPurchase($this->helper);
        $products = $productAgainstPurchase->get_productagainst_purchase_products_lists($purchase['invoice_number']);
        $formattedData = formatPurchase($purchase, $paymentHistory, $products);
        echo json_encode($formattedData);
    }

    function get_purchase_list()
    {
        $i = 0;
        $this->helper->query = "SELECT * FROM purchase INNER JOIN vendor ON purchase.sold_by=vendor.vendor_id"
        . $this->helper->getSortingQuery('purchase', ['date_updated'])
        . $this->helper->getPaginationQuery();

        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT * FROM purchase INNER JOIN vendor ON purchase.sold_by=vendor.vendor_id";
        $row_counts = $this->helper->total_row();
        $pages_array = [];
        foreach ($total_rows as $row) {
            $paymentAgainstPurchase = new PaymentAgainstPurchase($this->helper);
            $paymentHistory = $paymentAgainstPurchase->get_payment_against_purchase($row['invoice_number']);
            $productAgainstPurchase = new ProductAgainstPurchase($this->helper);
            $products = $productAgainstPurchase->get_productagainst_purchase_products_lists($row['invoice_number']);    
            $pages_array[] = formatPurchase($row, $products, $paymentHistory);
        }
        $output = array(
            "count" =>    $row_counts,
            "rows"  =>    $pages_array
        );
        echo json_encode($output);
    }
}

function formatPurchase($row, $paymentHistory, $products)
{
    return (object) array(
        "id"                    => $row['id'],
        "cateogry"              => $row['cateogry'],
        "invoiceDate"           => $row['invoice_date'],
        "invoiceNumber"         => $row['invoice_number'],
        "vendorName"            => $row['vendor_name'],
        "vendorId"              => $row['vendor_id'],
        "transportCharges"      => $row['transport_charges'],
        "taxType"               => $row['tax_type'],
        "taxAmount"             => $row['tax_amount'],
        "taxableAmount"         => $row['taxable_amount'],
        "amountAfterTax"        => $row['total_amount_after_tax'],
        "createdBy"             => $row['created_by'],
        "dateUpdated"           => $row['date_updated'],
        "paymentStatus"         => $row['payment_status'],
        "amountPaid"            => $row['amount_paid'],
        "gstNumber"             => $row['gst_number'],
        "panNumber"             => $row['pan_card'],
        "paymentHistory"        => @$paymentHistory,
        "products"              => @$products
    );
}
