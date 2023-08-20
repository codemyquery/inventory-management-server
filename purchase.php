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
            ':hsn_sac'                   =>    $this->helper->clean_data($data['hsnSac']),
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
            ':credit_note_date'          =>    null,
            ':created_by'                =>    @$_SESSION["admin_id"] || 1,
        );
        $this->helper->query = "INSERT INTO addpurchase (cateogry, invoice_date, invoice_number, sold_by, gst_number, pan_number, product_name, hsn_sac, quantity, per_peice_price, transport_charges, tax_rate, tax_type, igst, cgst, sgst, tax_amount, taxable_amount, total_amount_after_tax, credit_note, credit_note_date, created_by, date_update) VALUES (:cateogry,:invoice_date,:invoice_number,:sold_by,:gst_number,:pan_number,:product_name,:hsn_sac,:quantity,:per_peice_price,:transport_charges,:tax_rate,:tax_type,:igst,:cgst,:sgst,:tax_amount,:taxable_amount,:total_amount_after_tax,:created_by)";
        return $this->helper->execute_query();
    }

    function get_purchase_list()
    {
        $this->helper->getSortingQuery([
            'dateUpdate',
            'credit_note',
            'credit_note_date',
            'invoice_date',
            'invoice_number',
            'sold_by',
            'gst_number'
        ]);
        $this->helper->query = "SELECT addpurchase, vendor.address, vendor.gst_number as gstNumber, vendor.pan_card as panNumber, mobile, email, dateUpdate, id FROM addpurchase " 
            . $this->helper->getSortingQuery(['vendor_name', 'dateUpdate']) 
            . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT COUNT(*) as count FROM vendor";
        $total_Count = $this->helper->query_result();
        $output = array(
            "count" =>    $total_Count[0]['count'],
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
            "soldBy"                => $row['sold_by'],
            "gstNumber"             => $row['gst_number'],
            "panNumber"             => $row['pan_number'],
            "productName"           => $row['product_name'],
            "hsnSac"                => $row['hsn_sac'],
            "quantity"              => $row['quantity'],
            "perPeicePrice"         => $row['per_peice_price'],
            "transportCharges"      => $row['transport_charges'],
            "taxRate"               => $row['tax_rate'],
            "taxType"               => $row['tax_type'],
            "igst"                  => $row['igst'],
            "cgst"                  => $row['cgst'],
            "sgst"                  => $row['sgst'],
            "hsnSac"                => $row['hsn_sac'],
            "taxAmount"             => $row['tax_amount'],
            "taxableAmount"         => $row['taxable_amount'],
            "totalAmountAfterTax"   => $row['total_amount_after_tax'],
            "creditNote"            => $row['credit_note'],
            "creditNoteDate"        => $row['credit_note_date'],
            "createdBy"             => $row['created_by'],
            "dateUpdated"           => $row['date_updated']
        );
    }
    return $pages_array;
}
