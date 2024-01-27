<?php
require_once('./helper.php');
class Vendor
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
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
            ':created_by'             =>    @$_SESSION["admin_id"] || 1,
            ':updated_by'             =>    @$_SESSION["admin_id"] || 1
        );
        $this->helper->query = "INSERT INTO vendor (vendor_id, vendor_name, address, gst_number, pan_card, mobile, email, created_by, updated_by)  VALUES (:vendor_id,:vendor_name,:address,:gst_number,:pan_card,:mobile,:email,:created_by,:updated_by)";
        return $this->helper->execute_query();
    }

    function update_vendor($data){
        $this->helper->data = array(
            ':vendor_id'              =>    $this->helper->clean_data($data['id']),
            ':vendor_name'            =>    $this->helper->clean_data($data['vendor']),
            ':address'                =>    $this->helper->clean_data($data['address']),
            ':mobile'                 =>    $this->helper->clean_data($data['mobile']),
            ':email'                  =>    $this->helper->clean_data($data['email']),
            ':updated_by'             =>    @$_SESSION["admin_id"] || 1,
            ':date_updated'           =>    $this->helper->get_current_datetimestamp()
        );
        $this->helper->query = "UPDATE vendor SET 
        vendor_name = :vendor_name, 
        address = :address, 
        mobile = :mobile, 
        email = :email, 
        updated_by = :updated_by,
        date_updated = :date_updated
        WHERE vendor_id = :vendor_id";
        return $this->helper->execute_query();
    }

    function get_vendor($vendorId){
        $this->helper->query = "SELECT *FROM vendor WHERE vendor_id='$vendorId'";
        if($this->helper->total_row() === 0){
            return null;
        }
        $vendor = $this->helper->query_result()[0];
        return formatVendorOutput($vendor);
    }

    function get_vendor_list()
    {
        $pages_array = [];
        $this->helper->query = "SELECT *FROM vendor "
            . $this->helper->getSortingQuery('vendor', t_vendor(@$_GET['orderBy']) )
            . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT COUNT(*) as count FROM vendor";
        $total_Count = $this->helper->query_result();
        foreach ($total_rows as $row) {
            $pages_array[] = formatVendorOutput($row);
        }
        return array(
            "count" =>    (int)$total_Count[0]['count'],
            "rows"  =>    $pages_array,
        );
    }
}

function formatVendorOutput($row)
{
    return (object) array(
        "id" => $row['vendor_id'],
        "vendor" => $row['vendor_name'],
        "dateUpdated" => $row['date_updated'],
        "emailVendor" => $row['email'],
        "mobileVendor" => $row['mobile'],
        "addressVendor" => $row['address'],
        "gstNumberVendor" => $row['gst_number'],
        "panNumberVendor" => $row['pan_card'],
    );
}

function t_vendor($fieldName){
    switch ($fieldName) {
        case 'dateUpdated':
            return 'date_updated';
        case 'vendor':
            return 'vendor_name';
        default:
            return '';
    }
}