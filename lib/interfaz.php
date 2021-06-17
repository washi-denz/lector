<?php
	class Interfaz{

		//interfaz gráfica de usuario
		var $idUsuario = 0;
		var $max       = 0;

		function __construct(&$parents){

			$this->parents = $parents;
			$this->init();

		}

		private function init(){	

			$this->get_modal("modalPrincipal");
			$this->get_modal_submit("modalPrincipalSubmit");
			$this->get_modal_alert("modalAlert");
			$this->get_modal_alert_confirm("modalAlertConfirm");

			if($this->parents->session->check_login()){
				$this->idUsuario = $this->parents->session->get("id_user");
			}

		}









		//-------------------------------------------------------------//
		//                        modal
		//-------------------------------------------------------------//

		public function get_modal($id,$title="",$body="",$button=""){
			$rtn = '
				<div class="modal fade" id="'.$id.'">
					<div class="modal-dialog" id="modalDialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="modalTitle">'.$title.'</h4>
        						<button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-hidden="true"></button>
							</div>
							<div class="modal-body" id="modalBody">
								'.$body.'
							</div>
							<div class="modal-msj" id="modalMsj"></div>
							<div class="modal-footer">				
								<span class="form-load" id="formLoad"></span>
								<button type="button" class="btn btn-outline-secondary" data-dismiss="modal"> Cerrar</button>
								<span id="modalFooter">'.$button.'</span>								
							</div>
						</div>
					</div>
				</div>
			';
			$this->parents->content->register($id,$rtn);
		}

		public function get_modal_submit($id,$title="",$body="")
		{
			$rtn = '
				<div class="modal fade" id="'.$id.'">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="modalTitleSubmit">'.$title.'</h4>
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							</div>
							<div class="modal-body" id="modalBodySubmit">
								'.$body.'
							</div>
						</div>
					</div>
				</div>'
			;
			$this->parents->content->register($id,$rtn);
		}

		public function get_modal_alert($id,$title="TestinK dice :",$body="",$button=""){
			$rtn = '
			<div class="modal fade" data-backdrop="static" data-keyboard="false" id="'.$id.'">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">							
							<h4 class="modal-title" id="modalAlertTitle">'.$title.'</h4>
						</div>
						<div class="modal-body" id="modalAlertBody">
							'.$body.'
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal"> Cerrar</button>
						</div>
					</div>
				</div>
			</div>';
			
			$this->parents->content->register($id,$rtn);
		}

		public function get_modal_alert_confirm($id,$title="TestinK dice :",$body="",$button=""){
			$rtn = '
			<div class="modal fade" data-backdrop="static" data-keyboard="false" id="'.$id.'">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">							
							<h4 class="modal-title" id="modalAlertConfirmTitle">'.$title.'</h4>
						</div>
						<div class="modal-body" id="modalAlertConfirmBody">
							'.$body.'
						</div>
						<div class="modal-footer">
							<span id="modalAlertConfirmFooter">'.$button.'</span>
							<button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
						</div>
					</div>
				</div>
			</div>';

			$this->parents->content->register($id,$rtn);
		}

		public function get_modal_static($id,$title="",$body="",$button=""){
			$rtn = '
				<div class="modal fade" id="'.$id.'" style="z-index:1060;">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="modalTitleStatic">'.$title.'</h4>
        						<button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-hidden="true"></button>
							</div>
							<div class="modal-body" id="modalBodyStatic">
								'.$body.'
							</div>
							<div class="modal-footer">
								<span id="idLoadModal"></span>
								<span id="modalFooterStatic">'.$button.'</span>
								<button type="button" class="btn btn-outline-secondary" data-dismiss="modal"> Cerrar</button>
							</div>
						</div>
					</div>
				</div>
			';

			$this->parents->content->register($id,$rtn);
		}

		function get_modal_type($tipo='',$id='modalDynamic',$cad= array()){

			$str = '';

			if($tipo == 'static')
			{
				$str = '
					<div class="modal fade" id="'.$id.'" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content" id="idContentDynamic"></div>
						</div>
					</div>
				';
			}
			elseif($tipo == 'submit')
			{
				$str = '
					<div class="modal fade" id="'.$id.'">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="modalTitleSubmit">'.$title.'</h4>
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								</div>
								<div class="modal-body" id="modalBodySubmit">
								</div>
							</div>
						</div>
					</div>'
				;

			}else{

			}

			return $str;
		}

		//-------------------------------------------------------------//
		//                           Nav
		//-------------------------------------------------------------//

		function str_nav($datos=array()){

			$str   = '';
			$data1 = htmlspecialchars(json_encode(array("redirect"=>"auto")));

			$link1 = '<a class="dropdown-item" href="'.URL.'/init/login">Ingresar</a>';
			$link2 = '<a class="dropdown-item" href="'.URL.'/init/register">Registrarse</a>';
			$link3 = '<a class="dropdown-item" href="'.URL.'/pay">Planes y precios</a>';

			if($this->parents->session->check_login()){
				$str.='
					<a class="dropdown-item" href="'.URL.'/init">Inicio</a>
					<a class="dropdown-item" href="'.URL.'/admin/list_test">Ir a lista test<i class="notify"><span class="notify-public"></span></i></a>
					<a class="dropdown-item send" data-destine="init/crearTestHoy">Crear test</a>					
					<a class="dropdown-item send" data-destine="init/listaAdmision">Admisión<i class="notify"><span class="notify-admission"></span></i></a>
					<div class="dropdown-divider"></div>
					'.$link1.'
					'.$link2.'
					<div class="dropdown-divider"></div>
					'.$link3.'
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="'.URL.'/init/about">Acerca</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item send" data-destine="user/salir" data-data="'.$data1.'">Salir</a>
				';
			}else{

				$str.= '
					<a class="dropdown-item" href="'.URL.'/init">Inicio</a>
					<a class="dropdown-item active send" data-destine="init/crearTestHoy">Crear test</a>	
					'.$link1.'
					'.$link2.'
					<div class="dropdown-divider"></div>
					'.$link3.'
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="'.URL.'/init/about">Acerca</a>
				';
			}

			return $str;
		}

		function str_nav_user(){

			$rc = $this->parents->gn->rtn_consulta("id,nombre_publico","usuarios","id=".$this->parents->session->get("id_user"));

			//$img            = $this->parents->interfaz->str('img-perfil');
			$img            = '[Mi imagen]';
			$nombre_publico = $rc[0]->nombre_publico;
			$tipo_usuario   = $this->parents->session->get('type_user');
			$nombre_publico_slug = $this->parents->gn->post_slug($nombre_publico);

			$href = URL."/view/user/".$nombre_publico_slug."-".$rc[0]->id."/box/1";

			$str='
				<li>
					<a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="icon-user"></i>
					</a>
					<div class="dropdown-menu dropdown-user">
						<div class="img-user">
							'.$img.'
						</div>
						<span>'.$tipo_usuario.'</span>
						<h6>'.$nombre_publico.'</h6>										
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="'.$href.'">Ver mis test públicas</a>
						<a class="dropdown-item" href="'.URL.'/admin/profile">Ir a perfil</a>										
					</div>
				</li>
			';
			return $str;	
		}

		function str_container_nav_view($active=0,$tipo='list',$cad=array()){
			$str = '';

			if($tipo == 'list')
			{
				$str = '
					<ul class="nav-view">
						<li><a class="'.(($active == 1)? 'active':'').'" href="'.URL.'/init">INICIO</a></li>
						<li><a class="'.(($active == 2)? 'active':'').'" href="'.URL.'/admin/list_test">CREA TEST</a></li>
						<li><a class="'.(($active == 3)? 'active':'').'" href="'.URL.'/admin/list_test/public" class="active">PUBLICADOS</a></li>
						<li>
							<a class="'.(($active == 4)? 'active':'').'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								ADMISIÓN <i class="notify"><span class="notify-public"></span></i>
							</a>
							<div class="dropdown-menu">
								<a class="dropdown-item '.(($cad == 5)? 'active':'').'" href="'.URL.'/admin/list_test/resolve?view=me">Mis admisiones</a>
								<a class="dropdown-item '.(($cad == 6)? 'active':'').'" href="'.URL.'/admin/list_test/resolve?view=mypublics">Admisiones publicas<i class="notify"><span class="notify-public"></span></i></a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item '.(($cad == 7)? 'active':'').'" href="'.URL.'/admin/list_test/resolve?view=auto">Admision de prueba</a>								
							</div>
						</li>
					</ul>
				';
			}

			if($tipo == 'edit')
			{
				$str = '
					<ul class="nav-view">
						<li><a class="'.(($active == 1)? 'active':'').'" href="'.URL.'/init">INICIO</a></li>
						<li><a class="'.(($active == 2)? 'active':'').'" href="'.URL.'/admin/list_test">CREA TEST</a></li>
						<li><a class="'.(($active == 3)? 'active':'').'" class="active">EDITAR</a></li>
					</ul>
				';
			}

			if($tipo == 'group')
			{
				$str = '
					<ul class="nav-view">
						<li><a class="'.(($active == 1)? 'active':'').'" href="'.URL.'/init">INICIO</a></li>
						<li><a class="'.(($active == 2)? 'active':'').'" href="'.URL.'/admin/list_test">CREA TEST</a></li>
						<li><a class="'.(($active == 3)? 'active':'').'" href="'.URL.'/admin/group">GRUPOS</a></li>
					</ul>
				';
			}

			return $str;
		}

		public function str_dropdown($arrayObj){

			$item   = '';
			$i      = 0;
			$divide = '<div class="dropdown-divider"></div>';

			$active = $arrayObj->active;
		
			foreach($arrayObj->item as $obj){
				$i++;				
				
				if(isset($obj->divide) && $obj->divide == true){
					$item .= $divide;
					$i--;
				}
				else{
					$item .= '<a class="dropdown-item '.(($active == $i)? 'active':'').'" href="'.$obj->url.'">'.$obj->titulo.'</a>';
				}						
			}

			$str = '
				<div class="dropdown float-end">
					<a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-down-open-1"></i></a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
						'.$item.'
					</div>
				</div>
			';
			return $str;
		}

		//-------------------------------------------------------------//
		//                       generalidades
		//-------------------------------------------------------------//

		function gn($tipo=null,$obj=array(),$cad=[]){
			$str = null;

			if($tipo == 'login')
			{
				$str = '
					<form id="formLogin">
						<div class="mb-3">
							<label class="form-label" for="email">Correo electrónico o usuario:</label>
							<input type="text" class="form-control" name="usuario" id="usuario" placeholder="Email o usuario" required>
						</div>
						<div class="mb-3">
							<label class="form-label" for="exampleInputPassword1">Contraseña:</label>
							<input type="password" class="form-control" name="clave" id="clave" placeholder="Contraseña" required>
						</div>
						<div class="class="form-label" form-msj" id="formMsj"></div>
						<div class="mb-3 d-flex">
							<span class="form-load" id="formLoad"></span>						
							'.$obj->btn.'
						</div>
						<div class="form-bottom text-center">
							<label><a href="'.URL.'/init/register" class="text-blue-600">¿Nuevo aquí? Registrarme</a></label>
							<label class="d-block"><a href="'.URL.'/init/recover_password" class="text-blue-600">Olvidé mi clave</a></label>
						</div>
					</form>
				';

			}
			if($tipo == '...')
			{

				$btn_default = '<button type="submit" class="btn btn-primary bg-color:2 m-auto px-5 py-2 send" data-destine="admin/registrar" data-serialize="formRegistrar" data-data="'.(htmlspecialchars(json_encode(array("load"=>"formLoad_bottom")))).'">Registrarse</button>';
				$btn  = ($btn != '')? $btn : $btn_default;
	
				$str= '			
					<div class="form-header">
						<h2 class="text-gray-800 font-semibold text-2xl pt-2">¡¡ Regístrate !! es Gratis</h2>
					</div>
					<div class="form-body">
						<form id="formRegistrar">
							<div class="mb-3 row">
								<div class="col-sm-12 col-md-4 col-lg-4">
									<label class="text-gray-800 font-semibold mb-2">Email</label>
								</div>
								<div class="col-sm-12 col-md-8 col-lg-8">
									<input type="email" class="form-control" name="correo" placeholder="Email"/>
									<div class="msj" id="idCorreo"></div>
								</div>
							</div>
							<div class="mb-3 row">
								<div class="col-sm-12 col-md-4 col-lg-4">
									<label class="text-gray-800 font-semibold mb-2">Contraseña</label>
								</div>
								<div class="col-sm-12 col-md-8 col-lg-8">
									<input type="password" class="form-control" name="clave1" placeholder="Contraseña" />
								</div>
							</div>
							<div class="mb-3 row">
								<div class="col-sm-12 col-md-4 col-lg-4">
									<label class="text-gray-800 font-semibold mb-2">Confirma contraseña</label>
								</div>
								<div class="col-sm-12 col-md-8 col-lg-8">
									<input type="password" class="form-control" name="clave2" placeholder="Confirmar contraseña" />
									<div class="msj" id="idClave2"></div>
								</div>
							</div>
							<div class="form-check">
								<label class="form-check-label">
								  <input type="checkbox" name="terminos" class="form-check-input">
								  Al crear tu cuenta, estás aceptando los <a href="'.URL.'/init/terms_and_policy" class="text-blue-500">Términos y política de privacidad</a> de <strong>'.APP_NAME.'</strong>.
								</label>
							</div>
							<div class="form-msj" id="formMsj"></div>
							<div class="d-flex my-4">						
								<span class="form-load" id="formLoad"></span>
								'.$btn.'
							</div>
						</form>	
					</div>
					<div class="form-footer">
					</div>
				';
			}

			if($tipo == 'img-perfil')
			{
				$rc     = $this->parents->gn->rtn_consulta("img","usuarios","idUsuario=".$this->parents->session->get("idUser"));
				$imgURL = ($rc[0]->img != 'img.png')? URL."/data/img_user/".$rc[0]->img."?upd=".rand() : URL_THEME."/img/default/user.png";

				$str = '<img src="'.$imgURL.'"/>';
			}

			return $str;
		}

		function msj($tipo,$cont='',$style=''){

			$msj   = '';
			$style = ($style!='')?'style="'.$style.'"':'';

			if($tipo == "success")
			{
				$msj='<div class="alert alert-success" role="alert" '.$style.'>'.$cont.'</div>';
			}
			elseif($tipo == "info")
			{
				$msj='<div class="alert alert-info" role="alert" '.$style.'>'.$cont.'</div>';
			}
			elseif($tipo == "warning")
			{
				$msj='<div class="alert alert-warning" role="alert" '.$style.'>'.$cont.'</div>';
			}
			elseif($tipo == "danger")
			{
				$msj='<div class="alert alert-danger" role="alert" '.$style.'>'.$cont.'</div>';
			}
			elseif($tipo == "success-close")
			{
				$msj='
				<div class="alert alert-success alert-dismissible" role="alert" '.$style.'>
					<button type="button" class="btn-close fs-8" data-dismiss="alert" aria-label="Close"></button>
					'.$cont.'
				</div>
				';
			}
			elseif($tipo == "info-close")
			{
				$msj='
				<div class="alert alert-info alert-dismissible" role="alert" '.$style.'>
					<button type="button" class="btn-close fs-8" data-dismiss="alert" aria-label="Close"></button>
					'.$cont.'
				</div>
				';
			}
			elseif($tipo == "warning-close")
			{
				$msj='
				<div class="alert alert-warning alert-dismissible" role="alert" '.$style.'>
					<button type="button" class="btn-close fs-8" data-dismiss="alert" aria-label="Close"></button>
					'.$cont.'
				</div>
				';
			}
			elseif($tipo == "danger-close")
			{
				$msj='
				<div class="alert alert-danger alert-dismissible" role="alert" '.$style.'>
					<button type="button" class="btn-close fs-8" data-dismiss="alert" aria-label="Close"></button>
					'.$cont.'
				</div>
				';
			}
			else
			{
				$msj = $cont."<br>";
			}
			//...

			return $msj;
		}

		function msj_ayuda($tipo){
			$msj = '';

			//init
			if($tipo == '')
			{

			}
			//admin
			if($tipo == 'puntuacion')
			{
				$msj = '
					La puntuación es como la \'nota\' del  de cualquier test. 
					Nota: El número ingresado tiene que ser divisible con el número total
					de preguntas.
				';
			}
			if($tipo == 'duracion')
			{
				$msj = '
					La duración del examen puede ser LIBRE o puedes configurarlo a tu gusto (00:01 minutos,...,00:15 minutos,01:00 una hora,...)
					Nota: En test LIBRE no se considera el tiempo.
				';
			}
			if($tipo == 'crear-test-descripcion'){
				$msj = '
					En la descripción se puede mensionar de que trata el test a grandes rasgos (ó un resumen) y las fuente(s) utilizadas, éste ultimo si las tuviera.
					Nota: la descripción no debe pasar los 255 carácteres.
				';
			}
			if($tipo == 'eligir-imagen'){
				$msj = '
					Elija entre una imagen generada (automáticamnete) o una subida por usted.
					Nota: Esta imagen pertenece a test principal y la que verá el público en general,elija una la imagen que esté lo más realcionado al test.
				';
			}

			return $msj;
		}

		function paginacion($Pag=1,$cad=array()){

			$TotalPag = 0;
			$numReg   = 0;
			$numReg   = isset($cad['numReg'])? $cad['numReg'] : 0;

			$get_view = isset($_GET['view'])? 'view='.$_GET['view'].'&':'';
		
			if($numReg <= 0) return null;

			$TotalPag = ($numReg/$this->max);
			$TotalPag = (($TotalPag-(int)$TotalPag)>0)? (int)$TotalPag+1:(int)$TotalPag;
			$TotalPag = ($TotalPag<=0)?1:$TotalPag;

			$min=$Pag-2;
			$max=$Pag+2;
			
			$min = ($max>$TotalPag)? $min+($TotalPag-$max) : $min;
			$max = ($min<=0)? $max+((-1*$min)+1): $max;

			$min = ($min<=0)? 1 : $min;
			$max = ($max>$TotalPag)? $TotalPag : $max;

			$next=($max >= $TotalPag)?'':'<li class="page-item"><a href="?'.$get_view.'pag='.($max+1).'" class="page-link">Siguiente</a></li>';
			
			$back=($min <= 1)?'':'<li class="page-item"><a href="?'.$get_view.'pag='.$min.'" class="page-link">Anterior</a></li>';
			
			$rtn="";
			for($i=$min;$i<=$max;$i++)
			{
				$active = ($i==$Pag)?'active':'';
				$rtn.='<li class="page-item '.$active.'"><a href="?'.$get_view.'pag='.$i.'" class="page-link">'.$i.'</a></li>';
			}			
			
			return $back.$rtn.$next;
		
		}

	}
	// paralel
?>