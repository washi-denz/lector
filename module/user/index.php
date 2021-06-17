<?php
	require dirname(__FILE__)."/function.php";

	class User extends Controller{

		var $idUsuario = 0;

		function __construct(){
			parent::__construct();
			$this->idUsuario = $this->session->get("id_user");
		}

		function error(){
			$this->content->put_title("ERROR");
			require URI_THEME."/section/404.php";
		}

		function json($modo){

			$fn = new fnUser($this);

			switch($modo){
				case 'salir':
					echo $fn->salir($_REQUEST);
					exit;
				break;
				case "login":
					echo $fn->login($_REQUEST);
					exit;
				break;
				case "registrar":
					echo $fn->registrar($_REQUEST);
					exit;
				break;

				default:
					echo json_encode(array("success"=>false,"notification"=>"Accion no definida."),JSON_PRETTY_PRINT);
					exit;
				break;
			}

		}
	}
?>