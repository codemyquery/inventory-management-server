<?php
require_once('./helper.php');
class ProductAgainstPurchase
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_product_against_purchase($data, $invoiceNumber){
        $this->helper->data = array(
            ':invoice_number'         =>    $this->helper->clean_data($invoiceNumber),
            ':product_name'           =>    $this->helper->clean_data(@$data['productName']),
            ':per_piece_price'        =>    $this->helper->clean_data(@$data['perPiecePrice']),
            ':quantity'               =>    $this->helper->clean_data(@$data['quantity']),
            ':taxrate'                =>    $this->helper->clean_data(@$data['taxrate']),
            ':sgst'                   =>    $this->helper->clean_data(@$data['sgst']),
            ':cgst'                   =>    $this->helper->clean_data(@$data['cgst']),
            ':igst'                   =>    $this->helper->clean_data(@$data['igst']),
            ':total'                  =>    $this->helper->clean_data(@$data['total']),
            ':totalTax'               =>    $this->helper->clean_data(@$data['totalTax']),
            ':credit_note'            =>    NULL,
            ':credit_note_date'       =>    NULL,
            ':created_by'             =>    @$_SESSION["admin_id"] || 1,
            ':date_created'           =>    $this->helper->get_current_datetimestamp(),
            ':updated_by'             =>    NULL,
            ':date_updated'           =>    NULL
        );
        $this->helper->query = "INSERT INTO products_against_purchase  
        (
            invoice_number,
            product_name,
            per_piece_price,
            quantity,
            taxrate,
            csgt,
            sgst,
            igst,
            total_tax,
            total,
            credit_note,
            created_by,
            date_created,
            updated_by,
            date_updated
        )  
        VALUES 
        (
            :invoice_number,
            :product_name,
            :per_piece_price,
            :quantity,
            :taxrate,
            :sgst,
            :cgst,
            :igst,
            :total_tax,
            :total,
            :credit_note,
            :created_by,
            :date_created,
            :updated_by,
            :date_updated
        )";
        return $this->helper->execute_query();
    }
}
