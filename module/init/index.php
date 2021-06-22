<?php
	require dirname(__FILE__)."/function.php";

	class Init extends Controller{

		var $idUsuario=0;

		function __construct(){

			parent::__construct();

			if($this->session->check_login() && $this->session->check_login_expiry()){
				$this->idUsuario = $this->session->get("id_user");
			}else{
				$this->session->remove();
			}
		}

		function index(){
			$this->view();
		}

		function view($nombre=null,$uniqid=null){

			if($this->gn->verifica_valor($uniqid) && $this->gn->existe_uniqid($uniqid)){

				$this->content->put_title(APP_NAME);

				$fn = new fnInit($this);
				require URI_THEME."/view/init/init.php";	

			}else{
				$this->error();	
			}
		}

		function login(){
			$this->content->put_title("Ingresar | ".APP_NAME);
			require URI_THEME."/view/init/login.php";
		}
		function register(){
			$this->content->put_title("Registrarse | ".APP_NAME);
			require URI_THEME."/view/init/register.php";
		}

		function about(){
			$this->content->put_title("Acerca | ".APP_NAME);
			require URI_THEME."/view/init/about.php";
		}
		
		function error(){
			$this->content->put_title(APP_NAME."| ERROR");
			require URI_THEME."/section/404.php";
		}

		function json($modo){

			$fn = new fnInit($this);

			switch($modo){
				//init
				case "enviarRespuestas":
					echo $fn->enviarRespuestas($_REQUEST);
					exit;
				break;


				default:
					echo json_encode(array("success"=>false,"notification"=>"Accion no definida."),JSON_PRETTY_PRINT);
					exit();
				break;
			}

		}
	}
?>