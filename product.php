<?php
require_once('./helper.php');
class Product
{
    var $helper;
    function __construct()
    {
        $this->helper = new Helper();
    }

    function create_new_product($data)
    {
        $this->helper->data = array(
            ':product_name'           =>    $this->helper->clean_data(@$data['productName']),
            ':hsn_sac'                =>    $this->helper->clean_data(@$data['hsnSac']),
            ':per_peice_price'        =>    $this->helper->clean_data(@$data['perPiecePrice']),
            ':quantity'               =>    $this->helper->clean_data(@$data['quantity']),
            ':created_by'             =>    @$_SESSION["admin_id"] || 1
        );
        $this->helper->query = "INSERT INTO products (product_name, hsn_sac, per_peice_price, quantity, created_by)  
        VALUES (:product_name,:hsn_sac,:per_peice_price,:quantity,:created_by)";
        return $this->helper->execute_query();
    }

    function get_product_list()
    {
        $this->helper->query = "SELECT product_name id, product_name, hsn_sac, per_peice_price, quantity, created_by, date_updated FROM products " 
            . $this->helper->getFilterQuery(['productName', 'hsnSac', 'dateUpdated']) 
            . $this->helper->getSortingQuery(['product_name', 'date_update']) 
            . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT COUNT(DISTINCT product_name) as count FROM products ". $this->helper->getFilterQuery(['productName', 'hsnSac', 'dateUpdated']);
        $total_Count = $this->helper->query_result();
        $output = array(
            "count" =>    (int)$total_Count[0]['count'],
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
