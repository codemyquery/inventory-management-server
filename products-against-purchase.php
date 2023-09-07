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
            ':csgt'                   =>    $this->helper->clean_data(@$data['cgst']),
            ':sgst'                   =>    $this->helper->clean_data(@$data['sgst']),
            ':igst'                   =>    $this->helper->clean_data(@$data['igst']),
            ':total_tax'              =>    $this->helper->clean_data(@$data['totalTax']),
            ':total'                  =>    $this->helper->clean_data(@$data['total']),
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
            credit_note_date,
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
            :csgt,
            :sgst,
            :igst,
            :total_tax,
            :total,
            :credit_note,
            :credit_note_date,
            :created_by,
            :date_created,
            :updated_by,
            :date_updated
        )";
        return $this->helper->execute_query();
    }

    function get_productagainst_purchase_products_lists($invoice_number){
        $this->helper->query = "SELECT * FROM products_against_purchase WHERE invoice_number='$invoice_number'";
        $total_rows = $this->helper->query_result();
        return format_product_against_purchase($total_rows);
    }
}

function format_product_against_purchase($total_rows){
    @$i = 1;
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id"               => $i++,
            "invoice_number"   => $row['invoice_number'],
            "productName"      => $row['product_name'],
            "perPiecePrice"    => $row['per_piece_price'],          
            "quantity"         => $row['quantity'],          
            "taxrate"          => $row['taxrate'],          
            "csgt"             => $row['csgt'],          
            "sgst"             => $row['sgst'],          
            "igst"             => $row['igst'],          
            "totalTax"         => $row['total_tax'],          
            "total"            => $row['total'],          
            "total"            => $row['credit_note'],          
            "creditNoteDate"   => $row['credit_note_date'],          
            "createdBy"        => $row['created_by'],          
            "dateCreated"      => $row['date_created'],          
            "updatedBy"        => $row['updated_by'],          
            "dateUpdated"      => $row['date_updated'],          
        );
    }
    return $pages_array;
}