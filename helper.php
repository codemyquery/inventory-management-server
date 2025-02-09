<?php
require_once('./dbconfig.php');
class Helper
{
	var $host;
	var $username;
	var $password;
	var $database;
	var $connect;
	var $home_page;
	var $query;
	var $data;
	var $statement;
	var $filedata;
	var $printError;

	function __construct()
	{
		$this->printError = false;
		$this->host = HOST;
		$this->username = USER_NAME;
		$this->password = PASSWORD;
		$this->database = DATABASE_NAME;
		$this->home_page = API_HOME_PAGE;
		$this->connect = new PDO("mysql:host=$this->host; dbname=$this->database", "$this->username", "$this->password");
		session_start();
	}

	function print_error($print) {
		$this->printError = $print;
	}

	function execute_query()
	{
		if($this->printError){
			$this->connect->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		}
		$this->statement = $this->connect->prepare($this->query);
		$result = $this->statement->execute($this->data);
		$this->data = null;
		if($this->printError){
			print_r($this->query);
			print_r($this->connect->errorInfo());
		}
		return $result;
	}

	function total_row()
	{
		$this->execute_query();
		return $this->statement->rowCount();
	}

	function send_email($receiver_email, $subject, $body)
	{
		$mail = new PHPMailer;

		$mail->IsSMTP();

		$mail->Host = '';

		$mail->Port = '';

		$mail->SMTPAuth = true;

		$mail->Username = '';

		$mail->Password = '';

		$mail->SMTPSecure = 'tls';

		$mail->From = '';

		$mail->FromName = 'Ashutosh Singh';

		$mail->AddAddress($receiver_email, '');

		$mail->IsHTML(true);

		$mail->Subject = $subject;

		$mail->Body = $body;

		$mail->Send();

		$headers = 'From: Verification@beytech.in' . "\r\n" .
			'Reply-To: ' . $receiver_email . "\r\n" .
			'Content-type:text/html;charset=UTF-8' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		mail($receiver_email, $subject, $body, $headers);
	}

	function start() {
		$this->connect->beginTransaction();
	}

	function commit(){
		$this->connect->commit();
	}

	function rollback(){
		$this->connect->rollBack();
	}

	function redirect($page)
	{
		header('location:' . $page . '');
		exit;
	}

	function query_result()
	{
		$this->execute_query();
		return $this->statement->fetchAll();
	}

