<?php
date_default_timezone_set('Asia/Kolkata');
require_once('./class.phpmailer.php');
require_once('./statusCode.php');
require_once('./helper.php');
require_once('./purchase.php');
require_once('./vendor.php');
require_once('./product.php');
$helper = new Helper;
$method = $_SERVER['REQUEST_METHOD'];

//*****************Allow cross origion******************** */
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
		header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	exit(0);
}
//*****************Allow cross origion******************** */

try {
	$bodyRawData = json_decode(file_get_contents('php://input'), true);
} catch (\Throwable $th) {
	http_response_code(BAD_REQUEST);
	echo `{ error: 'Invalid Data' }`;
}
if (@$bodyRawData['route']['page'] == 'login') {
	if ($bodyRawData['route']['action'] == 'login') {
		$helper->data = array(
			':email'	=>	@$_POST['admin_email'],
			':mobile'	=>  @$_POST['admin_mobile'],
			':password' =>  @$_POST['admin_password']
		);
		$helper->query = "SELECT * FROM admin WHERE email = :email OR mobile = :mobile AND password = :password";
		$total_row = $helper->total_row();
		if ($total_row > 0) {
			$result = $helper->query_result();
			foreach ($result as $row) {
				if ($_POST['password'] === $row['password']) {
					$_SESSION['admin_id'] = $row['id'];
					$output = array(
						'name'		=>	$row['name'],
						'email'		=>	$row['email'],
						'mobile'	=>	$row['mobile'],
						'roles'		=>	$row['roles']
					);
				} else {
					$output = array(
						'error'	=>	'Wrong Password'
					);
				}
			}
		} else {
			$output = array(
				'error'		=>	'Email/Mobile does not exists'
			);
		}
		echo json_encode($output);
	}
}

if (@$bodyRawData['route']['page'] == 'purchase') {
	@$result = null;
	@$purchase = new Purchase();
	if ($method === 'POST') { // For Create request
		if ($bodyRawData['route']['actions'] == 'addPurchase') {
			@$result = $purchase->create_purchase_order($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
} else if (@$bodyRawData['route']['page'] == 'vendor' || @$_GET['page'] === "vendor") {
	@$result = null;
	@$vendor = new Vendor();
	if ($method === 'POST') { // For Create request
		if ($bodyRawData['route']['actions'] == 'addVendor') {
			$result = $vendor->create_new_vendor($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === "GET") { // For fetch requests
		if (@$_GET['actions'] == 'getVendorList') {
			$vendor->get_vendor_list();
		}
	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
} else if (@$bodyRawData['route']['page'] == 'product' || @$_GET['page'] === "product") {
	@$result = null;
	@$product = new Product();
	if ($method === "GET") { // For fetch requests
		if (@$_GET['actions'] == 'getProductList') {
			$product->get_product_list();
		}
	} else if ($method === "POST") {
		if ($bodyRawData['route']['actions'] == 'addProduct') {
			$result = $product->create_new_product($bodyRawData['data']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
}
