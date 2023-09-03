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
            ':per_peice_price'        =>    $this->helper->clean_data(@$data['perPiecePrice']),
            ':quantity'               =>    $this->helper->clean_data(@$data['quantity']),
            ':taxrate'                =>    $this->helper->clean_data(@$data['taxrate']),
            ':created_by'             =>    @$_SESSION["admin_id"] || 1,
            ':date_created'           =>    $this->helper->get_current_datetimestamp()
        );
        $this->helper->query = "SELECT * FROM products WHERE product_name='" . $data['productName'] . "'";
        if ($this->helper->total_row() !== 0) {
            $perPiecePrice = 0;
            $product = $this->helper->query_result()[0];
            $totalQuantity = ($product['quantity'] + $data['quantity']);
            if($data['perPiecePrice'] > $product['per_peice_price']){
                $perPiecePrice = $data['perPiecePrice'];
            }else{
                $perPiecePrice = $product['per_peice_price'];
            }
            $this->helper->query = "UPDATE products set 
            quantity='" . $totalQuantity . "', 
            per_peice_price='" . $perPiecePrice . "',
            updated_by='" . @$_SESSION["admin_id"] || 1 . "',
            WHERE id='" . $product['id'] . "'";
            return $this->helper->execute_query();
        } else {
            $this->helper->query = "INSERT INTO products 
            (
                product_name, 
                hsn_sac, 
                per_peice_price, 
                quantity, 
                taxrate, 
                created_by, 
                date_created
            ) 
            VALUES (
                :product_name,
                :hsn_sac,
                :per_peice_price,
                :quantity,
                :taxrate,
                :created_by,
                :date_created
            )";
            return $this->helper->execute_query();
        }
    }

    function get_product_list()
    {
        $this->helper->query = "SELECT product_name id, product_name, hsn_sac, per_peice_price, quantity, created_by, date_updated FROM products "
            . $this->helper->getFilterQuery(['productName', 'hsnSac', 'dateUpdated'])
            . $this->helper->getSortingQuery('products',['product_name', 'date_update'])
            . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT product_name FROM products " . $this->helper->getFilterQuery(['productName', 'hsnSac', 'dateUpdated']);
        $output = array(
            "count" =>    (int)$this->helper->total_row(),
            "rows"  =>    formatProductOutput($total_rows),
        );
        echo json_encode($output);
    }
}

function formatProductOutput($total_rows)
{
    @$i = 0;
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id" => ++$i,
            "productName" => $row['product_name'],
            "hsnSac" => $row['hsn_sac'],
            "perPeicePrice" => $row['per_peice_price'],
            "quantity" => $row['quantity'],
            "dateUpdate" => $row['date_updated']
        );
    }
    return $pages_array;
}
