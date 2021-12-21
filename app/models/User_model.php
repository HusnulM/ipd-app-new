<?php

class User_model{
	
	private $db;
	private $table = 't_user';

	public function __construct()
	{
		$this->db = new Database;
	}

	public function userList(){
		$this->db->query("SELECT a.*, b.department as deptname FROM t_user as a left join t_department as b on a.department = b.id");
		return $this->db->resultSet();
	}

	public function getUserbyid($username){
		$this->db->query("SELECT * FROM t_user WHERE username = '$username'");
		return $this->db->single();
	}

	public function register($data){

		$this->db->query('SELECT * FROM t_user WHERE username=:username');
		$this->db->bind('username',$data['username']);
		$this->db->execute();
		$result = $this->db->rowCount();

		if($result > 0 ){
			return 'X';
		}else{
			$currentDate = date('Y-m-d');
			$options = [
			    'cost' => 12,
			];
			$password = password_hash($data['password'], PASSWORD_BCRYPT, $options);

			$query = "INSERT INTO t_user (username, password, nama, email, department, createdby, createdon) 
					  VALUES(:username, :password, :nama, :email, :department, :createdby, :createdon)";
			$this->db->query($query);
			$this->db->bind('username',    $data['username']);
			$this->db->bind('password',    $password);
			$this->db->bind('nama',        $data['nama']);
			$this->db->bind('email',       $data['email']);
			$this->db->bind('department',  $data['department']);
			$this->db->bind('createdby',   $_SESSION['usr']['user']);
			$this->db->bind('createdon',   $currentDate);			
			$this->db->execute();

			return $this->db->rowCount();
		}
	}

	public function deleteData($id){

		$this->db->query("DELETE FROM t_user_role WHERE username=:username");
        $this->db->bind('username',$id);
        $this->db->execute();

		$this->db->query('DELETE FROM t_user WHERE username=:username');
		$this->db->bind('username',$id);
		$this->db->execute();

		return $this->db->rowCount();
	}

	public function updatePass(){
		$options = [
			'cost' => 12,
		];
		$password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);

		$this->db->query('UPDATE t_user SET password=:password WHERE username=:username');
		$this->db->bind('username',$_POST['username']);
		$this->db->bind('password',$password);
		$this->db->execute();

		return $this->db->rowCount();
	}

	public function updateuser($data){

		$options = [
			'cost' => 12,
		];

		if($_POST['password'] === ""){
			$password = $data['oldpass'];
		}else{
			$password = password_hash($data['password'], PASSWORD_BCRYPT, $options);
		}

		$this->db->query('UPDATE t_user SET password=:password, nama=:nama, email=:email, department=:department WHERE username=:username');
		$this->db->bind('username',   $data['username']);
		$this->db->bind('password',   $password);
		$this->db->bind('nama',       $data['nama']);
		$this->db->bind('email',      $data['email']);
		$this->db->bind('department', $data['department']);
		$this->db->execute();

		return $this->db->rowCount();
	}
}