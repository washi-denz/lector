<?php
	class General{

		var $parents;

		function __construct(&$parents){
			$this->parents   = $parents;
			$this->idUsuario = $this->parents->session->get("id_user");
		}

		public function redireccion($url,$no=false){
			if(!$no){
				header("Location:".$url);
				exit();
			}
		}

		public function redireccion_ajax($url){
			$rtn = array(
				"success"     => true,
				"redirection" => $url
			);
			return json_encode($rtn,JSON_PRETTY_PRINT);
		}
		
		public function sesion_automatica($bool=false){

			$array_session= array("id_user"=>1,"public_name"=>'Washi');//config
			//$array_session= array("idUser"=>6,"public_name"=>'Elon',"type_user"=>'DOCENTE');//config

			if(is_array($array_session) && $bool == true){
				$this->parents->session->put_login($array_session);
				//$this->redireccion($url);
			}else{
				$this->parents->session->remove();
			}
		}

		function rtn_consulta($atrib,$tabla,$condicion=''){
			//valor,data,idData=1
			$rtn   = array();
			$query = "SELECT ".$atrib." FROM ".$tabla.";";
			$where = " WHERE ";

			if($condicion!=''){
				$query="SELECT ".$atrib." FROM ".$tabla.$where.$condicion.";";
			}		

			if($this->parents->sql->consulta($query)){
				$rtn=$this->parents->sql->resultado;
				return $rtn;
			}
			return $rtn;
		}

		function rtn_consulta_unica($atrib,$tabla,$condicion=''){
			//valor,data,idData=1
			$rtn   = array();
			$query = "SELECT ".$atrib." FROM ".$tabla.";";
			$where = " WHERE ";

			if($condicion!=''){
				$query = "SELECT ".$atrib." FROM ".$tabla.$where.$condicion.";";
			}		

			if($this->parents->sql->consulta($query)){
				$rtn = $this->parents->sql->resultado;
				return $rtn[0]->$atrib;
			}
			return $rtn;
		}

		function rtn_fn($cad = array()){
			$rtn = array();

			foreach($cad as $ind => $fn){

				if(is_array($fn)){
					$rtn[$ind] = call_user_func_array(array($this,$ind),$fn);
				}else{
					$rtn[$fn] = $this->$fn();
				}
			}

			return (object) $rtn;
		}

		function rtn_num_alumnos(){
			$rc = $this->rtn_consulta('COUNT(*) AS numReg','alumnos','idUsuario='.$this->idUsuario);
			return $rc[0]->numReg;
		}
		
		//-------------------------------------------------------------//
		//                 generalidades
		//-------------------------------------------------------------//

		function verifica_valor($str){
			//retorna true si hay caracteres significativos
			//retorna false si se encuenta:
			// - vacio
			// - " " o "   " espcio(s) simple(s)
			// - "/t" o "/t/t..." tabulaciones
			return (trim($str)!='') ? true : false;
		}
		
		function login($datos){
			
			$rtn   = array();

			$query = "SELECT id,nombre_publico,estado FROM usuarios WHERE (correo LIKE ? OR usuario LIKE ?) AND clave = ?;";

			$usuario    = $datos["usuario"];
			$contrasena = md5($datos["clave"]);

			$datos_seguros = array($usuario,$usuario,$contrasena);

			if($this->parents->sql->consulta_segura($query,$datos_seguros)){

				$resultado = $this->parents->sql->resultado;

				if(count($resultado)>0){

					foreach($resultado as $obj){

						if($obj->estado == 'ACTIVA'){

							$login_session= array(
								"id_user"      => $obj->id,
								"public_name"  => $obj->nombre_publico,
							);

							$this->parents->session->put_login($login_session);					

							$rtn = array(
								"success" => true
							);
							return $rtn;
						}
						elseif($obj->estado == 'INACTIVA'){
							$msj = $this->parents->interfaz->msj("success","Por su seguridad , su cuenta está inactiva por el momento<br><i>- Actívela por <a href='".URL."admin/activate'>Aquí</a></i>");
						}
						elseif($obj->estado == 'BLOQUEADA'){
							$msj = $this->parents->interfaz->msj("danger","Su cuenta está BLOQUEADA<br> Quiere saber más:<br><i>- Envienos un mensaje por <a>Aquí</a></i>");
						}
						else{
							$msj = $this->parents->interfaz->msj("danger","Correo electrónico o Usuario o Contraseña incorrectas.");
						}

					}

				}else{
					$msj = $this->parents->interfaz->msj("danger","Usuario o clave incorrecto.");
				}

			}else{
				$msj = $this->parents->interfaz->msj("danger","ENTRADA DE DATOS NO PERMITIDA , Paralel segurity");
			}

			$rtn = array(
					"success" => false,
					"msj"     => $msj
				);

			return $rtn;
		}

		function ingresar($datos){

			$rtn = array();

			$usuario  = (isset($datos['usuario']))? $datos['usuario'] : '';
			$clave    = (isset($datos['clave']))?   $datos['clave']   : '';

			$redirect = (isset($datos['redirect']))? $datos['redirect'] : '';

			$rtn = array(
				"success" => true,
				"update"  => array()
			);

			if($this->verifica_valor($usuario) && $this->verifica_valor($clave)){
 
				$rtn = $this->login($datos);

				if($rtn["success"]){

					if($redirect != ''){

						$rtn["update"][] = array(
							"action" => "redirection",
							"value"  => $redirect
						);

					}else{
					
						$str1 = $this->parents->interfaz->str_nav_user();
						$str2 = $this->parents->interfaz->str_nav();

						$rtn["update"][] = array(
							"id"     => "iconUser",
							"action" => "before" ,
							"value"  => $str1
						);
						
						$rtn["update"][] = array(
							"id"     => "dropdownmenu",
							"action" => "html" ,
							"value"  => $str2
						);
						
					}

				}else{
					$rtn["update"][] = array(							
						"id"     => "formMsj",
						"action" => "html",
						"value"  => $rtn["msj"]
					);
	
				}
			}else{

				$rtn["update"][] = array(
					"id"     => "formMsj",
					"action" => "html",
					"value"  => $this->parents->interfaz->msj("warning","Se encontrarón campo(s) vacio(s).")
				);

			}

			return $rtn;
		}

		function remove_accent($str) 
		{ 
		  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
		  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
		  return str_replace($a, $b, $str); 
		} 

		function post_slug($str,$separate='-') 
		{ 
		  return strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),array('',$separate,''),$this->remove_accent($str))); 
		}

		function array_to_object($array,$nivel='n'){

			$object = (object) array();
			
			//un nivel array = ['name' => 'cp','age' => '2','new' => 'no'];
			if($nivel == 'uno')
				$object = (object) $array;
			//dos niveles
			if($nivel == 'dos'){
				$object = new stdClass();
				foreach ($array as $key => $value)
				{
				    $object->$key = $value;
				}
			}
			//n niveles
			if($nivel == 'n')
			$object = json_decode(json_encode($array), FALSE);

			return $object;
		}

	}
	// paralel
?>