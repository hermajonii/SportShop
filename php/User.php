<?php
	class User{
		private $idUser;
		private $firstName;
		private $lastName;
		private $address;
		private $phoneNumber;
		private $role;
		private $username;
		private $password;
		private $email;
		public function __construct($idUser,$firstName,$lastName,$address,$phoneNumber,$role,$username,$password,$email){
			$this->idUser=$idUser;
			$this->firstName=$firstName;
			$this->lastName=$lastName;
			$this->address=$address;
			$this->phoneNumber=$phoneNumber;
			$this->role=$role;
			$this->username=$username;
			$this->password=$password;
			$this->email=$email;
		}
		public function __set($name,$value){
			$this->$name=$value;
		}
		public function __get($name){
			if(isset($this->$name))
				return $this->$name;
			return "";
		}
	}
?>
