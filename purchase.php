<?php
require_once('./helper.php');
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
            ':cateogry'                  =>    $this->helper->clean_data($data['cateogry']),
            ':invoice_date'              =>    $this->helper->clean_data($data['invoiceDate']),
            ':invoice_number'            =>    $this->helper->clean_data($data['invoiceNumber']),
            ':sold_by'                   =>    $this->helper->clean_data($data['soldBy']),
            ':gst_number'                =>    $this->helper->clean_data($data['gstNumber']),
            ':pan_number'                =>    $this->helper->clean_data($data['pancard']),
            ':product_name'              =>    $this->helper->clean_data($data['productName']),
            ':hsn_sac'                   =>    $this->helper->clean_data($data['hsn_sac']),
            ':quantity'                  =>    $this->helper->clean_data($data['quantity']),
            ':per_peice_price'           =>    $this->helper->clean_data($data['perPiecePrice']),
            ':transport_charges'         =>    $this->helper->clean_data($data['transportCharges']),
            ':tax_rate'                  =>    $this->helper->clean_data($data['taxrate']),
            ':tax_type'                  =>    $this->helper->clean_data($data['taxType']),
            ':igst'                      =>    $this->helper->clean_data($data['igst']),
            ':cgst'                      =>    $this->helper->clean_data($data['cgst']),
            ':sgst'                      =>    $this->helper->clean_data($data['sgst']),
            ':tax_amount'                =>    $this->helper->clean_data($data['taxAmount']),
            ':taxable_amount'            =>    $this->helper->clean_data($data['taxableAmount']),
            ':total_amount_after_tax'    =>    $this->helper->clean_data($data['amountAfterTax']),
            ':created_by'                =>    @$_SESSION["admin_id"] || 1,
            ':dateUpdate'                =>    date("Y-m-d h:i:sa")
        );

        $this->helper->query = "INSERT INTO addpurchase (cateogry, invoice_date, invoice_number, sold_by, gst_number, pan_number, product_name, hsn_sac, quantity, per_peice_price, transport_charges, tax_rate, tax_type, igst, cgst, sgst, tax_amount, taxable_amount, total_amount_after_tax, created_by, dateUpdate) VALUES (:cateogry,:invoice_date,:invoice_number,:sold_by,:gst_number,:pan_number,:product_name,:hsn_sac,:quantity,:per_peice_price,:transport_charges,:tax_rate,:tax_type,:igst,:cgst,:sgst,:tax_amount,:taxable_amount,:total_amount_after_tax,:created_by,:dateUpdate)";
        return $this->helper->execute_query();
    }
}
