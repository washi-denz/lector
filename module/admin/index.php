<?php
	require dirname(__FILE__)."/function.php";

	class Admin extends Controller{
		var $idUsuario = 0;
		function __construct(){
			parent::__construct();
			$this->idUsuario = $this->session->get("id_user");
		}

		function index(){
			$this->gn->redireccion(URL."/init",$this->session->check_login());
			$this->admin();
		}

		function admin($view = "",$idex=""){
			
			//Iniciar sessión automáticamnete
			//$this->gn->sesion_automatica(true);

			if(ACTION == '' && $view == '')
			{
				$this->content->put_title(APP_NAME." | Crear lectura");
				$fn = new fnAdmin($this);
				require URI_THEME."/view/admin/admin.php";	
			}
			elseif(ACTION == 'admin' && $view == 'student')
			{
				$this->student();	
			}
			elseif(ACTION == 'admin' && $view == 'pdf')
			{
				$this->pdf();			
			}
			else{
				$this->error();
			}

		}
		
		function student(){

			$this->content->put_title(APP_NAME." | Alumnos");
			$fn = new fnAdmin($this);
			require URI_THEME."/view/admin/student.php";

		}

		function pdf(){
			$this->content->put_title(APP_NAME." | Subir PDFs");
			require URI_THEME."/view/admin/pdf.php";
		}

		function error(){
			$this->content->put_title("ERROR");
			require URI_THEME."/section/404.php";
		}

		function json($modo){

			$fn = new fnAdmin($this);

			switch($modo){

				//crear lectura
				case "modalCrearLectura":
					echo $fn->modalCrearLectura();
					exit;
				break;
				case "guardarCrearLectura":
					echo $fn->guardarCrearLectura($_REQUEST,$_FILES);
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