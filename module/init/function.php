<?php
	class fnInit{
		var $idUsuario = 0;
		var $bd;
		var $resultado;

		function __construct(&$parents){
			$this->parents   = $parents;

			if($this->parents->session->check_login()){
				$this->idUsuario = $this->parents->session->get("idUser");
			}
		}

		//-------------------------------------------------------------//
		//                           Login
		//-------------------------------------------------------------//

		function conexion_remota(){
			try{
				$this->bd = new PDO(DB_TYPE_EXTERNAL.':host='.DB_HOST_EXTERNAL.';dbname='.DB_NAME_EXTERNAL.';charset='.DB_CHARSET_EXTERNAL,DB_USER_EXTERNAL,DB_PASS_EXTERNAL);
				$this->bd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
				return true;

			}catch(Exception $e){
				return false;
			}
		}
		function consulta_remota($sql){
			
			if($this->conexion_remota()){
				$query = $this->bd->prepare($sql);

				if($query->execute()){
					$this->resultado = $query->fetchAll();
					return true;
				}
				return false;
			}else{
				return false;
			}

		}

		function salir($datos){

			$this->parents->session->remove();
			
			$rtn = array(
				"success" => true,
				"update"  => array(
					array(
						"action" => "redirection",
						"value"  => $datos["redirect"]
					)
				)
			);
			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		//-------------------------------------------------------------//
		//                          init
		//-------------------------------------------------------------//

		function mostrarModalBusqueda(){
			
			$rtn = array(
				"success"=>true,
				"update" =>array(
					array(
						"id"     => "modalBoxTop",
						"action" => "openModal"
					),
					array(
						"id"     => "idBuscar",
						"action" => "focus",
						"delay"  => 500
					),
				)
			);
			
			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		function mostrarListaExamSesion(){
			//Retornammos los elementos de sesión del día

			$sesion_array = $_SESSION['testink_list']['_lista_idex'];
			$limit        = 20; //config
			$str          = '';

			$query2 = "
				SELECT e.idExamen,e.idex,e.titulo,e.img,e.estado,e.duracion,e.fijar_fecha,e.tiempo_inicial,e.tiempo_final,e.idUsuario FROM examen e 
					INNER JOIN examen_config ec ON e.idExamen = ec.idExamen
				WHERE (ec.publicar='SI' AND ec.eliminar='NO')  AND (".$this->parents->gn->agregar_or($sesion_array,'e.idex').") ORDER BY registro DESC LIMIT ".$limit.";
			";

			if($this->parents->sql->consulta($query2)){
				$resultado = $this->parents->sql->resultado;
				foreach($resultado as $obj){

						$obj->idUsuario = $this->idUsuario;
						$obj->resueltas = $this->parents->gn->rtn_num_resueltas($obj->idExamen);

						$str .= $this->parents->interfaz->list_item_exam($obj,array("select"=>"box"));
				}
			}

			return '<div class="box">'.$str.'</div>';
		}

		function listaIdexSesion(){

			$rtn = array();

			//idex random de últimas publicaiones
			$query = "
				SELECT e.idex  FROM examen e 
					INNER JOIN examen_config ec ON e.idExamen = ec.idExamen
				WHERE (ec.publicar='SI' AND ec.eliminar='NO') AND RAND() ORDER BY e.publicacion DESC LIMIT 4;
			";
			$this->parents->sql->consulta($query);

			foreach($this->parents->sql->resultado as $obj){
				$rtn[] = $obj->idex;
			}

			//idex random de todos los registros
			$query = "
				SELECT e.idex  FROM examen e 
					INNER JOIN examen_config ec ON e.idExamen = ec.idExamen
				WHERE (ec.publicar='SI' AND ec.eliminar='NO') AND (".$this->parents->gn->agregar_negacion($rtn,'e.idex').") AND RAND() LIMIT 7;
			";
			$this->parents->sql->consulta($query);

			foreach($this->parents->sql->resultado as $obj){
				$rtn[] = $obj->idex;
			}

			return $rtn;

		}

		function listaIdprsInicial(){
			//Contentrá una lista json de idrps random
			$rtn = array();
			//SELECT*FROM vista_elemento WHERE RAND() ORDER BY publicacion DESC LIMIT 9;
			foreach($this->parents->gn->rtn_consulta("idex","vista_elemento","RAND() ORDER BY publicacion DESC LIMIT 4") as $obj){
				$rtn[] = $obj->idrps;
			}
			
			foreach($this->parents->gn->rtn_consulta("idrps","vista_elemento",$this->parents->gn->agregar_negacion($rtn,'idrps')." AND RAND() LIMIT 5") as $obj){
				$rtn[] = $obj->idrps;
			}
			return $rtn;
		}

		function crearTestHoy($datos){

			$rtn = array();

			if($this->parents->session->check_login()){
				$rtn = array(
					"success" => true,
					"update"  => array(
						array(
							"action" => "redirection",
							"value"  => URL."/admin/list_test?modal=createTest"
						)
					)
				);
			}else{

				if(isset($datos["url"])){
	 				unset($datos["url"]);
	 			}

	 			$datos["destine"]  = "init/crearTestHoyLogin";
	 			$datos["redirect"] = URL."/admin/list_test?modal=createTest";

				$rtn = $this->parents->gn->modal_login($datos);
			}

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		function crearTestHoyLogin($datos){

			$rtn1 = $this->parents->gn->ingresar($datos);

			//Iniciar si la sesión fue correcta
			if($rtn1["success"]){
				return json_encode($rtn1,JSON_PRETTY_PRINT);
			}
			return json_encode($rtn1,JSON_PRETTY_PRINT);
		}

		function rtnListTestWorld($test_array = array()){
			
			$rtn_array = array();
			//El usuario ADMIN es 2
			$idUsuario = 2;//config

			foreach($test_array as $val){

				$query = "
					SELECT e.idExamen,e.idex,e.titulo,e.img FROM examen e 
						INNER JOIN examen_config ec ON e.idExamen = ec.idExamen
					WHERE (e.idex = '".$val."' AND e.idUsuario = ".$idUsuario.") AND (ec.publicar='SI' AND ec.eliminar='NO') ;
				";

				if($this->parents->sql->consulta($query)){

					foreach($this->parents->sql->resultado as $obj){

						$obj->resueltas = $this->parents->gn->rtn_num_resueltas($obj->idExamen);

						$rtn_array[]    = $this->parents->interfaz->list_item_exam($obj,array("select"=>"test-world"));
					}				
				}
			}

			return $rtn_array;
		}

		//-------------------------------------------------------------//
		//           		       Admisión                
		//-------------------------------------------------------------//

		function mostrarListaExamenes(){
			//Comprobar si está publicado
			//Comprobar si está eliminado

			$str='';

			if($this->parents->session->type_user("USUARIO")){

				$query="SELECT * FROM examen e INNER JOIN examen_config ec ON e.idExamen=ec.idExamen WHERE ec.publicar='SI' AND ec.eliminar='NO' ORDER BY e.publicacion DESC;";

				if($this->parents->sql->consulta($query)){
					$resultado1 = $this->parents->sql->resultado;
					foreach($resultado1 as $obj1){
						if($this->parents->gn->existe_registro("admision","idExamen=".$obj1->idExamen." AND idUsuario=".$this->idUsuario)){
							if($this->parents->gn->existe_registro("admision","estado_admision='ACTIVA' AND idExamen=".$obj1->idExamen." AND idUsuario=".$this->idUsuario)){
								$str .= $this->parents->interfaz->box_exam($obj1,array("estado_admision"=>'ACTIVA'));
							}else{
								$str .= $this->parents->interfaz->box_exam($obj1,array("estado_admision"=>'CONCLUIDA'));
							}
						}else{
							$str .= $this->parents->interfaz->box_exam($obj1,array("estado_admision"=>''));
						}

					}
				}

			}else{
				$query="SELECT * FROM examen e INNER JOIN examen_config ec ON e.idExamen=ec.idExamen WHERE ec.publicar='SI' AND ec.eliminar='NO' ORDER BY e.publicacion DESC;";

				if($this->parents->sql->consulta($query)){
					foreach($this->parents->sql->resultado as $obj){
						$str .= $this->parents->interfaz->box_exam($obj,array("estado_admision"=>''));
					}
				}

			}

			return $str;

		}

		function redirectAperturaExamen($datos){

			$idex   = $datos['idex'];
			$titulo = $this->parents->gn->rtn_titulo_examen($idex);

			$rtn=array(
				"success"     => true,
				"redirection" => URL."/init/test/".$this->parents->gn->post_slug($titulo)."/".$idex
			);

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		function mostrarModalAperturaExamen($datos, $ajax = true){

			//Verificar examen pública
			//Verificar examen cancelada
			//Verificar tipo usuario,redireccionar a login en caso contrario.
			//Verificar idex para la lista de idexs de session
			//El número de admisión indica:
			//- 0 que no existe admisión y que se puede crear una.
			//- 1 existe admisión y se puede redireccionar si es el caso
			//- n indica que existe mas de uno y se mostrará un modal con  las lista  de los subexamenes.

			$rtn      = array();
			$str      = '';
			$idex     = $datos["idex"];
			$idExamen = $this->parents->gn->rtn_id($idex);
			$idad     = $this->parents->gn->rtn_id_admision($idExamen);

			$data     = htmlspecialchars(json_encode(array("idex"=>$idex)));

			if($this->parents->gn->idex_publica($idex)){
			
				if($this->parents->session->type_users(array('ADMIN','USUARIO','DOCENTE','EMPRESA'))){

					//Datos del examen					

					$query = "
						SELECT e.idex,e.titulo,u.nombre_publico,e.estado,e.duracion,e.fijar_fecha,e.tiempo_inicial,e.tiempo_final,u.idUsuario FROM examen e 
							INNER JOIN examen_config ce ON e.idExamen  = ce.idExamen 
							INNER JOIN usuario u        ON e.idUsuario = u.idUsuario
						WHERE e.idExamen = ".$idExamen." AND (ce.publicar = 'SI' AND ce.eliminar='NO');
					";

					$this->parents->sql->consulta($query);
					$resultado = $this->parents->sql->resultado;
					$obj       = $resultado[0];

					$numExamAmision = $this->parents->gn->rtn_num_exam_admision($idExamen);

					$nombre_publico = $this->parents->gn->rtn_autor($obj->idUsuario,$obj->nombre_publico);

					$autor = '
						Autor del test : '.$nombre_publico.' 
						'.$this->parents->interfaz->str('link-ver-test-public',[$obj->nombre_publico,$obj->idUsuario,'Ver+']).'
					';

					$ver_test = $this->parents->interfaz->str('ver-test',[$idex,$idad,'Ver test']);
					
					//Antes,verificar test cancelada
					if($this->parents->gn->existe_admision_cancelada($idad)){
						$str = '
							<div class="text-center font-normal text-xl" title="Título del test virtual">
								'.$obj->titulo.'									
							</div>
							<p class="text-center text-gray-500" title="Autor">
								'.$autor.'
							</p>
							<div class="text-center mt-3">'.$ver_test.'</div>
						';

						$rtn = $this->parents->interfaz->str_rtn_array('Test cancelada',$str);

						return json_encode($rtn,JSON_PRETTY_PRINT);
					}

					//Mostrar modal iniciar examen.
					if($numExamAmision == 0)
					{					
						$detalles = ($resultado[0]->estado == 'LIBRE')? '<div class="animate__animated animate__rubberBand"><span>'.$obj->estado.'</span></div> ':'<i class="icon-calendar" title="Fecha"></i> <span>'.$this->parents->gn->get_current_time($obj->fijar_fecha.' '.$obj->tiempo_inicial).' </span><i class="icon-clock" title="Tiempo"></i> <span>'.$obj->duracion.'</span>';
						$btn      = '<button class="btn btn-success send" data-destine="init/iniciarExamen" data-data="'.$data.'">Iniciar</button>';

						if($obj->estado == 'LIBRE')
							$detalles = '<div class="animate__animated animate__rubberBand text-center text-success"><span>'.$obj->estado.'</span></div> ';

						if($obj->estado == 'RESTRINGIDA'){
							$detalles  = '<i class="icon-calendar" title="Fecha"></i> <span>'.$this->parents->gn->get_current_time($obj->fijar_fecha.' '.$obj->tiempo_inicial).' </span><i class="icon-clock" title="Tiempo"></i> <span>'.$obj->duracion.'</span>';
							$detalles .= $this->parents->interfaz->str_msj_estado_examen($obj->estado);
						}

						if($obj->estado == 'CONCODIGO'){
							$detalles .= $this->parents->interfaz->str_msj_estado_examen($obj->estado);
							$btn       = '<button class="btn btn-success send" data-destine="init/iniciarExamen" data-data="'.$data.'" data-serialize="formClave">Iniciar</button>';
						}
						
						$str='
							<div class="text-center font-normal text-xl" title="Título del test virtual">
								'.$obj->titulo.'									
							</div>
							<p class="text-center text-gray-500" title="Autor">
								'.$autor.'
							</p>
							<div class="details">
								'.$detalles.'						
							</div>
							<div class="counter" id="idCounter"></div>
						';

						$rtn = $this->parents->interfaz->str_rtn_array('Desea Iniciar el Test Virtual',$str,$btn);
			
					}
					
					//Redireccionar a examen si está ACTIVA en caso contrario mostrar modal  de concluida.
					elseif($numExamAmision == 1)
					{

						$datos["idad"] = $idad;

						//Preguntar si el examen esta ACTIVA o CONCLUIDA
						if($this->parents->gn->existe_admision_activa($idad)){

							$rtn=array(
								"success" => true,
								"update"  => array(
									array(
										"action" => "notification",
										"value"  => "Redireccionando..."
									),
									array(
										"action" => "redirection",
										"value"  => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
									)
								)
							);
				
						}else{
							
							//Examen concluida.

							$rp = $this->parents->gn->rtn_puntuacion_full($idad,$idExamen);

							$acertada      = $rp["acertada"];
							$num_preguntas = $rp["num_preguntas"];

							foreach($this->parents->gn->rtn_consulta("*","admision","idAdmision=".$idad." AND idUsuario=".$this->parents->session->get("idUser")) as $obj1){
								$str='
									<div class="text-center font-normal text-xl" title="Título del test virtual">
										'.$obj->titulo.'									
									</div>

									<p class="text-center text-gray-500" title="Autor">
										'.$autor.'
									</p>

									<div class="flex justify-center">
										<div class="bg-gray-100 px-4 py-2 m-3 rounded-lg text-lg font-semibold shadow-md">'.$acertada.' / '.$num_preguntas.'</div>
									</div>

									<div class="text-center mb-3">
										<a class="text-blue-500">'.$ver_test.'</a> .										
										<a class="text-blue-500 send" data-destine="init/intentarNuevamente" data-data="'.$data.'">Inténtelo de nuevo<i class="icon-angle-double-right"></i></a>
									</div>
								';
								
								$str.= $this->mostrarModalResultadosExamen($datos,false);							
								
							}

							$rtn = $this->parents->interfaz->str_rtn_array('Test concluida',$str);
				
						}

					}

					//Mostrar modal mostrar lista examenes.
					elseif($numExamAmision >1)
					{
						$lista         = '';

						$lista.='<ul class="list-group list-group-flush mt-2">';

							$query = "
								SELECT a.idAdmision,a.estado_admision,a.registro_inicial,e.idex,e.titulo,e.idUsuario AS e_idUsuario FROM admision a 
									INNER JOIN examen e ON a.idExamen=e.idExamen 
								WHERE a.idExamen=".$idExamen." AND a.idUsuario=".$this->parents->session->get("idUser")." ORDER BY a.registro_inicial DESC;
							";

							if($this->parents->sql->consulta($query)){
								
								foreach($this->parents->sql->resultado as $obj){
									
									$str_icon      = '';
									$str_resultado = '';

									if($obj->estado_admision == 'ACTIVA')
										$str_icon = '<i class="icon-spin3 animate-spin"></i>';
									if($obj->estado_admision == 'CONCLUIDA'){

										$data = htmlspecialchars(json_encode(array("idex"=>$obj->idex,"idad"=>$obj->idAdmision,"titulo"=>$obj->titulo,"code_action"=>"ma")));
										$str_icon       = '<i class="icon-check-1"></i>';
										$str_resultado  = '<span class="send btn btn-success btn-sm" data-destine="init/mostrarModalResultadosExamen" data-data="'.$data.'" title="Resultados del examen" style="padding:0.1rem 0.1rem;font-size:0.7rem;"><i class="icon-doc-text"></i></span>';

									}
									if($obj->estado_admision == 'CANCELADA')
										$str_icon = '<i class="icon-attention-alt"></i>';

									$lista .= '
										<li class="list-group-item">											
											<h6 class="item-title">'.$str_icon.' '.$obj->titulo.'</h6><a href="'.URL.'/view/test/'.$this->parents->gn->rtn_post_exam($obj->idex,$obj->idAdmision).'" class="text-primary ms-1">ver test</a> 
											<div class="item-subtitle text-secondary ms-4">
												<span class="text-dark">'.$this->parents->gn->rtn_autor($obj->e_idUsuario).'</span> .
												<span> Hace '.$this->parents->gn->get_elapsed_time($obj->registro_inicial).'</span> .
												'.$str_resultado.'
											</div>
										</li>
									';

								}	
							}
						$lista.='</ul>';

						$str='
							<div class="row">
								<div class="col-8">
									<h5 class="">
										'.$obj->titulo.'
									</h5>
								</div>
								<div class="col-4 text-center">
									<a href="#" class="send" data-destine="init/intentarNuevamente" data-data="'.$data.'">Inténtelo de nuevo<i class="icon-angle-double-right"></i></a>
								</div>
							</div>
							'.$lista.'
						';

						$rtn = $this->parents->interfaz->str_rtn_array('Lista de test',$str);
			
					}		
				
				}else{

					if(isset($datos["url"])){
		 				unset($datos["url"]);
		 			}

		 			$datos["destine"]  = "init/loginApertura";

					$rtn = $this->parents->gn->modal_login($datos);

				}
				

			}else{
				$rtn = array(
					"success" => true,
					"update"  => array(
						array(
							"action" => "redirection",
							"value"  => URL."/".MODULE."/error"
						)
					)
				);
			}

			return ($ajax)? json_encode($rtn,JSON_PRETTY_PRINT): $rtn;
		}

		function loginApertura($datos){

			$rtn1 = $this->parents->gn->ingresar($datos);
			
			if($rtn1["success"]){

				//mostrar modal
				$rtn2 = $this->mostrarModalAperturaExamen($datos,false);

				foreach($rtn2["update"] as $val){
					$rtn1["update"][] = $val;
				}
				
				return json_encode($rtn1,JSON_PRETTY_PRINT);
			}


			return json_encode($rtn1,JSON_PRETTY_PRINT);
		}

		function intentarNuevamente($datos){

            // MEJORAR 
            // NO está verificando el número de examenes activa ( NO PUEDE HABER DOS EXAMENES 'ACTIVAS' DEL MISMO AUTOR EN PARALELO )
			//Antes verificar si hay examen ACTIVA del usuario actual(No puede haber dos examenes Activas del segundo usuario)

			$idExamen = $this->parents->gn->rtn_id($datos["idex"]);
			//Verificar el número maximo de exámenes de admisión
			$num_exam_admision = $this->parents->gn->rtn_num_exam_admision($idExamen);
			$num_intentos      = $this->parents->gn->rtn_num_intentos($idExamen);

			if($num_intentos > $num_exam_admision){
				//Crear admisión y redireccionar examen
				if($this->parents->sql->insertar("admision",array("idUsuario"=>$this->idUsuario,"idExamen"=>$idExamen))){
					$idad = $this->parents->sql->LAST_INSERT_ID();
					if($this->parents->sql->modificar("admision",array("estado_examen"=>"EXTEMPORANEA"),array("idAdmision"=>$idad))){
						//redireccionar...
						$rtn=array(
							"success"     => true,
							"redirection" => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
						);
					}
				}	
			}else{
				$rtn = array(
					"success" => false,
					"update"  => array(
						array(
							"action" => "notification",
							"value"  => "Para el test actual sólo se permite ".$num_intentos." intento(s)."
						)
					)
				);
			}

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		function iniciarExamen($datos){

			// Verificar nuevamente tipo de usuario
			// Verificar nuevamente si el examen es público
			// Verificar si existe admisión del examen actual
			// Crear registro de admisión
			// Verificar admisión está en estado ACTIVA
			// No puede haber un suario con dos exámnes a la vez en el mismo tiempo.
			// En admisión el examen esta ACTIVA entoces redireccionar examen actual
			// En admision el examen está COCLUIDA entoces mostrar mensaje

			$idex     = $datos["idex"];
			$idExamen = $this->parents->gn->rtn_id($datos["idex"]);			
			$rtn      = array();

			if($this->parents->session->type_users(array('USUARIO','DOCENTE','EMPRESA'))){
				
				if($this->parents->gn->idex_publica($datos["idex"])){

					$rc   = $this->parents->gn->rtn_consulta("idAdmision","admision","idExamen=".$idExamen." AND idUsuario=".$this->idUsuario);

					if(count($rc) == 1){

						$idad = $rc[0]->idAdmision;

						if($this->parents->gn->existe_admision_activa($idad)){
							//redireccionar a examen ACTIVA
								$rtn=array(
									"success"     => true,
									"redirection" => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
								);
						}else{
							//Mostrar mensaje de examen CONCLUIDA y RESULTADOS básicos
							$rtn = array(
								"success" => false,
								"update"  => array(
									array(
										"action" => "notification",
										"type"   => "notific-bottom",
										"value"  => "EXAMEN CONCLUIDA"
									)
								)					
							);
						}

					}else{

						$rc = $this->parents->gn->rtn_consulta("estado","examen","idExamen=".$idExamen);

						if($rc[0]->estado == 'LIBRE')
						{
							//Examen LIBRE
							//Crear admisión y redireccionar examen

							if($this->parents->sql->insertar("admision",array("idUsuario"=>$this->idUsuario,"idExamen"=>$idExamen))){

								$idad = $this->parents->sql->LAST_INSERT_ID();

								if($this->parents->sql->modificar("admision",array("estado_examen"=>"ACTIVA"),array("idAdmision"=>$idad))){								

									//Actualizar contador de exámenes resueltas
									$this->parents->gn->actualizar_num_resueltas($datos["idex"]);

									//redireccionar...
									$rtn = array(
										"success"=>true,
										"update" => array(
											array(
												"id"     => "idCounter",
												"action" => "html",
												"value"  => $this->parents->interfaz->get_str_container_number()
											),
											array(
												"action" => "redirection",
												"type"   => "delay",
												"value"  => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
											)
										)
									);
								}

							}

							return json_encode($rtn,JSON_PRETTY_PRINT);

						}


						date_default_timezone_set("America/Lima");


						if($rc[0]->estado == 'RESTRINGIDA')
						{
							//Examen RESTRINGIDO
							//Verificar fecha fijada
							$rc = $this->parents->gn->rtn_consulta("fijar_fecha,tiempo_inicial,tiempo_final","examen","idex='".$datos["idex"]."'");

							$fecha_servidor = strtotime(date("Y-m-d H:i:s",time()));
							$fecha_inicial  = strtotime($rc[0]->fijar_fecha.$rc[0]->tiempo_inicial);
							$fecha_final    = strtotime($rc[0]->fijar_fecha.$rc[0]->tiempo_final);

							if($fecha_servidor >= $fecha_inicial  && $fecha_servidor <= $fecha_final)
							{
								//Crear admisión y redireccionar examen
								//Poner el estado del examen en ACTIVA

								if($this->parents->sql->insertar("admision",array("idUsuario"=>$this->idUsuario,"idExamen"=>$idExamen))){
									$idad = $this->parents->sql->LAST_INSERT_ID();
									if($this->parents->sql->modificar("admision",array("estado_examen"=>"ACTIVA"),array("idAdmision"=>$idad))){
										
										//Actualizar contador de exámenes resueltas
										$this->parents->gn->actualizar_num_resueltas($datos["idex"]);
										
										//redireccionar...
										$rtn = array(
											"success"=>true,
											"update" => array(
												array(
													"id"     => "idCounter",
													"action" => "html",
													"value"  => $this->parents->interfaz->get_str_container_number()
												),
												array(
													"action" => "redirection",
													"type"   => "delay",
													"value"  => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
												)
											)
										);
									}
								}
							}
							elseif($fecha_servidor > $fecha_final)
							{
								//Crear admisión y redireccionar examen
								//Poner el estado del examen en EXTEMPORANEA

								if($this->parents->sql->insertar("admision",array("idUsuario"=>$this->idUsuario,"idExamen"=>$idExamen))){
									$idad = $this->parents->sql->LAST_INSERT_ID();
									if($this->parents->sql->modificar("admision",array("estado_examen"=>"EXTEMPORANEA"),array("idAdmision"=>$idad))){
										//redireccionar...
										$rtn = array(
											"success"=>true,
											"update" => array(
												array(
													"id"     => "idCounter",
													"action" => "html",
													"value"  => $this->parents->interfaz->get_str_container_number()
												),
												array(
													"action" => "redirection",
													"type"   => "delay",
													"value"  => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
												)
											)
										);
									}
								}
							}
							elseif($fecha_servidor < $fecha_inicial)
							{
								$rtn=array(
									"success"      => false,
									"notification" => "Aun no está lista el examen."
								);
							}else{
								$rtn=array(
									"success"      => false,
									"notification" => "No se fijó una fecha."
								);
							}
							
							return json_encode($rtn,JSON_PRETTY_PRINT);
							
						}

						if($rc[0]->estado == 'CONCODIGO')
						{
							//Examen CONCODIGO
							//verificar con código
							//Verificar fecha fijada


							$clave = (isset($datos["clave"]))?$datos["clave"]:'';

							if($this->parents->gn->verifica_valor($clave)){
								if(!$this->parents->gn->verificar_exam_clave($clave,$idExamen)){
									$msj = "Código inválido.";
									$rtn = array(
										"success"=> false,
										"update" => array(
											array(
												"id"     => "modalMsg",
												"action" => "html",
												"value"  => $this->parents->interfaz->msj("warning",$msj)
											)
										)
									);
									return json_encode($rtn,JSON_PRETTY_PRINT);
								}
							}else{
								$msj = "Ingrese el código del test.";
								$rtn = array(
									"success"=> false,
									"update" => array(
										array(
											"id"     => "modalMsg",
											"action" => "html",
											"value"  => $this->parents->interfaz->msj("danger",$msj)
										)
									)
								);
								return json_encode($rtn,JSON_PRETTY_PRINT);
							}

							$rc = $this->parents->gn->rtn_consulta("fijar_fecha,tiempo_inicial,tiempo_final","examen","idex='".$datos["idex"]."'");

							$fecha_servidor = strtotime(date("Y-m-d H:i:s",time()));
							$fecha_inicial  = strtotime($rc[0]->fijar_fecha.$rc[0]->tiempo_inicial);
							$fecha_final    = strtotime($rc[0]->fijar_fecha.$rc[0]->tiempo_final);

							if($fecha_servidor >= $fecha_inicial  && $fecha_servidor <= $fecha_final)
							{
								//Crear admisión y redireccionar examen
								//Poner el estado del examen en ACTIVA

								if($this->parents->sql->insertar("admision",array("idUsuario"=>$this->idUsuario,"idExamen"=>$idExamen))){
									$idad = $this->parents->sql->LAST_INSERT_ID();
									if($this->parents->sql->modificar("admision",array("estado_examen"=>"ACTIVA"),array("idAdmision"=>$idad))){

										//Actualizar contador de exámenes resueltas
										$this->parents->gn->actualizar_num_resueltas($datos["idex"]);

										//redireccionar...
										$rtn = array(
											"success"=>true,
											"update" => array(
												array(
													"id"     => "modalMsg",
													"action" => "html",
													"value"  => ""
												),
												array(
													"id"     => "idCounter",
													"action" => "html",
													"value"  => $this->parents->interfaz->get_str_container_number()
												),
												array(
													"action" => "redirection",
													"type"   => "delay",
													"value"  => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
												)
											)
										);
									}
								}
							}
							elseif($fecha_servidor > $fecha_final)
							{
								//Crear admisión y redireccionar examen
								//Poner el estado del examen en EXTEMPORANEA

								if($this->parents->sql->insertar("admision",array("idUsuario"=>$this->idUsuario,"idExamen"=>$idExamen))){
									$idad = $this->parents->sql->LAST_INSERT_ID();
									if($this->parents->sql->modificar("admision",array("estado_examen"=>"EXTEMPORANEA"),array("idAdmision"=>$idad))){
										//redireccionar...
										$rtn = array(
											"success"=>true,
											"update" => array(
												array(
													"id"     => "modalMsg",
													"action" => "html",
													"value"  => ""
												),
												array(
													"id"     => "idCounter",
													"action" => "html",
													"value"  => $this->parents->interfaz->get_str_container_number()
												),
												array(
													"action" => "redirection",
													"type"   => "delay",
													"value"  => URL."/view/test/".$this->parents->gn->rtn_post_exam($datos["idex"],$idad)
												)
											)
										);
									}
								}
							}
							elseif($fecha_servidor < $fecha_inicial)
							{
								$rtn=array(
									"success"      => false,
									"notification" => "Aun no está lista el examen."
								);
							}else{
								$rtn=array(
									"success"      => false,
									"notification" => "No se fijó una fecha."
								);
							}
							
							return json_encode($rtn,JSON_PRETTY_PRINT);
						}

					}
					
				}else{
					$rtn=array(
						"success" => false,
						"update"  => array(
							array(
								"action" => "notification",
								"type"   => "notific-bottom",
								"value"  => "El examen actual no está PUBLICADO."
							)
						)
					);
				}

			}else{

				$estado = $this->parents->gn->rtn_consulta_unica('estado','examen','idExamen='.$idExamen);

				if($estado == 'LIBRE')
				{
					//Examen LIBRE
					//Crear admisión y redireccionar examen

					if($this->parents->sql->insertar("admision",array("idExamen"=>$idExamen,"idUsuario"=>0))){

						$idad = $this->parents->sql->LAST_INSERT_ID();

						if($this->parents->sql->modificar("admision",array("estado_admision"=>"TMP"),array("idAdmision"=>$idad))){								

							//Actualizar contador de exámenes resueltas
							$this->parents->gn->actualizar_num_resueltas($idex);

							//redireccionar...
							$rtn = array(
								"success"=>true,
								"update" => array(
									array(
										"id"     => "idCounter",
										"action" => "html",
										"value"  => $this->parents->interfaz->get_str_container_number()
									),
									array(
										"action" => "redirection",
										"type"   => "delay",
										"value"  => URL."/view/test/".$this->parents->gn->rtn_post_exam($idex,$idad)
									)
								)
							);
						}

					}

					//return json_encode($rtn,JSON_PRETTY_PRINT);

				}else{

					//verificar políticas de seguridad
					$rtn=array(
						"success" => false,
						"update"  => array(
							array(
								"action" => "notification",
								"type"   => "notific-bottom",
								"value"  => "Usted NO está permitido realizar ésta acción."
							)
						)
					);

				}

			}

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		function verificarExamenActiva($idUsuario){
			// Verificar 
			// Redireccionar a ese examen
			$rtn=array();

			$query="SELECT*FROM admision WHERE estado_admision='ACTIVA' AND idUsuario=".$idUsuario.";";
			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				if(count($resultado) == 1){
					foreach($resultado as $obj){
						$rtn=array(
							"success"   => true,
							"idExamen"  => $obj->idExamen,
							"idUsuario" => $obj->idUsuario
						);
					}
				}else{

					$rtn=array("success"=>false);
				}
			}else{

				$rtn=array("success"=>false);
			}

			return $rtn;
		}

		function guardarRespuesta($datos){

			//Verificar si el examen está CONCLUIDA

			$rtn = array();

			if($this->parents->gn->existe_registro("admision","estado_admision <> 'CONCLUIDA' AND idAdmision=".$datos["idad"]." AND idUsuario=".$this->idUsuario)){
				
				$tipo_eleccion = $this->parents->gn->rtn_tipo_eleccion($datos["idPregunta"]);

				if($tipo_eleccion == 'ES')
				{
					if($this->parents->gn->existe_registro("respuesta","idAdmision=".$datos["idad"]." AND idPregunta=".$datos["idPregunta"]." AND idUsuario=".$this->idUsuario)){
						//Actualizar
						if($this->parents->sql->modificar("respuesta",array("idAdmision"=>$datos["idad"],"idPregunta"=>$datos["idPregunta"],"idAlternativa"=>$datos["idAlternativa"],"idUsuario"=>$this->idUsuario),array("idAdmision"=>$datos["idad"],"idPregunta"=>$datos["idPregunta"],"idUsuario"=>$this->idUsuario))){
							$rtn = array(
								"success"=>true
							);
						}else{
							$rtn = array(
								"success"      => false,
								"notification" => "No se modificó correctamente.\r - Inténtelo de nuevo."
							);
						}
					}else{
						//Crear
						if($this->parents->sql->insertar("respuesta",array("idAdmision"=>$datos["idad"],"idPregunta"=>$datos["idPregunta"],"idAlternativa"=>$datos["idAlternativa"],"idUsuario"=>$this->idUsuario))){
							$rtn = array(
								"success"=>true
							);
						}else{
							$rtn = array(
								"success"      => false,
								"notification" => "No se insertó correctamente.\r - Inténtelo de nuevo."
							);
						}
					}
				}
				elseif($tipo_eleccion == 'EM')
				{
					//Si existe quitar
					//Si no existe agregar 

					//data-data="{"idad":"1","idPregunta":"1","idAlternativa":"1","code_action":"em"}"
					if($this->parents->gn->existe_registro("respuesta","idAdmision=".$datos["idad"]." AND idPregunta=".$datos["idPregunta"]."  AND idAlternativa=".$datos["idAlternativa"]." AND idUsuario=".$this->idUsuario)){
						if($this->parents->sql->eliminar("respuesta",array("idAdmision"=>$datos["idad"],"idPregunta"=>$datos["idPregunta"],"idAlternativa"=>$datos["idAlternativa"],"idUsuario"=>$this->idUsuario),"AND")){
							$rtn = array(
								"success"=>true
							);
						}else{
							$rtn = array(
								"success"      => false,
								"notification" => "No se eliminó corréctamente.\r - Inténtelo de nuevo."
							);
						}
					}else{
						if($this->parents->sql->insertar("respuesta",array("idAdmision"=>$datos["idad"],"idPregunta"=>$datos["idPregunta"],"idAlternativa"=>$datos["idAlternativa"],"idUsuario"=>$this->idUsuario))){
							$rtn = array(
								"success"=>true
							);
						}else{
							$rtn = array(
								"success"      => false,
								"notification" => "No se insertó correctamente.\r - Inténtelo de nuevo."
							);
						}
					}

				}else{
					//No hacer nada
					$rtn = array(
						"success"      => false,
						"notification" => "No hacer nada."
					);
				}
			}else{
				
				$rtn = array(
					"success"      => false,
					"notification" => "El examen está CONCLUIDA.\r - Utilice los botones para ver los resultados del examen."
				);
			}

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}
		
		function mostrarModalResultadosExamen($datos,$ajax=true){

			//Obtener la nota base y demas datos
			//Obtenemos array de alternativas correctas del examen
			//Comparamos el array de alternativas correctas con las alternativas de las respuestas

			$str      = '';
			$idex     = $datos["idex"];
			$idExamen = $this->parents->gn->rtn_id($idex);
			$idad     = $datos["idad"];

			if($this->parents->gn->existe_admision_concluida($idad)){

				$idUsuario  = $this->parents->session->get("idUser");		

				$query = "SELECT*FROM examen e INNER JOIN admision a ON e.idExamen=a.idExamen WHERE a.idAdmision=".$idad." AND a.idUsuario=".$idUsuario.";";

				$this->parents->sql->consulta($query);

				foreach($this->parents->sql->resultado as $obj){

					//retorna puntucaión
					$rp = $this->parents->gn->rtn_puntuacion_full($idad,$idExamen,$idUsuario);

					$puntuacion_total = ($obj->estado == 'LIBRE')? 'AUTO':$rp["puntuacion_total"];

					$str='
						<div class="divide-y divide-gray-200 border rounded-lg font-mono text-xs shadow-lg">
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Estado del test','valor'=>$obj->estado)).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Tipo de test','valor'=>$obj->tipo)).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Puntuación total','valor'=>$puntuacion_total)).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Número de preguntas','valor'=>$rp["num_preguntas"])).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Acertada','valor'=>$rp["acertada"])).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Fallida','valor'=>$rp["fallida"])).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Sin resolver','valor'=>$rp["no_resuelta"])).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Duración','valor'=>($this->parents->gn->dif_fechas($obj->registro_final,$obj->registro_inicial)))).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Inicio','valor'=>$obj->registro_inicial)).'
							'.$this->parents->interfaz->str_gn('nombre-valor',(object) array('nombre'=>'Final','valor'=>$obj->registro_final)).'
						</div>	
					';
					
				}
				
			}else{
				$str=$this->parents->interfaz->msj("info-close","Revise su examen virtual y luego vea los reasultados por aquí.");
			}

			$btn = (isset($datos["code_action"]) && $datos["code_action"] == "ma")?'<button class="btn btn-success send" data-destine="init/mostrarModalAperturaExamen" data-data="'.(htmlspecialchars(json_encode(array("idex"=>$idex,"titulo"=>$datos["titulo"])))).'"><i class="icon-left-open-1"></i>Atrás</button>':'';

			$rtn = $this->parents->interfaz->str_rtn_array('Resultados del test',$str,$btn);

			if($ajax){
				if(isset($datos["rtn_str_array"])){

					return $rtn;
				}
				return json_encode($rtn,JSON_PRETTY_PRINT);
			}else{
				return $str;
			}

		}

		function listaAdmision(){

			$str   = '';
			$lista = '';
			$limit = 10;//config

			//Verificamos tipo de usuario
			if($this->parents->session->type_users(array('USUARIO','DOCENTE','EMPRESA'))){

					$query = "
						SELECT a.idAdmision,a.estado_admision,a.registro_inicial,e.idex,e.titulo,e.idUsuario AS e_idUsuario FROM admision a 
							INNER JOIN examen e ON a.idExamen = e.idExamen 
							INNER JOIN examen_config ec ON ec.idExamen = e.idExamen 
						WHERE a.idUsuario = ".$this->idUsuario." AND ec.publicar = 'SI' AND ec.eliminar = 'NO' ORDER BY a.registro_inicial DESC LIMIT ".$limit.";
					";

					if($this->parents->sql->consulta($query)){
						$resultado = $this->parents->sql->resultado;

						if(count($resultado)>0){
							foreach($this->parents->sql->resultado as $obj){
								if($obj->estado_admision == 'ACTIVA')
									$str_icon = '<i class="icon-spin3 animate-spin"></i>';
								if($obj->estado_admision == 'CONCLUIDA')
									$str_icon = '<i class="icon-check-1"></i>';
								if($obj->estado_admision == 'CANCELADA')
									$str_icon = '<i class="icon-attention-alt"></i>';

								$lista .= '
									<li class="list-group-item">											
										<h6 class="item-title font-medium">'.$str_icon.' '.$obj->titulo.'</h6><a href="'.URL.'/view/test/'.$this->parents->gn->rtn_post_exam($obj->idex,$obj->idAdmision).'" class="text-blue-400 ms-1">ver test</a> 
										<div class="item-subtitle ml-4">
											<span>Autor: '.$this->parents->gn->rtn_autor($obj->e_idUsuario).'</span> .
											<span> Hace '.$this->parents->gn->get_elapsed_time($obj->registro_inicial).'</span>
										</div>
									</li>
								';

							}

							$str .= '
								<ul class="list-group list-group-flush overflow-auto">
									'.$lista.'
								</ul>
							';
						}else{
							$str = $this->parents->interfaz->get_str_container_empty();
						}

					}

			}

			$rtn = array(
				"success" => true,
				"update"  => array(
					array(
						"id"     => "modalPrincipal",
						"action" => "showModal"
					),
					array(
						"id"     => "modalTitle",
						"action" => "html",
						"value"  => "Lista de admisiones recientes"
					),
					array(
						"id"     => "modalBody",
						"action" => "html",
						"value"  => $str
					),
					array(
						"id"     => "modalPrincipal",
						"action" => "openModal"
					)
				)
			);

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		//-------------------------------------------------------------//
		//                           Búsqueda
		//-------------------------------------------------------------//

		function busqueda($txt,$logic='AND',$ajax=false){
			//mostrar solo 12 , mejorar mas adelante
			//limitando la busqueda a 50 para el ordenamiento NC DC

			//busca 
				//define para ordenar y semejantes
				//cuando el resultado es 0 ó 1 muestra semejanza
			//ordena 
				//la primera palabra define la búsqueda  
			//muestra

			$mostrar = 7;//config
			$cont    = 0;

			$textArray  = array();

			$rtnArray   = array();	

			$resultado1 = array();
			$resultado2 = array();		

			$array_new1 = array();
			$array_new2 = array();

			$str     = '';

			$limit   = MAX_REG_BUSQ; 					

			if($this->parents->gn->verifica_valor($txt) && $this->parents->gn->post_slug($txt,' ')!=""){

				//limpiar
				$txt_ps   = $this->parents->gn->post_slug($txt,' ');
				$txt_ps   = trim($txt_ps);

				//crear array
				$textArray = explode(' ',$txt_ps);

				$query = "
					SELECT e.idExamen,e.idex,e.titulo,e.img,e.idUsuario FROM examen e 
					INNER JOIN examen_config ec ON e.idExamen = ec.idExamen
					INNER JOIN usuario u ON u.idUsuario = e.idUsuario
					WHERE ".($this->parents->gn->agregar_concat_ws($textArray,"e.idex,e.titulo,e.img,u.nombre_publico",$logic))." AND (ec.publicar='SI' AND ec.eliminar='NO') LIMIT ".$limit.";
				";


				$this->parents->sql->consulta($query);
				$resultado = $this->parents->sql->resultado;

				// 0 indica no se encontró ninguna coincidencia
				// 1 indica que se encontró solo una coincidencia
				// n indica que hay mas de dos coincidencias

				$str='';

				$count = count($resultado);

				if($count == 0)
				{
					//intente buscar con or
					if($logic == 'AND'){
						return $this->busqueda($txt,'OR');
					}else{
						$msj1  = 'No se encontró resultados para \''.$txt.'\', inténtalo de nuevo.';
						$msj1 .= '<a class="search icon-search-1 send" data-destine="init/mostrarModalBusqueda" title="Buscar por nombre del test,autor o id">Buscar ...</a>';
						$msj2  = 'Sí el test que te interesa no está aquí ,solicitalo ,envia un mensaje a la comunidad <a class="text-primary">Por aquí</a>';
						$str1  = $this->parents->interfaz->str_busqueda_vacia($msj1);
						$str2  = $this->parents->interfaz->str_gn('alerta-triangular',(object) array("msj"=>$msj2));
						return $str1.$str2;
					}

				}
				if($count == 1)
				{
					//solo uno o podría haber más
					foreach($resultado as $obj){
						$obj->resueltas = $this->parents->gn->rtn_num_resueltas($obj->idExamen);
						$str .= $this->parents->interfaz->list_item_exam($obj,array("select"=>"card"));
					}
					return $str;
				}
				if($count > 1)
				{
					
					foreach($resultado as $obj){
						$obj->resueltas = $this->parents->gn->rtn_num_resueltas($obj->idExamen);
						$str .= $this->parents->interfaz->list_item_exam($obj,array("select"=>"card"));
					}
					return $str;

				}
						

			}else{
				return "RESULTADO INICIAL...";
				//return $this->mostrarElementosElegidosRecientemente();
			}

		}

		function rtnTituloTest(){

			$rtnArray = array();

			$query    = "SELECT titulo FROM examen e INNER JOIN examen_config ec ON e.idExamen = ec.idExamen WHERE ec.publicar = 'SI' AND ec.eliminar='NO';";

			if($this->parents->sql->consulta($query)){

				$resultado=$this->parents->sql->resultado;

				foreach($resultado as $obj){
				  //$rtn['img']    = $obj->img;
					$rtn["titulo"] = $obj->titulo;							
					$rtnArray[]    = $rtn;
				}
			}
			return json_encode($rtnArray,JSON_PRETTY_PRINT);
		}

		//-------------------------------------------------------------//
		//                      Notificaciones
		//-------------------------------------------------------------//

		function notificacion($datos){

			//Mostrar número de test recientes
			//Mostrar lista de notificaiones de test resueltas recientemente colocados entre el más y menos reciente.
			//Mostrar modal de alerta cuando se haya cambiado de usuario

			$rtn  = array();
			$num1 = 0;
			$num2 = 0;
			$num3 = 0;

			$idUsuario = ($this->idUsuario) ? $this->idUsuario:0;

			//verificar si hay sessión
			if($this->parents->session->check_login()){

				if($datos["idUsuario"] == $idUsuario){
		
					foreach($this->parents->gn->rtn_consulta("notificar_adm_public,notificar_comentar","usuario","idUsuario=".$this->idUsuario) as $obj){
						
						//registro de admisiones activas
						$num1 = $num1 + $this->numAdmisionActiva();

						//registros de recientes de admisiones públicas
						$num2 = $num2 + $this->numAdmisionPublicas($obj->notificar_adm_public);
						/*
						//registro de examenes activas
						$num1 = $num1 + count($this->listaExamenRestringida($obj->notificar));
						//registro  de los examenes de usuarios seguidos				
						$num2 = $num2 + count($this->listaExamenUsuarioSeguido($obj->notificar));
						*/
					}

					$num3 = $num1+$num2;

					$num1 = ($num1>0)? $num1:"";
					$num2 = ($num2>0)? $num2:"";
					$num3 = ($num3>0)? $num3:"";


					$rtn = array(
						"success" => true,
						"update"  => array(
							array(
								"class"  => "notify-admission",
								"action" => "html",
								"type"   => "class",
								"value"  => $num1  
							),
							array(
								"class"  => "notify-public",
								"action" => "html",
								"type"   => "class",
								"value"  => $num2  
							),
							array(
								"class"  => "notify-adm-pub",
								"action" => "html",
								"type"   => "class",
								"value"  => $num3  
							)
						)
					);

				}else{

					//Mostrar modal de alerta de cambio de usuario

					$data = htmlspecialchars(json_encode(array("redirect"=>"auto")));			

					$btn  = '<button type="button" class="btn btn-default send" data-destine="init/salir" data-data="'.$data.'">Actualizar página</button>';

					$rtn = array(
						"success" => false,
						"update"  => array(
							array(
								"id"     => "modalAlertConfirmTitle",
								"action" => "html",
								"value"  => APP_NAME." dice:"
							),
							array(
								"id"     => "modalAlertConfirmBody",
								"action" => "html",
								"value"  => "Se detectó un cambio de usuario."
							),
							array(
								"id"     => "modalAlertConfirmFooter",
								"action" => "html",
								"value"  => $btn
							),
							array(
								"id"     => "modalAlertConfirm",
								"action" => "openModal"
							)
						)
					);

				}
																		
			}else{
				$rtn=array(
					"success" => true
				);
			}

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		function numAdmisionActiva(){
			
			$query = "
					SELECT e.idex FROM examen e 
						INNER JOIN  admision ad ON e.idExamen  = ad.idExamen 
					WHERE (ad.estado_admision = 'ACTIVA') AND (e.idUsuario <> ".$this->idUsuario." AND ad.idUsuario = ".$this->idUsuario.");
					";

			if($this->parents->sql->consulta($query)){
				return count($this->parents->sql->resultado);
			}
			return 0;
		}

		function numAdmisionPublicas($fecha){		

			$query = "
				SELECT e.idex FROM examen e 
					INNER JOIN  admision ad ON e.idExamen  = ad.idExamen
					INNER JOIN  usuario  u  ON u.idUsuario = ad.idUsuario 
				WHERE (ad.registro_final >='".$fecha."') AND (e.idUsuario=".$this->idUsuario." AND ad.idUsuario <> ".$this->idUsuario.")
			";

			if($this->parents->sql->consulta($query)){
				return count($this->parents->sql->resultado);
			}
			
			return 0;
		}

		//-------------------------------------------------------------//
		//                      Seguir
		//-------------------------------------------------------------//

		function seguir($datos,$ajax=true){
			//verificar login				
				//actualizar seguidor.
				//redirecionar a login

			$rtn       = array();
			$idUsuario = $this->parents->session->get("idUser");

			if($this->parents->session->check_login()){

				//verificamos idUsuarios y seguir
				$viu = $this->parents->gn->verificar_id_usuarios([$idUsuario,$datos["idUsuario"]]);
				$vs  = $this->parents->gn->verificar_seguir($idUsuario,$datos["idUsuario"]);

				if($viu && !$vs){

					if($this->parents->sql->insertar("seguir",array("idUsuario_1"=>$idUsuario,"idUsuario_2"=>$datos["idUsuario"]))){

						$rtn = array(
							"success" => true,
							"update"  => array(
								array(
									"id"     => "containerFollow",
									"action" => "html",
									"value"  => $this->parents->interfaz->str_dejar_seguir($datos)
								),
								array(
									"action" => "notification",
									"type"   => "notific-bottom",
									"time"   => 1000,
									"value"  => "En hora buena, gracias por seguirme."
								)
							)
						);
					}

				}else{
					$rtn = array(
						"success" => true,
						"update"  => array(
							array(
								"action" => "redirection",
								"type"   => "auto"
							)
						)
					);
				}

			}else{


				if(isset($datos["url"]))
	 				unset($datos["url"]);	 			

	 			$datos["destine"]  = "init/loginSeguir";

				$rtn = $this->parents->gn->modal_login($datos);
			}

			return ($ajax)? json_encode($rtn,JSON_PRETTY_PRINT): $rtn;
		}

		function loginSeguir($datos){

			$rtn1 = $this->parents->gn->ingresar($datos);

			if($rtn1["success"]){

				$rtn2 = $this->seguir($datos,false);

				foreach($rtn2["update"] as $val){
					$rtn1["update"][] = $val;
				}
			
				return json_encode($rtn1,JSON_PRETTY_PRINT);
			}

			return json_encode($rtn1,JSON_PRETTY_PRINT);
		}

		function siguiendo($idUsuario){

			$str  = '';
			$data = htmlspecialchars(json_encode(array("idUsuario"=>$idUsuario)));

			if($idUsuario != $this->idUsuario){
				if($this->parents->gn->existe_registro("seguir","idUsuario_1=".$this->idUsuario." AND idUsuario_2=".$idUsuario)){
					$str = $this->parents->interfaz->get_dejar_seguir(array("idUsuario"=>$idUsuario));
				}else{
					$str = '<a class="SendAjax" data-destine="'.URL.'init/json/seguir" data-data="'.$data.'">Seguir</a>';
				}
			}

			echo $str;
		}

		function dejarDeSeguir($datos){

			$rtn = array();

			$str = $this->parents->interfaz->str_seguir(array("idUsuario"=>$datos["idUsuario"]));

			if($this->parents->session->check_login()){

				if($this->parents->sql->eliminar("seguir",array("idUsuario_1"=>$this->idUsuario,"idUsuario_2"=>$datos["idUsuario"]),"AND")){
					
					$rtn = array(
						"success" => true,
						"update"  => array(
							array(
								"id"     => "containerFollow",
								"action" => "html",
								"value"  => $str
							),
							array(
								"action" => "notification",
								"type"   => "notific-bottom",
								"time"   => 1000,
								"value"  => "Dejaste de seguirlo."
							)
						)
					);
					
				}
				return json_encode($rtn,JSON_PRETTY_PRINT);
			}
		}

		//-------------------------------------------------------------//
		//                      categoria
		//-------------------------------------------------------------//
				
		function mostrarModalCategoria($ajax=true){

			$str_categoria1 = $this->parents->interfaz->str_categoria(1);
			$str_categoria2 = $this->parents->interfaz->str_categoria(2);
			$str_categoria3 = $this->parents->interfaz->str_categoria(3);

			$msj           = $this->parents->interfaz->str('msj-mas-categoria');

			$str = '
			<div class="mod-header"></div><!--/mod-header-->
			<div class="mod-body">

				<div class="row justify-content-center">
					<div class="col-sx-12 col-sm-10 col-md-9">

						<div class="text-center text-dark fs-5 my-4 font-weight:5">Destacados</div>
						<div class="container-category d-flex flex-wrap justify-content-center">
						    '.$str_categoria1.'				    
						</div><!--/container-category-->
						<!--msj-->					

						<div class="text-center text-dark fs-5 my-4 font-weight:5">Tecnología</div>
						<div class="container-category d-flex flex-wrap justify-content-center">
						    '.$str_categoria2.'				    
						</div><!--/container-category-->
						<div class="text-center text-dark fs-5 my-4 font-weight:5">Ciencia y cultura</div>
						<div class="container-category d-flex flex-wrap justify-content-center">
						    '.$str_categoria3.'				    
						</div><!--/container-category-->
						<!--msj-->
						'.$this->parents->interfaz->msj('info-close',$msj,'margin-top:2rem;').'

					</div>
				</div>

			</div><!--/mod-body-->
			<div class="mod-footer"></div><!--/mod-footer-->
			';

			$rtn = array(
				"success" => true,
				"update"  => array(
					array(
						"id"     => "modalPrincipal",
						"action" => "showModal"
					),
					array(
						"id"     => "modalTitle",
						"action" => "html",
						"value"  => "Test"
					),
					array(
						"id"     => "modalBody",
						"action" => "html",
						"value"  => $str
					),
					array(
						"id"     => "modalPrincipal",
						"style"  => "modal-lg modal-fullscreen-sm-down",
						"action" => "openModal"
					)
				)
			);

			return ($ajax)?json_encode($rtn,JSON_PRETTY_PRINT):$str;
		}

		function mostrarSubcategoria($datos){

			//Verificamos exite subcategoria
			//En caso de no existir mostrar solo Categoria

			$str = '';
			$rtn = array(
				"success" => true,
				"update"  => array()
			);

			$idCategoria    = $datos["idCtg"];

			if($this->parents->gn->existe_subategorias_mostrar($idCategoria)){

				$rc = $this->parents->gn->rtn_consulta("idSubcategoria,idCategoria,descripcion","subcategoria","idCategoria =".$idCategoria." AND mostrar='SI' ORDER BY orden ASC");

				foreach($rc as $obj){
					$str .= $this->parents->interfaz->str_subcategoria($obj);
				}

				$str .= $this->parents->interfaz->str_subcategoria((object)array("idCategoria"=>$idCategoria,"descripcion"=>"todo"));

				$rtn["update"][] = array(
					"action" => "this",
					"type"   => "removeClass",
					"value"  => "send"
				);

				$rtn["update"][] = array(
					"action" => "this",
					"type"   => "after",
					"value"  => $str
				);

			}

			return json_encode($rtn,JSON_PRETTY_PRINT);

		}

		function mostrarListaCategoria($idCategoria,$idSubcategoria=0){// mejorar en su debido tiempo

			$str   = '';
			$limit = 30;//config
			$descripcion = '';

			$idCtg    = (isset($idCategoria) && is_numeric($idCategoria))? $idCategoria : 0;
			$idSubctg = (isset($idSubcategoria) && is_numeric($idSubcategoria))? $idSubcategoria : 0;

			$query = "
				SELECT e.idExamen,e.idex,e.titulo,e.img,e.publicacion,e.idUsuario FROM examen e 
					INNER JOIN examen_config ec ON e.idExamen = ec.idExamen
					INNER JOIN examen_categoria eca ON e.idExamen = eca.idExamen				
			";

			//Categoria y subcategoria
			if($idCtg != 0 && $idSubctg != 0){

				$query .= "
					WHERE (eca.idCategoria=".$idCtg." AND eca.idSubcategoria = ".$idSubctg.") AND (ec.publicar='SI' AND ec.eliminar='NO') ORDER BY e.registro DESC LIMIT ".$limit.";
				";

				$rc  = $this->parents->gn->rtn_consulta("descripcion","subcategoria","idSubcategoria=".$idSubctg." AND idCategoria=".$idCtg);
				$descripcion = (count($rc)>0)? $rc[0]->descripcion:'';
			}

			//Sólo categoria
			if($idCtg != 0 && $idSubctg == 0){

				$query .= "
					WHERE (eca.idCategoria=".$idCtg.") AND (ec.publicar='SI' AND ec.eliminar='NO') GROUP BY e.idExamen ORDER BY e.publicacion LIMIT ".$limit.";
				";

				$rc = $this->parents->gn->rtn_consulta("descripcion","categoria","idCategoria=".$idCtg);
				$descripcion = (count($rc)>0)? $rc[0]->descripcion:'';
			}
		
			if($this->parents->sql->consulta($query)){
				if($descripcion != ''){

					if(count($this->parents->sql->resultado)>0){

						foreach($this->parents->sql->resultado as $obj){
							
							$obj->resueltas = $this->parents->gn->rtn_num_resueltas($obj->idExamen);

							$str .= $this->parents->interfaz->list_item_exam($obj,array("select"=>"card"));
						}

					}else{
						$msj = 'No se encontrarón resultados para la opción "<strong>'.$descripcion.'</strong>", inténtelo de nuevo o elija otra:';
						$str  = $this->parents->interfaz->str_busqueda_vacia($msj);
						$str .= $this->mostrarModalCategoria(false);
					}

				}else{
					$msj = 'No se encontrarón resultados ,inténtelo de nuevo...';
					$str  = $this->parents->interfaz->str_busqueda_vacia($msj);
				}
			}

			return $str;
		}

		//-------------------------------------------------------------//
		//                       admin
		//-------------------------------------------------------------//

		function confirmarEmail($csm,$idUsuario){
			//verificar si existe CSM

			if($this->parents->gn->existe_registro("usuario","clave_seguridad_multiple='".$csm."'") && ACTION == 'confirm_email'){
				$this->parents->sql->modificar('usuario',array('estado'=>'ACTIVA','clave_seguridad_multiple'=>''),array('idUsuario'=>$idUsuario));
				$msj = "<strong>¡ FELICIDADES SU CUENTA SE ACTIVÓ CORRECTAMENTE !</strong> ,inicie sesión.";
				$msj = $this->parents->interfaz->tipo_msj("success",$msj);
			}else{
				$msj = "El LINK fue utilizado o caducó.<br> Inténtalo de nuevo.";
				$msj = $this->parents->interfaz->tipo_msj("danger",$msj);
			}
			return $msj;
		}

		//-------------------------------------------------------------//
		//                           init test
		//-------------------------------------------------------------//

		function rtnResumenContenidoTest($idex=null,$pag=1,$ajax=true){
			//Verificar exam publica

			$idExamen   = $this->parents->gn->rtn_id($idex);
			$tmp        = array();
			$exam_array = array();

			$rtn = array(
				"success" => true,
				"update"  => array()
			);

			foreach($this->parents->gn->rtn_consulta('*','pregunta','idExamen='.$idExamen) as $obj1){

				$tmp['idPregunta']  = $obj1->idPregunta;
				$tmp['descripcion'] = $obj1->descripcion;

				foreach($this->parents->gn->rtn_consulta('*','alternativa','idPregunta='.$obj1->idPregunta) as $obj2){
					$tmp['alternativas'][] = $obj2->descripcion;
				}

				$exam_array[] = $tmp;
				$tmp = array();
			}

			return ($ajax)?json_encode($rtn):$this->parents->gn->array_to_object($exam_array,'n');

		}

		function rtnDatosExamen($idex=null){
			$array = $this->parents->gn->rtn_consulta('idExamen,titulo,descripcion,img,estado,nivel,resueltas','examen',"idex='".$idex."'");
			return $array[0];
		}

		//-------------------------------------------------------------//
		//                           ...
		//-------------------------------------------------------------//

	}

	// Paralel
?>