	function clean_data($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	function Upload_file()
	{
		if (!empty($this->filedata['name'])) {
			$extension = pathinfo($this->filedata['name'], PATHINFO_EXTENSION);

			$new_name = uniqid() . '.' . $extension;

			$_source_path = $this->filedata['tmp_name'];

			$target_path = 'upload/' . $new_name;

			move_uploaded_file($_source_path, $target_path);

			return $new_name;
		}
	}

	function getPaginationQuery()
	{
		$pageNumber = @$_GET['pageNumber'];
		$itemPerPage = @$_GET['itemPerPage'];
		$offset = (int)$itemPerPage * (int)$pageNumber;
		if (strlen($itemPerPage) > 0 && strlen($pageNumber) > 0) {
			return " LIMIT " . $itemPerPage . " OFFSET " . $offset;
		} else {
			return "";
		}
	}

	function getSortingQuery( $tableName, $fieldName )
	{
		// FieldName -> $fieldName
		$order = @$_GET['order']; // Sorting Order ASC/DESC
		if (strlen($fieldName) > 0 && strlen($order) > 0) {
			return " ORDER BY " .$tableName .".". $fieldName . " " . $order;
		} else {
			return "";
		}
	}

	function getFilterQuery($allowedFileds){
		@$query = "";
		@$Valid_Operators = ['$in', '$like', '$eq', '$neq'];
		$operator  = @$_GET['operator']; // search opeartor
		$value  = @$_GET['value']; // value to search
		$field  = @$_GET['field']; // field to search
		if (strlen(@$operator) > 0 && strlen(@$value) > 0 && in_array(@$operator, @$Valid_Operators) && in_array(@$field, @$allowedFileds)) {
			if($field === 'productName'){
				switch ($operator) {
					case '$in':
						$query = " WHERE product_name ";
						break;
					case '$like':
						$query = " WHERE product_name ";
						break;
					case '$eq':
						$query= " WHERE product_name='".$value."' ";
						break;
					case '$neq':
						$query = " WHERE product_name ";
						break;
				}
			}
		}
		return $query;
	}

	function get_current_datetimestamp(){
		return date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
	}

	function delete_query_from_array($arrayData){
		return " (".str_repeat("?,", count($arrayData) - 1)."?)";
	}

	/* 
	function admin_session_private()
	{
		if(!isset($_SESSION['admin_id']))
		{
			$this->redirect('login.php');
		}
	}

	function admin_session_public()
	{
		if(isset($_SESSION['admin_id']))
		{
			$this->redirect('index.php');
		}
	}

	function Is_exam_is_not_started($online_exam_id)
	{
		$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

		$exam_datetime = '';

		$this->query = "
		SELECT online_exam_datetime FROM online_exam_table 
		WHERE online_exam_id = '$online_exam_id'
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			$exam_datetime = $row['online_exam_datetime'];
		}

		if($exam_datetime > $current_datetime)
		{
			return true;
		}
		return false;
	}

	function Get_exam_question_limit($exam_id)
	{
		$this->query = "
		SELECT total_question FROM online_exam_table 
		WHERE online_exam_id = '$exam_id'
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['total_question'];
		}
	}

	function Get_exam_total_question($exam_id)
	{
		$this->query = "
		SELECT question_id FROM question_table 
		WHERE online_exam_id = '$exam_id'
		";

		return $this->total_row();
	}

	function Is_allowed_add_question($exam_id)
	{
		$exam_question_limit = $this->Get_exam_question_limit($exam_id);

		$exam_total_question = $this->Get_exam_total_question($exam_id);

		if($exam_total_question >= $exam_question_limit)
		{
			return false;
		}
		return true;
	}

	function execute_question_with_last_id()
	{
		$this->statement = $this->connect->prepare($this->query);

		$this->statement->execute($this->data);

		return $this->connect->lastInsertId();
	}

	function Get_exam_id($exam_code)
	{
		$this->query = "
		SELECT online_exam_id FROM online_exam_table 
		WHERE online_exam_code = '$exam_code'
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['online_exam_id'];
		}
	}

	function user_session_private()
	{
		if(!isset($_SESSION['user_id']))
		{
			$this->redirect('index.php');
		}
	}

	function user_session_public()
	{
		if(isset($_SESSION['user_id']))
		{
			$this->redirect('index.php');
		}
	}

	function Fill_exam_list()
	{
		$this->query = "
		SELECT online_exam_id, online_exam_title 
			FROM online_exam_table 
			WHERE exam_cateogry='".$_SESSION['exam_cateogry']."' AND exam_sub_cateogry='".$_SESSION['exam_sub_cateogry']."' AND (online_exam_status = 'Created' OR online_exam_status = 'Pending') 
			ORDER BY online_exam_title ASC
		";
		$result = $this->query_result();
		$output = '';
		foreach($result as $row)
		{
			$output .= '<option value="'.$row["online_exam_id"].'">'.$row["online_exam_title"].'</option>';
		}
		return $output;
	}
	function If_user_already_enroll_exam($exam_id, $user_id)
	{
		$this->query = "
		SELECT * FROM user_exam_enroll_table 
		WHERE exam_id = '$exam_id' 
		AND user_id = '$user_id'
		";
		if($this->total_row() > 0)
		{
			return true;
		}
		return false;
	}

	function Change_exam_status($user_id)
	{
		$this->query = "
		SELECT * FROM user_exam_enroll_table 
		INNER JOIN online_exam_table 
		ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
		WHERE user_exam_enroll_table.user_id = '".$user_id."'
		";

		$result = $this->query_result();

		$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

		foreach($result as $row)
		{
			$exam_start_time = $row["online_exam_datetime"];

			$duration = $row["online_exam_duration"] . ' minute';

			$exam_end_time = strtotime($exam_start_time . '+' . $duration);

			$exam_end_time = date('Y-m-d H:i:s', $exam_end_time);

			$view_exam = '';

			if($current_datetime >= $exam_start_time && $current_datetime <= $exam_end_time)
			{
				//exam started
				$this->data = array(
					':online_exam_status'	=>	'Started'
				);

				$this->query = "
				UPDATE online_exam_table 
				SET online_exam_status = :online_exam_status 
				WHERE online_exam_id = '".$row['online_exam_id']."'
				";

				$this->execute_query();
			}
			else
			{
				if($current_datetime > $exam_end_time)
				{
					//exam completed
					$this->data = array(
						':online_exam_status'	=>	'Completed'
					);

					$this->query = "
					UPDATE online_exam_table 
					SET online_exam_status = :online_exam_status 
					WHERE online_exam_id = '".$row['online_exam_id']."'
					";

					$this->execute_query();
				}					
			}
		}
	}

	function Get_user_question_option($question_id, $user_id)
	{
		$this->query = "
		SELECT user_answer_option FROM user_exam_question_answer 
		WHERE question_id = '".$question_id."' 
		AND user_id = '".$user_id."'
		";
		$result = $this->query_result();
		foreach($result as $row)
		{
			return $row["user_answer_option"];
		}
	}

	function Get_question_right_answer_mark($exam_id)
	{
		$this->query = "
		SELECT marks_per_right_answer FROM online_exam_table 
		WHERE online_exam_id = '".$exam_id."' 
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['marks_per_right_answer'];
		}
	}

	function Get_question_wrong_answer_mark($exam_id)
	{
		$this->query = "
		SELECT marks_per_wrong_answer FROM online_exam_table 
		WHERE online_exam_id = '".$exam_id."' 
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['marks_per_wrong_answer'];
		}
	}

	function Get_question_answer_option($question_id)
	{
		$this->query = "
		SELECT answer_option FROM question_table 
		WHERE question_id = '".$question_id."' 
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['answer_option'];
		}
	}

	function Get_exam_status($exam_id)
	{
		$this->query = "
		SELECT online_exam_status FROM online_exam_table 
		WHERE online_exam_id = '".$exam_id."' 
		";
		$result = $this->query_result();
		foreach($result as $row)
		{
			return $row["online_exam_status"];
		}
	}
	function Get_user_exam_status($exam_id, $user_id)
	{
		$this->query = "
		SELECT attendance_status 
		FROM user_exam_enroll_table 
		WHERE exam_id = '$exam_id' 
		AND user_id = '$user_id'
		";
		$result = $this->query_result();
		foreach($result as $row)
		{
			return $row["attendance_status"];
		}
	}
	function Get_user_exam_status_andBadActivity($exam_id, $user_id)
	{
		$this->query = "
		SELECT attendance_status,user_improper_activity 
		FROM user_exam_enroll_table 
		WHERE exam_id = '$exam_id' 
		AND user_id = '$user_id'
		";
		$result = $this->query_result();
		foreach($result as $row)
		{
			@$data->attendance_status = $row["attendance_status"];
			@$data->user_improper_activity = $row["user_improper_activity"];
		}
		return $data;
	} */
}
