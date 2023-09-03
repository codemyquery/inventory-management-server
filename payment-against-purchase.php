<?php
require_once('./helper.php');
class PaymentAgainstPurchase
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function get_payment_against_purchase($invoice_number){
        $this->helper->query = "SELECT * FROM payment_against_puchase WHERE invoice_number='$invoice_number'";
        $total_rows = $this->helper->query_result();
        return format_payment_against_purchase($total_rows);
    }
}

function format_payment_against_purchase($total_rows){
    @$i = 1;
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id"           => $i++,
            "amountPaid"   => $row['amount_paid'],
            "datePaid"     => $row['date_paid'],
            "dateCreated"  => $row['date_created'],          
        );
    }
    return $pages_array;
}
