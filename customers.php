<?php
require_once('./helper.php');
require_once('./purchase.php');

class Customers
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_customer($customerData)
    {
        $this->helper->data = array(
            ':customer_id'          =>    $this->helper->clean_data($customerData['id']),
            ':name'                 =>    $this->helper->clean_data($customerData['name']),
            ':address'              =>    $this->helper->clean_data($customerData['phone']),
            ':phone'                =>    $this->helper->clean_data($customerData['state']),
            ':gstin'                =>    $this->helper->clean_data($customerData['gstin']),
            ':state'                =>    $this->helper->clean_data($customerData['state']),
            ':place_of_supply'      =>    $this->helper->clean_data($customerData['placeOfSupply'])
        );
        $this->helper->query = "INSERT INTO customers 
        (
            customer_id, 
            name, 
            address, 
            phone, 
            gstin,
            state,
            place_of_supply
        ) 
        VALUES (
            :customer_id,
            :name,
            :address,
            :phone,
            :gstin,
            :state,
            :place_of_supply
        )";
        return $this->helper->execute_query();
    }

    function get_customer($customer_id)
    {
        $this->helper->query = "SELECT * FROM customers WHERE customer_id='$customer_id'";
        $total_rows = $this->helper->query_result();
        return format_customers($total_rows[0]);
    }

    function get_customers_list()
    {
        $this->helper->query = "SELECT * FROM customers";
        $total_rows = $this->helper->query_result();
        $i = 1;
        $pages_array = [];
        foreach ($total_rows as $row) {
            $row['id'] = $i++;
            $pages_array[] = format_customers($row);
        }
        return $pages_array;
    }
}

function format_customers($row)
{
    return (object) array(
        "id"            => $row['customer_id'],
        "name"          => $row['name'],
        "address"       => $row['address'],
        "phone"         => $row['phone'],
        "gstin"         => $row['gstin'],
        "state"         => $row['state'],
        "placeOfSupply" => $row['place_of_supply'],
    );
}
