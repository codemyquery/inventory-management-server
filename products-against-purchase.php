<?php
require_once('./helper.php');
class ProductAgainstPurchase
{
    var $helper;
    function __construct($helper)
    {
        $this->helper = $helper;
    }

    function create_product_against_purchase($data, $invoiceNumber)
    {
        $this->helper->data = array(
            ':invoice_number'         =>    $this->helper->clean_data($invoiceNumber),
            ':product_name'           =>    $this->helper->clean_data(@$data['productName']),
            ':per_piece_price'        =>    $this->helper->clean_data(@$data['perPiecePrice']),
            ':quantity'               =>    $this->helper->clean_data(@$data['quantity']),
            ':taxrate'                =>    $this->helper->clean_data(@$data['taxrate']),
            ':csgt'                   =>    $this->helper->clean_data(@$data['cgst']),
            ':sgst'                   =>    $this->helper->clean_data(@$data['sgst']),
            ':igst'                   =>    $this->helper->clean_data(@$data['igst']),
            ':total_tax'              =>    $this->helper->clean_data(@$data['totalTax']),
            ':total'                  =>    $this->helper->clean_data(@$data['total']),
            ':credit_note'            =>    NULL,
            ':credit_note_date'       =>    NULL,
            ':created_by'             =>    @$_SESSION["admin_id"] || 1,
            ':date_created'           =>    $this->helper->get_current_datetimestamp(),
            ':updated_by'             =>    NULL,
            ':date_updated'           =>    NULL
        );
        $this->helper->query = "INSERT INTO products_against_purchase  
        (
            invoice_number,
            product_name,
            per_piece_price,
            quantity,
            taxrate,
            csgt,
            sgst,
            igst,
            total_tax,
            total,
            credit_note,
            credit_note_date,
            created_by,
            date_created,
            updated_by,
            date_updated
        )  
        VALUES 
        (
            :invoice_number,
            :product_name,
            :per_piece_price,
            :quantity,
            :taxrate,
            :csgt,
            :sgst,
            :igst,
            :total_tax,
            :total,
            :credit_note,
            :credit_note_date,
            :created_by,
            :date_created,
            :updated_by,
            :date_updated
        )";
        return $this->helper->execute_query();
    }

    function add_credit_note($data)
    {
        try {
            $this->helper->connect->beginTransaction();
            $creditNoteDate = $this->helper->clean_data(@$data['creditNoteDate']);
            $creditNoteDate = new DateTime($creditNoteDate);
            $creditNoteDate = $creditNoteDate->format('Y-m-d');
            $creditNoteNumber = $this->helper->clean_data(@$data['creditNoteNumber']);
            $invoiceNumber = $this->helper->clean_data(@$data['invoiceNumber']);
            $adminId = @$_SESSION["admin_id"] || 1;
            $products = $data['products'];
            for ($i = 0; $i < count($products); $i++) {
                $this->helper->query = "UPDATE products_against_purchase SET 
                credit_note='$creditNoteNumber',
                credit_note_date='$creditNoteDate'
                WHERE invoice_number='$invoiceNumber' AND product_name='$products[$i]'";
                if (!$this->helper->execute_query()) {
                    throw new Exception("Error in Processing credit note request", 1);
                }
            }
            $this->helper->connect->commit();
        } catch (\Throwable $th) {
            $this->helper->connect->rollBack();
            return false;
        }
        return true;
    }

    function get_productagainst_purchase_products_lists($invoice_number)
    {
        $this->helper->query = "SELECT 
        products_against_purchase.invoice_number ,
        products_against_purchase.product_name ,
        products_against_purchase.per_piece_price ,
        products_against_purchase.quantity ,
        products_against_purchase.taxrate ,
        products_against_purchase.csgt ,
        products_against_purchase.sgst ,
        products_against_purchase.igst ,
        products_against_purchase.total_tax ,
        products_against_purchase.total ,
        products_against_purchase.credit_note ,
        products_against_purchase.credit_note_date ,
        products_against_purchase.created_by ,
        products_against_purchase.date_created ,
        products_against_purchase.updated_by ,
        products_against_purchase.date_updated, 
        products.hsn_sac as hsnSac
        FROM products_against_purchase 
        INNER JOIN products ON products_against_purchase.product_name = products.product_name 
        WHERE invoice_number='$invoice_number'";
        $total_rows = $this->helper->query_result();
        return format_product_against_purchase($total_rows);
    }
}

function format_product_against_purchase($total_rows)
{
    @$i = 1;
    @$pages_array = [];
    foreach ($total_rows as $row) {
        $pages_array[] = (object) array(
            "id"               => $i++,
            "invoice_number"   => $row['invoice_number'],
            "productName"      => $row['product_name'],
            "hsnSac"           => $row['hsnSac'],
            "perPiecePrice"    => (float)$row['per_piece_price'],
            "quantity"         => (int)$row['quantity'],
            "taxrate"          => (float)$row['taxrate'],
            "cgst"             => (float)$row['csgt'],
            "sgst"             => (float)$row['sgst'],
            "igst"             => (float)$row['igst'],
            "totalTax"         => (float)$row['total_tax'],
            "total"            => (float)$row['total'],
            "creditNote"       => $row['credit_note'],
            "creditNoteDate"   => $row['credit_note_date'],
            "createdBy"        => $row['created_by'],
            "dateCreated"      => $row['date_created'],
            "updatedBy"        => $row['updated_by'],
            "dateUpdated"      => $row['date_updated'],
        );
    }
    return $pages_array;
}
