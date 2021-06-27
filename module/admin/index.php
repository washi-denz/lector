<?php
	require dirname(__FILE__)."/function.php";

	class Admin extends Controller{
		var $idUsuario = 0;
		function __construct(){
			parent::__construct();

			$this->gn->redireccion(URL."/init",$this->session->check_login());
			$this->idUsuario = $this->session->get("id_user");
			
		}

		function index(){
			//$this->gn->redireccion(URL."/init",$this->session->check_login());
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
			elseif(ACTION == 'admin' && $view == 'edit')
			{
				$this->edit($uniqid);			
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

		function edit($uniqid){
			//existe uniqid
			if($this->gn->existe_uniqid($uniqid)){

				$this->content->put_title(APP_NAME." | editar recurso PDF" );
				$fn = new fnAdmin($this);
				require URI_THEME."/view/admin/edit.php";

			}else{
				return $this->error();
			}
		}

		function deliver($uniqid=null){
			//existe uniqid
			if($this->gn->existe_uniqid($uniqid)){

				$this->content->put_title(APP_NAME." | Entregas" );
				$fn = new fnAdmin($this);
				require URI_THEME."/view/admin/deliver.php";

			}else{
				return $this->error();
			}
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

				case "verLectura":
					echo $fn->verLectura($_REQUEST);
					exit;
				break;

				case "editarLectura":
					echo $fn->editarLectura($_REQUEST);
					exit;
				break;

				case "modalCompartir":
					echo $fn->modalCompartir($_REQUEST);
					exit;
				break;

				case "listaEntregar":
					echo $fn->listaEntregar($_REQUEST);
					exit;
				break;

				case "modalVerRespuestaEntrega":
					echo $fn->modalVerRespuestaEntrega($_REQUEST);
					exit;
				break;

				//estudiantes
				case "modalAgregarAlumno":
					echo $fn->modalAgregarAlumno();
					exit;
				break;
				
				case "guardarAgregarAlumno":
					echo $fn->guardarAgregarAlumno($_REQUEST);
					exit;
				break;

				//editar
				case "modalModificarPDF":
					echo $fn->modalModificarPDF($_REQUEST);
					exit;
				break;

				case "actualizarModificarPDF":
					echo $fn->actualizarModificarPDF($_REQUEST,$_FILES);
					exit;
				break;

				case "modalAgregarPregunta":
					echo $fn->modalAgregarPregunta($_REQUEST);
					exit;
				break;

				case "guardarAgregarPregunta":
					echo $fn->guardarAgregarPregunta($_REQUEST,$_FILES);
					exit;
				break;

				//generalidades
				case "modificarRegistro":
					echo $fn->modificarRegistro($_REQUEST);
					exit;
				break;

				case "modalActualizarCampo":
					echo $fn->modalActualizarCampo($_REQUEST);
					exit;
				break;

				case "actualizarCampo":
					echo $fn->actualizarCampo($_REQUEST);
					exit;
				break;

				case "modalEliminarRegistro":
					echo $fn->modalEliminarRegistro($_REQUEST);
					exit;
				break;

				case "eliminarRegistro":
					echo $fn->eliminarRegistro($_REQUEST);
					exit;
				break;

				case "modalDetalles":
					echo $fn->modalDetalles($_REQUEST);
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