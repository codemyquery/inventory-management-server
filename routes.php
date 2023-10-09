<?php
date_default_timezone_set('Asia/Kolkata');
require_once('./class.phpmailer.php');
require_once('./statusCode.php');
require_once('./helper.php');
require_once('./purchase.php');
require_once('./vendor.php');
require_once('./product.php');
require_once('./expense.php');
require_once('./products-against-purchase.php');
require_once('./Invoice.php');
$method = $_SERVER['REQUEST_METHOD'];
$helper = new Helper();
//*****************Allow cross origion******************** */
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS, DELETE");
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	exit(0);
}
//*****************Allow cross origion******************** */
try {
	@$bodyRawData = json_decode(file_get_contents('php://input'), true);
	@$page = $method === 'GET' ? $_GET['page'] : $bodyRawData['route']['page'];
	@$action = $method === 'GET' ? $_GET['actions'] : $bodyRawData['route']['actions'];
	@$itemID = $_GET['itemID'];
} catch (\Throwable $th) {
	http_response_code(BAD_REQUEST);
	echo `{ error: 'Invalid Data' }`;
}
if ($page === 'login') {
	if ($action === 'login') {
		echo json_encode(@$output);
	}
} else if ($page === 'purchase') {
	$result = null;
	$purchase = new Purchase($helper);
	if ($method === 'GET') {
		if ($action === 'getPurchaseList') {
			$result = $purchase->get_purchase_list();
		} else if ($action === 'getPurchase') {
			$result = $purchase->get_purchase($itemID);
		}
		echo json_encode($result);
	} else if ($method === 'POST') { // For Create request
		if ($action === 'addPurchase') {
			$result = $purchase->create_purchase_order($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === 'PUT'){
		if($action === 'updatePayments'){
			$result = $purchase->update_payment_history($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
} else if ($page === 'vendor') {
	$result = null;
	$vendor = new Vendor($helper);
	if ($method === 'POST') { // For Create request
		if ($action === 'addVendor') {
			$result = $vendor->create_new_vendor($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === "PUT") {
		if ($action === 'updateVendor') {
			$result = $vendor->update_vendor($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === "GET") { // For fetch requests
		if ($action === 'getVendorList') {
			$result = $vendor->get_vendor_list();
		} else if ($action === 'getVendor') {
			$result = $vendor->get_vendor($itemID);
		}
		echo json_encode($result);
	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
} else if ($page === 'product') {
	$result = null;
	$product = new Product($helper);
	if ($method === "GET") { // For fetch requests
		if ($action === 'getProductList') {
			$result = $product->get_product_list();
		}
		echo json_encode($result);
	} else if ($method === "POST") {
		if ($action === 'addProduct') {
			$result = $product->create_new_product($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === "PUT") {
	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
} else if ($page === 'expense') {
	$result = null;
	$expense = new Expense($helper);
	if ($method === "GET") { // For fetch requests
		if ($action === 'getExpenseList') {
			$expense->get_expense_list();
		} else if ($action === 'getExpense') {
			$expense->get_expense();
		}
	} else if ($method === "POST") {
		if ($action === 'addExpense') {
			$result = $expense->create_new_expense($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === "DELETE") {
		if ($action === 'deleteExpense') {
			$result = $expense->delete_expense($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
} else if($page === 'product-against-purchase') {
	$result = null;
	$productAgainstPurchase = new ProductAgainstPurchase($helper);
	if ($method === "PUT") {
		if ($action === 'addCreditNote') {
			$result = $productAgainstPurchase->add_credit_note($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	}
} else if($page === 'invoice') {
	$result = null;
	$invoice = new Invoice($helper);
	if ($method === "POST") {
		if ($action === 'addInvoice') {
			$result = $invoice->create_invoice_order($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if($method === 'GET'){
		if ($action === 'getInvoiceList') {
			$result = $invoice->get_invoice_list();
		} else if($action === 'getNextInvoiceNumber'){
			$result = $invoice->get_next_invoiceNumber();
		}
		echo json_encode($result);
	}
}
