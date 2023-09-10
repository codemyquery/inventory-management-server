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
            $paymentStatus = $this->helper->clean_data($data['paymentStatus']);
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
                ':payment_status'            =>    $paymentStatus,
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
                :created_by)";
            if ($this->helper->execute_query()) {
                @$product = new Product($this->helper);
                for ($i = 0; $i < count($data['products']); $i++) {
                    $productToUpdate = $data['products'][$i];
                    $productName = $productToUpdate['productName'];
                    $dbProductData = $product->get_product($productName);
                    if ($dbProductData === null) {
                        if (!$product->create_new_product($productToUpdate)) {
                            throw new Exception('Some product insertion failed due to some constrains');
                        }
                    } else {
                        if (!$product->update_product($productToUpdate, $dbProductData)) {
                            throw new Exception('Some product updation failed due to some constrains');
                        }
                    }
                }
                for ($i = 0; $i < count($data['products']); $i++) {
                    @$productAgainstPurchase = new ProductAgainstPurchase($this->helper);
                    if (!$productAgainstPurchase->create_product_against_purchase($data['products'][$i], $data['invoiceNumber'])) {
                        throw new Exception('Some products against purchase has not inserted');
                    }
                }
                @$paymentAgainstPuchase = new PaymentAgainstPurchase($this->helper);
                if($data['paymentStatus'] != 'Full Credit'){
                    if ($data['amountPaid'] <= $data['amountAfterTax']) {
                        $paymentDate = $data['amountPaid'] == $data['amountAfterTax'] ? $data['paymentDate'] : $data['invoiceDate'];
                        if (!$paymentAgainstPuchase->create_payment_history($data['invoiceNumber'], $data['amountPaid'], $paymentDate)) {
                            throw new Exception('Purchase record insertion for full paid failed against purchase');
                        }
                    } else {
                        throw new Exception('Amount paid is greater than Amount after Tax');
                    }    
                }
                $this->helper->connect->commit();
            } else {
                throw new Exception('Unable to insert the record for invoice table');
            }
        } catch (\Throwable $th) {
            $this->helper->connect->rollBack();
            return false;
        }
        return true;
    }

    function update_payment_history($data)
    {
        try {
            $this->helper->connect->beginTransaction();
            $invoiceNumber = $data['invoiceNumber'];
            $purchaseRecord = $this->get_purchase($data['invoiceNumber']);
            $paymentAgainstPuchase = new PaymentAgainstPurchase($this->helper);
            $sum = $paymentAgainstPuchase->get_sum_of_payment_against_purchase($data['invoiceNumber']);
            if (($sum + $data['amountPaid']) <= $purchaseRecord->amountAfterTax) {
                if ($paymentAgainstPuchase->create_payment_history($invoiceNumber, $data['amountPaid'], $data['paymentDate'])) {
                    if ($sum + $data['amountPaid'] == $purchaseRecord->amountAfterTax) {
                        $this->helper->query = "UPDATE purchase SET payment_status='Full Paid' WHERE invoice_number='$invoiceNumber'";
                        if (!$this->helper->execute_query()) {
                            throw new Exception("Error Processing Request in updating payment status", 1);
                        }
                    } else if ($purchaseRecord->paymentStatus == "Full Credit") {
                        $this->helper->query = "UPDATE purchase SET payment_status='Partial Paid' WHERE invoice_number='$invoiceNumber'";
                        if (!$this->helper->execute_query()) {
                            throw new Exception("Error Processing Request in updating payment status", 1);
                        }
                    }
                } else {
                    throw new Exception("Error processing request for adding payment history", 1);
                }
            } else {
                throw new Exception("Error Processing Request for updating payment history", 1);
            }
            $this->helper->connect->commit();
        } catch (\Throwable $th) {
            //throw $th;
            $this->helper->connect->rollBack();
            return false;
        }
        return true;
    }

    function get_purchase($invoiceNumber)
    {
        $this->helper->data = array(':invoiceNumber' => $this->helper->clean_data($invoiceNumber));
        $this->helper->query = "SELECT 
        purchase.id,
        purchase.sold_by,
        purchase.cateogry,
        purchase.invoice_date,
        purchase.invoice_number,
        purchase.tax_type,
        purchase.cgst,sgst,
        purchase.igst,
        purchase.tax_amount,
        purchase.taxable_amount,
        purchase.total_amount_after_tax,
        purchase.transport_charges,
        purchase.payment_status,
        purchase.created_by,
        purchase.date_created,
        purchase.updated_by,
        purchase.date_updated,
        vendor.vendor_id ,
        vendor.vendor_name,
        vendor.gst_number,
        vendor.pan_card 
        FROM purchase INNER JOIN vendor ON purchase.sold_by=vendor.vendor_id WHERE invoice_number= :invoiceNumber";
        $purchase = $this->helper->query_result()[0];
        $paymentAgainstPurchase = new PaymentAgainstPurchase($this->helper);
        $paymentHistory = $paymentAgainstPurchase->get_payments_against_purchase($purchase['invoice_number']);
        $productAgainstPurchase = new ProductAgainstPurchase($this->helper);
        $products = $productAgainstPurchase->get_productagainst_purchase_products_lists($purchase['invoice_number']);
        return formatPurchase($purchase, $paymentHistory, $products);
    }

    function get_purchase_list()
    {
        $this->helper->query = "SELECT 
        purchase.id,
        purchase.sold_by,
        purchase.cateogry,
        purchase.invoice_date,
        purchase.invoice_number,
        purchase.tax_type,
        purchase.cgst,sgst,
        purchase.igst,
        purchase.tax_amount,
        purchase.taxable_amount,
        purchase.total_amount_after_tax,
        purchase.transport_charges,
        purchase.payment_status,
        purchase.created_by,
        purchase.date_created,
        purchase.updated_by,
        purchase.date_updated,
        vendor.vendor_id ,
        vendor.vendor_name,
        vendor.gst_number,
        vendor.pan_card 
        FROM purchase INNER JOIN vendor ON purchase.sold_by=vendor.vendor_id"
            . $this->helper->getSortingQuery('purchase', ['date_updated'])
            . $this->helper->getPaginationQuery();

        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT COUNT(id) as totalRows FROM purchase";
        $row_counts = $this->helper->query_result()[0];
        $pages_array = [];
        $i = 1;
        foreach ($total_rows as $row) {
            $row['id'] = $i++;
            $paymentAgainstPurchase = new PaymentAgainstPurchase($this->helper);
            $paymentHistory = $paymentAgainstPurchase->get_payments_against_purchase($row['invoice_number']);
            $productAgainstPurchase = new ProductAgainstPurchase($this->helper);
            $products = $productAgainstPurchase->get_productagainst_purchase_products_lists($row['invoice_number']);
            $pages_array[] = formatPurchase($row, $products, $paymentHistory);
        }
        return array(
            "count" =>    (int)$row_counts['totalRows'],
            "rows"  =>    $pages_array
        );
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
        "transportCharges"      => (int)$row['transport_charges'],
        "taxType"               => $row['tax_type'],
        "taxAmount"             => (float)$row['tax_amount'],
        "taxableAmount"         => (float)$row['taxable_amount'],
        "amountAfterTax"        => (float)$row['total_amount_after_tax'],
        "createdBy"             => $row['created_by'],
        "dateUpdated"           => $row['date_updated'],
        "paymentStatus"         => $row['payment_status'],
        "gstNumber"             => $row['gst_number'],
        "panNumber"             => $row['pan_card'],
        "paymentHistory"        => @$paymentHistory,
        "products"              => @$products
    );
}
