<?php
require_once('./helper.php');
require_once('./product.php');
class Purchase
{
    var $helper;
    function __construct()
    {
        $this->helper = new Helper();
    }

    function create_purchase_order($data)
    {
        $this->helper->data = array(
            ':sold_by'                   =>    $this->helper->clean_data($data['vendorId']),
            ':cateogry'                  =>    $this->helper->clean_data($data['cateogry']),
            ':invoice_date'              =>    $this->helper->clean_data($data['invoiceDate']),
            ':invoice_number'            =>    $this->helper->clean_data($data['invoiceNumber']),
            ':products'                  =>    json_encode($data['products']),
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
            ':credit_note'               =>    $this->helper->clean_data($data['creditNote']),
            ':credit_note_date'          =>    $this->helper->clean_data(($data['creditNoteDate'])),
            ':created_by'                =>    @$_SESSION["admin_id"] || 1
        );
        $this->helper->query = "INSERT INTO addpurchase (
            sold_by, 
            cateogry, 
            invoice_date, 
            invoice_number, 
            products, 
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
            credit_note, 
            credit_note_date, 
            created_by) 
        VALUES (
            :sold_by,
            :cateogry,
            :invoice_date,
            :invoice_number,
            :products,
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
            :credit_note,
            :credit_note_date,
            :created_by)";
        @$query_result = $this->helper->execute_query();
        if($query_result){
            @$product = new Product();
            for ($i=0; $i < count($data['products']); $i++) { 
                $query_result = $product->create_new_product($data['products'][$i]);
            }
        }
        return $query_result;
    }

    function get_purchase($invoiceNumber){
        $this->helper->data = array( ':invoiceNumber' => $this->helper->clean_data($invoiceNumber) );
        $this->helper->query = "SELECT * FROM addpurchase INNER JOIN vendor ON addpurchase.sold_by=vendor.vendor_id WHERE invoice_number= :invoiceNumber";
        $purchase = $this->helper->query_result();
        echo json_encode(formatPurchase($purchase)[0]);
    }

    function get_purchase_list()
    {
        $this->helper->getSortingQuery('addpurchase',[
            'dateUpdate',
            'credit_note',
            'credit_note_date',
            'invoice_date',
            'invoice_number',
            'sold_by',
            'gst_number'
        ]);
        $this->helper->query = "SELECT * FROM addpurchase INNER JOIN vendor ON addpurchase.sold_by=vendor.vendor_id"
        . $this->helper->getSortingQuery('addpurchase', ['date_updated'])
        . $this->helper->getPaginationQuery();

        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT * FROM addpurchase INNER JOIN vendor ON addpurchase.sold_by=vendor.vendor_id";
        $output = array(
            "count" =>    $this->helper->total_row(),
            "rows"  =>    formatPurchase($total_rows)
        );
        echo json_encode($output);
    }
}

function formatPurchase($total_rows)
{
    @$i = 0;
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id"                    => ++$i,
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
            "creditNote"            => $row['credit_note'],
            "creditNoteDate"        => $row['credit_note_date'],
            "createdBy"             => $row['created_by'],
            "dateUpdated"           => $row['date_updated'],
            "paymentStatus"         => $row['payment_status'],
            "amountPaid"            => $row['amount_paid'],
            "products"              => json_decode($row['products']),
            "gstNumber"             => $row['gst_number'],
            "panNumber"             => $row['pan_card']
        );
    }
    return $pages_array;
}
