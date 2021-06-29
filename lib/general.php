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

		function rtn_id($uniqid){
			if($this->existe_registro('pdfs',"uniqid='".$uniqid."'")){
				$id = $this->rtn_consulta_unica('id','pdfs',"uniqid='".$uniqid."'");
				return $id;
			}
			return 0;
		}

		function rtn_id_usuario($uniqid){
			if($this->existe_registro('pdfs',"uniqid='".$uniqid."'")){
				$id = $this->rtn_consulta_unica('idUsuario','pdfs',"uniqid='".$uniqid."'");
				return $id;
			}
			return 0;	
		}

		function rtn_ids_alumnos_respuestas($id_pdf){

			$array = array();

			$rc = $this->parents->gn->rtn_consulta('idAlumno','respuestas','idPdf='.$id_pdf);

			foreach($rc as $obj){ 
				$array[] = $obj->idAlumno; 
			}

			// unimos datos repetidos
			$rtn = array_unique($array);

			return $rtn;
		}

		function rtn_uniqid($id){
			if($this->existe_registro('pdfs','id='.$id)){
				$uniqid = $this->rtn_consulta_unica('uniqid','pdfs','id='.$id);
				return $uniqid;
			}
			return null;
		}

		function rtn_num_alumnos(){
			$rc = $this->rtn_consulta('COUNT(*) AS numReg','alumnos','idUsuario='.$this->idUsuario);
			return $rc[0]->numReg;
		}

		function rtn_num_pdfs(){
			$rc = $this->rtn_consulta('COUNT(*) AS numReg','pdfs','idUsuario='.$this->idUsuario);
			return $rc[0]->numReg;
		}

		function rtn_num_preguntas($id_pdf){
			$rc = $this->rtn_consulta('COUNT(*) AS numReg','preguntas','idPdf='.$id_pdf);
			return $rc[0]->numReg;
		}

		function rtn_nombre_publico($id_usuario){
			$nombre_publico = $this->rtn_consulta_unica('nombre_publico','usuarios','id='.$id_usuario);
			return $nombre_publico;
		}

		function rtn_nombre_alumno($id_alumno){
			$rc = $this->rtn_consulta('nombres,apellidos','alumnos','id='.$id_alumno);
			return $rc[0]->nombres.' '.$rc[0]->apellidos;
		}

		function rtn_nombre_arch($nombre_arch){
			$nombre_arch = explode('.',$nombre_arch);
			return $nombre_arch[0];
		}

		function rtn_titulo_lectura($uniqid){
			$rc = $this->rtn_consulta('titulo','pdfs',"uniqid='".$uniqid."'");
			return $rc[0]->titulo;
		}

		function rtn_descripcion_lectura($uniqid){
			$rc = $this->rtn_consulta('descripcion','pdfs',"uniqid='".$uniqid."'");
			return $rc[0]->descripcion;
		}

		function rtn_src_lectura($uniqid){
			$rc = $this->rtn_consulta('nombre','pdfs',"uniqid='".$uniqid."'");
			return URL.'/data/pdfs/'.$uniqid.'/'.$rc[0]->nombre.'.pdf';
		}

		function rtn_fecha_reg_entrega($id_pdf,$id_alumno){
			$registro = $this->rtn_consulta_unica('registro','entregas','idPdf='.$id_pdf.' AND idAlumno='.$id_alumno);
			return $registro;
		}

		function rtn_fecha($fecha,$rtn='es'){
			// el formato de $fecha es  english
			$fecha = explode(' ',$fecha);
			$f     = explode('-',$fecha[0]);
			$anio  = $f[0];
			$mes   = $f[1];
			$dia   = $f[2];

			if($rtn == 'es'){
				return $dia."/".$mes."/".$anio;
			}
			elseif($rtn == 'en'){
				return $anio."-".$mes."-".$dia;
			}

			return '';
		}

		function rtn_hora($fecha,$rtn='24'){

		}

		function existe_registro($tabla,$condicion){

			$query = "SELECT COUNT(*) AS cant FROM ".$tabla." WHERE ".$condicion.";";
			if($this->parents->sql->consulta($query)){

				$resultado = $this->parents->sql->resultado;
				foreach($resultado as $obj){
					if($obj->cant > 0){
						return true;
					}else{
						return false;
					}
				}

				return false;
			}
			return false;
		}

		function existe_uniqid($uniqid){
			if($this->existe_registro('pdfs',"uniqid='".$uniqid."'"))
				return true;
			return false;
		}

		function verificar_respuestas_vacias($cad=[]){

			foreach($cad as $val){
				if(!$this->verifica_valor($val)){
					return true;
				}
			}
			return false;
		}

		function verificar_respuestas_total_vacias($id_pdf,$cad=[]){

			$cont = 0;

			$preg = $this->rtn_consulta('*','preguntas','idPdf='.$id_pdf);

			foreach($cad as $val){
				if(!$this->verifica_valor($val)){
					$cont++;
				}
			}

			if(count($preg) == $cont){
				return true;
			}
			return false;
		}

		function verificar_envio_respuestas($id_pdf,$id_alumno){

			if($this->existe_registro('respuestas','idPdf='.$id_pdf.' AND idAlumno='.$id_alumno)){
				return true;
			}
			return false;
		}

		function verificar_pdf_resuelto($id_pdf){
			
			if($this->existe_registro('respuestas','idPdf='.$id_pdf)){
				return true;
			}
			return false;
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

		function encriptar_id($uniqid,$id=0){

			//La encriptació es muy simple 
			//unimos los dos últimos digitos del id con los cuatro últimos digitos de uniqid
			//Ojo el id tiene que ser secuencial y único 1,2,3,...

			$id = ($id != 0)? $id :$this->rtn_consulta_unica('id','pdfs',"uniqid='".$uniqid."'");
			//$id = $this->rtn_consulta_unica('id','pdfs',"uniqid='".$uniqid."'");

			$id_str = (strlen($id) == 1)? '0'.$id : $id;

			return substr($id_str,-2).substr($uniqid,-4);
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

		function guardar_pdf($datos,$FILES){

			//antes:
			//-indicar extensiones permitidas
			//-indicar nombre
			//-indicar destino

			$rtn = array();

			//tamaño del archivo
			if(sizeof($FILES)>0 && $FILES["archivo"]["size"]>0){

				//datos file			
				$datos["file_nombre"] = $FILES["archivo"]["name"];
				$datos["file_tmp"]    = $FILES["archivo"]["tmp_name"];

				//tipo de archivo
				$extPermitidas      = implode(",",$datos["extPermitidas"]);			
				$extraerTipo        = explode(".",$datos["file_nombre"]);
				$datos["extension"] = end($extraerTipo);
				$extCorrecta        = in_array($datos["extension"],$datos["extPermitidas"]);

				if($extCorrecta){

					// crear carpeta vacía
					$this->parents->gn->crear_carpeta_vacia($datos['destino']);

					// guardar archivo
					$rtn = $this->guardar_arch($datos,$FILES);
				}else{				

					$rtn=array(
						"success" => false,
						"msj"     => "Sólo se acepta los siguientes formatos: ".$extPermitidas,
					);
				}

			}else{
				$rtn=array(
					"success" => false,
					"msj"     => "Elija un archivo.",
				);
			}

			return $rtn;

		}

		function guardar_arch($datos,$FILES){

			$nombreArch   = $datos["nombreArch"].".".$datos["extension"];
			$destino      = $datos["destino"]."/".$nombreArch;

			if($FILES["archivo"]["error"] <= 0){

				if(!file_exists($destino)){

					//mover archivo
					move_uploaded_file($datos["file_tmp"],$destino);

					$rtn=array(
						"success" => true,
						"msj"     => "Exito" 
					);									

				}else{
					$rtn=array(
						"success" => false,
						"msj"     => "Existe el archivo."
					);
				}
				
			}else{
				$rtn=array(
					"success" => false,
					"msj"     => "ERROR: Archivo corrupto."
				);
			}
			return $rtn;

		}

		function vaciar_directorio($fuente){

			//fuente   : (URI) .../link/link

			$odir = opendir($fuente);

			while($archivo = readdir($odir)){
				//hace un recorrido por todo los nombres de archivo
				if($archivo!="." && $archivo!=".."){
					unlink($fuente.'/'.$archivo);	
				}
			}

		}

		function eliminar_arch_directorio($fuente,$nombArch){

			//fuente   : (URI)
			//nombArch : img_123 (sin formato o extensión)

			$odir = opendir($fuente);

			while($archivo = readdir($odir)){
				//hace un recorrido por todo los nombres de archivo
				if($archivo!="." && $archivo!=".."){

					$valor = explode(".",$archivo);

					if($valor[0] == $nombArch){
						unlink($fuente.'/'.$archivo);
					}
				}
			}

		}

		function crear_carpeta_vacia($uri){
			//Si no existe carpeta lo crea
			if(!file_exists($uri)){
				mkdir($uri,0777,true);
				return true;
			}
			return false;
		}

		function validar($input,$datos){

			for($i=0;$i<count($rtnArray);$i++){
				if($rtnArray[$i]['success']!=true){
					$rpta = false;
				}
			}	
			
			$msj    = "";
			$patron = "";

			if($valor[2] == 'time_free')
			{	//00:00,00:01,...,LIBRE
				$patron = "/^(([01]?[0-9]|2[0-3]):[0-5][0-9]|LIBRE)$/";
			}
			elseif($valor[2] == 'time_ampm')
			{
				//7:59,07:59,14:59 24 horas
				$patron = "/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/";
			}
			elseif($valor[2] == 'date')
			{
				//2020-06-17
				$patron = "/^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))$/";
			}
			elseif($valor[2] == 'checkbox')
			{
				//...
			}

			$pm = preg_match($patron,$valor[0]);

			if($pm!=true){
				$msj = $valor[1];
			}	

			$rtn=array(
				"success" => (bool)$pm,
				"msj"     => $msj
			);
			return $rtn;
		}

	}
	// paralel
?>