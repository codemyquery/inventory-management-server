<?php
require_once('./helper.php');
require_once('./product.php');
class ProductAgainstInvoice
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_product_against_invoice($invoiceNumber,$products)
    {
        @$product = new Product($this->helper);
        $productName = $products['productName'];
        $dbProductData = $product->get_product($productName);
        $productToUpdate = array(
            'productName' => $this->helper->clean_data($products['productName']),
            'hsnSac' => $this->helper->clean_data($products['hsnSacCode']),
            'perPiecePrice' => $this->helper->clean_data($products['perPiecePrice']),
            'quantity' => $this->helper->clean_data($products['quantity']),
            'taxrate' => $this->helper->clean_data($products['cgst']+$products['igst']+$products['sgst']),
        );
        if ($dbProductData === null) {
            if (!$product->create_new_product($productToUpdate)) {
                throw new Exception('Some product insertion failed due to some constrains');
            }
        } else {
            if (!$product->update_product($productToUpdate, $dbProductData)) {
                throw new Exception('Some product updation failed due to some constrains');
            }
        }

        $this->helper->data = array(
            ':invoice_number'     =>    $this->helper->clean_data($invoiceNumber),
            ':product_name'       =>    $this->helper->clean_data($products['productName']),
            ':quantity'           =>    $this->helper->clean_data($products['quantity']),
            ':per_piece_price'    =>    $this->helper->clean_data($products['perPiecePrice']),
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
        return $this->helper->execute_query();
    }

    function get_product_list_against_invoice($itemID)
    {
        $this->helper->query = "SELECT 
        products_against_invoice.id, 
        products_against_invoice.invoice_number, 
        products.product_name, 
        products.hsn_sac, 
        products.quantity as quantity, 
        products_against_invoice.product_name, 
        products_against_invoice.quantity, 
        products_against_invoice.per_piece_price, 
        products_against_invoice.discount, 
        products_against_invoice.profit, 
        products_against_invoice.taxablevalue, 
        products_against_invoice.cgst, 
        products_against_invoice.sgst, 
        products_against_invoice.igst, 
        products_against_invoice.total 
        FROM products_against_invoice 
        INNER JOIN products ON products.product_name = products_against_invoice.product_name 
        WHERE invoice_number='$itemID'";

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
        'id'                => $row['id'],
        'invoiceNumber'     => $row['invoice_number'],
        'productName'       => $row['product_name'],
        'hsnSacCode'        => $row['hsn_sac'],
        'quantity'          => (float)$row['quantity'],
        'perPiecePrice'     => (float)$row['per_piece_price'],
        'discount'          => (float)$row['discount'],
        'profit'            => (float)$row['profit'],
        'taxableValue'      => (float)$row['taxablevalue'],
        'cgst'              => (float)$row['cgst'],
        'sgst'              => (float)$row['sgst'],
        'igst'              => (float)$row['igst'],
        'total'             => (float)$row['total']
    );;
}
