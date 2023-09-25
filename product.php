<?php
require_once('./helper.php');
class Product
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_new_product($data)
    {
        $this->helper->data = array(
            ':product_name'           =>    $this->helper->clean_data(@$data['productName']),
            ':hsn_sac'                =>    $this->helper->clean_data(@$data['hsnSac']),
            ':per_piece_price'        =>    $this->helper->clean_data(@$data['perPiecePrice']),
            ':quantity'               =>    $this->helper->clean_data(@$data['quantity']),
            ':taxrate'                =>    $this->helper->clean_data(@$data['taxrate']),
            ':created_by'             =>    @$_SESSION["admin_id"] || 1,
            ':date_created'           =>    $this->helper->get_current_datetimestamp()
        );
        $this->helper->query = "INSERT INTO products 
        (
            product_name, 
            hsn_sac, 
            per_piece_price, 
            quantity, 
            taxrate, 
            created_by, 
            date_created
        ) 
        VALUES (
            :product_name,
            :hsn_sac,
            :per_piece_price,
            :quantity,
            :taxrate,
            :created_by,
            :date_created
        )";
        return $this->helper->execute_query();
    }

    function get_product($productName){
        $this->helper->query = "SELECT * FROM products WHERE product_name='$productName'";
        if($this->helper->total_row() === 1){
            return formatProductOutput($this->helper->query_result()[0]);
        }else{
            return null;
        }
    }

    function update_product($productToUpdate, $dbProductData){
        $productName = $productToUpdate['productName'];
        $perPiecePrice = 0;
        $dbProductData = $dbProductData !== null ? $dbProductData : $this->get_product($productName);
        if($dbProductData !== null){
            $productToUpdateQuantity =  $productToUpdate['quantity'];
            $dbProductDataQuantity =  $dbProductData->quantity;
            $totalQuantity = (float)$productToUpdateQuantity + (float)$dbProductDataQuantity;
            $userId = @$_SESSION["admin_id"] || 1;
            if($productToUpdate['perPiecePrice'] > $dbProductData->perPiecePrice){
                $perPiecePrice = $productToUpdate['perPiecePrice'];
            }else{
                $perPiecePrice = $dbProductData->perPiecePrice;
            }
            $this->helper->query = "UPDATE products SET quantity='$totalQuantity',per_piece_price='$perPiecePrice',updated_by='$userId' WHERE product_name='$productName'";
            return $this->helper->execute_query();
        }else{
            return $productName . " not found.";
        }
    }

    function get_product_list()
    {
        $this->helper->query = "SELECT product_name id, product_name, taxrate, hsn_sac, per_piece_price, quantity, created_by, date_updated FROM products "
            . $this->helper->getPaginationQuery()
            . $this->helper->getFilterQuery(['productName', 'hsnSac', 'dateUpdated']);
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT product_name FROM products " . $this->helper->getFilterQuery(['productName', 'hsnSac', 'dateUpdated']);
        $pages_array = [];
        $id = 1;
        foreach ($total_rows as $row) {
            $row['id'] = $id++;
            $pages_array[] = formatProductOutput($row);
        }
        return array(
            "count" =>    (int)$this->helper->total_row(),
            "rows"  =>    $pages_array,
        );
    }
}

function formatProductOutput($row)
{
    return (object) array(
        "id"            => $row['id'],
        "productName"   => $row['product_name'],
        "hsnSac"        => $row['hsn_sac'],
        "perPiecePrice" => $row['per_piece_price'],
        "quantity"      => $row['quantity'],
        "dateUpdate"    => $row['date_updated'],
        "taxrate"       => $row['taxrate']
    );;
}
