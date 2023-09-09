<?php
require_once('./helper.php');
require_once('./purchase.php');

class PaymentAgainstPurchase
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_full_paid_payment_history($data) {
        $this->helper->data = array(
            ':invoice_number'         =>    $this->helper->clean_data($data['invoiceNumber']),
            ':amount_paid'            =>    $this->helper->clean_data($data['amountPaid']),
            ':date_paid'              =>    $this->helper->clean_data($data['paymentDate']),
            ':created_by'             =>    @$_SESSION["admin_id"] || 1,
            ':date_created'           =>    $this->helper->get_current_datetimestamp()
        );
        $this->helper->query = "INSERT INTO payment_against_puchase 
        (
            invoice_number, 
            amount_paid, 
            date_paid, 
            created_by, 
            date_created
        ) 
        VALUES (
            :invoice_number,
            :amount_paid,
            :date_paid,
            :created_by,
            :date_created
        )";
        return $this->helper->execute_query();
    }

    function create_partial_paid_payment_history($data){
        $purchase = new Purchase($this->helper);
        $purchaseRecord = $purchase->get_purchase($data['invoiceNumber']);
        $sum = $this->get_sum_of_payment_against_purchase($data['invoiceNumber']);
        if($sum == 0){
            $this->helper->data = array(
                ':invoice_number'         =>    $this->helper->clean_data($data['invoiceNumber']),
                ':amount_paid'            =>    $this->helper->clean_data($data['amountPaid']),
                ':date_paid'              =>    $this->helper->clean_data($data['paymentDate']),
                ':created_by'             =>    @$_SESSION["admin_id"] || 1,
                ':date_created'           =>    $this->helper->get_current_datetimestamp()
            );
        }else{
            $this->helper->data = array(
                ':invoice_number'         =>    $this->helper->clean_data($data['invoiceNumber']),
                ':amount_paid'            =>    $this->helper->clean_data($data['amountPaid']),
                ':date_paid'              =>    $this->helper->clean_data($data['paymentDate']),
                ':created_by'             =>    @$_SESSION["admin_id"] || 1,
                ':date_created'           =>    $this->helper->get_current_datetimestamp()
            );
        }
        $this->helper->query = "INSERT INTO payment_against_puchase 
        (
            invoice_number, 
            amount_paid, 
            date_paid, 
            created_by, 
            date_created
        ) 
        VALUES (
            :invoice_number,
            :amount_paid,
            :date_paid,
            :created_by,
            :date_created
        )";
        return $this->helper->execute_query();
    }

    function get_payments_against_purchase($invoice_number){
        $this->helper->query = "SELECT * FROM payment_against_puchase WHERE invoice_number='$invoice_number'";
        $total_rows = $this->helper->query_result();
        return format_payment_against_purchase($total_rows);
    }

    function get_sum_of_payment_against_purchase($invoice_number){
        $this->helper->query = "SELECT SUM(amount_paid) as total_payment FROM payment_against_puchase WHERE invoice_number='$invoice_number'";
        $total_rows = $this->helper->query_result();
        if($total_rows[0]['total_payment'] > 0){
            return $total_rows[0]['total_payment'];    
        }else{
            return 0;
        }
    }
}

function format_payment_against_purchase($total_rows){
    @$i = 1;
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id"           => $i++,
            "amountPaid"   => (int)$row['amount_paid'],
            "datePaid"     => $row['date_paid'],
            "dateCreated"  => $row['date_created'],          
        );
    }
    return $pages_array;
}
