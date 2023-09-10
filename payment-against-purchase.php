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

    function create_payment_history($invoiceNumber, $amountPaid, $paymentDate) {
        $this->helper->data = array(
            ':invoice_number'         =>    $this->helper->clean_data($invoiceNumber),
            ':amount_paid'            =>    $this->helper->clean_data($amountPaid),
            ':date_paid'              =>    $this->helper->clean_data($paymentDate),
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

    function get_payments_against_purchase($invoiceNumber){
        $this->helper->query = "SELECT * FROM payment_against_puchase WHERE invoice_number='$invoiceNumber'";
        $total_rows = $this->helper->query_result();
        $i = 1;
        $pages_array = [];
        foreach ($total_rows as $row) {
            $row['id'] = $i++;
            $pages_array[] = format_payment_against_purchase($row);
        }    
        return $pages_array;
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

function format_payment_against_purchase($row){
    return (object) array(
            "id"           => $row['id'],
            "amountPaid"   => (int)$row['amount_paid'],
            "datePaid"     => $row['date_paid'],
            "dateCreated"  => $row['date_created'],          
        );
}
