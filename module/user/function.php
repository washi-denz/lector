<?php
	class fnUser{

		var $idUsuario = 0;

		function __construct(&$parents){

			$this->parents   = $parents;
			$this->idUsuario = $this->parents->session->get("id_user");

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

		function login($datos){

			$rtn = $this->parents->gn->ingresar($datos);

			return json_encode($rtn,JSON_PRETTY_PRINT);
		}

		function registrar($datos){

			$rtnArray = array();
			$rpta     = true;
			$rtn      = array();

			$correo   = (isset($datos['correo']))   ? $datos['correo'] : '';
			$clave1   = (isset($datos['clave1']))   ? $datos['clave1'] : '';
			$clave2   = (isset($datos['clave2']))   ? $datos['clave2'] : '';
			$terminos = (isset($datos['terminos'])) ? $datos['terminos'] : 'off';
		
			$rtnArray[] = $this->parents->gn->validar('email',$correo);
			$rtnArray[] = $this->parents->gn->validar('code',array($clave1,$clave2));

			for($i=0;$i<count($rtnArray);$i++){
				if($rtnArray[$i]['success']!=true){
					$rpta = false;
				}
			}	

			if($rpta!=false){

				if(!$this->parents->gn->existe_registro("usuarios","correo = '".$correo."'")){

					if($terminos == 'on'){
						//mostrar mensaje de Exito y luego redireccionar a init

						//generar clave de seguridad multiple
						$csm = $this->parents->gn->generar_clave_seguridad_multiple();

						//Elije el tipo de usuario PREACTIVA
						//Con la preactividad aseguramos todas las condiciones necesarias para que una actividad ocurra. 

						$estado        = 'PREACTIVA';
						$tipoUsuario   = 'USUARIO';
						$nombrePublico = $this->parents->gn->generar_nombre_publico($correo);

						$array_usuario = array(
							'correo'         => $correo,
							'clave'          => md5($clave1),
							'cu'             => $clave1,
							'estado'         => $estado,
							'acepta_tpp'     => 'SI',
							'nombre_publico' => $nombrePublico,
							'idTipoUsuario'  => $tipoUsuario
						);

						if($this->parents->sql->insertar('usuario',$array_usuario)){

							//recuperar id usuario
							//nombre archivo
							//crear imagen
							//actualizar campo img de la tabla usuario
							

							$idUsuario  = $this->parents->sql->LAST_INSERT_ID();
							$nombreArch = "img_".$idUsuario.".png";

							$dataArray['nombre'] = $correo;
							$dataArray['uri']    = URI."/data/img_user/".$nombreArch;
					
							if($this->parents->gn->crear_img_usuario($dataArray)){
								$this->parents->sql->modificar('usuario',array('img'=>$nombreArch),array('idUsuario'=>$idUsuario));
							}	

							$login_session= array(
								"id_user"      => $idUsuario,
								"public_name" => $nombrePublico,
								"type_user"   => $tipoUsuario
							);

							$this->parents->session->put_login($login_session);	

							$msj = "EXITO...";
							$msj = $this->parents->interfaz->msj("success",$msj);

							$rtn = array(
								"success" => true,
								"update"  => array(
									array(
										"class"  => "msj",							
										"action" => "html",
										"type"   => "class",
										"value"  => ""
									),
									array(
										"id"     => "formMsj",
										"action" => "html",
										"value"  => $msj
									),
									array(
										"action" => "notification",
										"value"  => "Redireccionando..."
									),
									array(
										"action" => "redirection",
										"value"  => URL."/init/welcome"
									)
								)
							);
						}else{

						}

					}else{

						$msj = "<strong>Acepte</strong> los <strong>términos y políticas</strong> de ".APP_NAME;
						$msj = $this->parents->interfaz->msj("warning",$msj);

						$rtn = array(
							"success" => true,
							"update"  => array(
								array(
									"class"  => "msj",							
									"action" => "html",
									"type"   => "class",
									"value"  => ""
								),
								array(
									"id"     => "formMsj",
									"action" => "html",
									"value"  => $msj
								)
							)
						);
					}

				}else{
					$msj = "<strong>No se pudo crear su cuenta</strong>, porque ya existe un usuario con el email '".$correo."'.<br>Intentalo de nuevo con un nuevo email.";
					$msj = $this->parents->interfaz->msj("warning",$msj);

					$rtn = array(
						"success" => true,
						"update"  => array(
							array(
								"class"  => "msj",							
								"action" => "html",
								"type"   => "class",
								"value"  => ""
							),
							array(
								"id"     => "formMsj",
								"action" => "html",
								"value"  => $msj
							)
						)
					);
				}

			}else{
				$rtn=array(
					"success" => true,
					"update"  => array(
						array(
							"id"     => "idCorreo",
							"action" => "html",
							"value"  => $rtnArray[0]['msj']
						),
						array(
							"id"     => "idClave2",
							"action" => "html",
							"value"  => $rtnArray[1]['msj']
						)
					)
				);
			}
			return json_encode($rtn,JSON_PRETTY_PRINT);	
		}	

	}
?>