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
			/// nuevo ///
			//$this->get_modal_box_top("modalBoxTop");
			$this->get_modal_box_center_static("modalBoxCenterStatic");

			if($this->parents->session->check_login()){
				$this->idUsuario = $this->parents->session->get("idUser");
			}

		}

		//-------------------------------------------------------------//
		//                        general 
		//-------------------------------------------------------------//

		/////////////////////////  Nuevo  ///////////////////////////////
		public function get_modal_box_top($id,$content=""){
			$str ='
				<div class="modal fade modal-box-top" id="'.$id.'" tabindex="1" role="dialog" aria-labelledby="exampleModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content" id="idContentBoxTop">
							'.$content.'
						</div>
					</div>
				</div>
			';
			return $str;
		}

		public function get_modal_box_center(){

		}

		public function get_modal_box_center_static($id,$content=""){
			$str = '
				<div class="modal fade modal-box-center-static" id="'.$id.'" data-backdrop="static" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content" id="idContentBoxCenterStatic">
						'.$content.'
						</div>
					</div>
				</div>
			';
			$this->parents->content->register($id,$str);
		}

		public function get_modal_box_alert(){

		}

		/////////////////////////  Nuevo  ///////////////////////////////

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
					<a class="dropdown-item send" data-destine="init/salir" data-data="'.$data1.'">Salir</a>
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

		function str_nav_user(){

			$rc = $this->parents->gn->rtn_consulta("idUsuario,nombre_publico","usuario","idUsuario=".$this->parents->session->get("idUser"));

			$img            = $this->parents->interfaz->str('img-perfil');
			$nombre_publico = $rc[0]->nombre_publico;
			$tipo_usuario   = $this->parents->session->get('type_user');
			$nombre_publico_slug = $this->parents->gn->post_slug($nombre_publico);

			$href = URL."/view/user/".$nombre_publico_slug."-".$rc[0]->idUsuario."/box/1";

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

		//---------------------------------------------------------//
		//                           init
		//--------------------------------- -----------------------//

		function list_item_exam($obj,$datos=array()){

			$color = $this->parents->gn->rtn_estilo_lista($obj->idExamen);
			$letra = $this->parents->gn->rtn_letra_valida($obj->titulo);
		
			$data    = htmlspecialchars(json_encode(array("idex"=>$obj->idex)));

			$estado_admision = $this->parents->gn->rtn_estado_admision_parcial($obj->idExamen,$this->idUsuario);

			if($estado_admision == 'ACTIVA')
				$estado_admision = '<span class="check icon-spin3 animate-spin" title="Test no terminada"></span>';
			
			elseif($estado_admision == 'CONCLUIDA')
				$estado_admision = '<span class="icon-check-1 check" title="Test resuelto"></span>';

			elseif($estado_admision == 'CANCELADA')
				$estado_admision = '<span class="icon-attention-alt check" title="Test no terminada"></span>';
			
			if($datos["select"] == 'box')
			{
				//Estado del examen
				$fecha = ($obj->estado == 'LIBRE')? '<span>LIBRE</span>': '<i class="icon-calendar" title="Fecha"></i> <span>'.$this->parents->gn->get_current_time($obj->fijar_fecha.' '.$obj->tiempo_inicial).' </span><i class="icon-clock" title="Tiempo"></i> <span>'.$obj->duracion.'</span>';

				//Imagen
				$img   = ($obj->img != 'exam.png')? '<img src="'.URL.'/data/img_main_exam/'.$obj->img.'">' : '<div class="img-div max '.$color.'"><div class="letter">'.strtoupper($letra).'</div><div class="titulo">'.APP_NAME.'</div></div>';

				//Resueltas
				$num_exam_resueltas = ($obj->resueltas > 0) ? '<i class="icon-edit-3"></i>'.$obj->resueltas:'';

				//Vistas
				$num_vistas = $this->parents->gn->rtn_num_vistas($obj->idExamen);
				$num_vistas = ($num_vistas > 0)?'<i class="icon-eye"></i>'.$num_vistas:'';

				$str = '
					<div class="box-exam">
					    <div class="img send" data-destine="init/redirectAperturaExamen" data-data="'.$data.'">
					        '.$img.'				      
					    </div>
					    <div class="details">
							'.$fecha.'
					    </div>
					    <div class="title send" title="Examen virtual" data-destine="init/redirectAperturaExamen" data-data="'.$data.'">
					        '.$obj->titulo.'
					    </div>
					    <div class="subdetails">
					    	<span>'.$num_vistas.'</span>
					    	<span>'.$num_exam_resueltas.'</span>						  
						    <span class="icon-share-3 send" data-destine="admin/mostrarModalCompartir" data-data="'.$data.'"></span>
						    '.$estado_admision.'						
						</div>					    
					</div>
				';
			}

			if($datos["select"] == 'list')
			{
				$fecha = ($obj->estado == 'LIBRE')? '<span>LIBRE</span>': '<i class="icon-calendar" title="Fecha"></i> <span>'.$this->parents->gn->get_current_time($obj->fijar_fecha.' '.$obj->tiempo_inicial).' </span><i class="icon-clock" title="Tiempo"></i> <span>'.$obj->duracion.'</span>';
				$num   = (isset($datos['num']))? '<td>'.$datos['num'].'</td>':'';
				$img   = ($obj->img != 'exam.png')? '<img src="'.URL.'/data/img_main_exam/'.$obj->img.'" width="41.6" height="46.6">' : '<div class="img-div min '.$color.'"><div class="letter">'.strtoupper($letra).'</div><div class="titulo">'.APP_NAME.'</div></div>';

				$num_exam_resueltas = ($obj->resueltas > 0) ? $obj->resueltas.'<i class="icon-edit-3"></i>':'';

				$str = '
					<tr class="send" data-destine="init/redirectAperturaExamen" data-data="'.$data.'">
					    '.$num.'
					    <td class="image">
					       '.$img.'
					    </td>
					    <td class="details pl-2" title="Test virtual">
					        <h6 class="item-title text-gray-800 font-medium">'.$obj->titulo.'</h6>
					        <div class="item-subtitle pl-1">
									<span title="Número de preguntas"> Num preg ('.($this->parents->gn->rtn_num_preguntas($obj->idExamen)).')</span>
									'.$num_exam_resueltas.'									
								</a>
							</div>
					    </td>
					    <td class="subdetails position-r">
							'.$fecha.'
					        '.$estado_admision.'					        
					    </td>
					</tr>
				';
			}

			if($datos["select"] == 'card')
			{	
				$img = ($obj->img != 'exam.png')? 
					'<img src="'.URL.'/data/img_main_exam/'.$obj->img.'" width="41.6" height="46.6">'
					: $this->str_gn('img-div',(object) array('letra'=> $letra,'tamanio' => 'med','color'=>$color));
				
				$autor = $this->parents->gn->rtn_autor($obj->idUsuario);

				$link_autor = $this->str('url-test-public',[$autor,$obj->idUsuario]);

				$num_exam_resueltas = ($obj->resueltas > 0) ? $obj->resueltas.'<i class="icon-edit-3"></i>':'Sé el primero en resolverlo';

				$str = '
					<div class="container-card flex px-2 py-2">
						<div class="image img-med flex-none w-28.5 send" data-destine="init/redirectAperturaExamen" data-data="'.$data.'">
							'.$img.'					
						</div>					
						<div class="details flex-grow">
							<a class="title send" data-destine="init/redirectAperturaExamen" data-data="'.$data.'">'.$obj->titulo.'</a>
							<div class="autor">
								<span class="text-gray-800">'.$autor.'</span>
								<a href="'.$link_autor.'" class="icon-link-ext"></a>
							</div>
							<diV class="subdetails">
								<span>'.$num_exam_resueltas.'</span>
								'.$estado_admision.'
							</diV>
						</div>
					</div><!--/container-card-->
				';
			}

			if($datos["select"] == 'test-world'){

				$img                = ($obj->img != 'exam.png')? '<img src="'.URL.'/data/img_main_exam/'.$obj->img.'">' : '<img src="'.URL_THEME.'/img/default/testwink.png">';
				$num_exam_resueltas = ($obj->resueltas > 0) ? '<i class="icon-edit-3"></i>'.$obj->resueltas:'';

				//Vistas
				$num_vistas = $this->parents->gn->rtn_num_vistas($obj->idExamen);
				$num_vistas = ($num_vistas > 0)?'<i class="icon-eye"></i>'.$num_vistas:'';

				$str = '
					<div class="test-world send" data-destine="init/redirectAperturaExamen" data-data="'.$data.'">

						'.$img.'
						<div class="tw-title">'.$obj->titulo.'</div>

						<div class="tw-details">
							<span>'.$num_vistas.'</span>
							<span class="td-resolve">'.$num_exam_resueltas.'</span>
							<!--
							<span class="td-approved">18000<i class="icon-star"></i></span>
							<span class="td-disapproved">180<i class=" icon-star-half-alt"></i></span>
							-->
							'.$estado_admision.'						
							<!--
							<div class="dropdown float-end dropdown-test-world">
							    <a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis-vert"></i></a>
							    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="margin: 0rem;">
									<a class="dropdown-item" edit="cancel">180000<i class="icon-star"></i></a>
									<a class="dropdown-item" edit="new">180<i class=" icon-star-half-alt"></i></a>
								</div>
							</div>
							-->
						</div>
					</div><!--/test-world-->
				';
			}

			return $str;
		}

		function search_list_exam($rtn=array(),$ind){

			$str   = '';
			$img   = ($rtn[$ind]["img"] != 'exam.png')? URL."/data/img_main_exam/".$rtn[$ind]["img"] : URL_THEME."/img/default/exam_testink.png";
			$data  = htmlspecialchars(json_encode(array("idex"=>$rtn[$ind]["idex"])));
			$fecha = ($rtn[$ind]["estado"] == 'LIBRE')? '<span>LIBRE</span>': '<i class="icon-calendar" title="Fecha"></i> <span>'.$this->parents->gn->get_current_time($rtn[$ind]["fijar_fecha"].' '.$rtn[$ind]["tiempo_inicial"]).' </span><i class="icon-clock" title="Tiempo"></i> <span>'.$rtn[$ind]["duracion"].'</span>';

			$nombre_publico  = $this->parents->gn->post_slug($this->parents->gn->rtn_nombre_publico($rtn[$ind]["idUsuario"]));
			$estado_admision = '';

			if($this->parents->session->check_login()){
				if($this->parents->gn->existe_registro("admision","idExamen=".$rtn[$ind]["idExamen"]." AND idUsuario=".$this->idUsuario)){
					if($this->parents->gn->existe_registro("admision","estado_admision='ACTIVA' AND idExamen=".$rtn[$ind]["idExamen"]." AND idUsuario=".$this->idUsuario)){
						$estado_admision = '<div class="be-check" title="Examen no terminada"><i class="icon-spin3 animate-spin"></i></div>';
					}else{
						$estado_admision = '<div class="be-check" title="Examen resuelto"><i class="icon-check-1"></i></div>';
					}
				}
			}

			$str='
				<tr>
				    <td title="Examen virtual">
				        <h6 class="item-title cursor-p send" data-destine="init/mostrarModalAperturaExamen" data-data="'.$data.'">'.$rtn[$ind]["titulo"].'</h6>
				        <div class="item-subtitle">
								<a href="'.URL.'/view/user/'.$nombre_publico.'/'.$rtn[$ind]["idUsuario"].'">'.(strtoupper($this->parents->gn->rtn_nombre_publico($rtn[$ind]["idUsuario"]))).'</a> . <a> NUM PREGUNTAS ( <span title="Número de preguntas">'.($this->parents->gn->rtn_num_preguntas($rtn[$ind]["idExamen"])).'</span> )</a>
							</a>
						</div>
				    </td>
				    <td class="cursor-pointer send" data-destine="init/mostrarModalAperturaExamen" data-data="'.$data.'">
				        <img src="'.$img.'" style="width:3rem;height:3rem;"/>
				    </td>
				    <td class="position-r">
						'.$fecha.'
						'.$estado_admision.'
				    </td>
				</tr>
			';
			
			
			return $str;
		}

		function search_list_exam_new($obj,$datos=array()){

			$str   = '';
			$img   = ($obj->img != 'exam.png')? URL."/data/img_main_exam/".$obj->img : URL_THEME."/img/default/exam_testink.png";
			$data  = htmlspecialchars(json_encode(array("idex"=>$obj->idex)));
			$fecha = ($obj->estado == 'LIBRE')? '<span>LIBRE</span>': '<i class="icon-calendar" title="Fecha"></i> <span>'.$this->parents->gn->get_current_time($obj->fijar_fecha.' '.$obj->tiempo_inicial).' </span><i class="icon-clock" title="Tiempo"></i> <span>'.$obj->duracion.'</span>';

			$nombre_publico  = $this->parents->gn->post_slug($this->parents->gn->rtn_nombre_publico($obj->idUsuario));
			$estado_admision = '';

			if($this->parents->session->check_login()){
				if($this->parents->gn->existe_registro("admision","idExamen=".$obj->idExamen." AND idUsuario=".$this->idUsuario)){
					if($this->parents->gn->existe_registro("admision","estado_admision='ACTIVA' AND idExamen=".$obj->idExamen." AND idUsuario=".$this->idUsuario)){
						$estado_admision = '<div class="be-check" title="Examen no terminada"><i class="icon-spin3 animate-spin"></i></div>';
					}else{
						$estado_admision = '<div class="be-check" title="Examen resuelto"><i class="icon-check-1"></i></div>';
					}
				}
			}

			$str='
				<tr>
				    <td title="Examen virtual">
				        <h6 class="item-title cursor-p send" data-destine="init/mostrarModalAperturaExamen" data-data="'.$data.'">'.$obj->titulo.'</h6>
				        <div class="table-td-subtitle">
								<a href="'.URL.'/view/user/'.$nombre_publico.'/'.$obj->idUsuario.'">'.(strtoupper($this->parents->gn->rtn_nombre_publico($obj->idUsuario))).'</a> . <a> NUM PREGUNTAS ( <span title="Número de preguntas">'.($this->parents->gn->rtn_num_preguntas($obj->idExamen)).'</span> )</a>
							</a>
						</div>
				    </td>
				    <td class="cursor-p send" data-destine="init/mostrarModalAperturaExamen" data-data="'.$data.'">
				        <img src="'.$img.'" style="width:3rem;height:3rem;"/>
				    </td>
				    <td class="position-r">
						'.$fecha.'
						'.$estado_admision.'
				    </td>
				</tr>
			';
			
			
			return $str;
		}

		//---------------------------------------------------------//
		//                           admin
		//--------------------------------- -----------------------//

		function str_tipo_pregunta($cad=array()){

			if(!isset($cad["disabled"])){
				$str = '
					<input type="radio" name="tipo" id="radio'.$cad["i"].'" value="'.$cad["value"].'" '.$cad["checked"].'>
					<label class="box-shadow" for="radio'.$cad["i"].'">
						<img src="'.URL_THEME.'/img/type_question/'.strtolower($cad["estilo"]).'/'.(strtolower($cad["value"])).'.png" alt="">
						<div class="title">'.$cad["val"].'</div>
					</label>
				';
			}else{
				$str = '
					<input type="radio" '.$cad["disabled"].'>
					<label class="box-shadow filter-gray">
						<img src="'.URL_THEME.'/img/type_question/'.strtolower($cad["estilo"]).'/'.(strtolower($cad["value"])).'.png" alt="">
						<div class="title">'.$cad["val"].'</div>
					</label>
				';
			}
			return $str;
		}

		function str_btn_ayuda($tipo,$titulo=''){
			$str = '
				<i tabindex="0" class="icon-help-1" role="button" data-toggle="popover" data-trigger="focus" title="'.$titulo.'" data-content="'.$this->msj_ayuda($tipo).'"></i>
			';
			
			return $str;
		}
		//-------------------------------------------------------------//
		//                          msj
		//-------------------------------------------------------------//

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

		//-------------------------------------------------------------//
		//                          Categoría
		//-------------------------------------------------------------//

		function str_categoria($grupo=1){

			$str = '';

			$rc = $this->parents->gn->rtn_consulta("idCategoria,descripcion","categoria","grupo=".$grupo." AND mostrar='SI' ORDER BY orden ASC");//config

			foreach($rc as $obj){
				if($this->parents->gn->existe_subategorias_mostrar($obj->idCategoria)){
					$str .= '<a class="item px-4 py-2 m-2 d-block box-shadow:1 text-dark font-weight:5 send" data-destine="init/mostrarSubcategoria" data-data=\'{"idCtg":'.$obj->idCategoria.'}\'>'.$obj->descripcion.'</a>';	
				}else{
					$str .= '<a href="'.URL.'/init/?ctg='.$obj->idCategoria.'" class="item px-4 py-2 m-2 d-block box-shadow:1 text-dark font-weight:5">'.$obj->descripcion.'</a>';
				}
			}

			return $str;
		}

		function str_subcategoria($obj){

			$idCtg    = (isset($obj->idCategoria))   ? $obj->idCategoria:0;
			$idSubctg = (isset($obj->idSubcategoria))? $obj->idSubcategoria:0;

			$data = htmlspecialchars(json_encode(array("idCategoria"=>$idCtg,"idSubcategoria"=>$idSubctg)));
			$str = '<a href="'.URL.'/init/?ctg='.$idCtg.'&subctg='.$idSubctg.'" class="item active px-4 py-2 m-2 d-block box-shadow:1 text-dark">'.$obj->descripcion.'</a>';			

			return $str;
		}

		public function str_categoria__(){

			$str = '';

			$this->parents->sql->consulta("SELECT idCategoria,descripcion FROM categoria ORDER BY orden ASC;");
			$resultado = $this->parents->sql->resultado;

			foreach($resultado as $obj1){

				$str .= '
					<div class="container-category d-flex">
						<!--
						<div class="row">
						    <div class="col-10">
						        <h6>'.$obj1->descripcion.'</h6>
						    </div>
						    <div class="col-2">
						    </div>
						</div>
						-->
						<div class="px-4 py-2 m-3 bg-light d-inline-block box-shadow:1 text-dark cursor-pointer">
							'.$obj1->descripcion.'
						</div>
						<div class="container-category-item">
							<div class="row">
								<div class="col-10 ">
						';

				$this->parents->sql->consulta("SELECT idSubcategoria,idCategoria,descripcion FROM subcategoria WHERE idCategoria=".$obj1->idCategoria.";");
				$resultado = $this->parents->sql->resultado;

				foreach($resultado as $obj2){
					$str .= '
						<a href="'.URL.'/init/?ctg='.$obj1->idCategoria.'&subctg='.$obj2->idSubcategoria.'">'.$obj2->descripcion.'</a>
					';
				}

				$str .= '
							</div>
								<div class="col-2 p-0">
								</div>
							</div>
						</div><!--/container-category-item-->
					</div><!--/container-category-->
				';
			}

			return $str;
		}

		function str_select_categoria($obj,$cad=array()){
			$str = '';

			if($obj->tipo == 'form-select')
			{

				$str_list = '';
				$data = htmlspecialchars(json_encode(array($obj->idex)));

				foreach($this->parents->gn->rtn_consulta("idCategoria,descripcion","categoria ORDER BY orden ASC") as $obj1){				
					$str_list .= '<option value="'.$obj1->idCategoria.'">'.$obj1->descripcion.'</option>';
				}

				$str = '
					<select class="form-select mt-3" id="categSelect" data-data="'.$data.'">
						<option value="0" selected disabled>Elija una o varias Categorías</option>
						'.$str_list.'
					</select>
				';
				

			}

			if($obj->tipo == 'item')
			{

				$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idCategoria"=>$obj->idCategoria)));

				$str = '
				    <div class="category">
				        <div class="item box-shadow:1">
				            <a>'.$obj->descripcion.'</a>
				            <span class="cancel icon-cancel-1 send" data-destine="admin/eliminarSeleccionarCategoria" data-data="'.$data.'"></span>
				        </div>
				    </div>
				';
			}

			if($obj->tipo == 'subitem')
			{
				$idExamen        = $this->parents->gn->rtn_id($obj->idex);
				$str_subcat      = '';
				$str_drop_subcat = '';

				$data1 = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idCategoria"=>$obj->idCategoria))); 

				//Drop subcategoria
				foreach($this->parents->gn->rtn_consulta("idSubcategoria,descripcion","subcategoria","idCategoria=".$obj->idCategoria) as $obj1){

					$data2 = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idCategoria"=>$obj->idCategoria,"idSubcategoria"=>$obj1->idSubcategoria)));

					$str_drop_subcat  .= '<a class="dropdown-item send" data-destine="admin/seleccionarSubcategoria" data-data="'.$data2.'" title="Subcategoría">'.$obj1->descripcion.'</a>';
				}

				//Subcategoria
				foreach($this->parents->gn->rtn_consulta("idSubcategoria","examen_categoria","idExamen=".$idExamen." AND idCategoria=".$obj->idCategoria) as $obj2){
					if($obj2->idSubcategoria != 0){

						$data3 = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idCategoria"=>$obj->idCategoria,"idSubcategoria"=>$obj2->idSubcategoria)));

						$rc          = $this->parents->gn->rtn_consulta("descripcion","subcategoria","idSubcategoria=".$obj2->idSubcategoria);
						$str_subcat .= '<a class="box-shadow:1" title="Subcategoría">'.$rc[0]->descripcion.' <span class="cancel icon-cancel-1 send" data-destine="admin/eliminarSeleccionarCategoria" data-data="'.$data3.'"></span></a>';
					}		
				}

				$str = '
					<div class="category">
				        <div class="item box-shadow:1">
				            <a data-toggle="dropdown" aria-expanded="false" title="Categoría">'.$obj->descripcion.'<i class="icon-down-open-1"></i></a>
				            <span class="cancel icon-cancel-1 send" data-destine="admin/eliminarSeleccionarCategoria" data-data="'.$data1.'"></span>
				            <ul class="dropdown-menu mt-2">
				            	'.$str_drop_subcat.'
				            </ul>
				        </div>
				        <div class="subitem" id="subitem'.$obj->idCategoria.'">
				        	'.$str_subcat.'
				        </div>
				    </div>
				';
			}

			if($obj->tipo == 'subcategoria')
			{
				$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idCategoria"=>$obj->idCategoria,"idSubcategoria"=>$obj->idSubcategoria)));
				
				$rc  = $this->parents->gn->rtn_consulta("descripcion","subcategoria","idSubcategoria=".$obj->idSubcategoria);				
				$str = '<a class="box-shadow:1" title="Subcategoría">'.$rc[0]->descripcion.' <span class="cancel icon-cancel-1 send" data-destine="admin/eliminarSeleccionarCategoria" data-data="'.$data.'"></span></a>';
			}


			return $str;
		}

		function str_mostrar_select_categoria($obj,$cad=array()){

			$str  = '';
			$idex = $this->parents->gn->rtn_idex($obj->idExamen);
			$idcat = array();

			//genrear array unico de idCategoria
			foreach($this->parents->gn->rtn_consulta("idCategoria","examen_categoria","idExamen=".$obj->idExamen) as $obj1){
				$idcat[] = $obj1->idCategoria;
			}
			$idcat_unique = array_unique($idcat);


			foreach($idcat_unique as $idCat){
					
					$rc = $this->parents->gn->rtn_consulta("descripcion","categoria","idCategoria=".$idCat);

					$obj = (object) array("idex"=>$idex,"idCategoria"=>$idCat,"descripcion"=>$rc[0]->descripcion,"tipo"=>"item");

					if($this->parents->gn->existe_subcategorias($idCat)){
						$obj->tipo = "subitem";
					}

					$str .= $this->str_select_categoria($obj);


				/*
				foreach($this->parents->gn->rtn_consulta("idCategoria,idSubcategoria","examen_categoria","idCategoria=".$idCategoria) as $obj2){
					
					$rc = $this->parents->gn->rtn_consulta("descripcion","categoria","idCategoria=".$obj1->idCategoria);

					$obj = (object) array("idex"=>$idex,"idCategoria"=>$obj1->idCategoria,"descripcion"=>$rc[0]->descripcion,"tipo"=>"item");

					if($this->parents->gn->existe_subcategorias($obj1->idCategoria)){
						$obj->idSubcategoria = $obj2->idSubcategoria;
						$obj->tipo = "subitem";
					}

					$str .= $this->str_select_categoria($obj);

				}
				*/



			}

			return $str;
		}

		//-------------------------------------------------------------//
		//                         Grupo
		//-------------------------------------------------------------//

		function str_grupo($obj,$cad=array()){

			$str = '';

			if($obj->tipo == 'item')
			{
				$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idGrupo"=>$obj->idGrupo,"type"=>"grupo_test")));
				$class = (isset($obj->class) && $obj->class == 'active')? $obj->class:'';
				
				$str = '

					<a class="group '.$class.' position-relative px-4 py-2 d-block box-shadow:1 text-dark">
			            '.$obj->descripcion.'
			            <div class="position-absolute d-flex align-items-center hidden transition-tw:1">
			                <span class="icon-check-1 bg-color:3 cursor-pointer hover:bg-color-1 text-white rounded-circle send" data-destine="admin/seleccionarGrupo" data-data="'.$data.'"></span>
			                <span class="icon-cancel-1 bg-color:3 cursor-pointer hover:bg-color-1 text-white rounded-circle send" data-destine="admin/eliminarGrupo" data-data="'.$data.'"></span>
			            </div>
			        </a>

				';

			}

			return $str;
		}

		//-------------------------------------------------------------//
		//                      Envio de mensajes
		//-------------------------------------------------------------//

		function str_msj_recuperar_contrasena($obj,$datos=array()){

			$str=
			'
			<html>
				<head>
					<title>Recuperar contraseña</title>
					<style>
						body{
						   margin:0;
						   font-family:"Segoe UI","Helvetica Neue",Helvetica,Arial,sans-serif;
						   font-size:1.5rem;
						   font-weight:400;
						   line-height:1.7;
						   color:#212529;
						   background-color:#fff;
						   margin:1rem;
						}
						.redirect-container{
						    text-align:center_;
						}
						.redirect-container>a.rc-link{
						    background:#3bc9d9;
						    text-decoration:none;
						    color:#0b4147;
						    border-radius:0.5rem;
						    padding:1rem 1.5rem;
						    font-weight:500;
						    font-size:1.4rem;
						    transition:0.3s all;
						}
						.redirect-container>a.rc-link:hover{
						    background:#fff;
						    border:2px solid #3bc9d9;
						}
					</style>
				</head>
				<body>
					<div class="redirect-container">
					    Saludos,<br>'.$obj->nombre_publico.'
					    <p><strong>'.APP_NAME.'</strong>, le envia un Link de recuperación de contraseña.</p>
					    <a href="'.URL.'/init/change_password?csm_='.$datos['csm'].'&nca_='.md5(date("d-m-Y H:i:s")).'&id_='.$obj->idUsuario.'&active_=true" class="rc-link">Recuperar contraseña</a>
					</div>
				</body>
			</html>
			';
			return $str;

		}

		function get_str_msj_confirmar_cuenta($obj,$datos=array()){


			$str=
			'
			<html>
				<head>
					<title>Confirmar e-mail</title>
					<style>
						body{
						   margin:0;
						   font-family:"Segoe UI","Helvetica Neue",Helvetica,Arial,sans-serif;
						   font-size:1.5rem;
						   font-weight:400;
						   line-height:1.7;
						   color:#212529;
						   background-color:#fff;
						   margin:1rem;
						}
						.redirect-container{
						    text-align:center_;
						}
						.redirect-container>a.rc-link{
						    background:#3bc9d9;
						    text-decoration:none;
						    color:#0b4147;
						    border-radius:0.5rem;
						    padding:1rem 1.5rem;
						    font-weight:500;
						    font-size:1.4rem;
						    transition:0.3s all;
						}
						.redirect-container>a.rc-link:hover{
						    background:#fff;
						    border:2px solid #3bc9d9;
						}
					</style>
				</head>
				<body>
					<div class="redirect-container">
					    Saludos,<br>'.$obj->nombres.'
					    <p><strong>'.APP_NAME.'</strong>, le envia un Link para confirmar cuenta.</p>
					    <a href="'.URL.'/init/confirm_email?csm_='.$datos['csm'].'&nca_='.md5(date("d-m-Y H:i:s")).'&id_='.$obj->idUsuario.'&active_=true" class="rc-link">Confirmar cuenta</a>
					</div>
				</body>
			</html>
			';
			return $str;
		}

		function get_str_msj_suscriptores($obj){


			$str=
			'
			<html>
				<head>
					<title>Bienvenido Frelements</title>
					<style>
						body{
						   margin:0;
						   font-family:"Segoe UI","Helvetica Neue",Helvetica,Arial,sans-serif;
						   font-size:1.5rem;
						   font-weight:400;
						   line-height:1.7;
						   color:#212529;
						   background-color:#fff;
						   margin:1rem;
						}
						.redirect-container{
						    text-align:center_;
						}
						.redirect-container>a.rc-link{
						    background:#3bc9d9;
						    text-decoration:none;
						    color:#0b4147;
						    border-radius:0.5rem;
						    padding:1rem 1.5rem;
						    font-weight:500;
						    font-size:1.4rem;
						    transition:0.3s all;
						}
						.redirect-container>a.rc-link:hover{
						    background:#fff;
						    border:2px solid #3bc9d9;
						}
						.note{
							font-size:0.7rem;
							color:#7f8c8d;
						}
					</style>
				</head>
				<body>
					<div class="redirect-container">
					    Saludos,<br>'.$obj->nombreApellido.'
							<p>¡¡¡ Gracias por usar Classpiece !!! ...Hasta pronto Classpiece...</p>
							Hola, soy Washington Llacsa M. desarrollador y programador de Classpiece.com<br>
							Te presento mi nuevo proyecto llamado ‘<strong>Frelements</strong>’ en donde buscará los Elementos Web que necesita su página, al igual que Classpiece.<br>
							El proyecto de Classpiece demandaba mucho tiempo administrarla, así que ,lo reducimos en esta nueva versión.<br>
							Gracias<br>

							<p> Thanks for using Classpiece !!! ... See you soon Classpiece ... </p>
							Hi, I\'m Washington Llacsa M. developer and programmer of Classpiece.com <br>
							I present to you my new project called ‘<strong> Frelements </strong>’ where you will search for the Web Elements your page needs, as well as Classpiece. <br>
							The Classpiece project demanded a lot of time to manage it, so we reduced it in this new version. <br>
							Thanks <br><br>

							Atte: Ing.Washi<br>
							<p class="note">Nota: Su cuenta de Classpiece  se conservará en Frelements</p>
							<a href="https://frelements.com" class="rc-link">ir A FRELEMENTS</a>
					</div>
				</body>
			</html>
			';
			return $str;
		}

		//-------------------------------------------------------------//
		// 				          mostrar examen                        
		//-------------------------------------------------------------//

		function str_test_header($obj,$cad=array()){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{
				//Descripcion
				$descripcion = $this->parents->gn->rtn_exam_descripcion($obj->idExamen);

				$str = '
					<div class="'.$obj->prop1.' rounded-t p-2">
					    <div class="px-4 py-1 text-sm text-gray-100 border rounded border-white '.$obj->prop5.' float-right cursor-pointer">Testwink<i><span>1</span></i></div>
					    <h3 class="text-white text-3xl p-3 clear-right">
					       '.$obj->titulo.'
					    </h3>
					    <p class="text-gray-100 font-light p-3">
					    	'.$descripcion.'
					    </p>
					</div>
				';

			}

			if($obj->estilo == 'LINK')
			{
				
				$data     = htmlspecialchars(json_encode(array("id1"=>"#idDetails","id2"=>"#iconDown","icon_1"=>"icon-down-open-big","icon_2"=>"icon-up-open-big")));

				//Duración o tiempo transcurrido		
				$rc       = $this->parents->gn->rtn_consulta('registro_inicial,registro_final','admision','idAdmision='.$obj->idad);
				$duracion = $this->parents->gn->rtn_duracion($rc[0]->registro_final,$rc[0]->registro_inicial);


				$str = '
			        <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-xs-12 col-sm-8 col-md-5 position-relative">
                                <div class="row">
                                  <div class="col-8">
                                    <div class="title text-truncate" title="'.$obj->titulo.'">'.$obj->titulo.'</div>
                                  </div>
                                  <div class="col-4">
                                      <div class="container-meter float-end">
                                            <span class="meter">'.$obj->num_preguntas_resueltas.' de '.$obj->num_preguntas.'</span>
                                            <span class="icon-down-open-big" event="control" event-type="slide_icon" event-data="'.$data.'" id="iconDown" title="Detalles"></span>
                                            <div class="details '.$obj->prop1.' display-n" id="idDetails">
                                                <div class="section">
                                                    <label>Título</label>
                                                    <div>'.$obj->titulo.'</div>
                                                </div>
                                                <div class="section col-3">
                                                    <label>Tiempo</label>
                                                    <div class="clock">'.$duracion.'</div>
                                                </div>
                                                <button class="btn-test-link send" data-destine="view/cancelarTest" data-data=\'{"idex":"'.$obj->idex.'","idad":"'.$obj->idad.'"}\'>
                                                    Cancelar test
                                                </button>
                                            </div>
                                      </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
				';
				
			}

			if($obj->estilo == 'FIXED')
			{				
				//Test concluida
				$concluded   = ($this->parents->gn->rtn_estado_admision($obj->idad) == 'CONCLUIDA')? $this->str_test_concluded($obj):'';

				//Descripcion
				$descripcion = $this->parents->gn->rtn_exam_descripcion($obj->idExamen);

				//Lista test
				$item = '';
				$i    = 0;
				foreach($this->parents->gn->rtn_consulta("idPregunta","pregunta","idExamen=".$obj->idExamen) as $val){
					$i++;
					$item .= '<li><a event="control" event-type="scrolltop" event-data=\'{"id":"#preg'.$i.'","rest":"60"}\'><span>'.$i.'</span></a></li>';
				}

				//Duración o tiempo transcurrido
				$rc       = $this->parents->gn->rtn_consulta('registro_inicial,registro_final','admision','idAdmision='.$obj->idad);
				$duracion = $this->parents->gn->rtn_duracion($rc[0]->registro_final,$rc[0]->registro_inicial);

				$str = '
                    <div class="title">'.$obj->titulo.'</div>
                    '.$concluded.'
                    <div class="description">
                    '.$descripcion.'
                    </div>
                    
                    <div class="container-menu" id="containerMenu">
                        
                        <div class="row justify-content-center">
                            <div class="col-12 col-sm-10 col-md-9 col-lg-7">
                                
                                <div class="row position-relative">
                                    <div class="col-10">
                                        <div class="container-list-num">
                                            <ul class="list-num">
                                                '.$item.'
                                                <li><a event="control" event-type="scrolltop" event-data=\'{"id":"#result","rest":"60"}\' class="result '.$obj->prop1.' '.$obj->prop2.'"><span>R</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        
                                        <span class="icon-down-open-big slide-icon" event="control" event-type="slide_icon" event-data=\'{"id1":"#idDetails","id2":"#iconDown","icon_1":"icon-down-open-big","icon_2":"icon-up-open-big"}\' id="iconDown" title="Detalles"></span>
                                        
                                        <div class="container-details display-n" id="idDetails">
                                            <div class="section">
                                                <label>Tiempo transcurrido :</label>
                                                <span class="clock">'.$duracion.'</span>
                                                <button class="btn-fixed '.$obj->prop1.'" id="showClock">Mostrar</button>
                                            </div>
                                            <div class="section">
                                                <label>Ver resultado :</label>
                                                <button event="control" event-type="scrolltop" event-data=\'{"id":"#result","rest":"60"}\' class="btn-fixed '.$obj->prop1.'">R</button>
                                            </div>
                                            <div class="section">
                                                <label>Cancelar el test :</label>
                                                <button class="btn-fixed '.$obj->prop1.'">Cancelar</button>
                                            </div>
                                        </div><!--/container-details-->
                                        
                                        <div class="container-clock">
                                            <span class="clock">'.$duracion.'</span>
                                            <i class="icon-cancel-circle cancel" id="cancelClock"></i>
                                        </div><!--/container-clock-->
                                        
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
				';

			}

			return $str;
		}

		function str_test_body($obj1,$obj2,$cad=array()){

			# $obj1: idex,idPregunta,descripcion,img,subdescripcion,tipo,idExamen,estilo,num,prop1,idad
			# $obj2: idAlternativa,descripcion,idPregunta,respuesta,idad,estilo,prop4

			$str = '';
			$idex    = $this->parents->gn->rtn_idex($obj1->idExamen);

			if($obj1->estilo == 'CLASSIC')
			{
				$img = ($obj1->img != "default.png")?'<img src="'.URL.'/data/img_exam/'.$idex.'/'.$obj1->img.'" id="editImg'.$obj1->idPregunta.'" />' : '<img src="'.URL_THEME.'/img/default/exam_testink.png" id="editImg'.$obj1->idPregunta.'/>' ;
				//$datos = htmlspecialchars(json_encode(array("id1"=>"#cont".$obj1->idPregunta,"id2"=>"#icon".$obj1->idPregunta,"icon_1"=>"icon-up-open","icon_2"=>"icon-down-open")));
				$obj1->cont = $cad['cont'];

				if($obj1->tipo == 'ELECCION_SIMPLE' || $obj1->tipo == 'ELECCION_MULTIPLE')
				{				

					$str = '
						<div class="container-question">
						    
						    <div class="question my-3">
						        '.$this->str_test_body_question($obj1).'
						    </div><!--/question-->
						    
						    <div class="alternative pl-3 subcont open" id="cont'.$obj1->idPregunta.'">						   
								'.$this->str_test_body_alternative($obj2).'
						    </div><!--/alternative-->
						    
						</div><!--/container-question-->
					';

				}
				if($obj1->tipo == 'ELECCION_MULT_IMG_IZQUIERDO')
				{

					$data1 = htmlspecialchars(json_encode(array("tipo"=>"pregunta","id1"=>$obj1->idPregunta)));
					$data2 = htmlspecialchars(json_encode(array("idPregunta"=>$obj1->idPregunta,"idex_tmp"=>$obj1->idex)));

					$str = '
						<div class="container-question">

							<div class="question my-3">
								'.$this->str_test_body_question($obj1).'							   
							</div><!--/question-->

							<div class="flex flex-wrap subcont open" id="cont'.$obj1->idPregunta.'">

								<div class="image w-full md:w-2/5">
									'.$this->str_test_body_image($obj1).'
								</div><!--/imagen-->

								<div class="w-full md:w-3/5">

									<div class="alternative pl-3">
										'.$this->str_test_body_alternative($obj2).'
									</div><!--/alternative-->

								</div>

							</div>

						</div><!--/container-question-->
					';
				}

				if($obj1->tipo == 'ELECCION_MULT_IMG_DERECHO')
				{

					$data1 = htmlspecialchars(json_encode(array("tipo"=>"pregunta","id1"=>$obj1->idPregunta)));
					$data2 = htmlspecialchars(json_encode(array("idPregunta"=>$obj1->idPregunta,"idex_tmp"=>$obj1->idex)));

					$str = '
						<div class="container-question">

							<div class="question my-3">
								'.$this->str_test_body_question($obj1).'							   
							</div><!--/question-->

							<div class="flex flex-wrap subcont open" id="cont'.$obj1->idPregunta.'">

								<div class="image w-full md:w-2/5 md:order-last">
									'.$this->str_test_body_image($obj1).'
								</div><!--/imagen-->

								<div class="w-full md:w-3/5">

									<div class="alternative pl-3">
										'.$this->str_test_body_alternative($obj2).'
									</div><!--/alternative-->

								</div>

							</div>

						</div><!--/container-question-->
					';

				}
			}

			if($obj1->estilo == 'LINK')
			{
				if($obj1->tipo == 'ELECCION_SIMPLE' || $obj1->tipo == 'ELECCION_MULTIPLE')
				{
					$str = '
                        <div class="container-question">
                            
                            <div class="row justify-content-center mr-0">
                                <div class="col-xs-12 col-sm-10 col-md-10 col-lg-7">
                                    
                                    <div class="question">
                                    	'.$this->str_test_body_question($obj1).'
                                    </div><!--/question-->
                                    <div class="alternative">
                                    	<form id="formAlt">
                                    		'.$this->str_test_body_alternative($obj2).'
                                    	</form>
                                    </div><!--/alternative-->
                                    
                                </div>
                            </div><!--/row-->
                            
                        </div><!--/container-question-->
					';
				}
				if($obj1->tipo == 'ELECCION_MULT_IMG_CENTER')
				{
					$str = '
						<div class="container-question">
						    
						    <div class="row justify-content-center mr-0">
						        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-7">
						            
						            <div class="question">
						                '.$this->str_test_body_question($obj1).'
						            </div><!--/question-->
						            <div class="image">
						                '.$this->str_test_body_image($obj1).'
						            </div><!--/image-->
						            <div class="alternative">
						            	<form id="formAlt">
						            		'.$this->str_test_body_alternative($obj2).'
						            	</form>
						            </div><!--/alternative-->
						            
						        </div>
						    </div><!--/row-->
						    
						</div><!--/container-question-->
					';
				}
			}

			if($obj1->estilo == 'FIXED')
			{
				if($obj1->tipo == 'ELECCION_SIMPLE' || $obj1->tipo == 'ELECCION_MULTIPLE')
				{
					$str = '
						<div class="container-question" id="preg'.$obj1->num.'">

						    <div class="question">
								'.$this->str_test_body_question($obj1).'
						    </div><!--/question-->

						    <div class="alternative">
						    	<form id="formAlt'.$obj1->idPregunta.'">
						    		'.$this->str_test_body_alternative($obj2).'
						    	</form>
						    </div><!--/alternative-->

							<div class="subdescription flex justify-center" id="subdesc'.$obj1->idPregunta.'">
								'.$this->str_test_body_subdescripcion($obj1).'
							</div><!--/subdescription-->	

						</div><!--/container-question-->
					';
				}
				if($obj1->tipo == 'ELECCION_MULT_IMG_TOP')
				{
					$str = '
						<div class="container-question" id="preg'.$obj1->num.'">

						    <div class="image">
						        '.$this->str_test_body_image($obj1).'
						    </div><!--/image-->

						    <div class="question">
								'.$this->str_test_body_question($obj1).'
						    </div><!--/question-->

						    <div class="alternative">
						    	<form id="formAlt'.$obj1->idPregunta.'">
						    	'.$this->str_test_body_alternative($obj2).'
						    	</form>
						    </div><!--/alternative-->

							<div class="subdescription flex justify-center" id="subdesc'.$obj1->idPregunta.'">
								'.$this->str_test_body_subdescripcion($obj1).'
							</div><!--/subdescription-->	

						</div><!--/container-question-->
					';
				}
			}

			return $str;
		}

		function str_test_body_question($obj){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{
				$datos = htmlspecialchars(json_encode(array("id1"=>"#cont".$obj->idPregunta,"id2"=>"#icon".$obj->idPregunta,"icon_1"=>"icon-up-open","icon_2"=>"icon-down-open")));
				$str = '
				    <div class="title flex">
                    	<div class="text-lg">'.$obj->cont.'.</div>
                    	<div class="text-lg font-medium pl-0.5">
                    	    '.$obj->descripcion.'
                    	</div>
                        <span class="container-eye icon-down-open hidden text-gray-300 cursor-pointer" event="control" event-type="slide_icon" event-data="'.$datos.'" id="icon'.$obj->idPregunta.'" title="Ocultar pregunta"></span>
                    </div>
				';
			}

			if($obj->estilo == 'LINK')
			{
				$str ='
 					<div class="title">'.$obj->descripcion.'</div>
				';
			}

			if($obj->estilo == 'FIXED')
			{
				$str ='
					<div class="title">
						<div class="num">'.$obj->num.'<span class="vertical-line '.$obj->prop1.'"></span></div>
						'.$obj->descripcion.'
					</div>
				';
			}

			return $str;

		}

		function str_test_body_image($obj){

			$str = '';
			$urlImg  = ($obj->img != 'default.png')? URL.'/data/img_exam/'.$obj->idex.'/'.$obj->img.'?upd='.rand() : URL_THEME.'/img/default/exam_testink.png';

			if($obj->estilo == 'CLASSIC')
			{
				$str = '
					<div class="mx-2 my-2">
						<img src="'.$urlImg.'" alt="">    
					</div>
				';
			}

			if($obj->estilo == 'LINK')
			{
				$str ='
					<img src="'.$urlImg.'" alt="">
				';
			}

			if($obj->estilo == 'FIXED')
			{
				$str ='
					<img src="'.$urlImg.'" alt="" class="m-auto">
				';
			}

			return $str;

		}

		function str_test_body_alternative($resultado){

			$str  = '';
			$ind  = 0;

			foreach($resultado as $obj){
				
				//$data = htmlspecialchars(json_encode(array("tipo"=>"alt","id1"=>$obj->idPregunta,"id2"=>$obj->idAlternativa)));


				$type = 'radio';
				$name = $obj->idPregunta;

				// ES y EM
				if($this->parents->gn->rtn_tipo_eleccion($obj->idPregunta) == 'EM'){
					$type = 'checkbox';
					$name = $obj->idAlternativa;
				}

				if($obj->estilo == 'CLASSIC')
				{
					if($this->parents->session->check_login()){

						$idad = (isset($obj->idad))? $obj->idad:0;
						$alfa = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','z','y','z');
						
						$data = htmlspecialchars(json_encode(array("idad"=>$idad,"idPregunta"=>$obj->idPregunta,"idAlternativa"=>$obj->idAlternativa)));

						$attr_alternativa = '';
						$attr_disabled    = '';
						$attr_checked     = ($this->parents->gn->existe_registro("respuesta","idAdmision=".$idad." AND idPregunta=".$obj->idPregunta." AND idAlternativa=".$obj->idAlternativa." AND idUsuario=".$this->idUsuario))?"checked":"";

						$attr_send1 = 'send-nopd';
						$attr_send2 = 'data-destine="init/guardarRespuesta" data-data="'.$data.'"';

						$id_editanswer = 'id = "editAnswer'.$obj->idAlternativa.'"';

						$attr_correcta = '';

						$msj = '';

						//Existe admisión concluida
						if($this->parents->gn->existe_admision_concluida($idad)){

							$attr_correcta = ($obj->respuesta =='SI')?'py-1 px-2 bg-green-100':'';

							$obj->idad = $idad;
							$obj->idAlternativa = $obj->idAlternativa;

							$vri = $this->parents->gn->verificar_alt_correcta_incorrecta($obj,$this->idUsuario);

							if($vri === true)  $attr_alternativa = 'icon-check-1 text-green-400';
							if($vri === false) $attr_alternativa = 'icon-cancel-5 text-red-400';
						    if($vri === null)  $attr_alternativa = '';

						    $msj = $this->parents->gn->verificar_preg_correcta($obj->idad,$obj->idPregunta,$this->idUsuario);

							$attr_disabled    = 'disabled';
							$attr_alt_json    = '';

							$attr_send1       = '';
							$attr_send2       = '';

							$id_editanswer    = '';

						}

						//Para mostra alternativas en Editar aspecto de test
						if(isset($obj->aspecto) && $obj->aspecto === true){
							$attr_send1 = '';
							$attr_send2 = '';
						}

						$str .= '
							<div class="alt flex relative">
								<i class="'.$attr_alternativa.' absolute top-3 -left-5 " '.$id_editanswer.'></i>
								<input type="'.$type.'" name="alt'.$name.'" id="alt'.$obj->idAlternativa.'" class="hidden" '.$attr_checked.' '.$attr_disabled.'>
								<label class="'.$obj->prop4.' rounded-lg pr-2 pb-1 '.$attr_send1.'" '.$attr_send2.' for="alt'.$obj->idAlternativa.'">
								    <div class="flex p-1 cursor-pointer">
								       <div class="'.$obj->prop6.' showicon relative text-3xl '.$obj->prop2.'"><span class="absolute text-base left-4 '.$obj->prop3.'" style="top:0.4rem;">'.strtoupper($alfa[$ind]).'</span></div>
								       <div class="rounded-lg pt-1.5 '.$attr_correcta.'">'.$obj->descripcion.$msj.'</div>
								   </div>
								</label>
							</div>
						';

					}else{


					}

				}

				if($obj->estilo == 'LINK')
				{
 
					$str .= '

                        <div class="alt">
                            <input type="'.$type.'" id="alt'.$obj->idAlternativa.'" name="alt[]" value="'.$obj->idAlternativa.'">
                            <label class="border-'.($ind+1).'" for="alt'.$obj->idAlternativa.'">
                                '.$obj->descripcion.'
                            </label>
                        </div>
					';
				}

				if($obj->estilo == 'FIXED')
				{
					$data = htmlspecialchars(json_encode(array("idPregunta"=>$obj->idPregunta,"idAlternativa"=>$obj->idAlternativa)));

					$attr_alt1 = '';
					$attr_alt2 = '';
								
					$arrayIdAlt = array();
					$rc = $this->parents->gn->rtn_consulta("idAlternativa","respuesta","idAdmision=".$obj->idad." AND idPregunta=".$obj->idPregunta." AND idUsuario=".$this->idUsuario);
					foreach($rc as $obj1){ $arrayIdAlt[] = $obj1->idAlternativa;}

					//Existe pregunta resuelta
					if($this->parents->gn->existe_preg_resuelta($obj->idad,$obj->idPregunta)){

						//pregunta alternativa resuelta
						if($this->parents->gn->existe_alt_resuelta($obj->idad,$obj->idPregunta,$obj->idAlternativa)){

							//verificar respuesta
							if($this->parents->gn->verificar_respuesta($arrayIdAlt,$obj->idPregunta)){
								$attr_alt1 = 'border:true';
							}else{
								$attr_alt1 = 'border:false';
							}

						}

						//verificar alternativa correcta ,verificando la respuesta
						if($this->parents->gn->verificar_alt_correcta($obj->idAlternativa,$obj->idPregunta) && !$this->parents->gn->verificar_respuesta($arrayIdAlt,$obj->idPregunta)){
							$attr_alt2 = "bg:true";
						}

						$obj->prop4 = '';
					}


					$str .= '
						<div class="alt">
							<input type="'.$type.'" id="alt'.$obj->idAlternativa.'" name ="alt[]" value="'.$obj->idAlternativa.'">
							<label class="'.$obj->prop4.' '.$attr_alt1.' '.$attr_alt2.'" for="alt'.$obj->idAlternativa.'" data-json="'.$data.'" alt="select">
								'.$obj->descripcion.'
							</label>
						</div>
					';
				}

				$ind++;
			}

			return $str;

		}

		function str_test_body_subdescripcion($obj){

			$str = '';	

			if($obj->estilo == 'CLASSIC')
			{
				$str = '

				';
			}

			if($obj->estilo == 'LINK')
			{
				$str ='

				';
			}

			if($obj->estilo == 'FIXED')
			{
				//verificar pregunta resuelta				
				if($this->parents->gn->verificar_preg_resuelta($obj->idad,$obj->idPregunta,$this->idUsuario)){
					$subdescripcion = ($this->parents->gn->verifica_valor($obj->subdescripcion))? $obj->subdescripcion:null;
					//$str = '<div class="w-full sm:w-1/2 pb-3">'.$subdescripcion.'</div>';
					$str = $this->str('subdescripcion',[$subdescripcion]);
				}				
				
			}

			return $str;

		}

		function str_test_footer($obj){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{

			}
			if($obj->estilo == 'LINK')
			{
				$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idad"=>$obj->idad,"idPregunta"=>$obj->idPregunta)));
				$str ='
					<div class="next" id="siguiente1" data-destine = "view/guardarRespuestaLink" data-data="'.$data.'" data-serialize="formAlt"><i class="icon-right-bold"></i></div>
				';
			}

			return $str;
		}

		function str_test_respuesta($obj){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{

			}
			if($obj->estilo == 'LINK')
			{

				$data     = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idad"=>$obj->idad)));
				$idExamen = $this->parents->gn->rtn_id($obj->idex);

				$class1 = ($obj->rpta)? 'correct' : 'error';
				$class2 = ($obj->rpta)? 'icon-check-1' : 'icon-cancel-5';				

				$strSelect  = '';
				$strCorrect = '';

				foreach($obj->arraySelect as $val){//mejorar
					$strSelect .= '<li class="title"><i class=""></i> '.$val["descripcion"].'</li>';
				}

				if(!$obj->rpta){
					foreach($obj->arrayCorrect as $obj1){
						$strCorrect .= '<li class="title"><i class="icon-check-1"></i> '.$obj1->descripcion.'</li>';
					}
				}

				$script = '';
				$points = ($obj->rpta)? $this->parents->gn->rtn_puntuacion_pregunta($idExamen) : 0;

				if($points > 0){
					$script = '
						<script>
							$(function(){
							    var num  = '.$points.';//config
							    var i    = 0;
							    var time = 0;

							    var min = 6;//config
							    i = (num > min)? num-min : i;

							    var si = setInterval(function(){
							       i++;
							       if(i == num){
							          $("#numPoint").html(\'<div class="animate__animated animate__heartBeat number">+\'+i+\'</div>\');
							            clearInterval(si);
							       }else{
							           $("#numPoint").html(\'<div class="animate__animated animate__bounceIn number">\'+i+\'</div>\');
							       }
							      
							   },200);
							});
						</script>
					';
				}else{
					$points = '<div class="animate__animated animate__heartBeat number">+0</div>';
				}

				$str ='
					<div class="modal-header '.$class1.' d-block">
					    <div class="row">
					        <div class="col-4">
					            <i class="'.$class2.'"></i>
					        </div>
					        <div class="col-8">					      
					            <ul class="list-style-type:none">
					            	'.$strSelect.'
					            </ul>
					        </div>
					    </div>
					</div>
					<div class="modal-body '.$class1.'">
					    <div class="row">
					        <div class="col-4">
					            <div class="point">
					                <div class="num" id="numPoint">'.$points.'</div>
					                <span>Points</span>
					            </div>
					        </div>
					        <div class="col-8">
					        	<ul class="list-style-type:none mt-2">
					            	'.$strCorrect.'
					            </ul>
					            <p></p>
					        </div>
					    </div>
					</div>
					<div class="modal-footer">
					    <button type="button" class="btn btn-primary" id="siguiente2" data-data="'.$data.'">Siguiente</button>
					</div>
					'.$script.'
				';
			}


			return $str;
		}

		function str_test_result($obj){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{
				//$rp = $this->parents->gn->rtn_puntuacion($obj->idad,$obj->idExamen,$obj->idUsuario);
				$rp = $this->parents->gn->rtn_puntuacion_full($obj->idad,$obj->idExamen);

				$num_preguntas    = $rp["num_preguntas"];
				$acertada         = $rp["acertada"];

				$str = '
					<div class="bg-gray-50 rounded-lg m-2 p-3 flex justify-center items-center flex-col h-30">
						<div class="font-semibold text-4xl text-gray-500">'.$acertada.' / '.$num_preguntas.'</div> 
						<h3 class="p-2 font-light">correctas</h3>
					</div>
				';

				//Guardar datos del resultado
				$array = array(
					"idAdmision"    => $obj->idad,
					"idExamen"      => $obj->idExamen,
					"num_preguntas" => $rp["num_preguntas"],
					"acertada"      => $rp["acertada"],
					"fallida"       => $rp["fallida"],
					"resuelta"      => $rp["resuelta"],
					"no_resuelta"   => $rp["no_resuelta"],
					"premio"        => 'NINGUNO',
					"idUsuario"     => $obj->idUsuario
				);

				$this->parents->gn->guardar_resultado_exam($array);
				
			}

			if($obj->estilo == 'LINK')
			{				
				$titulo = $this->parents->gn->rtn_consulta("titulo","examen","idExamen=".$obj->idExamen);
				$titulo = $titulo[0]->titulo;

				$rp = $this->parents->gn->rtn_puntuacion_full($obj->idad,$obj->idExamen);

				$puntuacion       = $rp["puntuacion"];
				$puntuacion_total = $rp["puntuacion_total"];
				$acertada         = $rp["acertada"];
				$fallida          = $rp["fallida"];
				$num_preguntas    = $rp["num_preguntas"];

				$porcent_acertada = (100*$acertada) / $num_preguntas;
				$porcent_fallida  = (100*$fallida) / $num_preguntas;

				$porcent_acertada = round($porcent_acertada,0);
				$porcent_fallida  = round($porcent_fallida,0);
				
				$str ='
					<div class="container-result">
					    <div class="header">
					        <div class="title"> Felicidades haz culminado el test</div>
					    </div><!--/header-->
					    <div class="body">
					        <div class="title">'.$titulo.'</div>
					        <div class="point">
					    		<span>hoy día ganaste</span>
					            <div class="num" id="numPoint">+'.$puntuacion.'</div>
					            <span>Points</span>
					        </div>
					    </div><!--/body-->
					    <div class="footer">
					        <div class="percent correct">
					            <svg>
					                <circle cx="40" cy="40" r="40"></circle>
					                <circle cx="40" cy="40" r="40"></circle>
					            </svg>
					            
					            <div class="number">'.$porcent_acertada.'%</div>
					            <div class="text">CORRECTA</div>
					        </div>
					        
					        <div class="percent error" percent=40>
					            <svg>
					                <circle cx="40" cy="40" r="40"></circle>
					                <circle cx="40" cy="40" r="40"></circle>
					            </svg>
					            
					            <div class="number">'.$porcent_fallida.'%</div>
					            <div class="text">INCORRECTA</div>
					        </div>

					        <div class="...">
					        	'.$this->str('buscar-mas',[$titulo,"Buscar similares"]).'
					        </div><!--/...-->

					    </div><!--/footer-->
					</div><!--/container-result-->
					<script>
						function porcent(){
							$(".percent").each(function(){
								$(".percent.correct svg circle:nth-child(2)").css("stroke-dashoffset","calc(251 - (251 * '.$porcent_acertada.') / 100)");
								$(".percent.error svg circle:nth-child(2)").css("stroke-dashoffset","calc(251 - (251 * '.$porcent_fallida.') / 100)");
							});
						}
						$(function(){ porcent(); });
					</script>
				';

				//Guardar datos del resultado
				$array = array(
					"idAdmision"    => $obj->idad,
					"idExamen"      => $obj->idExamen,
					"num_preguntas" => $rp["num_preguntas"],
					"acertada"      => $rp["acertada"],
					"fallida"       => $rp["fallida"],
					"resuelta"      => $rp["resuelta"],
					"no_resuelta"   => $rp["no_resuelta"],
					"premio"        => 'NINGUNO',
					"idUsuario"     => $obj->idUsuario
				);

				if($this->parents->session->check_login()){
					$this->parents->gn->guardar_resultado_exam($array);
				}

			}

			if($obj->estilo == 'FIXED')
			{

					$porcent_acertada = 0;
					$porcent_fallida  = 0;

					$number  = '0/0';
					$points  = '<br>';
					$class_default = 'default';
					$script        = '';

					if($this->parents->gn->existe_admision_concluida($obj->idad)){
						
						
						$rp = $this->parents->gn->rtn_puntuacion_full($obj->idad,$obj->idExamen);

						$puntuacion       = $rp["puntuacion"];
						$puntuacion_total = $rp["puntuacion_total"];
						$acertada         = $rp["acertada"];
						$fallida          = $rp["fallida"];
						$num_preguntas    = $rp["num_preguntas"];
						
						$porcent_acertada = (100*$acertada) / $num_preguntas;
						$porcent_fallida  = (100*$fallida) / $num_preguntas;

						$porcent_acertada = round($porcent_acertada,0);
						$porcent_fallida  = round($porcent_fallida,0);
						
						$number  = $acertada.'/'.$num_preguntas;
						$points  = '<span class="b">Hoy ganaste</span><span class="c">+'.$puntuacion.' points</span>';

						$class_default = '';
						$script        = '
							<script>
								var  perc2 = document.querySelector(".percent svg circle:nth-child(2)");
								perc2.style.cssText = \'stroke-dashoffset:calc(380 - (380 * '.$porcent_acertada.') / 100)\';								
							</script>
						';

						//Guardar datos del resultado
						$array = array(
							"idAdmision"    => $obj->idad,
							"idExamen"      => $obj->idExamen,
							"num_preguntas" => $rp["num_preguntas"],
							"acertada"      => $rp["acertada"],
							"fallida"       => $rp["fallida"],
							"resuelta"      => $rp["resuelta"],
							"no_resuelta"   => $rp["no_resuelta"],
							"premio"        => 'NINGUNO',
							"idUsuario"     => $obj->idUsuario
						);

						$this->parents->gn->guardar_resultado_exam($array);
					}

					$str = '
						<div class="percent '.$class_default.'">
							<svg>
								<circle cx="40" cy="40" r="60"></circle>
								<circle cx="40" cy="40" r="60"></circle>
							</svg>

							<div class="number">'.$number.'</div>
							<div class="text">
								<span class="a">ACIERTOS</span>
								'.$points.'
							</div>
						</div>
						'.$script.'
					';

			}

			return $str;
		}

		function str_test_concluded($obj){
			
			$str = '';

			if($obj->estilo == 'CLASSIC')
			{
				$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idad"=>$obj->idad,"tipo"=>"exam")));

				$str = '
					<div class="container-concluded flex justify-center mb-6">
						<div class="'.$obj->prop1.' py-2 px-6 pt-4 m-2 shadow-lg rounded-sm text-center">
							<h3 class="text-gray-50 md:text-xl font-semibold">TEST VIRTUAL CONCLUIDA</h3>
							<a class="icon-doc-text py-1 text-gray-100 hover:bg-gray-200 hover:text-gray-700 block send" data-destine="init/mostrarModalResultadosExamen" data-data="'.$data.'">Ver más resultados</a>
						</div>
					</div>
				';
			}

			if($obj->estilo == 'FIXED')
			{
				$str = '
					<div class="container-concluded '.$obj->prop1.'">
					    <span>TEST VIRTUAL CONCLUIDA</span>
					    <a event="control" event-type="scrollfast" event-data=\'{"id":"#result","rest":"60"}\'>Ver resultados</a>
					</div>
				';
			}

			return $str;
		}

		function str_test_cancelada($obj){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{
				$str = '
					<div class="container-concluded flex justify-center mb-6">
						<div class="'.$obj->prop1.' py-2 px-6 pt-4 m-2 shadow-lg rounded-sm text-center">
							<h3 class="text-gray-50 md:text-xl font-semibold">TEST VIRTUAL CANCELADA</h3>
						</div>
					</div>
				';
			}

			return $str;

		}

		function str_test_element($obj){

			$str = '';

			if($obj->tipo == 'CLASSIC')
			{

			}

			if($obj->tipo == 'FIXED')
			{			

				if($obj->subtipo == 'button')
				{

					$data = htmlspecialchars(json_encode(array("idad"=>$obj->idad)));

					$str = '					
						<button class="btn btn-lg btn-score '.$obj->prop1.' send" id="btnscore" data-destine="view/puntuarTestFixed" data-data="'.$data.'">PUNTUAR</button>
					';
				}

				if($obj->subtipo == 'container-result-footer')
				{
					$idUsuario       = $this->parents->gn->rtn_consulta_unica('idUsuario','examen',"idex='".$obj->idex."'");
					$idExamen        = $this->parents->gn->rtn_id($obj->idex);
					$nombre_publico  = $this->parents->gn->rtn_exam_nombre_publico($idExamen);
					$ver_test_public = $this->str('link-ver-test-public',[$nombre_publico,$idUsuario,'+ test']);

					$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex)));

					$str = '
						<div class="row">
							<div class="col-5">
								<label>Compartir</label>
								<div class="social-bar">
									<a class="send" data-destine="admin/mostrarModalCompartir" data-data="'.$data.'"><i class="icon-whatsapp"></i></a>
									<a class="send" data-destine="admin/mostrarModalCompartir" data-data="'.$data.'"><i class="icon-facebook"></i></a>
									<a class="send" data-destine="admin/mostrarModalCompartir" data-data="'.$data.'"><i class="icon-twitter"></i></a>
									<a class="send" data-destine="admin/mostrarModalCompartir" data-data="'.$data.'"><i class="icon-google"></i></a>
								</div>
							</div>							
							<div class="col-7">
								<label>¿Qué piensas del test?</label>
								<div class="emoticons">
									<a class="send cursor-p" data-destine="view/selectValoracion" data-data=\'{"slc":1,"idex":"'.$obj->idex.'"}\'><i class="icon-smile"></i></a>
									<a class="send cursor-p" data-destine="view/selectValoracion" data-data=\'{"slc":2,"idex":"'.$obj->idex.'"}\'><i class="icon-meh"></i></a>
									<a class="send cursor-p" data-destine="view/selectValoracion" data-data=\'{"slc":3,"idex":"'.$obj->idex.'"}\'><i class="icon-frown"></i></a>
									<a class="report cursor-p send" data-destine="view/reportarValoracion"  data-data=\'{"slc":4,"idex":"'.$obj->idex.'"}\'>Report</a>
								</div>
							</div>
						</div>
						<div class="bottom">AUTOR DEL TEST <strong>'.$nombre_publico.'</strong> / '.$ver_test_public.'</div>
					';
				}

			}

			return $str;
		}

		//-------------------------------------------------------------//
		// 				          Editar examen                        
		//-------------------------------------------------------------//

		function str_edit_tipo_estilo($obj1,$obj2,$cad=array()){

			$str  = '';
			$data = htmlspecialchars(json_encode(array("idPregunta"=>$obj1->idPregunta,"estilo"=>$obj1->estilo)));

			if($obj1->estilo == 'CLASSIC')
			{
				if($obj1->tipo == 'ELECCION_SIMPLE' || $obj1->tipo == 'ELECCION_MULTIPLE')
				{
					$str = '
						<div class="edit:container-question edit:classic" id="editCont'.$obj1->idPregunta.'" order="'.$obj1->orden.'">
	                        <div class="edit:config" data-json="'.$data.'">
	                        	'.$this->str_edit_config($obj1->orden).'
	                        </div><!--/config-->
						    <div class="edit:question">
						        '.$this->str_edit_pregunta($obj1).'
						    </div><!--/question-->
						    <div class="edit:alternative" id="editAlternative'.$obj1->idPregunta.'">
								'.$this->str_edit_alternativa($obj2).'
						    </div><!--/alternative-->
							<div class="edit:subdescription" id="editSubdescription'.$obj1->idPregunta.'">
								'.$this->str_edit_subdescripcion($obj1).'
							</div><!--/subdescription-->
						</div><!--/container-question-->
					';
				}			
				if($obj1->tipo == 'ELECCION_MULT_IMG_IZQUIERDO'){
					$str = '
						<div class="edit:container-question edit:classic" id="editCont'.$obj1->idPregunta.'" order="'.$obj1->orden.'">
						
							<div class="edit:config" data-json="'.$data.'">
								'.$this->str_edit_config($obj1->orden).'
							</div><!--/config-->

							<div class="edit:question">
							    '.$this->str_edit_pregunta($obj1).'
							</div><!--/question-->

							<div class="row">
							    <div class="col-xs-12 col-sm-6 col-md-5">
							        <div class="edit:image">
							        	'.$this->str_edit_imagen($obj1).'
							        </div><!--/image-->
							    </div>
							    <div class="col-xs-12 col-sm-6 col-md-7">
							        <div class="edit:alternative" id="editAlternative'.$obj1->idPregunta.'">
							        	'.$this->str_edit_alternativa($obj2).'
							        </div><!--/alternative-->
									<div class="edit:subdescription" id="editSubdescription'.$obj1->idPregunta.'">
										'.$this->str_edit_subdescripcion($obj1).'
									</div><!--/subdescription-->
							    </div>
							</div>

						</div><!--/container-question-->
					';
				}
				if($obj1->tipo == 'ELECCION_MULT_IMG_DERECHO'){
					$str = '
						<div class="edit:container-question edit:classic" id="editCont'.$obj1->idPregunta.'" order="'.$obj1->orden.'">

							<div class="edit:config" data-json="'.$data.'">
								'.$this->str_edit_config($obj1->orden).'
							</div><!--/config-->

							<div class="edit:question">
							    '.$this->str_edit_pregunta($obj1).'
							</div><!--/question-->

							<div class="row">
							    <div class="col-xs-12 col-sm-6 col-md-7">
							    	<div class="edit:alternative" id="editAlternative'.$obj1->idPregunta.'">
							        	'.$this->str_edit_alternativa($obj2).'
							        </div><!--/alternative-->
							        <div class="edit:subdescription" id="editSubdescription'.$obj1->idPregunta.'">
										'.$this->str_edit_subdescripcion($obj1).'
									</div><!--/subdescription-->
							    </div>
							    <div class="col-xs-12 col-sm-6 col-md-5">
									<div class="edit:image">
									'.$this->str_edit_imagen($obj1).'
									</div><!--/image-->
							    </div>
							</div>

						</div><!--/container-question-->
					';
				}

			}
			if($obj1->estilo == 'LINK')
			{			
				if($obj1->tipo == 'ELECCION_SIMPLE' || $obj1->tipo== 'ELECCION_MULTIPLE')
				{
					$str = '
						<div class="edit:container-question edit:link" id="editCont'.$obj1->idPregunta.'" order="'.$obj1->orden.'">
	                        <div class="edit:config" data-json="'.$data.'">
	                        	'.$this->str_edit_config($obj1->orden).'
	                        </div><!--/config-->
	                        
	                        <div class="row justify-content-center">
	                            <div class="col-xs-12 col-sm-6">
	                                
	                                <div class="edit:question">
	                                	'.$this->str_edit_pregunta($obj1).'
	                                </div><!--/question-->
	                                <div class="edit:image">	                         
	                                </div><!--/image-->
	                                <div class="edit:alternative" id="editAlternative'.$obj1->idPregunta.'">
	                                	'.$this->str_edit_alternativa($obj2).'
	                                </div><!--/alternative-->
	                                <div class="edit:subdescription" id="editSubdescription'.$obj1->idPregunta.'">
										'.$this->str_edit_subdescripcion($obj1).'
									</div><!--/subdescription-->
	                            </div>
	                        </div><!--/row-->
	                        
	                    </div><!--/container-question-->
					';
				}
				if($obj1->tipo== 'ELECCION_MULT_IMG_CENTER')
				{
					$str = '
						<div class="edit:container-question edit:link" id="editCont'.$obj1->idPregunta.'" order="'.$obj1->orden.'">

	                        <div class="edit:config" data-json="'.$data.'">
	                        	'.$this->str_edit_config($obj1->orden).'
	                        </div><!--/config-->

	                        <div class="row justify-content-center">
	                            <div class="col-xs-12 col-sm-6">
	                                
	                                <div class="edit:question">
	                                    '.$this->str_edit_pregunta($obj1).'
	                                </div><!--/question-->
	                                <div class="edit:image">
	                                	'.$this->str_edit_imagen($obj1).'
	                                </div><!--/image-->
	                                <div class="edit:alternative" id="editAlternative'.$obj1->idPregunta.'">
										'.$this->str_edit_alternativa($obj2).'
	                                </div><!--/alternative-->
	                                <div class="edit:subdescription" id="editSubdescription'.$obj1->idPregunta.'">
										'.$this->str_edit_subdescripcion($obj1).'
									</div><!--/subdescription-->
	                            </div>
	                        </div><!--/row-->
	                    </div><!--/container-question-->
					';
				}
			}

			if($obj1->estilo == 'FIXED'){
				if($obj1->tipo == 'ELECCION_SIMPLE'  || $obj1->tipo== 'ELECCION_MULTIPLE')
				{
					$str = '
						<div class="edit:container-question edit:fixed" id="editCont'.$obj1->idPregunta.'" order="'.$obj1->orden.'">
	                        <div class="edit:config" data-json="'.$data.'">
	                        	'.$this->str_edit_config($obj1->orden).'
	                        </div><!--/config-->
	                        
	                        <div class="row justify-content-center">
	                            <div class="col-xs-12 col-sm-8">
	                                
	                                <div class="edit:question">
	                                	'.$this->str_edit_pregunta($obj1).'
	                                </div><!--/question-->
	                               <div class="edit:image">	                         
	                                </div><!--/image-->
	                                <div class="edit:alternative" id="editAlternative'.$obj1->idPregunta.'">
	                                	'.$this->str_edit_alternativa($obj2).'
	                                </div><!--/alternative-->
									<div class="edit:subdescription" id="editSubdescription'.$obj1->idPregunta.'">
										'.$this->str_edit_subdescripcion($obj1).'
									</div><!--/subdescription-->
	                            </div>
	                        </div><!--/row-->
	                        
	                    </div><!--/container-question-->
					';
				}
				if($obj1->tipo== 'ELECCION_MULT_IMG_TOP')
				{
					$str = '
						<div class="edit:container-question edit:fixed" id="editCont'.$obj1->idPregunta.'" order="'.$obj1->orden.'">

	                        <div class="edit:config" data-json="'.$data.'">
	                        	'.$this->str_edit_config($obj1->orden).'
	                        </div><!--/config-->

	                        <div class="row justify-content-center">
	                            <div class="col-xs-12 col-sm-8">
	                               	<div class="edit:image">
	                                	'.$this->str_edit_imagen($obj1).'
	                                </div><!--/image-->
	                                <div class="edit:question">
	                                    '.$this->str_edit_pregunta($obj1).'
	                                </div><!--/question-->
	                                <div class="edit:alternative" id="editAlternative'.$obj1->idPregunta.'">
										'.$this->str_edit_alternativa($obj2).'
	                                </div><!--/alternative-->
	                                <div class="edit:subdescription" id="editSubdescription'.$obj1->idPregunta.'">
										'.$this->str_edit_subdescripcion($obj1).'
									</div><!--/subdescription-->
	                            </div>
	                        </div><!--/row-->
	                    </div><!--/container-question-->
					';
				}
			}
			return $str;
		}

		function str_edit_titulo($obj){

			$data = htmlspecialchars(json_encode(array("tipo"=>"examen","id0"=>$obj->idex)));

			$str = '
				<div class="mb-3 position-relative">
					<div class="title" contenteditable="true" data-json="'.$data.'" id="edit'.$obj->idex.'">
					'.$obj->titulo.'
					</div>
					<div class="dropdown float-end dropdown-header">
						<a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-down-open-1"></i></a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" data-json="'.$data.'">
						    <a class="dropdown-item" edit="new" type="pregunta">Nueva Pregunta</a>
							<a class="dropdown-item" edit="cancel">Cancelar edición</a>
						</div>
					</div>
				</div>
			';

			return $str;
		}

		function str_edit_descripcion($obj){

			$data = htmlspecialchars(json_encode(array("tipo"=>"descripcion","id0"=>$obj->idex)));

			$str = '
				<div class="edit:description mb-3 position-relative">
					<div class="description bg-light p-3 mb-3" contenteditable="true" data-json="'.$data.'" id="editDesc'.$obj->idex.'">
					'.$obj->descripcion.'
					</div>
					<div class="dropdown float-end dropdown-hover">
						<a class="icon-pencil-1" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" data-json="'.$data.'">
							<a class="dropdown-item" edit="cancel">Cancelar edición</a>
						</div>
					</div>
				</div>
			';

			return $str;
		}

		function str_edit_config($num=0){
			$str = '
				<div class="number">'.$num.'</div>
				<div class="option"><i class=" icon-wrench-1" edit="wrench"></i></div>
				<div class="arrow"><i class="icon-up-dir" move="up"></i><i class="icon-down-dir" move="down"></i></div>
			';
			return $str;
		}

		function str_edit_pregunta($obj){

			$data = htmlspecialchars(json_encode(array("tipo"=>"pregunta","id0"=>$obj->idex,"id1"=>$obj->idPregunta)));

			$str = '
			    <div class="title" contenteditable="true" data-json="'.$data.'" id="edit'.$obj->idPregunta.'">'.$obj->descripcion.'</div>
			    <div class="dropdown float-end dropdown-hover">
			    	<a class="icon-pencil-1" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
			    	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" data-json="'.$data.'">
			    		<a class="dropdown-item" edit="cancel">Cancelar edición</a>
			    		<a class="dropdown-item" edit="new" type="subdescripcion">Agregar descripción</a>
			    		<a class="dropdown-item" edit="remove">Eliminar</a>
			    		<div class="dropdown-divider"></div>
			    		<a class="dropdown-item" edit="new" type="alt">Nueva Alternativa</a>
			    		<a class="dropdown-item" edit="new" type="pregunta">Nueva Pregunta</a>			    	
			    	</div>
			    </div>
			';
			return $str;
		}

		function str_edit_imagen($obj){

			$img  = ($obj->img != 'default.png')? '<img src="'.URL.'/data/img_exam/'.$obj->idex.'/'.$obj->img.'?upd='.rand().'" alt="">': '';
			$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idPregunta"=>$obj->idPregunta)));

			$str1 = '
				<div class="file" id="file'.$obj->idPregunta.'">
					'.$img.'
					<form id="formArchivoImg'.$obj->idPregunta.'">
						<label for="idFile'.$obj->idPregunta.'">
							<input type="file" name="archivo" class="file d-none" id="idFile'.$obj->idPregunta.'" edit="img" data-destine="admin/guardarImgPreg" data-data="'.$data.'" data-serialize="formArchivoImg'.$obj->idPregunta.'">																		
							<span><i class="icon-picture-2"></i></span>
						</label>
					</form>
				</div>
			';

			
			$str2 = '
				<div class="file">
					<img src="'.$img.'" id="preview-avatar" alt="">
					<input type="file" class="form-file-input custom-file-input" id="avatar" name="archivo" data-target="preview-avatar" accept="image/png, image/jpeg">
					<label for="avatar">
						<span class="custom-file-label"><i class="icon-picture-2"></i></span>
					</label>
				</div>
			';
		
			return $str1;
		}

		function str_edit_alternativa($resultado){


			$str  = '';
			$ind  = 0;

			foreach($resultado as $obj){
				
				$data = htmlspecialchars(json_encode(array("tipo"=>"alt","id1"=>$obj->idPregunta,"id2"=>$obj->idAlternativa)));

				$respuesta = ($obj->respuesta == 'SI')?'icon-check-1':'';

				if($obj->estilo == 'CLASSIC')
				{
					$alfa = array('a','b','c','d','e','f','g','h','i','j','k');

					$str .= '
						<div class="edit:alt" id="editAlt'.$obj->idAlternativa.'">
							<i class="icon-answer '.$respuesta.'" id="editAnswer'.$obj->idAlternativa.'" title="Respuesta"></i>
							<div class="subtitle"><div id="idText'.$obj->idAlternativa.'" >'.strtoupper($alfa[$ind]).'.</div><div id="edit'.$obj->idAlternativa.'" contenteditable="true" data-json="'.$data.'" >'.$obj->descripcion.'</div></div>
							<div class="dropdown float-end dropdown-hover">
						    	<a class="icon-pencil-1" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
						    	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" data-json="'.$data.'">
									<a class="dropdown-item" edit="select" type="select">Seleccionar Rpta</a>
									<a class="dropdown-item" edit="select" type="deselect">Deseleccionar Rpta</a>
						    	    <div class="dropdown-divider"></div>
						    		<a class="dropdown-item" edit="cancel">Cancelar edición</a>
						    		<a class="dropdown-item" edit="new">Nueva alterntiva</a>
						    		<a class="dropdown-item" edit="remove">Eliminar</a>
						    	</div>
						    </div>
						</div>
					';

				}

				elseif($obj->estilo == 'LINK')
				{
					$str.='
						<div class="edit:alt" id="editAlt'.$obj->idAlternativa.'">
						    <i class="icon-answer '.$respuesta.'" id="editAnswer'.$obj->idAlternativa.'" title="Respuesta"></i>
						    <div class="subtitle" contenteditable="true" data-json="'.$data.'" id="edit'.$obj->idAlternativa.'">'.$obj->descripcion.'</div>
						    <div class="dropdown float-end dropdown-hover">
						    	<a class="icon-pencil-1" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
						    	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" data-json="'.$data.'">
									<a class="dropdown-item" edit="select" type="select">Seleccionar Rpta</a>
									<a class="dropdown-item" edit="select" type="deselect">Deseleccionar Rpta</a>
						    	    <div class="dropdown-divider"></div>
						    		<a class="dropdown-item" edit="cancel">Cancelar edición</a>
						    		<a class="dropdown-item" edit="new">Nueva alternativa</a>
						    		<a class="dropdown-item" edit="remove">Eliminar</a>
						    	</div>
						    </div>
						</div>
					';
				}

				elseif($obj->estilo == 'FIXED')
				{
					$str.='
						<div class="edit:alt" id="editAlt'.$obj->idAlternativa.'">
						    <i class="icon-answer '.$respuesta.'" id="editAnswer'.$obj->idAlternativa.'" title="Respuesta"></i>
						    <div class="subtitle" contenteditable="true" data-json="'.$data.'" id="edit'.$obj->idAlternativa.'">'.$obj->descripcion.'</div>
						    <div class="dropdown float-end dropdown-hover">
						    	<a class="icon-pencil-1" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
						    	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" data-json="'.$data.'">
									<a class="dropdown-item" edit="select" type="select">Seleccionar Rpta</a>
									<a class="dropdown-item" edit="select" type="deselect">Deseleccionar Rpta</a>
						    	    <div class="dropdown-divider"></div>
						    		<a class="dropdown-item" edit="cancel">Cancelar edición</a>
						    		<a class="dropdown-item" edit="new">Nueva alternativa</a>
						    		<a class="dropdown-item" edit="remove">Eliminar</a>
						    	</div>
						    </div>
						</div>
					';
				}

				$ind++;
			}

			return $str;
		}

		function str_edit_subdescripcion($obj,$cad = array()){

			$data = htmlspecialchars(json_encode(array("tipo"=>"subdescripcion","id1"=>$obj->idPregunta)));

			$str = '
				<div class="edit:subdesc mb-3 position-relative">
					<div class="subdesc bg-light p-3 mb-3" contenteditable="true" data-json="'.$data.'" id="editSubDesc'.$obj->idPregunta.'">
					'.$obj->subdescripcion.'
					</div>
					<div class="dropdown float-end dropdown-hover">
						<a class="icon-pencil-1"  role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" data-json="'.$data.'">
							<a class="dropdown-item" edit="cancel">Cancelar edición</a>
							<a class="dropdown-item" edit="remove">Eliminar</a>
						</div>
					</div>
				</div>
			';


			if(!(isset($cad['modifier']) && $cad['modifier'] == 'vacio')){
				$str = ($this->parents->gn->verifica_valor($obj->subdescripcion))? $str:'';			
			}

			return $str;
		}

		//-------------------------------------------------------------//
		// 				   	Editar aspecto del examen                        
		//-------------------------------------------------------------//

		function str_edit_aspecto_test($obj){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{				
				$str = '

					<div class="container">				
						<div class="row justify-content-center">
							<div class="col-12 col-sm-10 col-md-9 col-lg-8">

								<div class="container-test bg-white my-3 mb-12 sm:my-5 shadow-lg rounded-b">
									<div class="test-header">
										<div class="'.$obj->prop1.' rounded-t p-2">
										    <div class="px-4 py-1 text-sm text-gray-100 border rounded border-white '.$obj->prop5.' float-right cursor-pointer">Testwink</div>
										    <h3 class="text-white text-3xl p-3 clear-right">
										       '.$obj->titulo.'
										    </h3>
										    <p class="text-gray-100 font-light p-3">
										    	'.$obj->descripcion.'
										    </p>
										</div>
										<div class="flex flex-row-reverse">
										    <div class="bg-gray-50 rounded-bl-lg pl-4 pr-3 py-3">
										      <a class="icon-clock-1 text-gray-600" title="reloj"></a>
										      <a class="icon-doc-text text-gray-600" title="Resultados del examen"></a>
										      <a class="icon-eye-off-1 text-gray-600" title="Proteger preguntas"></a>
										    </div>
										</div>
									</div><!--test-header-->
									<div class="test-body p-3 relative">
										'.$obj->body.'
									</div><!--/test-body-->
									<div class="test-footer">
										<div class="bg-gray-50 rounded-b p-2">
											<div class="flex justify-center">
												<div class="'.$obj->prop1.' hover:bg-indigo-700 text-gray-50 py-3 px-4 m-3 text-xl cursor-pointer rounded">
													Enviar respuestas
												</div>
											</div>
											<div class="w-full h-20">
											</div>
										</div>		                	
									</div><!--/test-footer-->

								</div><!--/container-test-->

							</div>
						</div>
					</div>
				';
			}
			if($obj->estilo == 'LINK')
			{
				$data     = htmlspecialchars(json_encode(array("id1"=>"#idDetails","id2"=>"#iconDown","icon_1"=>"icon-down-open-big","icon_2"=>"icon-up-open-big")));

				$str = '
					<div class="'.$obj->prop1.' position-relative">
					    <div class="container">
					        <div class="row justify-content-center pb-5">
					            <div class="col-12 col-sm-10 col-md-7">
					                
					                <div class="container-test link" id="testContainer">
					                    
					                    <div class="test-header" id="testHeader">
					                    	<div class="container">
												<div class="row justify-content-center">
												    <div class="col-xs-12 col-sm-8 col-md-5 position-relative">
												        <div class="row">
												          <div class="col-8">
												            <div class="title text-truncate" title="'.$obj->titulo.'">'.$obj->titulo.'</div>
												          </div>
												          <div class="col-4">
												              <div class="container-meter float-end">
												                    <span class="meter">1 de 1</span>
												                    <span class="icon-down-open-big" event="control" event-type="slide_icon" event-data="'.$data.'" id="iconDown" title="Detalles"></span>
												                    <div class="details '.$obj->prop1.' display-n" id="idDetails">
												                        <div class="section">
												                            <label>Título</label>
												                            <div>'.$obj->titulo.'</div>
												                        </div>
												                        <div class="section col-3">
												                            <label>Tiempo</label>
												                            <div class="clock">00:00</div>
												                        </div>
												                        <button class="btn-test-link">
												                            Cancelar test
												                        </button>
												                    </div>
												              </div>
												          </div>
												        </div>
												    </div>
												</div>
											</div>
					                    </div><!--/test-header-->
					                    <div class="test-body" id="testBody">
					                    	'.$obj->body.'
					                    </div><!--/test-body-->
					                    <div class="test-footer" id="testFooter">					                    	
					                    </div><!--/test-footer-->
					                </div><!--/container-test-edit-->
					                
					            </div>
					        </div>
					    </div>
					</div>
				';
			}

			if($obj->estilo == 'FIXED')
			{
				
				$str = '
					<div class="container">
					    <div class="row justify-content-center">
					        <div class="col-12 col-sm-10 col-md-9 col-lg-7">
					            
					            <div class="container-test" id="testContainer">
					                
					                <div class="test-header" id="testHeader">					                    
					                    <div class="title">'.$obj->titulo.'</div>					            
					                    <div class="description">
					                    '.$obj->descripcion.'
					                    </div>   
					                </div><!--/test-header-->
					                <div class="test-body" id="testBody">
					                	'.$obj->body.'
					                </div><!--/test-body-->
					                <div class="test-footer" id="testFooter">
					                    
					                    <button class="btn btn-lg btn-score '.$obj->prop1.'">PUNTUAR</button>
					                    
					                    <div class="container-result '.$obj->prop1.'" id="result">
					                        <div class="body">
					                            <div class="percent default">
					                                <svg>
					                                    <circle cx="40" cy="40" r="60"></circle>
					                                    <circle cx="40" cy="40" r="60"></circle>
					                                </svg>
					                                
					                                <div class="number">0/0</div>
					                                <div class="text">
					                                    <span class="a">ACIERTOS</span>
					                                    <span class="b">Hoy ganaste</span>
					                                    <span class="c">+180 points</span>
					                                </div>
					                            </div>
					                        </div>
					                        <div class="footer">
					                            <div class="row">
					                                <div class="col-5">
					                                    <label>Compartir</label>
					                                    <div class="social-bar">
					                                        <a href="#"><i class="icon-whatsapp"></i></a>
					                                        <a href="#"><i class="icon-facebook"></i></a>
					                                        <a href="#"><i class="icon-twitter"></i></a>
					                                        <a href="#"><i class="icon-google"></i></a>
					                                    </div>
					                                </div>
					                                <div class="col-7">
					                                    <label>¿Qué piensas del test?</label>
					                                    <div class="emoticons">
					                                        <a href="#"><i class="icon-smile"></i></a>
					                                        <a href="#"><i class="icon-meh"></i></a>
					                                        <a href="#"><i class="icon-frown"></i></a>
					                                        <a class="report">Report</a>
					                                    </div>
					                                </div>
					                            </div>
					                            <div class="bottom">AUTOR DEL TEST <strong>Wtest</strong> / <a href="#"> +test</a></div>
					                        </div>
					                    </div><!--/container-result-->
					                    
					                </div><!--/test-footer-->
					            </div><!--/container-test-edit-->
					            
					        </div>
					    </div>
					</div>
				';
				
			}

			return $str;
		}

		//-------------------------------------------------------------//
		// 			  Botones de edicion del aspecto del test                    
		//-------------------------------------------------------------//

		function str_btn_edit_aspecto_test($obj){

			$str = '';

			if($obj->estilo == 'CLASSIC')
			{
				$str = '
					<div class="d-flex flex-wrap my-3" id="idColor">
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-1" select=\'{"prop1":"bg:color-1","prop2":"icon:color-1","prop4":"hover:color-1","prop5":"hover-2:color-1"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-2" select=\'{"prop1":"bg:color-2","prop2":"icon:color-2","prop4":"hover:color-2","prop5":"hover-2:color-2"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-3" select=\'{"prop1":"bg:color-3","prop2":"icon:color-3","prop4":"hover:color-3","prop5":"hover-2:color-3"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-4" select=\'{"prop1":"bg:color-4","prop2":"icon:color-4","prop4":"hover:color-4","prop5":"hover-2:color-4"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-5" select=\'{"prop1":"bg:color-5","prop2":"icon:color-5","prop4":"hover:color-5","prop5":"hover-2:color-5"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-6" select=\'{"prop1":"bg:color-6","prop2":"icon:color-6","prop4":"hover:color-6","prop5":"hover-2:color-6"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-7" select=\'{"prop1":"bg:color-7","prop2":"icon:color-7","prop4":"hover:color-7","prop5":"hover-2:color-7"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bg::color-8" select=\'{"prop1":"bg:color-8","prop2":"icon:color-8","prop4":"hover:color-8","prop5":"hover-2:color-8"}\' style="width:1.8rem;height:1.8rem"></div>						
					</div>

					<div id="idFormaIcon">
						<span class="icon-form icon-circle"        select=\'{"prop6":"icon-circle"}\'></span>
						<span class="icon-form icon-comment"       select=\'{"prop6":"icon-comment"}\'></span>
						<span class="icon-form icon-certificate-1" select=\'{"prop6":"icon-certificate-1"}\'></span>
						<span class="icon-form icon-heart"         select=\'{"prop6":"icon-heart"}\'></span>
						<span class="icon-form icon-stop"          select=\'{"prop6":"icon-stop"}\'></span>
						<span class="icon-form icon-cloud"         select=\'{"prop6":"icon-cloud"}\'></span>
						<span class="icon-form icon-comment-1"     select=\'{"prop6":"icon-comment-1"}\'></span>
						<span class="icon-form icon-star"          select=\'{"prop6":"icon-star"}\'></span>
					</div>

					<div class="d-flex flex-wrap my-3 bg-light" id="idColorTexto">
						<div class="bg-light rounded-circle m-1 cursor-pointer text::color-1" select=\'{"prop3":"text:color-1"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer text::color-2" select=\'{"prop3":"text:color-2"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer text::color-3" select=\'{"prop3":"text:color-3"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer text::color-4" select=\'{"prop3":"text:color-4"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer text::color-5" select=\'{"prop3":"text:color-5"}\' style="width:1.8rem;height:1.8rem"></div>
					</div>
				';
				
			}
			if($obj->estilo == 'LINK')
			{
				$str = '
					<div class="d-flex flex-wrap my-3" id="selectBackground">
						<div class="bg-light rounded-circle m-1 cursor-pointer gradbtn-01" select=\'{"prop1":"grad-01"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer gradbtn-02" select=\'{"prop1":"grad-02"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer gradbtn-03" select=\'{"prop1":"grad-03"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer gradbtn-04" select=\'{"prop1":"grad-04"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer gradbtn-05" select=\'{"prop1":"grad-05"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer gradbtn-06" select=\'{"prop1":"grad-06"}\' style="width:1.8rem;height:1.8rem"></div>
					</div>
				';
			}

			if($obj->estilo == 'FIXED')
			{
				$str = '
					<div class="d-flex flex-wrap my-3" id="selectColor">
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:1" select=\'{"prop1":"bg:1","prop2":"border:1","prop3":"color:1","prop4":"hover:bg-1"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:2" select=\'{"prop1":"bg:2","prop2":"border:2","prop3":"color:2","prop4":"hover:bg-2"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:3" select=\'{"prop1":"bg:3","prop2":"border:3","prop3":"color:3","prop4":"hover:bg-3"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:4" select=\'{"prop1":"bg:4","prop2":"border:4","prop3":"color:4","prop4":"hover:bg-4"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:5" select=\'{"prop1":"bg:5","prop2":"border:5","prop3":"color:5","prop4":"hover:bg-5"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:6" select=\'{"prop1":"bg:6","prop2":"border:6","prop3":"color:6","prop4":"hover:bg-6"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:7" select=\'{"prop1":"bg:7","prop2":"border:7","prop3":"color:7","prop4":"hover:bg-7"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:8" select=\'{"prop1":"bg:8","prop2":"border:8","prop3":"color:8","prop4":"hover:bg-8"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:9" select=\'{"prop1":"bg:9","prop2":"border:9","prop3":"color:9","prop4":"hover:bg-9"}\' style="width:1.8rem;height:1.8rem"></div>
						<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:10" select=\'{"prop1":"bg:10","prop2":"border:10","prop3":"color:10","prop4":"hover:bg-10"}\' style="width:1.8rem;height:1.8rem"></div>
					</div>
				';

			}

			return $str;
		}

		//-------------------------------------------------------------//
		// 				         modal apertura                        
		//-------------------------------------------------------------//

		function str_msj_estado_examen($estado){
			$str = '';

			$msj1 = '
				<label>Detalles<label>
				<ul style="text-align:left;padding:0.5rem;">
					<li>Una vez comenzado el test permanecer el la página web.</li>
					<li>No abrir o visualizar otras páginas mientras se está desarrollando ,porque,
					el sistema lo detectará y registrará como inactividad.</li>
				</ul>
			';

			$msj2 ='<i class="icon-megaphone-1"></i> Asegúrese de tener una buena <strong>conexión a internet</strong> y no ser interrumpido.';


			if($estado == 'LIBRE')
			{

			}
			if($estado == 'RESTRINGIDA')
			{

				$str  = $msj1;
				$str .= $this->msj("info",$msj2,'text-align:left;');

			}
			if($estado == 'CONCODIGO')
			{
				$str  = $this->msj("success",$msj1,'text-align:left;');
				$str .= $this->msj("info",$msj2,'text-align:left;');
				$str .= '
				<form id="formClave">
					<div class="mb-3">
						<label for="" class="form-label">Ingrese el código:</label>
						<input type="password" class="form-control" name="clave" placeholder="Contraseña">
					</div>
				</form>
				';
			}
			return $str;
		}

		//-------------------------------------------------------------//
		//                         default txt
		//-------------------------------------------------------------//

		function str_default($tipo,$cad=array()){

			$rtn = array();

			//estilo de test
			if($tipo == 'CLASSIC')
			{
				$rtn = array(
					"prop1" => "bg:color-1",
					"prop2" => "icon:color-1",
					"prop3" => "text:color-1",
					"prop4" => "hover:color-1",
					"prop5" => "hover-2:color-1",
					"prop6" => "icon-circle",
				);
			}

			if($tipo == 'LINK')
			{
				$rtn = array(
					"prop1" => "grad-01"					
				);
			}

			if($tipo == 'FIXED')
			{
				$rtn = array(
					"prop1" => "bg:1",
					"prop2" => "border:1",
					"prop3" => "color:1",
					"prop4" => "hover:bg-1"
				);		
			}

			//view 

			if($tipo == 'estilo-usuario')
			{
				$rtn = array(
					"prop1" => "",
					"prop2" => "",
					"prop3" => "bg-img:1"
				);
			}

			if($tipo == 'nivel-exam')
			{

				//sólo 5 niveles por lo pronto
				//nivel = [0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1]

				$rtn = array(
					'0'   => 'NINGUNO',
					'0.1' => 'Muy fácil',
					'0.2' => 'Fácil',
					'0.3' => 'Intermedio',
					'0.4' => 'Difícil',
					'0.5' => 'Muy difícil'
				);
			}

			if($tipo == 'estado-exam')
			{

				//sólo 5 niveles por lo pronto
				//nivel = [0,0.1,0.2,0.3,0.4,0.5,0.6,0.7,0.8,0.9,1]

				$rtn = array(
					'LIBRE'       => 'LIBRE',//público
					'RESTRINGIDA' => '<i class="icon-lock"></i>',//privado
					'CONCODIGO'   => '<i class="icon-lock"></i>',//privado
				);
			}

			if($tipo == 'estilo-lista')
			{
				$rtn = array(
					'img-color-1' => [40,210,199],
					'img-color-2' => [88,214,141],
					'img-color-3' => [52,152,219],
					'img-color-4' => [244,208,63],
					'img-color-5' => [230,176,170],
					'img-color-6' => [215,189,226],
					'img-color-7' => [169,204,227],
					'img-color-8' => [202,207,210]
				);
			}

			return $rtn;
		}

		function rtn_array_elige_estilo($estilo){

			$rtn = array(
				"CLASSIC" => array(
					'ELECCION_SIMPLE'             => 'Elección simple',
					'ELECCION_MULTIPLE'           => 'Elección múltiple',
					'ELECCION_MULT_IMG_IZQUIERDO' => 'Elección imagen izquierdo',
					'ELECCION_MULT_IMG_DERECHO'   => 'Elección imagen derecho',
					'LIKERT'                      => 'Likert',
					'VERDAD_FALSO'                => 'Verdad o falso',
					'RELACIONAL'                  => 'Relacional',
					'COMPLETAR'                   => 'Completar'
				),
				"LINK" => array(
					'ELECCION_MULTIPLE'           => 'Elección simple / multiple',
					'ELECCION_MULT_IMG_CENTER'    => 'Elección con imagen'
				),
				"FIXED" => array(
					'ELECCION_MULTIPLE'           => 'Elección simple / multiple',
					'ELECCION_MULT_IMG_TOP'       => 'Elección con imagen'
				)
			);

			return $rtn[$estilo];
		}

		function rtn_tipo_pregunta($ind){

			$rtn = array(
				'ELECCION_SIMPLE'             => 'Elección simple',
				'ELECCION_MULTIPLE'           => 'Elección múltiple',
				'ELECCION_MULT_IMG_IZQUIERDO' => 'Elección imagen izquierdo',
				'ELECCION_MULT_IMG_DERECHO'   => 'Elección imagen derecho',
				'LIKERT'                      => 'Likert',
				'VERDAD_FALSO'                => 'Verdad o falso',
				'RELACIONAL'                  => 'Relacional',
				'COMPLETAR'                   => 'Completar',
				'ELECCION_MULT_IMG_TOP'       => 'Elección con imagen',
				'ELECCION_MULT_IMG_CENTER'    => 'Elección con imagen',

			);

			return $rtn[$ind];
		}

		function rtn_array_tipo_pregunta_img(){
			$rtn = array(
				'ELECCION_MULT_IMG_IZQUIERDO',
				'ELECCION_MULT_IMG_DERECHO',
				'ELECCION_MULT_IMG_TOP',
				'ELECCION_MULT_IMG_CENTER',
				'ELECCION_MULT_IMG_BOTTOM'
			);

			return $rtn;
		}

		//-------------------------------------------------------------//
		//                 Mostrar lista de reg en tabla
		//-------------------------------------------------------------//

		function str_list_exam_creadas($obj,$cad=array()){

			$obj->idex   = (isset($obj->idex))?   $obj->idex : $this->parents->gn->rtn_idex($obj->idExamen);

			//encriptar idex
			$encriptar_idex = $this->parents->gn->encriptar_idex($obj->idex);

			$data1 = htmlspecialchars(json_encode(array("idex"=>$obj->idex)));
			$data2 = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"type"=>"att","subtype"=>"att2")));

			//img
			$color = $this->parents->gn->rtn_estilo_lista($obj->idExamen);
			$letra = $this->parents->gn->rtn_letra_valida($obj->titulo);
			
			$img = ($obj->img != 'exam.png')? '<img src="'.URL.'/data/img_main_exam/'.$obj->img.'?upd='.rand().'" class="_img'.$encriptar_idex.'">' 
			: $this->str_gn('img-div',(object) array('letra'=>$letra,'tamanio'=>'min _id'.$encriptar_idex,'color'=>$color));
			
				

			//Número de preguntas
			$num_pregunta = $this->parents->gn->rtn_num_preguntas($obj->idex);

			//Str número de preguntas
			if(isset($obj->nota_base)){
				$str_num_pregunta = '<span title="Número de preguntas">'.$num_pregunta.'</span>';
				
				if($num_pregunta>0){
					$resto = $obj->nota_base % $num_pregunta;
					if($resto > 0){
						$str_num_pregunta ='<span style="color:red;" title="Número de preguntas">'.$num_pregunta.'</span>';
					}
				}		
			}


			//estilo
			if(isset($obj->estilo))
				$str_estilo ='<span class="color-tk _e'.$encriptar_idex.'">'.strtolower($obj->estilo).'</span>';

			$btnAP = '';

			//nivel
			$nivel = (isset($obj->nivel))? $this->parents->gn->rtn_nivel_str($obj->nivel):'NINGUNO';


			if($cad["tipo"] == 'creadas')
			{	
				//estado del ....
				$css_estado = "btn-outline-success";

				if($obj->estado =='INACTIVA')
					$css_estado = "btn-outline-secondary";
				elseif($obj->estado == 'FINALIZADO')
					$css_estado = "btn-success";
				
				$img = '<div class="cursor-pointer _ie'.$encriptar_idex.' send" data-destine="admin/mostrarModalSubirImgExam" data-data="'.$data1.'">'.$img.'</div>';

				$btnAP  = '<td><button class="btn btn-outline-secondary btn-sm send" data-destine="admin/mostrarModalEligeTipoPregunta" data-data="'.$data1.'" title="Agregar pregunta">AP</button></td>';

				$links1 = '
					<a class="send text-blue-500" data-destine="admin/verificaVerEditarExamen" data-data="'.$data1.'" title="Ver y editar">Ver y editar</a> .							
					<span class="text-secondary"> estilo '.$str_estilo.'</span> .
					<span class="text-secondary"> Num preguntas ( '.$str_num_pregunta.' )</span>
					<a>'.(isset($obj->publicar) && ($obj->publicar == 'SI')?'.<i class="icon-globe-1"></i>':'').'<a>
				';

				$links2 = '
					<a class="dropdown-item send" data-destine="admin/mostrarModalPublicar" data-data="'.$data1.'" ><i class="icon-globe-1"></i> Publicar</a>
					<a class="dropdown-item send" data-destine="admin/mostrarModalCompartir" data-data="'.$data1.'"><i class="icon-share-3"></i> Compartir</a>
					<a class="dropdown-item send" data-destine="admin/verificaVerEditarExamen" data-data="'.$data1.'"><i class=" icon-eye"></i> Ver y editar</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item send" data-destine="admin/mostrarModalAgrupar" data-data="'.(htmlspecialchars(json_encode(array("idex"=>$obj->idex,"type"=>"grupo_test")))).'">Agrupar</a>
					<a class="dropdown-item send" data-destine="admin/mostrarModalCategoria" data-data="'.$data1.'">Categoría</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item send" data-destine="admin/mostrarModalActualizarNombre" data-data="'.$data2.'"><i class=" icon-pencil"></i> Editar title</a>
					<a class="dropdown-item send" data-destine="admin/mostrarModalAlertExamenEliminar" data-data=\'{"idex":"'.$obj->idex.'","type":"'.$cad["tipo"].'"}\'><i class=" icon-cancel-2"></i> Eliminar</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item send" data-destine="admin/mostrarModalNivelExam" data-data="'.$data1.'" title="Nivel del test">Nivel <span class="text-xs text-color:1 _n'.$encriptar_idex.'">'.$nivel.'</span></a>
					<a class="dropdown-item send" data-destine="admin/mostrarModalConfigBasica" data-data="'.$data1.'" title="Configuraciones básicas"><i class="icon-cog"></i> Config</a>				
				';
			}
			if($cad["tipo"] == 'publico')
			{
				$img = '<div class="cursor-pointer _ie'.$encriptar_idex.' send" data-destine="admin/mostrarModalSubirImgExam" data-data="'.$data1.'">'.$img.'</div>';

				$links1 = '
					<a class="send text-blue-500" data-destine="admin/verificaVerEditarExamen" data-data="'.$data1.'" title="Ver y editar">Ver y editar</a> .							
					<span class="text-secondary"> estilo '.$str_estilo.'</span> .
					<span class="text-secondary"> Num preguntas ( '.$str_num_pregunta.' )</span>
					<a>'.(isset($obj->publicar) && ($obj->publicar == 'SI')?'.<i class="icon-globe-1"></i>':'').'<a>
				';

				$links2 = '
					<a class="dropdown-item send" data-destine="admin/mostrarModalCompartir" data-data="'.$data1.'"><i class="icon-share-3"></i> Compartir</a>
					<a class="dropdown-item send" data-destine="admin/verificaVerEditarExamen" data-data="'.$data1.'" title="Ver y editar"><i class=" icon-eye"></i> Ver y editar</a>				
					<div class="dropdown-divider"></div>
					
					<a class="dropdown-item send" data-destine="admin/mostrarModalAgrupar" data-data="'.(htmlspecialchars(json_encode(array("idex"=>$obj->idex,"type"=>"grupo_test")))).'">Agrupar</a>
					<a class="dropdown-item send" data-destine="admin/mostrarModalCategoria" data-data="'.$data1.'">Categoría</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item send" data-destine="admin/mostrarModalActualizarNombre" data-data="'.$data2.'"><i class=" icon-pencil"></i> Editar title</a>
					<a class="dropdown-item send" data-destine="admin/mostrarModalAlertExamenEliminar" data-data=\'{"idex":"'.$obj->idex.'","type":"'.$cad["tipo"].'"}\'><i class=" icon-cancel-2"></i> Eliminar</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item send" data-destine="admin/mostrarModalNivelExam" data-data="'.$data1.'" title="Nivel del test">Nivel <span class="text-xs text-color:1 _n'.$encriptar_idex.'">'.$nivel.'</span></a>
					<a class="dropdown-item send" data-destine="admin/mostrarModalConfigBasica" data-data="'.$data1.'" title="Configuraciones básicas"><i class="icon-cog"></i> Config</a>
				';

			}

			if($cad["tipo"] == 'admision'){

				$idex  = $this->parents->gn->rtn_idex($obj->idExamen);
				$ps    = $this->parents->gn->post_slug($obj->titulo);							
				$autor = $this->parents->gn->rtn_autor($obj->e_idUsuario);

				$links1 = '
					<a href="'.URL.'/view/test/'.$ps.'/'.$idex.'-'.$obj->idAdmision.'" class="text-blue-500" title="Ver el examen">Ver resultado</a> .					
					<span class="text-secondary">Num preguntas ( '.$this->parents->gn->rtn_num_preguntas($obj->idExamen).' )</span> .					
					<a>autor ( <span class="color-tk">'.$autor.'</span> )<a> . 
					'.$this->str('link-ver-test-public',[$autor,$obj->e_idUsuario,'ver test públicas']).'
				';

				$links2 = '
					<a class="dropdown-item send" data-destine="admin/modalDestallesResultExam" data-data=\'{"idex":"'.$obj->idex.'","idad":"'.$obj->idAdmision.'"}\'>Detalles</a>
					<a class="dropdown-item" href="'.URL.'/view/test/'.$ps.'/'.$idex.'-'.$obj->idAdmision.'" title="Ver el examen">Ver resultados</a>					
				';
			}

			if($cad["tipo"] == 'admision_publica'){

				$agrupar  = ($this->parents->session->type_users(array('DOCENTE','EMPRESA')))?'<a class="dropdown-item" href="#">Añadir a un grupo</a>':'<a class="dropdown-item disabled" href="#">Añadir a un grupo</a>';

				$links1 = '
					<a>Resuelto por : <span class="color-tk">'.$obj->nombre_publico.'</span></a> .
					'.$this->str('link-ver-test-public',[$obj->nombre_publico,$obj->ad_idUsuario,'ver test públicas']).'
				';
				
				$links2 = '
					<a class="dropdown-item send" data-destine="admin/modalDestallesResultExam" data-data=\'{"idex":"'.$obj->idex.'","idad":"'.$obj->idAdmision.'"}\'>Detalles</a>
					'.$agrupar.'
				';
			}

			if($cad["tipo"] == 'admision_auto'){

				//$agrupar  = ($this->parents->session->type_users(array('DOCENTE','EMPRESA')))?'<a class="dropdown-item" href="#">Añadir a un grupo</a>':'<a class="dropdown-item disabled" href="#">Añadir a un grupo</a>';

				$links1 = '
					<a>Resuelto por : <span class="color-tk">'.$this->parents->gn->rtn_autor($obj->ad_idUsuario,$obj->nombre_publico,'Tí').'</span></a>
				';
				
				$links2 = '
					<a class="dropdown-item" href="#">Volver a intentarlo</a>
				';
				
			}



			$str='
				<tr>
					<td class="img-min">'.$img.'</td>
					<td>
						<h6 class="item-title text-gray-800 font-medium">'.$obj->titulo.'</h6>
						<div class="item-subtitle">
							'.$links1.'
						</div>
					</td>	
					'.$btnAP.'
					<td>
						<a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="icon-ellipsis-vert"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right" style="z-index:1100;">
							'.$links2.'
						</di>
					</td>
				</tr>
			';

			return $str;
		}

		function paginacion($Pag=1,$cad=array()){//$action_code="",$idload="",

			$TotalPag = 0;
			$numReg   = 0;
			$idLoad   = isset($cad["idLoad"])? $cad["idLoad"] :"";
			$query    = "";

			$get_view = isset($_GET['view'])? 'view='.$_GET['view'].'&':'';

			if($cad["tipo"] == "creadas")
				$numReg = $this->parents->gn->rtn_num_exam_creadas();
			if($cad["tipo"] == "publico")
				$numReg = $this->parents->gn->rtn_num_exam_publica();
			if($cad["tipo"] == 'admision')
				$numReg = $this->parents->gn->rtn_num_exam_admision();
				

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

			$next=($max >= $TotalPag)?'':'<li class="page-item"><a href="?'.$get_view.'pag='.($max+1).'" class="page-link" data-data="'.htmlspecialchars(json_encode(array("Pag"=>($max+1),"tipo"=>$cad["tipo"],"idload"=>$idLoad))).'">Siguiente</a></li>';
			
			$back=($min <= 1)?'':'<li class="page-item"><a href="?'.$get_view.'pag='.$min.'" class="page-link" data-data="'.htmlspecialchars(json_encode(array("Pag"=>($min),"tipo"=>$cad["tipo"],"idload"=>$idLoad))).'">Anterior</a></li>';
			
			$rtn="";
			for($i=$min;$i<=$max;$i++)
			{
				$active = ($i==$Pag)?'active':'';
				$rtn.='<li class="page-item '.$active.'"><a href="?'.$get_view.'pag='.$i.'" class="page-link" data-destine="admin/siguiente" data-data="'.htmlspecialchars(json_encode(array("Pag"=>$i,"tipo"=>$cad["tipo"],"idload"=>$idLoad))).'">'.$i.'</a></li>';
			}			
			
			return $back.$rtn.$next;
		
		}

		//-------------------------------------------------------------//
		//                         	module view
		//-------------------------------------------------------------//

		public function str_banner_usuario($idUsuario=0,$tipoSelect='box',$numGrupo=1){

			$rc = $this->parents->gn->rtn_consulta("nombre_publico,img","usuario","idUsuario=".$idUsuario);

			$prop = ($this->parents->gn->existe_estilo_usuario($idUsuario))? $this->parents->gn->rtn_estilo_usuario($idUsuario):$this->parents->interfaz->str_default('estilo-usuario');
			
			$str='
				<div class="dropdown">
					<a class="btn btn-light btn-sm" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="icon-art-gallery"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
						<div class="d-flex flex-wrap justify-content-center" id="idBanColor">
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:1" select=\'{"prop1":"bg:1"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:2" select=\'{"prop1":"bg:2"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:3" select=\'{"prop1":"bg:3"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:4" select=\'{"prop1":"bg:4"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:5" select=\'{"prop1":"bg:5"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:6" select=\'{"prop1":"bg:6"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:7" select=\'{"prop1":"bg:7"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:8" select=\'{"prop1":"bg:8"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:9" select=\'{"prop1":"bg:9"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:10" select=\'{"prop1":"bg:10"}\' style="width:1.8rem;height:1.8rem"></div>
							<div class="bg-light rounded-circle m-1 cursor-pointer bgbtn:11" select=\'{"prop1":"bg:11"}\' style="width:1.8rem;height:1.8rem"></div>
						</div>
						<div class="dropdown-divider"></div>
						<div class="d-flex flex-wrap justify-content-center" id="idBanImgPatron">
							<div class="bg-light m-1 cursor-pointer" select=\'{"prop2":"bg-img-pat:1"}\'><img src="'.URL_THEME.'/img/banner/ban_mini_1.png" class="w-20 h-7"></div>						
						</div>
					</div>
				</div>
			';

			$banner_color   = ($this->idUsuario == $idUsuario)? $str:'';
			$nombre_publico = $rc[0]->nombre_publico;
			$img_url        = ($rc[0]->img != 'img.png')? URL."/data/img_user/".$rc[0]->img."?upd=".rand() : URL_THEME."/img/default/user.png";	

			$data = htmlspecialchars(json_encode(array("idUsuario"=>$idUsuario)));
			
			//seguir
			$seguir ='';
			if($this->idUsuario != $idUsuario)
				$seguir = ($this->parents->session->check_login() && $this->parents->gn->verificar_seguir($this->idUsuario,$idUsuario))? $this->str_dejar_seguir(array("idUsuario"=>$idUsuario)) : $this->str_seguir(array("idUsuario"=>$idUsuario));

			$str = '
				<div class="container-user '.$prop['prop1'].' '.$prop['prop2'].'" id="contUser">
				    <div class="container">
				        <div class="row">
				            <div class="col-8 col-sm-8 col-md-8">
				                <div class="details">
				                    
				                    <div class="item"><h1>Test virtuales de</h1></div>
				                    <div class="item" style="display:flex;align-items:center;">
					                    <img src="'.$img_url.'">					                    
					                    <div class="text-truncate ">'.$nombre_publico.'</div> 
					                    <span class="follow" id="containerFollow">'.$seguir.'</span>	
				                    </div>

				                </div>
				            </div>
				            <div class="col-4 col-sm-4 col-md-4">
				                <div class="btn-group float-end mt-4" role="group">
				                  <a href="../box/'.$numGrupo.'" class="btn btn-light btn-sm '.(($tipoSelect=='box')?'active':'').'"><i class="icon-th-large-outline"></i></a>
				                  <a href="../list/'.$numGrupo.'"class="btn btn-light btn-sm '.(($tipoSelect=='list')?'active':'').'"><i class="icon-list-3"></i></a>
								  '.$banner_color.'
				                </div>
				            </div>
				        </div>
				        
				    </div>
				</div>
			';
			return $str;
		}

		//-------------------------------------------------------------//
		//                         Compartir
		//-------------------------------------------------------------//

		public function str_box_compartir_test($obj){

			$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"ajax"=>true)));

			$str = '
				<div class="container-box-center-static">
					<p><strong>Testink</strong> de "'.$obj->rtn_exam_titulo.'" ha sido compartido por <strong>'.$obj->rtn_exam_nombre_publico.'</strong><p>
					<a class="btn btn-action btn-lg btn-block send" data-destine="share/aperturaCompartir" data-data="'.$data.'">Vamos hacerlo</a>
					<p><strong class="send" data-destine="init/cancelarModalCompartirTest" data-security="_'.rand().'">Cancelar</strong></p>
				</div>
			';
			return $str;

		}
		public function str_modal_compartir($idex){

			$titulo = $this->parents->gn->rtn_consulta_unica("titulo","examen","idex='".$idex."'");
			$titulo = $this->parents->gn->post_slug($titulo);

			$urlPublic = URL."/share/publics/".$titulo."/".$idex;

			$str = '
				<div class="form-group">
					<label>Por :</label>
					<div class="container-share-social">
						<!--
						<a class="share-whatsapp" title="whatsapp"><i class="icon-whatsapp"></i></a>
						-->
						<a class="share-fb" title="facebook"><i class="icon-facebook-official"></i></a>
						<a class="share-twitter" title="twitter"><i class="icon-twitter-7"></i></a>
						<a class="share-google-plus" title="google"><i class="icon-googleplus-rect-1"></i></a>						
					</div>
				</div>

				<div class="form-group">
					<label class="form-label position-relative">O copia el siguiente vínculo : 
						<div event="control" event-type="copy" event-container="'.$urlPublic.'" class="container-copy color-tk">Copiar</div>
					</label>
					<input type="text" class="form-control" value="'.$urlPublic.'">
				</div>
			';

			return $str;
		}

		//-------------------------------------------------------------//
		//                           detalles del usuario
		//-------------------------------------------------------------//

		function str_view_user_details($idUsuario=0){

			$fn = array(
				"rtn_num_total_exam_resuelta_public"   => array($idUsuario),
				"rtn_num_toal_exam_resuelta_concluida" => array($idUsuario),
				"rtn_num_exam_publica"                 => array($idUsuario)
			);

			$fn = $this->parents->gn->rtn_fn($fn);

			$str = '
				<div class="item px-3.5 py-1.5">Test resueltas <span class="text-gray-500 float-right">'.$fn->rtn_num_toal_exam_resuelta_concluida.'</span></div>
				<div class="item px-3.5 py-1.5">Test publicadas <span class="text-gray-500 float-right">'.$fn->rtn_num_exam_publica.'</span></div>			
				<div class="item px-3.5 py-1.5">Test publicadas resueltas por el público <span class="text-gray-500 float-right">'.$fn->rtn_num_total_exam_resuelta_public.'</span></div>				
				<!--
				<div class="item px-3.5 py-1.5">Puntuación asignada <span class="text-gray-500 float-right">0</span></div>
				<div class="item px-3.5 py-1.5">Puntuación real <span class="text-gray-500 float-right">0</span></div>
				-->
			';

			return $str;
		}

		//-------------------------------------------------------------//
		//                           str general
		//-------------------------------------------------------------//

		public function get_str_box_search(){
			$str='				
				<form action="'.URL.'/init" method="GET">
					<div class="container-search" id="idContainerSearch">
						<div class="search">
							<input type="search" name="search" id="idBuscar" placeholder="Busque en '.APP_NAME.'" title="Para cancelar la busqueda presione la tecla [Esc]." autocomplete="off">
				    		<span>					    		
				    			<button type="submit" class="icon-search"></button>
				    		</span>
						</div>
						<table class="container-searching table-search" id="idTablaBusqueda">
							<thead></thead>
							<tbody></tbody>
							<tfoot></tfoot>
						</table>
						<div class="details">
						    <span class="title">Buscar por nombre del test,autor ó id</span>
						    <i class="icon-cancel-1" data-dismiss="modal"></i>    
						</div>
					</div>				
				</form>		

			';
			return $str;
		}

		public function get_str_modif_lapiz($array=array()){

			$data = htmlspecialchars(json_encode($array));

			$str = '
				<div class="slide-icon modify-data"><i class="icon-pencil" event="control" event-type="slide_icon" event-data="'.$data.'" id="'.$array['id2'].'" title="Agregar más datos"></i></div>
			';
			return $str;
		}

		public function get_str_dropdown_mostrar_lista_test($active=0){

			$str = '
				<div class="dropdown float-end">
					<a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-down-open-1"></i></a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
						<a class="dropdown-item '.(($active == 1)? 'active':'').'" href="'.URL.'/admin/list_test">Lista test creadas</a>
						<a class="dropdown-item '.(($active == 2)? 'active':'').'" href="'.URL.'/admin/list_test/public">Lista test públicos</a>
						<h6 class="dropdown-header">Lista test grupal</h6>
						<a class="dropdown-item" href="'.URL.'/admin/group?type=test">Lista de grupos</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item '.(($active == 3)? 'active':'').'" href="#">Reciclaje</a>
					</div>
				</div>
			';
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

		public function get_str_dropdown_admision($idex,$active=0){

			$str = '
				<div class="dropdown float-end">
					<a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-down-open-1"></i></a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
						<a class="dropdown-item '.(($active == 1)? 'active':'').'" href="'.URL.'/admin/admission">Lista admisión</a>
						<a class="dropdown-item '.(($active == 2)? 'active':'').'" href="'.URL.'/admin/admission/?type=test_active">Lista test actuales</a>
						<a class="dropdown-item '.(($active == 3)? 'active':'').'" href="'.URL.'/admin/admission/?type=test_extem">Lista test extemporáneas</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item '.(($active == 4)? 'active':'').'" href="#">Reciclaje</a>
					</div>
				</div>
			';
			return $str;
		}

		function get_str_login($btn=''){

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
						'.$btn.'
					</div>
					<div class="form-bottom text-center">
						<label><a href="'.URL.'/init/register" class="text-blue-600">¿Nuevo aquí? Registrarme</a></label>
						<label class="d-block"><a href="'.URL.'/init/recover_password" class="text-blue-600">Olvidé mi clave</a></label>
					</div>
				</form>
			';

			return $str;
		}

		function str_registrarse($btn=''){

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

			return $str;
		}

		public function get_str_msj_preactiva(){
			$msj = '
				<strong>Por razones de seguridad.</strong> 
				Se recomienda completar el registro de usuario con la verificación de e-mail
				<a>Eviar para verificar mi cuenta</a>
			';
			return $this->msj("success",$msj);
		}

		public function get_str_container_number(){
			$str = '
				<div class="container-number text-center font-size:6 fw-bold text-color:1" id="contNumber"></div>

				<script>
					$(function(){
					    var i=3;					    
					    var si = setInterval(function(){
					       if(i == 0){
					          $("#contNumber").html(\'<div class="animate__animated animate__zoomOut">\'+i+\'</div>\');
					            clearInterval(si);
					       }else{
					           $("#contNumber").html(\'<div class="animate__animated animate__bounceIn">\'+i+\'</div>\');
					       }
					       i--;
					   },500);
					});
				</script>
			';
			return $str;
		}

		public function get_str_cero_registros($datos=array()){//borrarrr

			$str ='
				<tr>
					<td colspan="2">
						<i><b>Se encontraron 0 regitros...</b></i>
					</td>
				</tr>
			';

			return $str;
		}

		public function get_str_container_empty($titulo = ''){

			$titulo = ($this->parents->gn->verifica_valor($titulo))? $titulo : 'VACÍA POR EL MOMENTO !!!';
			$str = '
				<div class="container-empty">
					<img src="'.URL_THEME.'/img/people/cornet2.png" alt="" class="m-auto">
					<h5 class="animate__animated animate__headShake animate__infinite infinite">'.$titulo.'</h5>
				</div>
			';
			return $str;

		}

		public function str_registro_vacio($cad=array()){

			$imgSrc    = (isset($cad["imgSrc"]))?    $cad["imgSrc"] : URL_THEME."/img/people/cornet2.png";
			$titulo    = (isset($cad["titulo"]))?    $cad["titulo"] : "¡ VACÍA POR EL MOMENTO !";		
			$subtitulo = (isset($cad["subtitulo"]))? $cad["subtitulo"]: "";

			$str = '
				<div class="container-empty">
					<img src="'.$imgSrc.'" alt="" class="m-auto">
					<h5 class="animate__animated animate__headShake animate__infinite infinite">'.$titulo.'</h5>
					'.$subtitulo.'
				</div>
			';

			return $str;
		}

		public function str_busqueda_vacia($term){
			$str='
			<div class="container-empty-search">
			    <div class="ces-title">
			        <i class="icon-attention-circled"></i>RESULTADOS 0
			    </div>
			    <div class="ces-msj">
			        <div class="cm-text">
			            '.$term.'
			            <div class="cm-triangle"></div>
			        </div>
			    </div>
			</div>
			';			
			return $str;
		}

		public function get_str_elige_estilo($datos=array(),$tipo='link'){

			$str = '';

			if($tipo == 'link')
			{
				$str ='
					<div class="row">
					    <div class="col-6">
					        <div class="container-choose-style">
					            <label>Link</label>
					            <a href="'.URL.'/init/edit_test_today?idex='.$datos["idex"].'&style=link">
					                <img src="'.URL_THEME.'/img/choose_style/elige_estilo_1.png" alt="">
					            </a> 
					        </div>
					    </div>
					    <div class="col-6">
					        <div class="container-choose-style">
					            <label>Clásico</label>
					            <a href="'.URL.'/init/edit_test_today?idex='.$datos["idex"].'&style=classic">
					                <img src="'.URL_THEME.'/img/choose_style/elige_estilo_2.png" alt="">
					            </a> 
					        </div>
					    </div>
					</div>
				';
			}
			elseif($tipo == 'select')
			{

			}
			return $str;
		}

		public function list_item_array($rtn=array(),$ind){

			$urlItem = URL.MODULE."/view/".$this->parents->gn->post_slug($rtn[$ind]["titulo"])."/".$rtn[$ind]["idex"];
			$urlImg  = ($rtn[$ind]["img"]!='exam.png')? URL.'/data/img_exam/'.$rtn[$ind]["idex"].'/'.$rtn[$ind]["img"].'?upd='.rand() : URL_THEME.'/img/default/exam_testink.png';

			$rtn='
				<li>					
					<div class="rl-image">
						<a href="'.$urlItem.'"><img src="'.$urlImg.'" width="100" height="90"></a>
					</div>			
					<div class="rl-details">
						<div class="rld-title"><a href="'.$urlItem.'">'.$rtn[$ind]["titulo"].'</a></div>
						<div class="rld-author"><a href="'.URL.'/view/user/nombre.../'.$rtn[$ind]["idUsuario"].'">Ver Autor</a></div>
					</div>				
				</li>';
			return $rtn;
		}

		//-------------------------------------------------------------//
		//                          General
		//-------------------------------------------------------------//

		function str($tipo,$cad=array()){

			$str = '';

			//String de una sola línea
			if($tipo == 'link-ver-test-public')
			{
				// [0] : nombre del autor, [1] : idUsuario, [2] : nombre_link
				if(IDUSUARIO_ADMIN != $cad[1]){
					$str = '<a href="'.URL.'/view/user/'.$this->parents->gn->post_slug($cad[0]).'-'.$cad[1].'/box/1" class="text-blue-500">'.$cad[2].'</a>';
				}else{
					$str = '<a href="'.URL.'/init" class="text-blue-500">'.$cad[2].'</a>';		
				}
			}

			if($tipo == 'url-test-public')
			{
				// [0] : nombre del autor, [1] : idUsuario
				if(IDUSUARIO_ADMIN != $cad[1]){
					$str = URL.'/view/user/'.$this->parents->gn->post_slug($cad[0]).'-'.$cad[1].'/box/1';
				}else{
					$str = URL.'/init';		
				}
			}

			if($tipo == 'buscar-mas')
			{				
				// [0] : texto buscado, [1] : nombre_link
				$str = '<a href="'.URL.'/init?search='.$cad[0].'" class="text-blue-500">'.$cad[1].'</a>';
			}

			if($tipo == 'ver-test')
			{
				// [0] : idex, [1] : idad, [2] : nombre_link
				$str = '<a href="'.URL.'/view/test/'.$this->parents->gn->rtn_post_exam($cad[0],$cad[1]).'" class="text-blue-500">'.$cad[2].'</a>';
			}

			if($tipo == 'msj-testwink')
			{
				$str = '<strong>Testea</strong> a tus amigos , alumnos, empleados y clientes de una manera fácil';
			}

			if($tipo == 'img-perfil')
			{
				$rc     = $this->parents->gn->rtn_consulta("img","usuario","idUsuario=".$this->parents->session->get("idUser"));
				$imgURL = ($rc[0]->img != 'img.png')? URL."/data/img_user/".$rc[0]->img."?upd=".rand() : URL_THEME."/img/default/user.png";

				$str = '<img src="'.$imgURL.'"/>';
			}

			if($tipo == 'subdescripcion')
			{
				$str = '<div class="w-full sm:w-1/2 pb-3">'.$cad[0].'</div>';
			}

			//mensajes texto

			if($tipo == 'msj-testwink')
			{
				$str = '<strong>Testea</strong> a tus amigos , alumnos, empleados y clientes de una manera fácil';
			}
			if($tipo == 'msj-bienvenida')
			{
				$str = 'Bienvenido a '.APP_NAME.'. Para completar el registro debes confirmar tu e-mail.<a href="#">Confirmar e-mail</a>';
			}
			if($tipo == 'msj-no-test')
			{
				$str = 'No se a Encontrado test creados';
			}
			if($tipo == 'msj-no-test')
			{
				$str = 'No tiene publicados Publicados...';
			}

			if($tipo == 'msj-mas-categoria')
			{
				$str = 'A medida que '.APP_NAME.' crece se agregará más categorias públicas';
			}
			return $str;

		}

		function str_msj($tipo,$obj=array(),$cad=array()){
			$str = '';

			if($tipo == 'nivel')
			{
				$str = 'El nivel es el grado de dificultad del test';
			}

			return $str;
		}

		function str_gn($tipo,$obj=array(),$cad=array()){

			$str = '';

			if($tipo == 'alerta-triangular')
			{
				//$cad mensaje
				$str ='
					<div class="ces-msj p-2 my-0 mb-3">
						<div class="cm-text">
							'.$obj->msj.'
							<div class="cm-triangle"></div>
						</div>
					</div>
				';
			}

			if($tipo == 'radio-checkbox')
			{	
				$form_check_inline = (isset($obj->form_check_inline))? $form_check_inline:null;
				$checked           = (isset($obj->checked))? $obj->checked:null;
				$disabled          = (isset($obj->disabled))? $obj->disabled:null;
				$id                = (isset($obj->id)) ? $obj->id:null;
				$type              = (isset($obj->type))? $obj->type:'radio';
				$name              = (isset($obj->name))? $obj->name:null;
				$title             = (isset($obj->title))? $obj->title:null;

				//extras
				$send              = (isset($obj->send))? $obj->send:null;
				$data_destine      = (isset($obj->data_destine))? 'data-destine="'.$obj->data_destine.'"':null;
				$data_data         = (isset($obj->data_data))? 'data-data="'.$obj->data_data.'"':null;

				$str = '
					<div class="form-check'.$form_check_inline.'">
						<input class="form-check-input cursor-pointer '.$send.'" type="'.$type.'" name="'.$name.'" id="'.$id.'" '.$data_destine.' '.$data_data.' '.$checked.' '.$disabled.'>
						<label class="form-check-label cursor-pointer" for="'.$id.'">
							'.$title.'
						</label>
					</div>
				';
			}

			if($tipo == 'barra-porcentaje')
			{
				$str = '
					<div class="flex my-2 ml-2">
						<div class="flex-1 bg-gray-100 w-full">
							<div class="bg-color:1 text-xs text-white py-2" style="width:'.$obj->num.'%;"></div>
						</div>
						<div class="flex-shrink w-8 text-xs text-center mx-1">'.$obj->num.'%</div>
					</div>
				';
			}

			if($tipo == 'img-div')
			{

				$letra   = (isset($obj->letra))? strtoupper($obj->letra):'T';
				$tamanio = (isset($obj->tamanio))? $obj->tamanio:'med';
				$color   = (isset($obj->color))? $obj->color:'img-color-1';

				$send         = (isset($obj->send))? $obj->send:null;
				$data_destine = (isset($obj->data_destine))? 'data-destine="'.$obj->data_destine.'"':null;
				$data_data    = (isset($obj->data_data))? 'data-data="'.$obj->data_data.'"':null;

				$str = '
					<div class="img-div '.$tamanio.' '.$color.' '.$send.'" '.$data_destine.' '.$data_data.'>
						<div class="letter">'.$letra.'</div>
						<div class="titulo">'.APP_NAME.'</div>
					</div>
				';

			}

			if($tipo == 'color-div')
			{
				$str = '
					<div class="select-color img-color_1" select="'.htmlspecialchars(json_encode(array("color"=>"img-color-1"))).'"></div>
					<div class="select-color img-color_2" select="'.htmlspecialchars(json_encode(array("color"=>"img-color-2"))).'"></div>
					<div class="select-color img-color_3" select="'.htmlspecialchars(json_encode(array("color"=>"img-color-3"))).'"></div>   
					<div class="select-color img-color_4" select="'.htmlspecialchars(json_encode(array("color"=>"img-color-4"))).'"></div>
					<div class="img-color-rand round"     select="'.htmlspecialchars(json_encode(array("color"=>"img-color-5"))).'"></div>
				';

			}

			if($tipo == 'file-img')
			{

				//form_ denegamos el formulario para evitar anidamiento de formularios

				$encriptar_idex = (isset($obj->encriptar_idex))? $obj->encriptar_idex:null;
				$img            = (isset($obj->img))? $obj->img:null;

				$send         = (isset($obj->send))? $obj->send:null;
				$data_destine = (isset($obj->data_destine))? 'data-destine="'.$obj->data_destine.'"':null;
				$data_data    = (isset($obj->data_data))? 'data-data="'.$obj->data_data.'"':null;

				$str = '
					<div class="container-file relative">
						<div class="image _ie'.$encriptar_idex.'">
							'.$img.'
						</div>
						<form_ id="formArchivoImg'.$encriptar_idex.'">
							<label for="idFile'.$encriptar_idex.'">
								<input type="file" name="archivo" class="file hidden '.$send.'" file="img" id="idFile'.$encriptar_idex.'" '.$data_destine.' '.$data_data.' data-serialize="formArchivoImg'.$encriptar_idex.'">																		
								<span><i class="icon-picture-2"></i></span>
							</label>
						</form_>
					</div>
				';
			}

			if($tipo == 'form-descripcion')
			{

				$descripcion = (isset($obj->descripcion))?$obj->descripcion:null;

				$str = '
					<input class="form-check-input cursor-p" type="checkbox" name="descripcion_check" id="checkbox1" event="control" event-type="checkbox_text" event-id="checkbox2" event-data=\'["Sin descripción","Con descripción"]\' '.$obj->checked.'>
					<label class="text-primary cursor-p" for="checkbox1">'.$obj->check_text.'</label>

					<div class="'.$obj->check_display.'" event-for="checkbox2">
					    <textarea class="form-control mt-2" rows="2" name="descripcion" placeholder="Descripción del test" id="idForm1">'.$descripcion.'</textarea>
					</div>
				';

				//$str = $obj->checked;
			}

			if($tipo == 'form-duracion')
			{
				///////////falta modificar
				$str = '
					<input type="text" class="form-control" list="idList" name="duracion" value="'.$obj->duracion.'" id="idForm3" disabled>
					<datalist id="idList">
						<option value="LIBRE" selected>
						<option value="00:15" label="15 min">
						<option value="00:30" label="Media hora">
						<option value="01:00" label="1 Hora">
						<option value="01:30" label="1 Hora y media">
					</datalist>
				';
			}

			if($tipo == 'nombre-valor'){
				$str = '
					<div class="flex px-3 py-2">
					    <div class="flex-shink w-32 font-semibold">'.$obj->nombre.'</div>
					    <div class="flex-grow pl-3">'.$obj->valor.'</div>
					</div>
				';
			}

			if($tipo == 'check-dinamic')
			{
				//modificarrrrrr
				$descripcion = (isset($obj->descripcion))?$obj->descripcion:null;
				//$obj->checked =

				$str = '
					<input class="form-check-input cursor-pointer" type="checkbox" name="descripcion_check" id="checkbox1" event="control" event-type="checkbox_text" event-id="checkbox2" event-data=\'["Sin descripción","Con descripción"]\' '.$obj->checked.'>
					<label class="text-primary cursor-p" for="checkbox1">'.$obj->check_text.'</label>
				';

				//$str = $obj->checked;
			}

			if($tipo == 'check-dinamic-all')
			{
				//modificarrrrrr
				$descripcion = (isset($obj->descripcion))?$obj->descripcion:null;
				$obj->checked =

				$send         = (isset($obj->send))? $obj->send:null;
				$data_destine = (isset($obj->data_destine))? 'data-destine="'.$obj->data_destine.'"':null;
				$data_data    = (isset($obj->data_data))? 'data-data="'.$obj->data_data.'"':null;
			}

			return $str;


		}

		function str_foreach($tipo='',$obj,$cad=array()){

			$str = '';

			//Perfil
			if($tipo == 'seguid')
			{	
				$imgURL = ($obj->img != 'img.png')? URL."/data/img_user/".$obj->img."?upd=".rand() : URL_THEME."/img/default/user.png";

				$str_follow = $this->str_dejar_seguir(array("idUsuario" => $obj->idUsuario,"destine"=>"admin/dejarDeSeguir"));

				if(isset($obj->follow) && $obj->follow == false){
					$str_follow = '';
				}

				$str_href = $this->str('url-test-public',[$obj->nombre_publico,$obj->idUsuario]);

				$str = '
					<div class="container-card-follow d-flex border shadow-sm rounded">
						<div class="px-2 py-2"><img src="'.$imgURL.'" alt="" class="rounded-circle"></div>
					    <div class="px-2 py-2">
					        <h6 class="m-0">'.$obj->nombre_publico.'</h6>
					        <div class="d-flex flex-wrap">
					            <a href="'.$str_href.'" class="badge rounded-pill bg-light text-dark fw-light mt-1 mr-1">Ver test</a>
					            '.$str_follow.'
							</div>													
					    </div>
					</div>
				';
			}

			//...
			if($tipo == '...')
			{

			}

			return $str;

		}

		function str_check_switch($obj){
			$str = '
				<div class="form-check form-switch">
				  <input class="form-check-input '.$obj->style.'" type="checkbox" id="'.$obj->id.'" '.$obj->attr.'>
				  <label class="form-check-label" for="'.$obj->id.'">'.$obj->title.'</label>
				</div>
			';
			return $str;
		}

		function str_seguir($datos){

			$data = htmlspecialchars(json_encode(array("idUsuario"=>$datos["idUsuario"])));

			$str  = '<a class="send" data-destine="init/seguir" data-data="'.$data.'">Seguir</a>';

			return $str;
		}

		function str_dejar_seguir($datos){

			$data    = htmlspecialchars(json_encode(array("idUsuario"=>$datos["idUsuario"])));
			$destine = (isset($datos["destine"]))? $datos["destine"]:'init/dejarDeSeguir';

			$str='
				<div class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">
						Siguiendo
					</a>
					<ul class="dropdown-menu">
						<li class="dropdown-header send" data-destine="'.$destine.'" data-data="'.$data.'"><a>Dejar de seguir</a></li>
					</ul>
				</div>
			';
			return $str;
		}

		function str_test_world($idex=null){

			$data = htmlspecialchars(json_encode(array()));
			//config manual
			//ingresar los ids del examen que desea mostrar

			$rtn_array = array(
				0 => ''
			);

			$str='
				<div class="test-world">
					<img src="'.URL_THEME.'/img/init/one_piece.png" alt="">
					<div class="tw-title">¿ Cuanto sabes sobre Monkey D. Luffy ?</div>
					<div class="tw-details">
						<span class="td-resolve">180000<i class="icon-edit-3"></i></span>
						<span class="td-approved">18000<i class="icon-star"></i></span>
						<span class="td-disapproved">180<i class=" icon-star-half-alt"></i></span>
						<div class="dropdown float-end dropdown-test-world">
							<a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis-vert"></i></a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" style="margin: 0rem;">
								<a class="dropdown-item" edit="cancel">180000<i class="icon-star"></i></a>
								<a class="dropdown-item" edit="new">180<i class=" icon-star-half-alt"></i></a>
							</div>
						</div>
					</div>
				</div><!--/test-world-->
			';

			return $str;
		}

		function str_aside_search($pag=1,$type=''){

			$str = '
				<aside data-pushbar-id="right-center" data-pushbar-direction="right">
					<div class="container-filter" id="containerPagination">

					<label class="form-label text-white">Buscar :</label>
						<input type="search" id="search" class="form-control form-control-sm mb-3" placeholder="Digite el texto buscado y presione Enter." title="Para cancelar la búsqueda presione la tecla Esc." data-type="'.$type.'">

						<label class="form-label text-white">Paginación :</label>
						<ul class="pagination pagination-sm justify-content-center" id="pagination">
							'.$this->paginacion($pag,array("tipo"=>$type)).'
						</ul>
						<span data-pushbar-close class="icon-cancel-1"></span>
					</div><!--/container-filter-->
				</aside>
			';
			return $str;
		}

		function str_meta_data($property,$content){
			return '<meta '.$property.' content="'.$content.'"/>'."\n";
		}

		function str_meta($id=null,$tipo=''){

			$str   = '';

			$nombre       = '';
			$descripcion  = '';
			$url          = '';
			$urlImg       = '';
			$publicacion  = '';
			$modificacion = '';

			if($tipo == 'exams-public'){

				//encriptar idex
				$encriptar_idex = $this->parents->gn->encriptar_idex($id);

				//verificar idex pública
				if(!$this->parents->gn->idex_publica($id)) return null;

				$query = "
						SELECT e.idex,e.titulo,e.descripcion,e.img,e.publicacion,e.modificacion,u.nombre_publico FROM examen e 
							INNER JOIN usuario u ON e.idUsuario=u.idUsuario 
						WHERE e.idex='".$id."';
					";

				if($this->parents->gn->verifica_valor($query) && $this->parents->sql->consulta($query)){
					foreach($this->parents->sql->resultado as $obj){						
						$nombre       = $obj->titulo;
						$descripcion  = ($this->parents->gn->verifica_valor($obj->descripcion))? $this->parents->gn->rtn_reducir_txt($obj->descripcion):'';
						$url          = URL."/share/public/view/".$this->parents->gn->post_slug($obj->titulo)."/".$obj->idex;
						$defaultImg   = ($obj->img == 'exam.png')? URL.'/data/img_main_exam_tmp/img_tmp_'.$encriptar_idex.'.png?upd='.rand() : URL_THEME.'/img/default/exam_testwink.png';
						$urlImg       = ($obj->img != 'exam.png')? URL.'/data/img_main_exam/'.$obj->img.'?upd='.rand() : $defaultImg;											
						$publicacion  = $obj->publicacion;
						$modificacion = $obj->modificacion;
					}
				}
			}

			if($tipo == 'usuarios-public'){
				//...
			}

			$str .= $this->str_meta_data("property='og:type'",'element');
			$str .= $this->str_meta_data("property='og:title'",$nombre);
			$str .= $this->str_meta_data("property='og:description'",$descripcion);
			$str .= $this->str_meta_data("property='og:image'",$urlImg);
			$str .= $this->str_meta_data("property='og:image:width'",300);
			$str .= $this->str_meta_data("property='og:image:height'",220);
			$str .= $this->str_meta_data("property='og:url'",$url);
			$str .= $this->str_meta_data("property='og:site_name'",APP_NAME);
			$str .= $this->str_meta_data("property='article:published_time'",$publicacion);
			$str .= $this->str_meta_data("property='og:updated_time'",$modificacion);

			$str .= $this->str_meta_data("name='twitter:card'","summary_large_image");
			$str .= $this->str_meta_data("name='twitter:title'",$nombre);
			$str .= $this->str_meta_data("name='twitter:description'",$descripcion);
			$str .= $this->str_meta_data("name='twitter:image'",$urlImg);
			$str .= $this->str_meta_data("name='twitter:site'",APP_NAME);

			return $str;

		}

		function str_cropper(){

                $str = '
                    <link rel="stylesheet" href="'.URL_THEME.'/plugins/cropper/cropper.css">


                    <style>
                    </style>

                    <div class="container-cut-img">
                        <img src="'.URL_THEME.'/img/default/img.jpg" class="" id="image" alt="img">
                        <button type="button" id="button" class="btn btn-primary mt-3 mb-3">Recortar</button>
                        <div class="bg-gray-400" id="result"></div>                    
                    </div>

                    <script src="'.URL_THEME.'/plugins/cropper/cropper.js"></script>

                    <script>
                        window.addEventListener(\'DOMContentLoaded\', function () {
                          var image = document.querySelector(\'#image\');
                          //var data = document.querySelector(\'#data\');
                          var button = document.getElementById(\'button\');
                          var result = document.getElementById(\'result\');
                          var minCroppedWidth   = '.IMG_PREG_MIN_WIDTH.';
                          var minCroppedHeight  = '.IMG_PREG_MIN_HEIGHT.';
                          var maxCroppedWidth   = '.IMG_PREG_MAX_WIDTH.';
                          var maxCroppedHeight  = '.IMG_PREG_MAX_HEIGHT.';
                          var cropper = new Cropper(image, {                          
                            aspectRatio: 1,
                            viewMode: 1,
                            ready: function () {
                              croppable = true;
                            },
                            data: {
                              width: (minCroppedWidth + maxCroppedWidth) / 2,
                              height: (minCroppedHeight + maxCroppedHeight) / 2,
                            },

                            crop: function (event) {
                              var width = event.detail.width;
                              var height = event.detail.height;

                              if (
                                width < minCroppedWidth
                                || height < minCroppedHeight
                                || width > maxCroppedWidth
                                || height > maxCroppedHeight
                              ) {
                                cropper.setData({
                                  width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
                                  height: Math.max(minCroppedHeight, Math.min(maxCroppedHeight, height)),
                                });
                              }

                              //data.textContent = JSON.stringify(cropper.getData(true));
                            },
                          });

                          button.onclick = function () {
                            result.innerHTML = "";
                            result.appendChild(cropper.getCroppedCanvas());
                          };
                        });
                    </script>
                ';
			return $str;

		}

		function str_subir_img_exam($obj,$cad=array()){			

			$k = (isset($obj->k))?$obj->k:1;

			$str = '
				<div class="container-eyelash">

				    <div class="container-img-auto '.(($obj->seleccion == 'auto')?'block':'hidden').'" id="mostrarImg'.($k).'">

						<!--<label>Elige un color: <i class="icon-help"></i></label>-->

				        <div class="img-auto position-relative">

				            <div class="box">
								<div class="box-exam">

									'.($this->str_gn('img-div',(object)array('letra'=>$obj->letra,'tamanio'=>'max _id'.$obj->encriptar_idex,'color'=>$obj->color))).'

								    <div class="details">
								    </div>
								    <div class="title" id="idTitulo2">
								       '.$obj->titulo.'
								    </div>
								    <div class="subdetails">
									</div>					    
								</div>
							</div>

							<div class="container-select-color" color="'.$obj->color.'" idex="'.$obj->idex.'">
								'.($this->str_gn('color-div')).'
							</div>

				        </div><!--/img-auto-->

				    </div>
				    <div class="container-img-file '.(($obj->seleccion == 'img')?'block':'hidden').'" id="mostrarImg'.(++$k).'">

						<!--<label>Elige una imagen: <i class="icon-help"></i></label>-->

							<div class="box">

								<div class="box-exam">
									<div class="img">
			';
			

			$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex)));

			$img  = ($obj->img != 'exam.png')? '<img src="'.URL.'/data/img_main_exam/'.$obj->img.'?upd='.rand().'" alt="">': '';

			$array = array(
				'encriptar_idex' => $obj->encriptar_idex,
				'img'            => $img,
				'data_destine'   => 'admin/guardarImgExam',
				'data_data'      => $data
			);

			$str .= $this->str_gn('file-img',(object) $array);

			$str .= '
									</div>
									<div class="details"></div>
									<div class="title">
									    '.$obj->titulo.'
									</div>
									<div class="subdetails"></div>					    
								</div><!--/box-exam-->

							</div><!--/box-->									              


				    </div> 

				</div><!--/container-eyelash-->

				<ul class="control-eyelash flex justify-center img-main-btn">
					'.$obj->btns.'
				</ul>
				
			';

			return $str;

		}

		function str_rtn_array($title='',$body='',$footer='',$cad=array()){

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