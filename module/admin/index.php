<?php
	require dirname(__FILE__)."/function.php";

	class Admin extends Controller{
		var $idUsuario = 0;
		function __construct(){
			parent::__construct();
			$this->idUsuario = $this->session->get("id_user");
		}

		function index(){
			$this->list_test();
		}

		function list_test($view="",$idex=""){
			
			//Iniciar sessión automáticamnete
			$this->gn->sesion_automatica(true);

			$this->gn->redireccion(URL."/init",$this->gn->verifica_permiso('list_test',$this->session->get('type_user')));

			if(ACTION == 'list_test' && $view == '')
			{
				$this->content->put_title(APP_NAME." | Lista de test");
				$fn = new fnAdmin($this);
				require URI_THEME."/view/admin/list_test.php";	
			}
			elseif(ACTION == 'list_test' && $view == 'edit')
			{
				$this->edit($idex);	
			}
			elseif(ACTION == 'list_test' && $view == 'public')
			{
				$this->publics();			
			}
			elseif(ACTION == 'list_test' && $view == 'resolve')
			{
				if(isset($_GET["view"]) && $this->gn->verifica_valor($_GET["view"])){
					$this->resolve($_GET["view"]);
					//$this->admission($_GET["view"]);
				}else{
					$this->error();
				}
			}
			elseif(ACTION == 'list_test' && $view == 'auto'){
				if(isset($_GET["view"]) && $this->gn->verifica_valor($_GET["view"])){
					$this->resolve($_GET["view"]);				
				}else{
					$this->error();
				}
			}
			else{
				$this->error();
			}

		}

		function error(){
			$this->content->put_title("ERROR");
			require URI_THEME."/section/404.php";
		}

		function json($modo){

			$fn = new fnAdmin($this);

			switch($modo){
				//login y registro
				case "login":
					echo $fn->login($_REQUEST);
					exit();
				break;
				case "registrar":
					echo $fn->registrar($_REQUEST);
					exit;
				break;

				//crear examen

				default:
					echo json_encode(array("success"=>false,"notification"=>"Accion no definida."),JSON_PRETTY_PRINT);
					exit();
				break;
			}

		}
	}
?>