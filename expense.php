<?php

class Expense
{

    var $helper;
    function __construct()
    {
        $this->helper = new Helper();
    }

    function create_new_expense($data)
    {
        $this->helper->data = array(
            ':expense_id'           =>    $this->helper->clean_data(@$data['id']),
            ':date'                 =>    $this->helper->clean_data(@$data['expenseDate']),
            ':expense_purpose'      =>    $this->helper->clean_data(@$data['expensePurpose']),
            ':amount'               =>    $this->helper->clean_data(@$data['amount']),
            ':payment_mode'         =>    $this->helper->clean_data(@$data['paymentMode']),
            ':remarks'              =>    $this->helper->clean_data(@$data['remarks']),
            ':created_by'           =>    @$_SESSION["admin_id"] || 1
        );
        $this->helper->query = "INSERT INTO expense (expense_id, date, expense_purpose, amount, payment_mode, remarks, created_by)  
        VALUES (:expense_id, :date,:expense_purpose,:amount,:payment_mode,:remarks,:created_by)";
        return $this->helper->execute_query();
    }

    function get_expense()
    {
        $this->helper->query = "SELECT expense_id, date, expense_purpose, amount, payment_mode, remarks, created_by, date_updated FROM expense";
        $expense = $this->helper->query_result();
        echo json_encode(formatExpenseOutput($expense)[0]);
    }

    function delete_expense($data){
        $this->helper->data = $data;
        $this->helper->query = "DELETE FROM expense WHERE expense_id IN ".$this->helper->delete_query_from_array($data);
        return $this->helper->execute_query();
    }

    function get_expense_list()
    {
        $this->helper->query = "SELECT expense_id, date, expense_purpose, amount, payment_mode, remarks, created_by, date_updated FROM expense "
            . $this->helper->getFilterQuery(['expenseDate', 'expensePurpose', 'paymentMode', 'dateUpdated'])
            . $this->helper->getSortingQuery('expense', ['expenseDate', 'dateUpdated'])
            . $this->helper->getPaginationQuery();
        $total_rows = $this->helper->query_result();
        $this->helper->query = "SELECT id FROM expense "
            . $this->helper->getFilterQuery(['expenseDate', 'expensePurpose', 'paymentMode', 'dateUpdated']);
        $output = array(
            "count" =>    (int)$this->helper->total_row(),
            "rows"  =>    formatExpenseOutput($total_rows),
        );
        echo json_encode($output);
    }
}

function formatExpenseOutput($total_rows)
{
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id" => $row['expense_id'],
            "expenseDate" => $row['date'],
            "expensePurpose" => $row['expense_purpose'],
            "amount" => $row['amount'],
            "paymentMode" => $row['payment_mode'],
            "remarks" => $row['remarks'],
            "dateUpdated" => $row['date_updated'],
        );
    }
    return $pages_array;
}
