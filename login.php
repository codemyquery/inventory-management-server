<?php




/* $helper->data = array(
			':email'	=>	$_POST['admin_email'],
			':mobile'	=>  $_POST['admin_mobile'],
			':password' =>  $_POST['admin_password']
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
		} */