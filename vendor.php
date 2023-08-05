<?php
require_once('./helper.php');
class Vendor
{
    var $helper;
    function __construct()
    {
        $this->helper = new Helper();
    }

    function create_new_vendor($data)
    {
        $this->helper->data = array(
            ':vendor_name'            =>    $this->helper->clean_data($data['vendor']),
            ':address'                =>    $this->helper->clean_data($data['address']),
            ':gst_number'             =>    $this->helper->clean_data($data['gstNumber']),
            ':pan_card'               =>    $this->helper->clean_data($data['panNumber']),
            ':mobile'                 =>    $this->helper->clean_data($data['mobile']),
            ':email'                  =>    $this->helper->clean_data($data['email']),
            ':created_by'             =>    @$_SESSION["admin_id"] || 1,
            ':date_update'             =>    date("Y-m-d h:i:sa"),
            ':date_create'             =>    date("Y-m-d h:i:sa")
        );
        $this->helper->query = "INSERT INTO vendor (vendor_name, address, gst_number, pan_card, mobile, email, created_by, date_update, date_create)  VALUES (:vendor_name,:address,:gst_number,:pan_card,:mobile,:email,:created_by,:date_update,:date_create)";
        return $this->helper->execute_query();
    }

    function get_vendor_list()
    {
        $this->helper->query = "SELECT vendor_name as vendor, address, gst_number as gstNumber, pan_card as panNumber, mobile, email, date_update, id FROM vendor " . $this->helper->getSortingQuery(['vendor_name', 'date_update']) . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT COUNT(*) as count FROM vendor";
        $total_Count = $this->helper->query_result();
        $output = array(
            "count" =>    (int)$total_Count[0]['count'],
            "rows"  =>    formatVendorOutput($total_rows),
        );
        echo json_encode($output);
    }
}

function formatVendorOutput($total_rows)
{
    @$i = 0;
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id" => ++$i,
            "vendor" => $row['vendor'],
            "dateUpdate" => $row['date_update'],
            "emailVendor" => $row['email'],
            "mobileVendor" => $row['mobile'],
            "addressVendor" => $row['address'],
            "gstNumberVendor" => $row['gstNumber'],
            "panNumberVendor" => $row['panNumber'],
        );
    }
    return $pages_array;
}
