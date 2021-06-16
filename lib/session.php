<?php
	class session
	{
		function __construct(){
			if(!isset($_SESSION)){
				session_start();
			}
		}

		public function put($name, $data){
			$_SESSION["testink"][$name] = $data;
		}

		public function set($name, $data){
			$this->put($name, $data);
		}

		public function get($name){
			if(isset($_SESSION["testink"][$name])){
				return $_SESSION["testink"][$name];
			}
			return false;
		}

		public function remove($name = null){
			if($name!=null){
				unset($_SESSION["testink"][$name]);
			}
			else{
				$_SESSION["testink"] = array();
				unset($_SESSION["testink"]);
				//session_destroy();
			}
		}

		public function id_login(){
			$id_login = uniqid("login_testink");
			$expiry   = time()+6*3600;
			//$expiry   = time()+20;

			$this->put("success",true);
			$this->put("login_id",$id_login);
			$this->put("expiry",$expiry);
			$this->put("success_expiry",false);
			//setcookie("login_id_wady",$id_login, $expiry);
		}

		public function extend_login(){
			if( $this->get("success")!=false && $this->get("login_id")!=false && $this->get("expiry")!=false){
				if( $this->get("expiry") < time() ){
					$expiry = time()+3600;
					$this->put( "expiry", $expiry );
					setcookie( "login_id", $this->get("login_id"), $expiry, "/" );
				}
			}
		}

		public function check_login(){
			if( $this->get("success")!=false && $this->get("login_id")!=false){
				return true;
			}
			return false;
		}

		function check_login_expiry(){
			if($this->get("success")!=false){
				if(time() < $this->get("expiry")){+
					$this->put("success_expiry",false);
					return true;
				}else{
					$this->put("success_expiry",true);
					return false;
				}
			}
			return false;
		}

		public function put_login($data){ // crea session login
			if( is_array($data) ){
				foreach($data as $i=>$v){
					$this->put($i, $v);
				}
				$this->id_login();
			}
		}

		public function set_login($data){ // Modifica session de login
			if( is_array($data) ){
				foreach($data as $i=>$v){
					$this->set($i, $v);
				}
				$this->extend_login();
			}
		}

		public function type_user($user){
			if($this->get("type_user") == $user){
				return true;
			}
			return false;
		}

		public function type_users($users){
			if(is_array($users)){

				if(in_array($this->get("type_user"),$users)){
					return true;
				}
				return false;
			}
			return false;
		}

		function compare_user($idUsuario){
			if(isset($_SESSION["testink"]["idUser"])){
				if($_SESSION["testink"]["idUser"] == $idUsuario){
					return true;
				}
				return false;
			}
			return false;
		}

	}
?>
