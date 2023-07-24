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
            ':dateUpdate'             =>    date("Y-m-d h:i:sa"),
            ':dateCreate'             =>    date("Y-m-d h:i:sa")
        );
        $this->helper->query = "INSERT INTO vendor (vendor_name, address, gst_number, pan_card, mobile, email, created_by, dateUpdate, dateCreate)  VALUES (:vendor_name,:address,:gst_number,:pan_card,:mobile,:email,:created_by,:dateUpdate,:dateCreate)";
        return $this->helper->execute_query();
    }

    function get_vendor_list()
    {
        $this->helper->getSortingQuery(['vendor_name', 'dateUpdate']) . "Ashutosh";
        $this->helper->query = "SELECT vendor.vendor_name as vendor, vendor.address, vendor.gst_number as gstNumber, vendor.pan_card as panNumber, mobile, email, dateUpdate, id 
        FROM vendor " . $this->helper->getSortingQuery(['vendor_name', 'dateUpdate']) . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();

        $this->helper->query = "SELECT COUNT(*) as count FROM vendor";
        $total_Count = $this->helper->query_result();
        $output = array(
            "rows"  =>    $total_rows,
            "count" =>    $total_Count[0]['count']
        );
        echo json_encode($output);
    }
}
