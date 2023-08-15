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
            ':vendor_id'              =>    $this->helper->clean_data($data['id']),
            ':vendor_name'            =>    $this->helper->clean_data($data['vendor']),
            ':address'                =>    $this->helper->clean_data($data['address']),
            ':gst_number'             =>    $this->helper->clean_data($data['gstNumber']),
            ':pan_card'               =>    $this->helper->clean_data($data['panNumber']),
            ':mobile'                 =>    $this->helper->clean_data($data['mobile']),
            ':email'                  =>    $this->helper->clean_data($data['email']),
            ':created_by'             =>    @$_SESSION["admin_id"] || 1
        );
        $this->helper->query = "INSERT INTO vendor (vendor_id, vendor_name, address, gst_number, pan_card, mobile, email, created_by)  VALUES (:vendor_id,:vendor_name,:address,:gst_number,:pan_card,:mobile,:email,:created_by)";
        return $this->helper->execute_query();
    }

    function get_vendor_list()
    {
        $this->helper->query = "SELECT *FROM vendor "
            . $this->helper->getSortingQuery(['vendor_name', 'date_update'])
            . $this->helper->getPaginationQuery();

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
            "vendorId" => $row['vendor_id'],
            "vendor" => $row['vendor_name'],
            "dateUpdate" => $row['date_updated'],
            "emailVendor" => $row['email'],
            "mobileVendor" => $row['mobile'],
            "addressVendor" => $row['address'],
            "gstNumberVendor" => $row['gst_number'],
            "panNumberVendor" => $row['pan_card'],
        );
    }
    return $pages_array;
}
