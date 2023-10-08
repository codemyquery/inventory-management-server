<?php
require_once('./helper.php');
class ProductAgainstInvoice
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_product_against_invoice($invoiceNumber,$products)
    {
        $this->helper->data = array(
            ':invoice_number'     =>    $this->helper->clean_data($invoiceNumber),
            ':product_name'       =>    $this->helper->clean_data($products['productName']),
            ':quantity'           =>    $this->helper->clean_data($products['quantity']),
            ':per_piece_price'    =>    $this->helper->clean_data($products['rate']),
            ':discount'           =>    $this->helper->clean_data($products['discount']),
            ':profit'             =>    $this->helper->clean_data($products['profit']),
            ':taxablevalue'       =>    $this->helper->clean_data($products['taxableValue']),
            ':cgst'               =>    $this->helper->clean_data($products['cgst']),
            ':sgst'               =>    $this->helper->clean_data($products['sgst']),
            ':igst'               =>    $this->helper->clean_data($products['igst']),
            ':total'              =>    $this->helper->clean_data($products['total'])
        );
        $this->helper->query = "INSERT INTO products_against_invoice 
        (
             invoice_number,
             product_name,
             quantity,
             per_piece_price,
             discount,
             profit,
             taxablevalue,
             cgst,
             sgst,
             igst,
             total) 
        VALUES (
            :invoice_number,
            :product_name,
            :quantity,
            :per_piece_price,
            :discount,
            :profit,
            :taxablevalue,
            :cgst,
            :sgst,
            :igst,
            :total)";
        return $this->helper->execute_query(true);
    }

    function get_product($productName){
        $this->helper->query = "SELECT * FROM products WHERE product_name='$productName'";
        if($this->helper->total_row() === 1){
            return formatProductsAgainstInvoice($this->helper->query_result()[0]);
        }else{
            return null;
        }
    }

    function get_product_list()
    {
        $this->helper->query = "SELECT * FROM products_against_invoice";
        $total_rows = $this->helper->query_result();
        $pages_array = [];
        $id = 1;
        foreach ($total_rows as $row) {
            $row['id'] = $id++;
            $pages_array[] = formatProductsAgainstInvoice($row);
        }
        return $pages_array;
    }
}

function formatProductsAgainstInvoice($row)
{
    return (object) array(
        'invoiceNumber'     => $row['invoice_number'],
        'productName'       => $row['product_name'],
        'quantity'          => $row['quantity'],
        'perPiecePrice'     => $row['per_piece_price'],
        'discount'          => $row['discount'],
        'profit'            => $row['profit'],
        'taxablevalue'      => $row['taxablevalue'],
        'cgst'              => $row['cgst'],
        'sgst'              => $row['sgst'],
        'igst'              => $row['igst'],
        'total'             => $row['total']
    );;
}
