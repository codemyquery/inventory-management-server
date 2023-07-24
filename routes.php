<?php
date_default_timezone_set('Asia/Kolkata');
require_once('./class.phpmailer.php');
require_once('./statusCode.php');
require_once('./helper.php');
require_once('./purchase.php');
require_once('./vendor.php');
$helper = new Helper;
$method = $_SERVER['REQUEST_METHOD'];
try {
	$data = json_decode(file_get_contents('php://input'), true);
} catch (\Throwable $th) {
	http_response_code(BAD_REQUEST);
	echo `{ error: 'Invalid Data' }`;
}

if (@$data['route']['page'] == 'login') {
	if ($data['route']['action'] == 'login') {
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

if (@$data['route']['page'] == 'purchase') {
	@$result = null;
	@$purchase = new Purchase();
	if ($method === 'POST') { // For Create request
		if ($data['route']['actions'] == 'addPurchase') {
			@$result = $purchase->create_purchase_order($data['body']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === 'PUT') { // For Update request

	} else if ($method === "GET") { // For fetch requests

	} else {
		http_response_code(METHOD_NOT_ALLOWED);
	}
} else if (@$data['route']['page'] == 'vendor' || @$_GET['page'] === "vendor") {
	@$result = null;
	@$vendor = new Vendor();
	if ($method === 'POST') { // For Create request
		if ($data['route']['actions'] == 'addVendor') {
			$result = $vendor->create_new_vendor($data['body']);
		}
		if (!$result) http_response_code(BAD_REQUEST);
		echo json_encode(array('status'    =>    $result));
	} else if ($method === 'PUT') { // For Update request

	} else if ($method === "GET") { // For fetch requests
		if (@$_GET['actions'] == 'getVendorList') {
			$vendor->get_vendor_list();
		}
	} else {
		//http_response_code(METHOD_NOT_ALLOWED);
	}
}

/* 
if(isset($_POST['page']))
{
	if($_POST['page'] == 'register')
	{
		if($_POST['action'] == 'check_email')
		{
			$helper->query = "
			SELECT * FROM admin_table 
			WHERE admin_email_address = '".trim($_POST["email"])."'
			";

			$total_row = $helper->total_row();

			if($total_row == 0)
			{
				$output = array(
					'success'	=>	true
				);

				echo json_encode($output);
			}
		}

		if($_POST['action'] == 'register')
		{
			$admin_verification_code = md5(rand());

			$receiver_email = $_POST['admin_email_address'];

			$helper->data = array(
				':admin_email_address'		=>	$receiver_email,
				':admin_password'			=>	password_hash($_POST['admin_password'], PASSWORD_DEFAULT),
				':admin_verfication_code'	=>	$admin_verification_code,
				':admin_type'				=>	'sub_master', 
				':admin_created_on'			=>	$current_datetime
			);

			$helper->query = "
			INSERT INTO admin_table 
			(admin_email_address, admin_password, admin_verfication_code, admin_type, admin_created_on) 
			VALUES 
			(:admin_email_address, :admin_password, :admin_verfication_code, :admin_type, :admin_created_on)
			";

			$helper->execute_query();

			$subject = 'Online Examination Registration Verification';

			$body = '
			<p>Thank you for registering.</p>
			<p>This is a verification E-Mail, please click the link to verify your eMail address by clicking this <a href="'.$helper->home_page.'verify_email.php?type=master&code='.$admin_verification_code.'" target="_blank"><b>link</b></a>.</p>
			<p>In case if you have any difficulty please eMail us.</p>
			<p>Thank you,</p>
			<p>BeyTech IT Online Examination System</p>
			';

			$helper->send_email($receiver_email, $subject, $body);

			$output = array(
				'success'	=>	true
			);

			echo json_encode($output);
		}
	}

	if($_POST['page'] == 'exam')
	{
		if($_POST['action'] == 'fetch')
		{
			$output = array();

			$helper->query = "
			SELECT * FROM online_exam_table 
			WHERE admin_id = '".$_SESSION["admin_id"]."' 
			AND (
			";

			if(isset($_POST['search']['value']))
			{
				$helper->query .= 'online_exam_title LIKE "%'.$_POST["search"]["value"].'%" ';
				
				$helper->query .= 'OR exam_cateogry LIKE "%'.$_POST["search"]["value"].'%" ';
				
				$helper->query .= 'OR exam_sub_cateogry LIKE "%'.$_POST["search"]["value"].'%" ';

				$helper->query .= 'OR online_exam_datetime LIKE "%'.$_POST["search"]["value"].'%" ';

				$helper->query .= 'OR online_exam_duration LIKE "%'.$_POST["search"]["value"].'%" ';

				$helper->query .= 'OR total_question LIKE "%'.$_POST["search"]["value"].'%" ';

				$helper->query .= 'OR marks_per_right_answer LIKE "%'.$_POST["search"]["value"].'%" ';

				$helper->query .= 'OR marks_per_wrong_answer LIKE "%'.$_POST["search"]["value"].'%" ';

				$helper->query .= 'OR online_exam_status LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			$helper->query .= ')';

			if(isset($_POST['order']))
			{
				$helper->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$helper->query .= 'ORDER BY online_exam_id DESC ';
			}

			$extra_query = '';

			if($_POST['length'] != -1)
			{
				$extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

			$filtered_rows = $helper->total_row();

			$helper->query .= $extra_query;

			$result = $helper->query_result();

			$helper->query = "
			SELECT * FROM online_exam_table 
			WHERE admin_id = '".$_SESSION["admin_id"]."'
			";

			$total_rows = $helper->total_row();

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = html_entity_decode($row['online_exam_title']);

				$sub_array[] = $row['exam_cateogry'];
				
				$sub_array[] = $row['exam_sub_cateogry'];
				
				$sub_array[] = $row['online_exam_datetime'];

				$sub_array[] = $row['online_exam_duration'] . ' Minute';

				$sub_array[] = $row['total_question'] . ' Question';

				$sub_array[] = $row['marks_per_right_answer'] . ' Mark';

				if($row['marks_per_wrong_answer'] == 0)
					$sub_array[] = $row['marks_per_wrong_answer'] . ' Mark';
				else					
					$sub_array[] = '-' . $row['marks_per_wrong_answer'] . ' Mark';


				$status = '';
				$edit_button = '';
				$delete_button = '';
				$question_button = '';
				$result_button = '';

				if($row['online_exam_status'] == 'Pending')
				{
					$status = '<span class="badge badge-warning">Pending</span>';
				}

				if($row['online_exam_status'] == 'Created')
				{
					$status = '<span class="badge badge-success">Created</span>';
				}

				if($row['online_exam_status'] == 'Started')
				{
					$status = '<span class="badge badge-primary">Started</span>';
				}

				if($row['online_exam_status'] == 'Completed')
				{
					$status = '<span class="badge badge-dark">Completed</span>';
				}

				if($helper->Is_exam_is_not_started($row["online_exam_id"]))
				{
					$edit_button = '
					<button type="button" name="edit" class="btn btn-primary btn-sm edit" id="'.$row['online_exam_id'].'">Edit</button>
					';

					$delete_button = '<button type="button" name="delete" class="btn btn-danger btn-sm delete" id="'.$row['online_exam_id'].'">Delete</button>';

				}
				else
				{
					$result_button = '<a href="exam_result.php?code='.$row["online_exam_code"].'" class="btn btn-dark btn-sm">Result</a>';
				}

				if($helper->Is_allowed_add_question($row['online_exam_id']))
				{
					$question_button = '
					<button type="button" name="add_question" class="btn btn-info btn-sm add_question" id="'.$row['online_exam_id'].'">Add Question</button>
					';
				}
				else
				{
					$question_button = '
					<a href="question.php?code='.$row['online_exam_code'].'" class="btn btn-warning btn-sm">View Question</a>
					';
				}

				$sub_array[] = $status;
				

				$sub_array[] ="";
				$sub_array[] = $question_button;
				$sub_array[] = $result_button;
				

				$sub_array[] = $edit_button . ' ' . $delete_button;

				$data[] = $sub_array;
			}

			$output = array(
				"draw"				=>	intval($_POST["draw"]),
				"recordsTotal"		=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"				=>	$data
			);

			echo json_encode($output);

		}

		if($_POST['action'] == 'Add')
		{
			$helper->data = array(
				':admin_id'				=>	$_SESSION['admin_id'],
				':online_exam_title'	=>	$helper->clean_data($_POST['online_exam_title']),
				':exam_cateogry'	=>	$_POST['exam_cateogry'],
				':exam_sub_cateogry'	=>	$_POST['exam_sub_cateogry'],
				':online_exam_datetime'	=>	$_POST['online_exam_datetime'] . ':00',
				':online_exam_duration'	=>	$_POST['online_exam_duration'],
				':total_question'		=>	$_POST['total_question'],
				':marks_per_right_answer'=>	$_POST['marks_per_right_answer'],
				':marks_per_wrong_answer'=>	$_POST['marks_per_wrong_answer'],
				':online_exam_created_on'=>	$current_datetime,
				':online_exam_status'	=>	'Pending',
				':online_exam_code'		=>	md5(rand())
			);

			$helper->query = "
			INSERT INTO online_exam_table 
			(admin_id, online_exam_title, exam_cateogry, exam_sub_cateogry, online_exam_datetime, online_exam_duration, total_question, marks_per_right_answer, marks_per_wrong_answer, online_exam_created_on, online_exam_status, online_exam_code) 
			VALUES (:admin_id, :online_exam_title, :exam_cateogry, :exam_sub_cateogry, :online_exam_datetime, :online_exam_duration, :total_question, :marks_per_right_answer, :marks_per_wrong_answer, :online_exam_created_on, :online_exam_status, :online_exam_code)
			";

			$helper->execute_query();

			$output = array(
				'success'	=>	'New Exam Details Added'
			);

			echo json_encode($output);
		}

		if($_POST['action'] == 'edit_fetch')
		{
			$helper->query = "
			SELECT * FROM online_exam_table 
			WHERE online_exam_id = '".$_POST["exam_id"]."'
			";

			$result = $helper->query_result();

			foreach($result as $row)
			{
				$output['online_exam_title'] = $row['online_exam_title'];
				
				$output['exam_cateogry'] = $row['exam_cateogry'];
				
				$output['exam_sub_cateogry'] = $row['exam_sub_cateogry'];

				$output['online_exam_datetime'] = $row['online_exam_datetime'];

				$output['online_exam_duration'] = $row['online_exam_duration'];

				$output['total_question'] = $row['total_question'];

				$output['marks_per_right_answer'] = $row['marks_per_right_answer'];

				$output['marks_per_wrong_answer'] = $row['marks_per_wrong_answer'];
			}

			echo json_encode($output);
		}

		if($_POST['action'] == 'Edit')
		{
			$helper->data = array(
				':online_exam_title'	=>	$_POST['online_exam_title'],
				':exam_cateogry'	=>	$_POST['exam_cateogry'],
				':exam_sub_cateogry'	=>	$_POST['exam_sub_cateogry'],
				':online_exam_datetime'	=>	$_POST['online_exam_datetime'] . ':00',
				':online_exam_duration'	=>	$_POST['online_exam_duration'],
				':total_question'		=>	$_POST['total_question'],
				':marks_per_right_answer'=>	$_POST['marks_per_right_answer'],
				':marks_per_wrong_answer'=>	$_POST['marks_per_wrong_answer'],
				':online_exam_id'		=>	$_POST['online_exam_id']
			);

			$helper->query = "
			UPDATE online_exam_table 
			SET online_exam_title = :online_exam_title, exam_cateogry = :exam_cateogry, exam_sub_cateogry = :exam_sub_cateogry, online_exam_datetime = :online_exam_datetime, online_exam_duration = :online_exam_duration, total_question = :total_question, marks_per_right_answer = :marks_per_right_answer, marks_per_wrong_answer = :marks_per_wrong_answer  
			WHERE online_exam_id = :online_exam_id
			";

			$helper->execute_query($helper->data);

			$output = array(
				'success'	=>	'Exam Details has been changed'
			);

			echo json_encode($output);
		}
		if($_POST['action'] == 'delete')
		{
			$helper->data = array(
				':online_exam_id'	=>	$_POST['exam_id']
			);

			$helper->query = "
			DELETE FROM online_exam_table 
			WHERE online_exam_id = :online_exam_id
			";

			$helper->execute_query();

			$output = array(
				'success'	=>	'Exam Details has been removed'
			);

			echo json_encode($output);
		}
	}

	if($_POST['page'] == 'question')
	{
		if($_POST['action'] == 'Add')
		{
			$helper->data = array(
				':online_exam_id'		=>	$_POST['online_exam_id'],
				':question_title'		=>	$helper->clean_data($_POST['question_title']),
				':answer_option'		=>	$_POST['answer_option']
			);

			$helper->query = "
			INSERT INTO question_table 
			(online_exam_id, question_title, answer_option) 
			VALUES (:online_exam_id, :question_title, :answer_option)
			";

			$question_id = $helper->execute_question_with_last_id($helper->data);

			for($count = 1; $count <= 4; $count++)
			{
				$helper->data = array(
					':question_id'		=>	$question_id,
					':option_number'	=>	$count,
					':option_title'		=>	$helper->clean_data($_POST['option_title_' . $count])
				);

				$helper->query = "
				INSERT INTO option_table 
				(question_id, option_number, option_title) 
				VALUES (:question_id, :option_number, :option_title)
				";

				$helper->execute_query($helper->data);
			}

			$output = array(
				'success'		=>	'Question Added'
			);

			echo json_encode($output);
		}

		if($_POST['action'] == 'fetch')
		{
			$output = array();
			$helper_id = '';
			if(isset($_POST['code']))
			{
				$helper_id = $helper->Get_exam_id($_POST['code']);
			}
			$helper->query = "
			SELECT * FROM question_table 
			WHERE online_exam_id = '".$helper_id."' 
			AND (
			";

			if(isset($_POST['search']['value']))
			{
				$helper->query .= 'question_title LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			$helper->query .= ')';

			if(isset($_POST["order"]))
			{
				$helper->query .= '
				ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' 
				';
			}
			else
			{
				$helper->query .= 'ORDER BY question_id ASC ';
			}

			$extra_query = '';

			if($_POST['length'] != -1)
			{
				$extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

			$filtered_rows = $helper->total_row();

			$helper->query .= $extra_query;

			$result = $helper->query_result();

			$helper->query = "
			SELECT * FROM question_table 
			WHERE online_exam_id = '".$helper_id."'
			";

			$total_rows = $helper->total_row();

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();

				$sub_array[] = $row['question_title'];

				$sub_array[] = 'Option ' . $row['answer_option'];

				$edit_button = '';
				$delete_button = '';

				if($helper->Is_exam_is_not_started($helper_id))
				{
					$edit_button = '<button type="button" name="edit" class="btn btn-primary btn-sm edit" id="'.$row['question_id'].'">Edit</button>';

					//$delete_button = '<button type="button" name="delete" class="btn btn-danger btn-sm delete" id="'.$row['question_id'].'">Delete</button>';
				}

				$sub_array[] = $edit_button . ' ' . $delete_button;

				$data[] = $sub_array;
			}

			$output = array(
				"draw"		=>	intval($_POST["draw"]),
				"recordsTotal"	=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"		=>	$data
			);

			echo json_encode($output);
		}

		if($_POST['action'] == 'edit_fetch')
		{
			$helper->query = "
			SELECT * FROM question_table 
			WHERE question_id = '".$_POST["question_id"]."'
			";

			$result = $helper->query_result();

			foreach($result as $row)
			{
				$output['question_title'] = html_entity_decode($row['question_title']);

				$output['answer_option'] = $row['answer_option'];

				for($count = 1; $count <= 4; $count++)
				{
					$helper->query = "
					SELECT option_title FROM option_table 
					WHERE question_id = '".$_POST["question_id"]."' 
					AND option_number = '".$count."'
					";

					$sub_result = $helper->query_result();

					foreach($sub_result as $sub_row)
					{
						$output["option_title_" . $count] = html_entity_decode($sub_row["option_title"]);
					}
				}
			}

			echo json_encode($output);
		}

		if($_POST['action'] == 'Edit')
		{
			$helper->data = array(
				':question_title'		=>	$_POST['question_title'],
				':answer_option'		=>	$_POST['answer_option'],
				':question_id'			=>	$_POST['question_id']
			);

			$helper->query = "
			UPDATE question_table 
			SET question_title = :question_title, answer_option = :answer_option 
			WHERE question_id = :question_id
			";

			$helper->execute_query();

			for($count = 1; $count <= 4; $count++)
			{
				$helper->data = array(
					':question_id'		=>	$_POST['question_id'],
					':option_number'	=>	$count,
					':option_title'		=>	$_POST['option_title_' . $count]
				);

				$helper->query = "
				UPDATE option_table 
				SET option_title = :option_title 
				WHERE question_id = :question_id 
				AND option_number = :option_number
				";
				$helper->execute_query();
			}

			$output = array(
				'success'	=>	'Question Edit'
			);

			echo json_encode($output);
		}
	}

	if($_POST['page'] == 'user')
	{
		if($_POST['action'] == 'fetch')
		{
			$output = array();

			$helper->query = "
			SELECT * FROM user_table 
			WHERE ";

			if(isset($_POST["search"]["value"]))
			{
			 	$helper->query .= 'user_email_address LIKE "%'.$_POST["search"]["value"].'%" ';
			 	$helper->query .= 'OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';
			 	$helper->query .= 'OR user_gender LIKE "%'.$_POST["search"]["value"].'%" ';
			 	$helper->query .= 'OR user_mobile_no LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			
			if(isset($_POST["order"]))
			{
				$helper->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$helper->query .= 'ORDER BY user_id DESC ';
			}

			$extra_query = '';

			if($_POST["length"] != -1)
			{
			 	$extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

			$filterd_rows = $helper->total_row();

			$helper->query .= $extra_query;

			$result = $helper->query_result();

			$helper->query = "
			SELECT * FROM user_table";

			$total_rows = $helper->total_row();

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = '<img src="../upload/'.$row["user_image"].'" class="img-thumbnail" width="75" />';
				$sub_array[] = $row["user_rollno"];
				$sub_array[] = $row["user_name"];
				$sub_array[] = $row["user_email_address"];
				$sub_array[] = $row["user_gender"];
				$sub_array[] = $row["user_mobile_no"];
				$sub_array[] = $row["exam_cateogry"];
				$sub_array[] = $row["exam_sub_cateogry"];
				$is_email_verified = '';
				$MakeVerifySelected = '';
				$MakeUnVerifySelected = '';
				if($row["user_email_verified"] == 'yes')
				{
					$is_email_verified = '<label class="badge badge-success">Yes</label>';
				}
				else
				{
					$is_email_verified = '<label class="badge badge-danger">No</label>';
				}
				if($row["is_user_verified"] == 'yes')
				{
					$is_user_verified = '<label class="badge badge-success">Yes</label>';
					$MakeVerifySelected = "selected";
				}
				else
				{
					$is_user_verified = '<label class="badge badge-danger">No</label>';	
					$MakeUnVerifySelected = "selected";
				}
							
				$sub_array[] = $is_email_verified;
				
			    $is_user_verified_div = '<div class="form-group">
                  <select data="'.$row["user_id"].'" email="'.$row["user_email_address"].'" username="'.$row["user_name"].'" onchange="UserAction(this)" name="user_verify" id="user_verify" class="form-control">
                      <option value="Verify" '.$MakeVerifySelected.'>Verify</option>
                      <option value="UnVerify" '.$MakeUnVerifySelected.'>UnVerify</option>
                      <option value="Delete">Delete</option>
                  </select> 
                  </div>';

				$sub_array[] = $is_user_verified;
				$sub_array[] = $is_user_verified_div;
				$data[] = $sub_array;
			}

			$output = array(
			 	"draw"    			=> 	intval($_POST["draw"]),
			 	"recordsTotal"  	=>  $total_rows,
			 	"recordsFiltered" 	=> 	$filterd_rows,
			 	"data"    			=> 	$data
			);
			echo json_encode($output);	
		}
		if($_POST['action'] == 'fetch_data')
		{
			$helper->query = "
			SELECT * FROM user_table 
			WHERE user_id = '".$_POST["user_id"]."'
			";
			$result = $helper->query_result();
			$output = '';
			foreach($result as $row)
			{
				$is_email_verified = '';
				if($row["user_email_verified"] == 'yes')
				{
					$is_email_verified = '<label class="badge badge-success">Email Verified</label>';
				}
				else
				{
					$is_email_verified = '<label class="badge badge-danger">Email Not Verified</label>';	
				}

				$output .= '
				<div class="row">
					<div class="col-md-12">
						<div align="center">
							<img src="../upload/'.$row["user_image"].'" class="img-thumbnail" width="200" />
						</div>
						<br />
						<table class="table table-bordered">
						    <tr>
								<th>Name</th>
								<td>'.$row["user_name"].'</td>
							</tr>
							<tr>
								<th>Course</th>
								<td>'.$row["exam_cateogry"].'</td>
							</tr>
							<tr>
								<th>Years</th>
								<td>'.$row["exam_sub_cateogry"].'</td>
							</tr>
							<tr>
								<th>Gender</th>
								<td>'.$row["user_gender"].'</td>
							</tr>
							<tr>
								<th>Address</th>
								<td>'.$row["user_rollno"].'</td>
							</tr>
							<tr>
								<th>Mobile No.</th>
								<td>'.$row["user_mobile_no"].'</td>
							</tr>
							<tr>
								<th>Email</th>
								<td>'.$row["user_email_address"].'</td>
							</tr>
							<tr>
								<th>Email Status</th>
								<td>'.$is_email_verified.'</td>
							</tr>
						</table>
					</div>
				</div>
				';
			}	
			echo $output;			
		}
		if($_POST['action'] == 'UserAction'){
		    
		    $is_user_verified_post_value = $_REQUEST["user_action"];
		    $receiver_email=$_REQUEST["email"];
		    
		    if($is_user_verified_post_value == "Verify"){
		        $is_user_verified_post_value = 'yes';
		        $type = "update";
		    }else if($is_user_verified_post_value == "UnVerify"){
		        $is_user_verified_post_value = 'no';
		        $type = "update";
		    }else if($is_user_verified_post_value == "Delete"){
		        $type = "delete";
		    }
		    
		    if($type == "update"){
            		$helper->data = array(
            			':is_user_verified_value'	=>	$is_user_verified_post_value
            		);
            
            		$helper->query = "
            		UPDATE user_table 
            		SET is_user_verified = :is_user_verified_value 
            		WHERE user_id = '".$_REQUEST['user_id']."'
            		";
		    }else if($type == "delete"){
            		$helper->query = "
            		DELETE FROM user_table
            		WHERE user_id = '".$_REQUEST['user_id']."'
            		";
		    }
    		$helper->execute_query();
    		
			$output = array(
				'success'	=>	true
			);
			
			$subject= 'Online Examination Registration Successfull';

			$body = '
			<p>Thank you for registering.</p>
			<p>Congratulations your email has been verified. Now you are eligible for the Examination, If someone didn\'t recieve the verification mail need not to worry
			contact the administration.</p>
			<p>In case if you have any difficulty please eMail us.</p>
			<p>Thank you,</p>
			<p>BeyTech IT Online Examination System</p>
			';

			$helper->send_email($receiver_email, $subject, $body);

			echo json_encode($output);		
		    
		}
	}

	if($_POST['page'] == 'exam_enroll')
	{
		if($_POST['action'] == 'fetch')
		{
			$output = array();

			$helper_id = $helper->Get_exam_id($_POST['code']);

			$helper->query = "
			SELECT * FROM user_exam_enroll_table 
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_enroll_table.user_id  
			WHERE user_exam_enroll_table.exam_id = '".$helper_id."' 
			AND (
			";

			if(isset($_POST['search']['value']))
			{
				$helper->query .= '
				user_table.user_name LIKE "%'.$_POST["search"]["value"].'%" 
				';
				$helper->query .= 'OR user_table.user_gender LIKE "%'.$_POST["search"]["value"].'%" ';
				$helper->query .= 'OR user_table.user_mobile_no LIKE "%'.$_POST["search"]["value"].'%" ';
				$helper->query .= 'OR user_table.user_email_verified LIKE "%'.$_POST["search"]["value"].'%" ';
			}
			$helper->query .= ') ';

			if(isset($_POST['order']))
			{
				$helper->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$helper->query .= 'ORDER BY user_exam_enroll_table.user_exam_enroll_id ASC ';
			}

			$extra_query = '';

			if($_POST['length'] != -1)
			{
				$extra_query = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

			$filtered_rows = $helper->total_row();

			$helper->query .= $extra_query;

			$result = $helper->query_result();

			$helper->query = "
			SELECT * FROM user_exam_enroll_table 
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_enroll_table.user_id  
			WHERE user_exam_enroll_table.exam_id = '".$helper_id."'
			";

			$total_rows = $helper->total_row();

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = "<img src='../upload/".$row["user_image"]."' class='img-thumbnail' width='75' />";
				$sub_array[] = $row["user_name"];
				$sub_array[] = $row["user_gender"];
				$sub_array[] = $row["user_mobile_no"];
				$is_email_verified = '';

				if($row['user_email_verified'] == 'yes')
				{
					$is_email_verified = '<label class="badge badge-success">Yes</label>';
				}
				else
				{
					$is_email_verified = '<label class="badge badge-danger">No</label>';
				}
				$sub_array[] = $is_email_verified;
				$result = '';

				if($helper->Get_exam_status($helper_id) == 'Completed')
				{
					$result = '<a href="user_exam_result.php?code='.$_POST['code'].'&id='.$row['user_id'].'" class="btn btn-info btn-sm" target="_blank">Result</a>';
				}
				$sub_array[] = $result;

				$data[] = $sub_array;
			}

			$output = array(
				"draw"				=>	intval($_POST["draw"]),
				"recordsTotal"		=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"				=>	$data
			);

			echo json_encode($output);
		}
	}

	if($_POST['page'] == 'exam_result')
	{
		if($_POST['action'] == 'fetch')
		{
			$output = array();
			$helper_id = $helper->Get_exam_id($_POST["code"]);
			$helper->query = "
			SELECT user_table.user_id, user_table.user_rollno, user_table.user_name, sum(user_exam_question_answer.marks) as total_mark  
			FROM user_exam_question_answer  
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_question_answer.user_id 
			WHERE user_exam_question_answer.exam_id = '$helper_id' 
			AND (
			";

			if(isset($_POST["search"]["value"]))
			{
				$helper->query .= 'user_table.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			$helper->query .= '
			) 
			GROUP BY user_exam_question_answer.user_id 
			';

			if(isset($_POST["order"]))
			{
				$helper->query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$helper->query .= 'ORDER BY total_mark DESC ';
			}

			$extra_query = '';

			if($_POST["length"] != -1)
			{
				$extra_query = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

			$filtered_rows = $helper->total_row();

			$helper->query .= $extra_query;

			$result = $helper->query_result();

			$helper->query = "
			SELECT 	user_table.user_rollno, user_table.user_name, sum(user_exam_question_answer.marks) as total_mark  
			FROM user_exam_question_answer  
			INNER JOIN user_table 
			ON user_table.user_id = user_exam_question_answer.user_id 
			WHERE user_exam_question_answer.exam_id = '$helper_id' 
			GROUP BY user_exam_question_answer.user_id 
			ORDER BY total_mark DESC
			";

			$total_rows = $helper->total_row();

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				
				$sub_array[] = $row["user_rollno"];
				$sub_array[] = $row["user_name"];
				$sub_array[] = $helper->Get_user_exam_status($helper_id, $row["user_id"]);
				$sub_array[] = $row["total_mark"];
				$data[] = $sub_array;
			}

			$output = array(
				"draw"				=>	intval($_POST["draw"]),
				"recordsTotal"		=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"				=>	$data
			);

			echo json_encode($output);
		}
	}
} */
