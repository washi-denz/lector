<?php
	class Interfaz{

		//interfaz gráfica de usuario
		var $idUsuario = 0;

		function __construct(&$parents){

			$this->parents = $parents;
			$this->init();

		}

		private function init(){	

			if($this->parents->session->check_login()){
				$this->idUsuario = $this->parents->session->get("id_user");
			}

		}

		//-------------------------------------------------------------//
		//                        modal
		//-------------------------------------------------------------//


		//-------------------------------------------------------------//
		//                           Nav
		//-------------------------------------------------------------//

		function nav_list($tipo='admin',$active=0,$cad=array()){

			$str = '';

			if($tipo == 'admin')
			{
				$str = '
					<li class="inline mx-1">
						<a class="font-medium inline-block p-2 '.(($active == 1)? 'border-b-2 border-blue-500':'').'" href="'.URL.'/admin">Crear lectura</a>
					</li>
					<li class="inline mx-1">
						<a class="font-medium inline-block p-2 '.(($active == 2)? 'border-b-2 border-blue-500':'').'" href="'.URL.'/admin/student">Estudiantes</a>
					</li>
				';
			}

			if($tipo == 'edit')
			{
				$str = '
					<li class="inline mx-1">
						<a class="font-medium inline-block p-2 '.(($active == 1)? 'border-b-2 border-blue-500':'').'" href="'.URL.'/admin">Crear lectura</a>
					</li>
					<li class="inline mx-1">
						<a class="font-medium inline-block p-2 '.(($active == 2)? 'border-b-2 border-blue-500':'').'">Editar</a>
					</li>
				';
			}

			if($tipo == 'deliver')
			{
				$str = '
					<li class="inline mx-1">
						<a class="font-medium inline-block p-2 '.(($active == 1)? 'border-b-2 border-blue-500':'').'" href="'.URL.'/admin">Crear lectura</a>
					</li>
					<li class="inline mx-1">
						<a class="font-medium inline-block p-2 '.(($active == 2)? 'border-b-2 border-blue-500':'').'">Entregas</a>
					</li>
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
		//                        admin
		//-------------------------------------------------------------//

		function mostrar_lista($tipo,$obj,$cad=[]){

			$links1 = null;
			$links2 = null;

			if($tipo == 'crear-lectura')
			{
				$data  = htmlspecialchars(json_encode(array('uniqid'=>$obj->uniqid)));
				$data2 = htmlspecialchars(json_encode(array('uniqid'=>$obj->uniqid,'type'=>'titulo-pdf')));
				$data3 = htmlspecialchars(json_encode(array('uniqid'=>$obj->uniqid,'type'=>'lectura')));

				$encriptar_id = $this->parents->gn->encriptar_id($obj->uniqid);
				$id_pdf       = $this->parents->gn->rtn_id($obj->uniqid);

				$num_preg = $this->parents->gn->rtn_num_preguntas($id_pdf);

				$links1 = '
					<a class="text-blue-500 cursor-pointer send" data-destine="admin/verLectura" data-data="'.$data.'" title="Ver y editar">Ver y editar</a> .
					<span class="text-gray-500"> Num preguntas ('.$num_preg.')</span>
				';

				$links2 = '
					<a class="dropdown-item cursor-pointer send" data-destine="admin/modalCompartir" data-data="'.$data.'"><i class="icon-share-3"></i> Compartir</a>
					<a class="dropdown-item cursor-pointer send" data-destine="admin/verLectura" data-data="'.$data.'" title="Ver y editar"><i class=" icon-eye"></i> Ver y editar</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item cursor-pointer send" data-destine="admin/modalActualizarCampo" data-data="'.$data2.'">Editar título</a>
					<a class="dropdown-item cursor-pointer send" data-destine="admin/modalEliminarRegistro" data-data="'.$data3.'">Eliminar</a>			
				';

				$str='
					<tr>
						<td>'.$cad['num'].'</td>
						<td>
							<h6 class="item-title text-gray-800 font-medium t_'.$encriptar_id.'">'.$obj->titulo.'</h6>
							<div class="item-subtitle text-sm">
								'.$links1.'
							</div>
						</td>
						<td><a href="'.URL.'/admin/deliver/'.$obj->uniqid.'" class="btn btn-success btn-sm">Ver entregas</a></td>
						<td>
							<a class="icon-ellipsis-vert cursor-pointer" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
							<div class="dropdown-menu dropdown-menu-right" style="z-index:1100;">
								'.$links2.'
							</di>
						</td>
					</tr>
				';

			}

			if($tipo == 'alumno')
			{
				$pag  = isset($_GET['pag'])? $_GET['pag']:0;

				$data = htmlspecialchars(json_encode(array('id_alumno'=>$obj->id,'type'=>'datos-alumno','pag'=>$pag)));

				$links = '
					<a class="dropdown-item send" data-destine="admin/modalActualizarCampo" data-data="'.$data.'">Editar datos</a>
					<a class="dropdown-item send" data-destine="admin/modalEliminarRegistro" data-data="'.$data.'">Eliminar</a>			
				';

				$str='
					<tr>
						<td>'.$cad['num'].'</td>
						<!--
						<td>[IMG]</td>
						-->
						<td>
							<h6 class="item-title text-gray-800 font-medium n_'.$obj->id.'">'.$obj->nombres.' '.$obj->apellidos.'</h6>
						</td>
						<td>
							<a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="icon-ellipsis-vert"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right" style="z-index:1100;">
								'.$links.'
							</di>
						</td>
					</tr>
				';

			}

			if($tipo == 'edit-preguntas'){

				$data         = htmlspecialchars(json_encode(array('uniqid'=>$obj->uniqid,'id_preg'=>$obj->id,'type'=>'pregunta')));
				$encriptar_id = $this->parents->gn->encriptar_id($obj->uniqid,$obj->id);

				$links = '
					<a class="dropdown-item send" data-destine="admin/modalActualizarCampo" data-data="'.$data.'">Editar pregunta</a>
					<a class="dropdown-item send" data-destine="admin/modalEliminarRegistro" data-data="'.$data.'">Eliminar</a>			
				';

				$str='
					<tr>
						<td>'.$cad['num'].'</td>
						<td>
							<h6 class="item-title text-gray-800 font-medium d_'.$encriptar_id.'">'.$obj->descripcion.'</h6>
						</td>
						<td>
							<a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="icon-ellipsis-vert"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right" style="z-index:1100;">
								'.$links.'
							</di>
						</td>
					</tr>
				';

			}

			if($tipo == 'card-entrega'){

				$data1 = htmlspecialchars(json_encode(array('id_alumno'=>$obj->id,'id_pdf'=>$obj->id_pdf)));
				$data2 = htmlspecialchars(json_encode(array('id_alumno'=>$obj->id,'id_pdf'=>$obj->id_pdf,'type'=>'respuesta')));

				$detalles = '
					<span 
						class        = "rounded-full text-sm text-green-800 cursor-pointer px-2 py-0.5 mr-3 mb-3 send" 
						data-destine = "admin/modalVerRespuestaEntrega" 
						data-data    = "'.$data1.'" 
						style        = "background:#58d68d!important;">
						Ver respuestas
					</span>
					<span 
						class        = "rounded-full text-sm text-gray-600 bg-gray-100 cursor-pointer px-2 py-0.5 send"
						data-destine = "admin/modalDetalles" 
						data-data    = "'.$data2.'">
						Ver detalles
					</span>
				';

				//$detalles = ($cad['type'] == 'entregar')? true:false;

				$str = '
					<div class="container-card-follow flex border shadow-sm rounded">
						<div class="flex-none px-3 py-3">
							<img src="'.URL_THEME.'/img/default/user_2.png" class="w-16 h-16 rounded-full">
						</div>
						<div class="flex-grow px-2 py-2">
							
							<h6 class="font-medium mb-3 '.(($cad['type'] == 'faltaEntregar')? 'mt-7':null).'">'.$obj->nombres.' '.$obj->apellidos.'</h6>
							'.(($cad['type'] == 'entregar')? $detalles:null).'
						</div>
					</div>
				';

			}

			return $str;
		}


		function form_modal_crear_lectura(){

			$str = '

			';

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

			if($tipo == 'registro-vacio')
			{

				//$imgSrc    = (isset($cad["imgSrc"]))?    $cad["imgSrc"] : URL_THEME."/img/people/cornet2.png";
				$titulo    = (isset($cad["titulo"]))?    $cad["titulo"] : "¡ VACÍA POR EL MOMENTO !";		
				$subtitulo = (isset($cad["subtitulo"]))? $cad["subtitulo"]: "";
	
				$str = '
					<div class="container-empty">
						<!--<img src="" alt="" class="m-auto">-->
						<h5 class="animate__animated animate__headShake animate__infinite infinite">'.$titulo.'</h5>
						'.$subtitulo.'
					</div>
				';

			}

			if($tipo == 'respuesta'){

				$obj->respuesta = ($this->parents->gn->verifica_valor($obj->respuesta))? $obj->respuesta: '<span class="bg-yellow-100 px-2">Respuesta vacía.</span>';

				$str = '
					<div class="bg-gray-100_ p-3_">
						<h3 class="bg-gray-100 rounded text-lg p-2"><span class="font-medium">'.$cad['num'].'.</span> '.$obj->pregunta.'</h3>
						<p class="p-2">'.$obj->respuesta.'</p>
					</div>
				';
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

		function paginacion($Pag=1,$cad=[]){

			$TotalPag = 0;
			$numReg   = 0;
			$numReg   = isset($cad['numReg'])? $cad['numReg'] : 0;

			$get_view = isset($_GET['view'])? 'view='.$_GET['view'].'&':'';
		
			if($numReg <= 0) return null;

			$TotalPag = ($numReg/REG_MAX);
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

		function rtn_array_modal_principal($title='',$body='',$footer='',$cad=[]){

			//Usar sólo cuando sea neceario
			$success = (isset($cad['success']))? $cad['success']:true;

			$rtn = array(
				"success" => $success,
				"update"  => array(
					array(
						"id"     => "modalPrincipal",
						"action" => "showModal"
					),
					array(
						"id"     => "modalTitle",
						"action" => "html",
						"value"  => $title
					),
					array(
						"id"     => "modalBody",
						"action" => "html",
						"value"  => $body
					),
					array(
						"id"     => "modalFooter",
						"action" => "html",
						"value"  => $footer
					),
					array(
						"id"     => "modalPrincipal",
						"action" => "openModal"
					)
				)
			);

			return $rtn;
		}

	}
	// paralel
?>