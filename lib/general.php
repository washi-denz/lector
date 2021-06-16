<?php
	class General{

		var $parents;
		var $min_num_examen = 2; //config

		function __construct(&$parents){
			$this->parents   = $parents;
			$this->idUsuario = $this->parents->session->get("idUser");
		}

		//-------------------------------------------------------------//
		//                             generalidades
		//-------------------------------------------------------------//

		public function hsc($array){
			if(is_array($array)){
				return htmlspecialchars(json_encode($array));	
			}
			return null;
		}

		function bool($bool){
			return $bool === 'true'? true: false;
		}
		
		function agregar_and($cad,$atrib){
			$dat = '';
			foreach($cad as $ind=>$val){
				$dat .= $atrib." LIKE '%".$cad[$ind]."%' AND ";
			}
			$dat = substr($dat,0,-5);

			return $dat;
		}
		
		function agregar_or($cad,$atrib){
			$dat='';
			foreach($cad as $ind=>$val){
				$dat .= $atrib." LIKE '%".$cad[$ind]."%' OR ";
			}
			$dat=substr($dat,0,-4);

			return $dat;
		}

		function agregar_or2($cad,$atrib){
			$dat='';
			foreach($cad as $ind=>$val){
				$dat .= $atrib."='".$cad[$ind]."' OR ";
			}
			$dat=substr($dat,0,-4);

			return $dat;
		}
		
		function agregar_negacion($cad,$atrib){
			$dat='';
			foreach($cad as $ind=>$val){
				$dat .= $atrib." <> '".$cad[$ind]."' AND ";
			}
			$dat=substr($dat,0,-4);

			return $dat;
		}

		function agregar_concat_ws($cad=array(),$atrib='',$logic='OR'){

			$dat='';

			foreach($cad as $val){
				$dat .= " CONCAT_WS(' ',".$atrib.") LIKE '%".$val."%' ".$logic." ";
			}

			$num = ($logic == 'AND')?4:3;//AND o OR

			$dat = substr($dat,0,-$num);

			return "(".$dat.")";
		}

		function modifica_o_agrega_valoracion($idUsuario=0,$valoracion,$idex=null){

			$tmp = array();

			$json = $this->rtn_json_string('valorar','examen',"idex='".$idex."'");

			if(is_object($json)){

				foreach($json as $ind=>$val){
					$tmp[$ind] = $val;
				}

				//Modifica o agrega
				$tmp[$idUsuario] = $valoracion;

				//decodifica
				$json = json_encode($tmp);

				//Guardar 
				$this->parents->sql->modificar('examen',array('valorar'=>$json),array("idex"=>$idex));

				return true;
			}

			return false;

		}

		function actualizar_notificar($tipo=''){

			if($tipo == 'notificar_adm_public')
			{
				$query = "UPDATE usuario SET notificar_adm_public = CURRENT_TIMESTAMP WHERE idUsuario=".$this->idUsuario;

				if($this->parents->sql->consulta($query)){
					return true;
				}
				return false;

			}
			if($tipo == '...'){

			}
			return true;

		}

		function actualizar_num_vistas($idex=null){

			$idExamen = $this->rtn_id($idex);

			if($this->existe_registro('redes','idExamen='.$idExamen)){
				if($this->parents->sql->consulta("UPDATE redes SET vista = vista + 1 WHERE idExamen=".$idExamen))
					return true;				
			}
			return false;
		}

		function actualizar_num_resueltas($idex=null){

			$idExamen = $this->rtn_id($idex);

			if($this->existe_registro('redes','idExamen='.$idExamen)){
				if($this->parents->sql->consulta("UPDATE redes SET resuelta = resuelta + 1 WHERE idExamen=".$idExamen))
					return true;				
			}
			return false;
		}

		function actualizar_num_valorar_public($idex=null){

			$idExamen = $this->rtn_id($idex);

			if($this->existe_registro('puntuacion','idExamen='.$idExamen)){
				if($this->parents->sql->consulta("UPDATE puntuacion SET valorar_public = valorar_public + 1 WHERE idExamen=".$idExamen))
					return true;				
			}
			return false;
		}

		function actualizar_reg($atrib,$tabla,$condicion=''){
			//...
		}


		//-------------------------------------------------------------//
		//                             importante
		//-------------------------------------------------------------//

		public function sesion_automatica($bool=false){

			$array_session= array("idUser"=>1,"public_name"=>'William Wallace',"type_user"=>'USUARIO');//config
			//$array_session= array("idUser"=>6,"public_name"=>'Elon',"type_user"=>'DOCENTE');//config

			if(is_array($array_session) && $bool == true){
				$this->parents->session->put_login($array_session);
				//$this->redireccion($url);
			}else{
				$this->parents->session->remove();
			}
		}

		public function validar_usuario($idusuario){
			// Validar usuario para saber si lo que envia 
			// llega a su destino correctamente.
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

		function idex_publica($idex){

			if($this->verifica_valor($idex)){
				$query="SELECT e.idex FROM examen e INNER JOIN examen_config ec ON e.idExamen=ec.idExamen WHERE e.idex='".$idex."' AND ec.publicar='SI' AND ec.eliminar='NO'";
				if($this->parents->sql->consulta($query)){
					if(count($this->parents->sql->resultado) > 0){
						return true;
					}else{
						return false;
					}	
				}
				return false;
			}else{
				return false;
			}

		}

		function rtn_titulo_examen($idex){
			return $this->rtn_consulta_unica('titulo','examen',"idex='".$idex."'");
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

		function rtn_fa($name='',$cad = array()){
			return call_user_func_array(array($this,$name),$cad);
		}

		function rtn_idex($id){
			if($this->existe_registro("examen_config","idExamen=".$id." AND eliminar='NO'")){
				$rc = $this->rtn_consulta("idex","examen","idExamen=".$id);
				return $rc[0]->idex;
			}else{
				return 0;
			}
		}

		function rtn_id($idex){
			$rc = $this->rtn_consulta("idExamen","examen","idex='".$idex."'");
			if($this->existe_registro("examen_config","idExamen=".$rc[0]->idExamen." AND eliminar='NO'")){
				return $rc[0]->idExamen;
			}else{
				return 0;
			}
		}

		function rtn_estilo_exam($idex){
			if($this->existe_registro("examen","idex='".$idex."'")){
				$rc = $this->rtn_consulta("estilo","examen","idex='".$idex."'");
				return $rc[0]->estilo;
			}else{
				return null;
			}
		}

		function  rtn_prop($tabla,$props='prop1',$cond=''){

			if($this->existe_registro($table,$cond)){

				$rc = $this->rtn_consulta($props,$tabla,$cond);
				$rc = (array) $rc[0];

				if(count($rc)>1){
					return $rc;
				}else{
					return $rc["prop1"];
				}

			}else{
				return null;
			}

		}

		function rtn_prop_estilo_exam($idExamen,$props='prop1'){

			//return array
			
			if($this->existe_registro("examen","idExamen=".$idExamen)){

				$rc = $this->rtn_consulta($props,"estilo_examen","idExamen=".$idExamen);
				$rc = (array) $rc[0];
				return $rc;
			}else{
				return null;
			}	
		}

		function rtn_idex_publica($id){
			if($this->existe_registro("examen_config","idExamen=".$id." AND publicar='SI' AND eliminar='NO'")){
				$rc = $this->rtn_consulta("idex","examen","idExamen=".$id);
				return $rc[0]->idex;
			}else{
				return 0;
			}
		}

		function rtn_id_publica($idex){
			$rc = $this->rtn_consulta("idExamen","examen","idex='".$idex."'");
			if($this->existe_registro("examen_config","idExamen=".$rc[0]->idExamen." AND publicar='SI' AND eliminar='NO'")){
				return $rc[0]->idExamen;
			}else{
				return 0;
			}
		}

		function rtn_id_admision($idExamen){

			$idUsuario = $this->parents->session->get("idUser");

			if($this->existe_registro("examen_config","idExamen=".$idExamen." AND publicar='SI' AND eliminar='NO'")){
				if($this->existe_registro("admision","idExamen=".$idExamen." AND idUsuario=".$idUsuario)){
					$rc = $this->rtn_consulta("idAdmision","admision","idExamen=".$idExamen." AND idUsuario=".$idUsuario);
					return $rc[0]->idAdmision;
				}
				return 0;
			}else{
				return 0;
			}
		}

		function rtn_idexam_admision($idad){
			if($this->existe_registro("admision","idAdmision=".$idad)){
				$rc = $this->rtn_consulta("idExamen","admision","idAdmision=".$idad);
				return $rc[0]->idExamen;
			}else{
				return 0;
			}
		}

		function rtn_idusuario_admision($idad){
			if($this->existe_registro("admision","idAdmision=".$idad)){
				return $this->rtn_consulta_unica("idUsuario","admision","idAdmision=".$idad);
			}else{
				return 0;
			}
		}

		function rtn_num_preguntas($id=null){
			//id : idExamen o idex
			$idExamen = (is_numeric($id))? $id: $this->rtn_id($id);

			if($this->existe_registro("examen","idExamen=".$idExamen)){
				$rc   = $this->rtn_consulta("COUNT(*) AS cont","pregunta","idExamen =".$idExamen);
				return $rc[0]->cont;
			}
			return 0;
		}

		function rtn_num_exam_total($idUsuario=0){
			
			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$query = "SELECT COUNT(*) AS cont FROM examen e INNER JOIN examen_config ec ON e.idExamen=ec.idExamen WHERE e.idUsuario=".$idUsuario.";";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return $resultado[0]->cont;
			}
			return 0;
		}

		function rtn_num_exam_creadas($idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$query = "SELECT COUNT(*) AS cont FROM examen e INNER JOIN examen_config ec ON e.idExamen=ec.idExamen WHERE ec.publicar='NO' AND ec.eliminar='NO' AND e.idUsuario=".$idUsuario.";";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return $resultado[0]->cont;
			}
			return 0;
		}

		function rtn_num_exam_publica($idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$query = "SELECT COUNT(*) AS cont FROM examen e INNER JOIN examen_config ec ON e.idExamen=ec.idExamen WHERE ec.publicar='SI' AND ec.eliminar='NO' AND e.idUsuario=".$idUsuario.";";
			
			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return $resultado[0]->cont;
			}
			return 0;
		}

		function rtn_num_exam_admision($idExamen=0){

			//retorna el número total o parte de los examenes de admisiones de dicho usuario

			if($idExamen != 0){
				if($this->existe_registro("examen_config","idExamen=".$idExamen." AND publicar='SI' AND eliminar='NO'")){
					$rc = $this->rtn_consulta("COUNT(*) AS cont","admision","idExamen=".$idExamen." AND idUsuario=".$this->parents->session->get("idUser"));
					return $rc[0]->cont;
				}else{
					return 0;
				}
			}else{

				$query = "
					SELECT COUNT(*) AS cont FROM examen e 
						INNER JOIN  admision ad ON e.idExamen = ad.idExamen
						INNER JOIN  usuario  u ON u.idUsuario = ad.idUsuario 
					WHERE e.idUsuario=1 AND ad.idUsuario <> 1 ORDER BY ad.registro DESC;
				";

				$this->parents->sql->consulta($query);
				$resultado = $this->parents->sql->resultado;

				return $resultado[0]->cont;
			}
			return 0;
		}

		function rtn_num_exam_admision_public($idExamen){

			//Retorna el número total del test resueltas por el público.
			//Y también, no considera las resueltas por el autor

			if($this->existe_registro("examen_config","idExamen=".$idExamen." AND publicar='SI' AND eliminar='NO'")){
				$rc = $this->rtn_consulta("COUNT(*) AS cont","admision","idExamen=".$idExamen." AND idUsuario <> ".$this->parents->session->get("idUser"));
				return $rc[0]->cont;
			}else{
				return 0;
			}

		}
		
		function rtn_num_intentos($idExamen){
			if($this->existe_registro("examen_config","idExamen=".$idExamen." AND publicar='SI' AND eliminar='NO'")){
				$rc = $this->rtn_consulta("num_intentos","examen","idExamen=".$idExamen);
				return $rc[0]->num_intentos;
			}else{
				return 0;
			}
		}

		function rtn_num_vistas($idExamen=0){
			if($this->existe_registro('redes','idExamen='.$idExamen)){
				return $this->rtn_consulta_unica('vista','redes','idExamen='.$idExamen);
			}
			return 0;
		}
		function rtn_num_resueltas($idExamen=0){
			if($this->existe_registro('redes','idExamen='.$idExamen)){
				return $this->rtn_consulta_unica('resuelta','redes','idExamen='.$idExamen);
			}
			return 0;
		}	

		function rtn_num_valorar($idExamen=0){

			$cont = 0;

			if($this->existe_registro('redes','idExamen='.$idExamen)){
				$rc   = $this->rtn_consulta_unica('valorar','redes','idExamen='.$idExamen);

				$rc   = json_decode($rc);

				foreach($rc as $obj){
					$cont++;
				}
				return $cont;
			}
			return 0;
		}

		function rtn_num_valorar_public($idExamen=0){
			if($this->existe_registro('redes','idExamen='.$idExamen)){
				return $this->rtn_consulta_unica('valorar_public','redes','idExamen='.$idExamen);
			}
			return 0;
		}

		function rtn_num_comentarios($idExamen=0){
			if($this->existe_registro('redes','idExamen='.$idExamen)){
				return $this->rtn_consulta_unica('comentar','redes','idExamen='.$idExamen);
			}
			return 0;
		}

		function rtn_post_exam($idex,$idad){

			$rc          = $this->rtn_consulta("titulo","examen","idex='".$idex."'");
			$titulo_slug = $this->post_slug($rc[0]->titulo);

			return $titulo_slug."/".$idex."-".$idad;
		}

		function rtn_idExamen($idad){
			if($this->existe_registro("admision","idAdmision=".$idad)){
				$rc = $this->rtn_consulta("idExamen","admision","idAdmision=".$idad);
				return $rc[0]->idExamen;
			}
			return 0;
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

		function rtn_enum($table,$field){
			$rtn = array();
			$query="SHOW COLUMNS FROM ".$table." WHERE  FIELD = '".$field."';";
			if($this->parents->sql->consulta($query)){
				foreach($this->parents->sql->resultado as $obj){

					preg_match("/^enum\(\'(.*)\'\)$/",$obj->Type,$matches);
					$rtn = explode("','",$matches[1]);
				}
			}

			return $rtn;//array
		}

		function rtn_estado_examen($idex){
			
			$rc = $this->rtn_consulta("estado","examen","idex='".$idex."'");
			if(count($rc) > 0){
				return $rc[0]->estado;
			}
			return null;

		}

		function rtn_estado_admision($idad,$idUsuario=0){

			if($idUsuario != 0){

				if($this->parents->gn->existe_registro("admision","idAdmision=".$idad." AND idUsuario=".$idUsuario)){
					$rc = $this->parents->gn->rtn_consulta("estado_admision","admision","idAdmision=".$idad." AND idUsuario=".$idUsuario);
					return $rc[0]->estado_admision;
				}

			}else{
				return $this->rtn_consulta_unica('estado_admision','admision','idAdmision='.$idad);
			}

			return null;

		}

		function rtn_estado_admision_parcial($idExamen,$idUsuario=0){//Mejorarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
			
			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			if($this->parents->gn->existe_registro("admision","idExamen=".$idExamen." AND idUsuario=".$idUsuario)){
				$rc = $this->parents->gn->rtn_consulta("estado_admision","admision","idExamen=".$idExamen." AND idUsuario=".$idUsuario);
				return $rc[0]->estado_admision;
			}
			return null;

		}

		function rtn_estado_compartir($cod){
			$rc = $this->rtn_consulta("estado","compartir","cod='".$cod."'");
			if(count($rc) > 0){
				return $rc[0]->estado;
			}
			return null;
		}

		function rtn_num_preguntas_resueltas($idad){
			$rtn = array();

			$rc = $this->rtn_consulta("idPregunta","respuesta","idAdmision=".$idad);

			foreach($rc as $obj){
				$rtn[] = $obj->idPregunta;
			}

			$rtn = array_unique($rtn);

			return count($rtn);
		}

		function rtn_num_alternativas($idPregunta){
			$rc   = $this->rtn_consulta("*","alternativa","idPregunta =".$idPregunta);
			$cont = count($rc);

			if($cont > 0){
				return $cont;
			}
			return 0;
		}

		function rtn_num_total_exam_resuelta_cae($idUsuario=0){//MEJORAR
			//cae : CONCLUIDAD ACTIVA EXTEMPORÁNEA

			$rc  = $this->rtn_consulta("COUNT(*) AS cont","admision","estado_admision='CONCLUIDA' AND (estado_examen='ACTIVA' OR estado_examen='EXTEMPORANEA') AND idUsuario =".$idUsuario);

			return $rc[0]->cont;
		}

		function rtn_num_total_exam_resuelta_ca($idUsuario=0){//MEJORAR
			//ca : CONCLUIDAD ACTIVA
			$rc  = $this->rtn_consulta("COUNT(*) AS cont","admision","estado_admision='CONCLUIDA' AND estado_examen='ACTIVA' AND idUsuario =".$idUsuario);
			return $rc[0]->cont;
		}

		function rtn_num_total_exam_resuelta_ce($idUsuario=0){//MEJORAR
			//ca : CONCLUIDAD EXTEMPORANEA
			$rc  = $this->rtn_consulta("COUNT(*) AS cont","admision","estado_admision='CONCLUIDA' AND estado_examen='EXTEMPORANEA' AND idUsuario =".$idUsuario);
			return $rc[0]->cont;
		}

		function rtn_num_toal_exam_resuelta_concluida($idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;
			
			$query = "
				SELECT COUNT(*) AS cont FROM admision ad 
					INNER JOIN examen e  ON ad.idExamen  = e.idExamen
					INNER JOIN usuario u ON ad.idUsuario = u.idUsuario 
				WHERE (ad.idUsuario=".$idUsuario." AND e.idUsuario <> ".$idUsuario.") AND ad.estado_admision='CONCLUIDA'
			";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return $resultado[0]->cont;
			}
			return 0;
		}

		function rtn_num_total_exam_resuelta_public($idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;
			
			$query = "
				SELECT COUNT(*) AS cont FROM examen e 
					INNER JOIN  admision ad ON e.idExamen  = ad.idExamen
					INNER JOIN  usuario  u  ON u.idUsuario = ad.idUsuario 
				WHERE e.idUsuario=".$idUsuario." AND ad.idUsuario <> ".$idUsuario."
			";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return $resultado[0]->cont;
			}
			return 0;
		}

		function rtn_num_total_seguidos($idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$query = "
				SELECT COUNT(*) AS cont FROM seguir s 
					INNER JOIN  usuario u ON s.idUsuario_2 = u.idUsuario 
				WHERE s.idUsuario_1 <> s.idUsuario_2 AND s.idUsuario_1=".$idUsuario.";
			";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return $resultado[0]->cont;
			}
			return 0;
		}

		function rtn_num_total_seguidores($idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$query = "
				SELECT COUNT(*) AS cont FROM seguir s 
					INNER JOIN  usuario u ON s.idUsuario_1 = u.idUsuario 
				WHERE s.idUsuario_1 <> s.idUsuario_2 AND s.idUsuario_2=".$idUsuario.";
			";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return $resultado[0]->cont;
			}
			return 0;
		}

		function  rtn_estilo_usuario($idUsuario){

			$rc = $this->rtn_consulta("*","estilo_usuario","idUsuario = ".$idUsuario);

			if(count($rc)>0){
				return (array) $rc[0];//conversion array
			}
			return $this->parents->interfaz->str_default('estilo-usuario');

		}

		function rtn_estilo_lista($idExamen){
			$rc = $this->rtn_consulta("prop1","estilo_lista","idExamen = ".$idExamen);

			if(count($rc)>0){
				return $rc[0]->prop1;
			}
			return $this->rtn_estilo_lista_defecto();
		}

		function rtn_estilo_lista_defecto(){
			return 'img-color-1';
		}

		function rtn_estilo_lista_rgb($idex){

			$idExamen         = $this->rtn_id($idex);
			$estilo_lista_rgb = $this->parents->interfaz->str_default('estilo-lista');

			if($this->existe_registro('estilo_lista','idExamen='.$idExamen)){
				$estilo_lista = $this->rtn_consulta_unica('prop1','estilo_lista','idExamen='.$idExamen);
				return $estilo_lista_rgb[$estilo_lista];
			}

			$estilo_lista = $this->rtn_estilo_lista_defecto();
			return $estilo_lista_rgb[$estilo_lista];

		}

		function rtn_estilo($attr,$idUsuario){
			$rc = $this->rtn_consulta($attr,"estilo","idUsuario=".$idUsuario);
			if(count($rc)>0){
				return $rc[0]->$attr;
			}
			return null;
		}

		function rtn_exam_titulo($idExamen=0){
			$rc = $this->rtn_consulta("titulo","examen","idExamen=".$idExamen);
			if(count($rc)>0){
				return $rc[0]->titulo;
			}
			return null;
		}

		function rtn_exam_nombre_publico($idExamen=0){

			$query ="SELECT u.nombre_publico FROM examen e INNER JOIN usuario u ON e.idUsuario=u.idUsuario WHERE e.idExamen=".$idExamen.";";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				if(count($resultado)>0){
					return $resultado[0]->nombre_publico;
				}
				return null;
			}
			return null;
		}

		function rtn_exam_descripcion($idExamen=0){

			$rcu = $this->rtn_consulta_unica('descripcion','examen','idExamen='.$idExamen);

			if($this->verifica_valor($rcu)){
				return $rcu;
			}
			return null;

		}

		function rtn_tipo_eleccion($idPregunta){

			//ES elección simple
			//EM elección múltiple

			//Todo esto será dependiendo al número de respuestas correctas
			//ejemplo si hay una respuesta será ES
			//si hay más de uno será EM

			$query = "SELECT respuesta FROM alternativa WHERE respuesta ='SI' AND idPregunta=".$idPregunta.";";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				
				if(count($resultado) == 1){
					return 'ES';
				}else{
					return 'EM';
				}
			}

		}

		function rtn_max_id($id,$tabla){
			$query = "SELECT MAX(".$id.") AS max_id FROM ".$tabla.";";
			if($this->parents->sql->consulta($query)){

				$resultado = $this->parents->sql->resultado;
				return ($resultado[0]->max_id + 1);

			}
			return 0;
		}

		function rtn_puntuacion($idad,$idExamen,$idUsuario=0){
			
			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->parents->session->get("idUser");

			$rtnArray = array();
			$rc       = $this->rtn_consulta("nota_base","examen","idExamen=".$idExamen);			

			$puntuacion       = 0;
			$puntuacion_total = $rc[0]->nota_base;
			$num_preguntas    = $this->rtn_num_preguntas($idExamen);
			$acertada         = 0;
			$fallida          = 0;

			$puntuacion_pregunta    = $puntuacion_total/$num_preguntas;

			//Preguntas
			$query1 = "SELECT idPregunta FROM pregunta WHERE idExamen=".$idExamen.";";

			$this->parents->sql->consulta($query1);

			foreach($this->parents->sql->resultado as $obj1){

				$array1   = array();
				$array2   = array();

				//Respuesta correctas
				$query2 = "SELECT alt.idAlternativa FROM pregunta p INNER JOIN alternativa alt ON p.idPregunta=alt.idPregunta WHERE (alt.respuesta='SI' AND p.idExamen=".$idExamen.") AND p.idPregunta=".$obj1->idPregunta.";";
			
				$this->parents->sql->consulta($query2);

				foreach($this->parents->sql->resultado as $obj2){
					$array1[] = $obj2->idAlternativa;
				}

				//Respuesta  del usuario
				$query3 = "SELECT idAlternativa FROM respuesta WHERE (idAdmision=".$idad."  AND idUsuario=".$idUsuario.") AND  idPregunta=".$obj1->idPregunta.";";
			
				$this->parents->sql->consulta($query3);

				foreach($this->parents->sql->resultado as $obj3){
					$array2[] = $obj3->idAlternativa;
				}

				
				if($this->comparar_valores_array($array1,$array2)){

					$puntuacion += $puntuacion_pregunta;
					$acertada   += 1;

				}else{

					$fallida    += 1;

				}

			}

			$rtnArray = array(
				"puntuacion"          => $puntuacion,
				"puntuacion_total"    => $puntuacion_total,
				"puntuacion_pregunta" => $puntuacion_pregunta,
				"acertada"            => $acertada,
				"fallida"             => $fallida,
				"num_preguntas"       => $num_preguntas
			);

			return $rtnArray;
		}

		function rtn_puntuacion_full($idad,$idExamen,$idUsuario=0){
						
			$rtn = array();	

			$num_preguntas    = $this->rtn_num_preguntas($idExamen);			
			$puntuacion       = 0;
			$nota_base        = $this->rtn_consulta_unica("nota_base","examen","idExamen=".$idExamen);
			$puntuacion_total = ($nota_base != 0)? $nota_base:$num_preguntas;			
			$acertada         = 0;
			$fallida          = 0;

			$resuelta         = 0;
			$no_resuelta      = 0;
			$cont_resuelta    = 0;

			$puntuacion_pregunta    = $puntuacion_total/$num_preguntas;

			//Preguntas
			$query1 = "SELECT idPregunta FROM pregunta WHERE idExamen=".$idExamen.";";

			$this->parents->sql->consulta($query1);

			foreach($this->parents->sql->resultado as $obj1){

				$array1   = array();
				$array2   = array();

				//Respuesta correctas
				$query2 = "SELECT alt.idAlternativa FROM pregunta p INNER JOIN alternativa alt ON p.idPregunta=alt.idPregunta WHERE (alt.respuesta='SI' AND p.idExamen=".$idExamen.") AND p.idPregunta=".$obj1->idPregunta.";";
			
				$this->parents->sql->consulta($query2);

				foreach($this->parents->sql->resultado as $obj2){
					$array1[] = $obj2->idAlternativa;
				}

				//Respuesta  del usuario
				$queryA = "SELECT idAlternativa FROM respuesta WHERE (idAdmision=".$idad."  AND idUsuario=".$idUsuario.") AND  idPregunta=".$obj1->idPregunta.";";
				$queryB = "SELECT idAlternativa FROM respuesta WHERE (idAdmision=".$idad."  AND idPregunta=".$obj1->idPregunta.");";

				$query3 = ($this->parents->session->check_login() && $idUsuario != 0)? $queryA:$queryB;
			
				$this->parents->sql->consulta($query3);

				foreach($this->parents->sql->resultado as $obj3){
					$array2[] = $obj3->idAlternativa;
					$cont_resuelta++;
				}

				//Puntuación,acertadas y fallidas
				if($this->comparar_valores_array($array1,$array2)){
					$puntuacion += $puntuacion_pregunta;
					$acertada   += 1;
				}else{
					if($cont_resuelta>0)
						$fallida    += 1;
				}

				//Resueltas y no resueltas
				if($cont_resuelta > 0){
					$resuelta    += 1;
				}else{
					$no_resuelta += 1;
				}

				$cont_resuelta = 0;

			}

			$rtn = array(
				"num_preguntas"       => $num_preguntas,
				"puntuacion"          => $puntuacion,
				"puntuacion_total"    => $puntuacion_total,
				"puntuacion_pregunta" => $puntuacion_pregunta,
				"acertada"            => $acertada,
				"fallida"             => $fallida,
				"resuelta"            => $resuelta,
				"no_resuelta"         => $no_resuelta
			);

			return $rtn;
		}

		function rtn_puntuacion_pregunta($idExamen){

			//El examen  nota base por tal razon 
			//se tomará el número total de preguntas como puntuación total
			//por lo tanto la puntuacion será la unidad

			$puntuacion_pregunta = 0;

			$rc = $this->rtn_consulta("nota_base","examen","idExamen=".$idExamen);			

			$puntuacion_total = $rc[0]->nota_base;
			$num_preguntas    = $this->rtn_num_preguntas($idExamen);

			if($puntuacion_total > 0){
				$puntuacion_pregunta    = $puntuacion_total/$num_preguntas;
			}else{
				$puntuacion_pregunta    = $num_preguntas/$num_preguntas;
			}

			return $puntuacion_pregunta;

		}

		function rtn_puntuacion_total($idUsuario=0){
			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;
			//falta desarrollar
		}

		function rtn_nombre_usuario($idUsuario){

			$rc = $this->rtn_consulta("nombres,apellidos","usuario","idUsuario=".$idUsuario);
			
			if($this->verifica_valor($rc[0]->nombres) && $this->verifica_valor($rc[0]->apellidos)){
				$rc = $this->rtn_consulta("CONCAT(UPPER(LEFT(nombres, 1)),LOWER(SUBSTRING(nombres, 2)),' ',UPPER(LEFT(apellidos,1)),LOWER(SUBSTRING(apellidos,2))) AS nombreApellido","usuario","idUsuario=".$idUsuario);
			}else{
				$rc = $this->rtn_consulta("nombre_publico AS nombreApellido","usuario","idUsuario=".$idUsuario);
			}

			return $rc[0]->nombreApellido;
		}

		function rtn_nombre_publico($idUsuario){

			if($this->existe_registro('usuario','idUsuario='.$idUsuario)){
				return $this->rtn_consulta_unica('nombre_publico','usuario','idUsuario='.$idUsuario);		
			}
			return null;
		}

		function rtn_array_idpregunta($idExamen){
			$rtn = array();
			$rc = $this->rtn_consulta("idPregunta","pregunta","idExamen=".$idExamen." ORDER BY orden ASC");

			foreach($rc as $obj){
				$rtn[] = $obj->idPregunta;
			}	
			return (count($rtn)>0)? $rtn : array();
		}

		function rtn_array_idpregunta_respuesta($idad){
			$rtn = array();

			$rc = $this->rtn_consulta("idPregunta","respuesta","idAdmision=".$idad);

			foreach($rc as $obj){
				$rtn[] = $obj->idPregunta;
			}

			//Una  puede tener varias respuestas
			//Aplicamos array_unique para tener un idpregunta único

			$rtn = array_unique($rtn);

			return (count($rtn)>0)? $rtn : array();
		}

		function rtn_array_alt_correctas($idPregunta){

			$rc = $this->rtn_consulta("idAlternativa,descripcion,respuesta","alternativa","respuesta='SI' AND idPregunta=".$idPregunta." ORDER BY orden ASC");
			return (count($rc)>0)? (array)$rc:array();

		}

		function rtn_array_idalternativas($idPregunta){

			$rtn = array();
			$rc = $this->rtn_consulta("idAlternativa","alternativa","idPregunta=".$idPregunta);

			foreach($rc as $obj){
				$rtn[] = $obj->idAlternativa;
			}	
			return (count($rtn)>0)? $rtn : array();
		}

		function rtn_num_alt_correctas($idPregunta){

			$query = "SELECT respuesta FROM alternativa WHERE respuesta ='SI' AND idPregunta=".$idPregunta.";";

			if($this->parents->sql->consulta($query)){
				$resultado = $this->parents->sql->resultado;
				return count($resultado);
			}
			return 0;
		}

		function rtn_datos_usuario_publico($idUsuario){//borrarr

			$num_total_exam_resuleta = $this->rtn_num_total_exam_resuelta_cae($idUsuario);
			$num_exam_subida         = $this->rtn_num_exam_publica($idUsuario); //Examenes públicas
			$puntuacion_total        = 0; //Puntuación todal obtenida
			$alcance                 = 0; //Es el número total de usuarios que resolvieron sus publicacione

		}

		function rtn_grupo_inicial($init,$idUsuario){

			$rtn = array();

			$rc  = $this->parents->gn->rtn_consulta("idGrupo,descripcion","examen_grupo","idUsuario=".$idUsuario);

			//eliminar grupos sin subgrupos
			foreach($rc as $ind=>$val){
				
				if(count($this->rtn_idexamen_subgrupo_publico($val->idGrupo)) == 0){
					unset($rc[$ind]);
				}

			}
							
			$rcTmp = array();
			
			$ind = 0;
			$p   = 0;
			$j   = $init-1;

			$guardar = $rc[$j];

			foreach($rc as $obj){
				if($ind <= $j){
					if($ind == 0){
						$rcTmp[$ind] = $guardar;
					}else{
						$rcTmp[$ind] = $rc[$p];
						$p++;	
					}
				}else{
					$rcTmp[$ind]= $rc[$ind];
				}
				$ind++;
			}

			return $rcTmp;//rtn (object)
			
			//return json_encode($rc);
		}

		function rtn_idexamen_subgrupo_publico($idGrupo,$idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$query = "
				SELECT es.idExamen FROM examen_subgrupo es 
					INNER JOIN examen_grupo eg  ON es.idGrupo  = eg.idGrupo 
					INNER JOIN examen_config ec ON es.idExamen = ec.idExamen
				WHERE (eg.idUsuario=".$idUsuario." AND es.idGrupo=".$idGrupo.") AND (ec.publicar='SI' AND ec.eliminar='NO');
			";

			if($this->parents->sql->consulta($query)){
				return $this->parents->sql->resultado;
			}
			return array();

		}

		function rtn_letra_valida($text){
			//Ni bien encuentra un caracater correcto lo muestra
			if($this->verifica_valor($text)){
				$ss = str_split($text);
				foreach($ss as $val){
					if(!preg_match("/(á|é|í|ó|ú|ñ|[!¡@#\$%\^&\*\¿\?_~\/=() ])/",$val)){
						return $val;
					}
				}
			}
			return 't';
		}

		function rtn_config_default($propiedad,$idUsuario=0){

			$valor     = 0;
		
			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			//recuperar json
			$json = $this->abrir_arch_json(URI."/config.json");
			
			//recuperar la base de datos
			if($this->existe_registro("config","propiedad='".$propiedad."' AND idUsuario=".$idUsuario)){

				$rc    = $this->rtn_consulta("propiedad,valor","config","propiedad='".$propiedad."' AND idUsuario=".$idUsuario);
				$valor = $rc[0]->valor;

			}else{				
				$valor = $json[$propiedad];
 			}
 			
			return $valor;

		}

		function rtn_json_string($campo,$tabla,$condicion){
			
			$rc   = $this->rtn_consulta($campo,$tabla,$condicion);
			$json = json_decode($rc[0]->$campo);

			if(is_object($json)){
				return $json;
			}
			return null;
		}

		function rtn_autor($idUsuario=0,$nombre_publico='',$usted='Tú'){

			$idUser = $this->parents->session->get("idUser");

			if($this->verifica_valor($nombre_publico)){
				$nombre_publico = ($idUser == $idUsuario)? $usted : $nombre_publico;	
			}else{
				$nombre_publico = $this->rtn_consulta_unica('nombre_publico','usuario','idUsuario='.$idUsuario);
				$nombre_publico = ($idUser == $idUsuario)? $usted : $nombre_publico;				
			}

			return $nombre_publico;
		}

		function rtn_duracion($fecha1='',$fecha2=''){
			$rtn = '00:00';

			if($this->verifica_valor($fecha1) && $this->verifica_valor($fecha2)){
				$rtn = $this->dif_fechas($fecha1,$fecha2);
			}
			return $rtn;
		}

		function rtn_reducir_txt($txt=null,$num_crt=0){

			$min = 100;//config min carácteres			

			if($this->verifica_valor($txt)){

				$num_crt = ($num_crt != 0)? $num_crt: $min;
				return (strlen($txt) >= $min)? substr($txt,0,$num_crt).'...':$txt;
			}
			return null;

		}

		function rtn_reducir_txt_porcent($txt=null,$porcent=10){

			//Ingrese el porcentage

			if(($porcent > 0 && $porcent <=100 )){
				$sl      = strlen($txt);
				$rpta    = ($porcent/100)*$sl;
				$num_crt = round($rpta);

				return substr($txt,0,$num_crt).'...';

			}
			return null;
		}

		function rtn_resultado_exam($idUsuario=0){

			//Retorna un array con valores vacios si no hay resgistros en examen_resultado

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->parents->session->get("idUser");

			$rtn = array(
				"num_preguntas"       => 0,
				"acertada"            => 0,
				"fallida"             => 0,
				"resuelta"            => 0,
				"no_resuelta"         => 0
			);

			$query = "
				SELECT 
					SUM(er.num_preguntas) AS num_preguntas,
					SUM(er.acertada) AS acertada,
					SUM(er.fallida) AS fallida,
					SUM(er.resuelta) AS resuelta,
					SUM(er.no_resuelta) AS no_resuelta
				FROM examen_resultado  er 
					INNER JOIN admision ad ON er.idAdmision = ad.idAdmision
					INNER JOIN examen e ON er.idExamen = e.idExamen 
				WHERE (ad.idUsuario = ".$idUsuario." AND e.idUsuario <> ".$idUsuario.");
			";

			if($this->parents->sql->consulta($query)){
				foreach($this->parents->sql->resultado as $obj){
					$rtn = array(
						"num_preguntas"       => $obj->num_preguntas,
						"acertada"            => $obj->acertada,
						"fallida"             => $obj->fallida,
						"resuelta"            => $obj->resuelta,
						"no_resuelta"         => $obj->no_resuelta
					);
				}
			}

			return $rtn;

		}

		function rtn_nivel_str($nivel=0){
			$rtn = $this->parents->interfaz->str_default('nivel-exam');
			return $rtn[$nivel];
		}

		function rtn_nivel_exam($idex=null){
			return $this->rtn_consulta_unica('nivel','examen',"idex='".$idex."'");
		}

		function rtn_estado_str($estado=null){
			$rtn = $this->parents->interfaz->str_default('estado-exam');
			return $rtn[$estado];
		}

		function rtn_abc(){
			return array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','z','y','z');
		}

		function rtn_random_array($array,$rango=0){
			//input
			// $array = array('a1','a200','a10','a56','a30');
			// rango =2,rango=3, ...

			$rtn_array = [];
			$rtn  = [];
			$cont = count($array);

			for($i=0 ; $i<$rango ; $i++){
				$rtn[] = random_int(0,$cont-1);	
			}

			//array con valores únicos
			$rtn = array_unique($rtn);

			//comprobamos rango de valores
			if(count($rtn) == $rango){
				foreach($rtn as $ind){
					$rtn_array[] = $array[$ind];
				}
				return $rtn_array;
			}else{
				return rtn_random_array($array,$rango);
			}

		}

		function existe_registro($tabla,$condicion){
			// elemento,idElemento=20
			$query = "SELECT COUNT(*) AS cant FROM ".$tabla." WHERE ".$condicion.";";
			if($this->parents->sql->consulta($query)){

				$resultado = $this->parents->sql->resultado;
				foreach($resultado as $obj){
					if($obj->cant>0){
						return true;
					}else{
						return false;
					}
				}

				return false;
			}
			return false;
		}

		function existe_admision($idad){

			if($this->existe_registro("admision","idAdmision=".$idad)){
				return true;
			}
			return false;

		}

		function existe_admision_activa($idad){

			if($this->existe_registro("admision","idAdmision=".$idad." AND estado_admision='ACTIVA'")){
				return true;
			}
			return false;
		}

		function existe_admision_concluida($idad){

			if($this->existe_registro("admision","idAdmision=".$idad." AND estado_admision='CONCLUIDA'")){
				return true;
			}
			return false;
		}

		function existe_admision_cancelada($idad){

			if($this->existe_registro("admision","idAdmision=".$idad." AND estado_admision='CANCELADA'")){
				return true;
			}
			return false;
		}

		function existe_admision_tmp($idad){

			if($this->existe_registro("admision","idAdmision=".$idad." AND estado_admision='TMP'")){
				return true;
			}
			return false;
		}

		function existe_examen_libre($idex){
			if($this->existe_registro("examen","estado ='LIBRE' AND idex='".$idex."'")){
				return true;
			}
			return false;
		}

		function existe_idex_publica($idex=null){
			if($this->verifica_valor($idex) && $this->existe_registro('examen',"idex='".$idex."'")){
				$query="SELECT e.idex FROM examen e INNER JOIN examen_config ec ON e.idExamen=ec.idExamen WHERE e.idex='".$idex."' AND ec.publicar='SI' AND ec.eliminar='NO'";
				if($this->parents->sql->consulta($query)){
					if(count($this->parents->sql->resultado) > 0){
						return true;
					}else{
						return false;
					}	
				}
				return false;
			}else{
				return false;
			}
		}

		function existe_idex_idad($datos){

			$query = "SELECT*FROM admision a INNER JOIN examen e ON a.idExamen=e.idExamen WHERE (e.idex='".$datos[0]."' AND a.idAdmision=".$datos[1].");";
			if($this->parents->sql->consulta($query)){
				if(count($this->parents->sql->resultado)>0){
					return true;
				}	
				return false;
			}
			return false;

		}

		function existe_cod_compartir($cod){

			//Existe código compartido
			//Verificar estado

			if($this->existe_registro("compartir","cod =".$cod." AND estado = 'ACTIVA'")){
				return true;
			}
			return false;

		}

		function existe_nombre_publico_usuario($nombre,$idUsuario){
			
			$rc = $this->rtn_consulta("nombre_publico","usuario","idUsuario=".$idUsuario);
			$np = $this->post_slug($rc[0]->nombre_publico);

			if($nombre == $np){
				return true;
			}
			return false;
		}

		function existe_estilo_exam($idExamen){
			if($this->existe_registro("estilo_examen","idExamen=".$idExamen)){
				return true;
			}
			return false;
		}

		function existe_idex($idex){

			if($this->existe_registro("examen_config","idExamen=".$this->rtn_id($idex)." AND eliminar='NO'")){
				return true;
			}
			return false;
		}

		function existe_estilo_usuario($idUsuario){

			if($this->existe_registro("estilo_usuario","idUsuario = ".$idUsuario)){
				return true;
			}
			return false;	
		}

		function existe_grupos($idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$rc = $this->rtn_consulta("idGrupo","examen_grupo","idUsuario=".$idUsuario);

			if(count($rc) > 0){
				return true;
			}
			return false;
		}

		function existe_subgrupos($idGrupo){

			$rc = $this->rtn_consulta("idGrupo","examen_subgrupo","idGrupo=".$idGrupo);

			if(count($rc) > 0){
				return true;
			}
			return false;
		}

		function existe_subgrupos_public($idGrupo){ }

		function existe_nombre_grupo($nomb){

			foreach($this->rtn_consulta("descripcion","examen_grupo","idUsuario=".$this->idUsuario) as $obj){
				if(strtolower($obj->descripcion) == strtolower($nomb)){
					return true;
				}
			}
			return false;

		}

		function existe_en_grupo($idExamen){

			if($this->existe_registro("examen_subgrupo","idExamen=".$idExamen)){
				return true;
			}
			return false;

		}

		function existe_exam_public_singrupo($idExamen,$idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			$query = "
				SELECT es.idExamen FROM examen_subgrupo es 
					INNER JOIN examen_grupo eg ON es.idGrupo=eg.idGrupo 
					INNER JOIN examen_config ec ON es.idExamen= ec.idExamen
				WHERE (eg.idUsuario=$idUsuario AND es.idExamen=$idExamen) AND (ec.publicar='SI' AND ec.eliminar='NO');
			";

			if($this->parents->sql->consulta($query)){
				if( count($this->parents->sql->resultado)>0 ){
					return true;
				}
			}
			return false;

		}

		function existe_preg_resuelta($idad,$idPreg){

			if($this->existe_registro("respuesta","idAdmision=".$idad." AND idPregunta=".$idPreg." AND idUsuario=".$this->idUsuario)){
				return true;
			}
			return false;
		}

		function existe_alt_resuelta($idad,$idPreg,$idAlt){

			if($this->existe_registro("respuesta","idAdmision=".$idad." AND idPregunta=".$idPreg." AND idAlternativa=".$idAlt." AND idUsuario=".$this->parents->session->get("idUser"))){
				return true;
			}
			return false;

		}

		function existe_subcategorias($idCat){

			$rc = $this->rtn_consulta("*","subcategoria","idCategoria=".$idCat);

			if(count($rc) > 0){
				return true;
			}
			return false;
		}

		function existe_subategorias_mostrar($idCat=0,$idSubCat=0){

			$rc = $this->rtn_consulta("*","subcategoria","idCategoria=".$idCat." AND mostrar='SI'");

			if(count($rc) > 0){
				return true;
			}
			return false;
		}

		function existe_categoria($idExamen,$idCat,$idSubCat=0){
			if($this->existe_registro("examen_categoria","idExamen=".$idExamen." AND idCategoria=".$idCat." AND idSubcategoria=".$idSubCat)){
				return true;
			}
			return false;
		}

		function existe_mas_categorias($idExamen,$idCat){
			
			$rc = $this->rtn_consulta("idCategoria","examen_categoria","idExamen=".$idExamen." AND idCategoria=".$idCat);

			if(count($rc) > 1){
				return true;
			}
			return false;
		}

		function generar_clave_seguridad_multiple(){

			date_default_timezone_set("America/Lima");

			$cad             = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@%";
			$cadBase         = md5(CSM_TOKEN.date("d-m-Y H:i:s"));
			$cad_aleatoria   = "";

			$longPassw  = 16;//config
			$lng_cadena = strlen($cad);

			for($i=0; $i<$longPassw; $i++){
				$aleatorio   = mt_rand(0,$lng_cadena-1);
				$cad_aleatoria.= substr($cad,$aleatorio,1);
			}
			return $cadBase.$cad_aleatoria;
		}

		function generar_nombre_publico($correo){

			if($this->verifica_valor($correo)){
				return substr($correo,0,5);
			}
			return 'tw_user';
		}

		function encriptar_idex($idex=null){

			//La encriptació es muy simple 
			//tomamos los últimos tres digitos del idex y lo unimos con el contador,así creará ids únicos
			//Ojo el contados debe ser secuencial 0,1,2,3,...

			//Mejorar en su debido tiempo

			$idExamen = $this->rtn_consulta_unica('idExamen','examen',"idex='".$idex."'");

			if(strlen($idExamen) == 1) $idExamen_str  = '0'.$idExamen;

			return substr($idExamen,-2).substr($idex,-4);
		}

		//-------------------------------------------------------------//
		//                             ...
		//-------------------------------------------------------------//

		function verifica_valor($str){
			//retorna true si hay caracteres significativos
			//retorna false si se encuenta:
			// - vacio
			// - " " o "   " espcio(s) simple(s)
			// - "/t" o "/t/t..." tabulaciones
			return (trim($str)!='') ? true : false;
		}

		function verifica_valor_array($str){
			//...
		}

		function verifica_valor_length($str){
			$str = $this->removeWhiteSpace($str);
			//$trim = trim($str);
			$cont = strlen($str);
			//return ($cont > 0)? true : false;
			return $cont;
		}

		function removeWhiteSpace($text)
		{
		    $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
		    //$text = preg_replace('/([\s])\1+/', ' ', $text);
		    $text = trim($text);
		    return $text;
		}

		function verifica_num_pregunta_publicar($num){
			
			if($num >= $min_num_examen){
				return true;
			}
			return false;
		}

		function verifica_divisibilidad_nota($num,$nota){

			if($nota%$num == 0 && $num >= $min_num_examen){
				return true;
			}
			return false;		
		}

		function verifica_nota_base($num,$nota){
			if($nota > 0){
				return true;
			}
			return false;
		}

		function verificar_plantilla($idex){
			//El usuario 2 es el admin testink
			if($this->existe_registro("examen","idex='".$idex."' AND idUsuario = 2")){
				return true;
			}
			return false;
		}

		function verifica_permiso($modulo,$tipoUsuario){
			//('init','USUARIO')
			$rc = $this->rtn_consulta("*","permisos","modulo='".$modulo."' AND idTipoUsuario='".$tipoUsuario."'");

			if(count($rc) > 0){
				return true;
			}
			return false;
		}

		function verifica_preactiva(){

			$rc = $this->rtn_consulta("estado","usuario","idUsuario=".$this->idUsuario);

			if($rc[0]->estado == 'PREACTIVA'){
				return true;
			}
			return false;

		}

		function verificar_alt_correcta($idAlt,$idPreg){

			$rc = $this->rtn_consulta("idAlternativa","alternativa","respuesta = 'SI' AND idAlternativa = ".$idAlt." AND idPregunta=".$idPreg);
			return (count($rc)>0)? true:false;

		}

		function verificar_respuesta($arraySelect = array(),$idPregunta){

			$rpta = true;
			$arrayIdAlt = array();

			//verificando cantidades
			if(count($arraySelect) > 0 && count($arraySelect) == count($this->rtn_array_alt_correctas($idPregunta))){

				$rc = $this->rtn_consulta("idAlternativa","alternativa","idPregunta=".$idPregunta." AND respuesta = 'SI' ORDER BY orden ASC");
				foreach($rc as $obj){ $arrayIdAlt[] = $obj->idAlternativa;}

				foreach($arraySelect as $idAlt){
					if(!in_array($idAlt,$arrayIdAlt)){
						$rpta = false;
					}
				}
			}else{
				$rpta = false;
			}

			return $rpta;
		}

		function verificar_alt_correcta_incorrecta($obj,$idUsuario=0){

			//$obj1->idad
			//$obj1->idPregunta

			//true  significa que es la resouesta corecta
			//false incorrecta
			//null  vacia

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;

			// preguntar si fue seleecionada la alternativa
			if($this->existe_registro("respuesta","(idAdmision=".$obj->idad." AND idPregunta=".$obj->idPregunta." AND idAlternativa=".$obj->idAlternativa.") AND idUsuario=".$idUsuario)){
				if($obj->respuesta == 'SI'){
					//verificar si es pregunta correcta
					if($this->verificar_preg_correcta($obj->idad,$obj->idPregunta,$this->idUsuario)){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
			return null;
		}

		function verificar_preg_correcta($idad,$idPreg,$idUsuario=0){

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->idUsuario;
			$idAlternativa_array = array();

			$rc1 = $this->rtn_consulta("idAlternativa,respuesta","alternativa","respuesta='SI' AND idPregunta=".$idPreg);

			$rc2 = $this->rtn_consulta("idAlternativa","respuesta","idAdmision=".$idad." AND idPregunta=".$idPreg." AND idUsuario=".$idUsuario);
			foreach($rc2 as $obj2){ $idAlternativa_array[] = $obj2->idAlternativa;}

			if(count($rc1) == count($rc2)){
				
				foreach($rc1 as $obj1){
					
					if(!in_array($obj1->idAlternativa,$idAlternativa_array)){
						return false;
					}
				}
				return true;
			}

			return false;
		}

		function verificar_preg_resuelta($idad,$idPreg,$idUsuario=0){

			//Se considera pregunta resuelta si el num de respuestas 
			//seleccionadas es mayor o igual con el num de alternativas.

			$idUsuario = ($idUsuario != 0)? $idUsuario : $this->parents->session->get('idUser');

			$rc1 = $this->rtn_consulta('idAlternativa,respuesta','alternativa',"respuesta='SI' AND idPregunta=".$idPreg);

			$rc2 = $this->rtn_consulta('idRespuesta,idAlternativa','respuesta',"idAdmision=".$idad." AND idPregunta=".$idPreg." AND idUsuario=".$idUsuario);

			if(count($rc2) >= count($rc1)){
				return true;
			}
			return false;

		}

		function verificar_seguir($idUsuario1,$idUsuario2){

			if($this->parents->session->check_login()){

				if($this->existe_registro("seguir","idUsuario_1=".$idUsuario1." AND idUsuario_2=".$idUsuario2)){
					return true;
				}
				return false;
			}
			return false;
		}

		function verificar_id_usuarios($cad=array()){

			//No puede haber duplicados
			//si todo los idUsuarios existen será true en caso contrario false

			if(is_array($cad)){

				$au = array_unique($cad);

				if(count($cad) == count($au)){
					foreach($cad as $val){
						if(!$this->existe_registro("usuario","idUsuario=".$val)){
							return false;
						}
					}
					return true;
				}
				return false;
			}
			return false;			
		}

		function verificar_compartir_con_msj($idExamen){
			if($this->existe_registro("examen_config","compartir_con_msj = 'SI' AND idExamen=".$idExamen)){
				return true;
			}
			return false;
		}

		function verificar_publicar($idExamen){
			if($this->existe_registro("examen_config","publicar='SI' AND eliminar='NO' AND idExamen=".$idExamen)){
				return true;
			}
			return false;
		}

		function verificar_exam($idex,$idUsuario=0){

			//verificar pregunta por pregunta 
			//mostrar mensaje de error preguntas vacias
			//mostrar mensaje de error alternativas vacias
			//mostrar mensaje de error imagen vacia
			//min  de alternativas por pregunta 2
	
			$msj       = array();		
			$idExamen  = $this->parents->gn->rtn_id($idex);
			$idUsuario = ($idUsuario!=0)? $idUsuario:$this->idUsuario;

			if($this->parents->gn->rtn_num_preguntas($idExamen) > 0){

				$cont = 0;

				//tipo pregunta array
				$cad = 	$this->parents->interfaz->rtn_array_tipo_pregunta_img();

				//Mostrar Examen
				$query = "SELECT idExamen,idex,titulo,estilo FROM examen WHERE idex = '".$idex."' AND idUsuario=".$idUsuario.";";

				if($this->parents->sql->consulta($query)){

					$resultado1 = $this->parents->sql->resultado;

					foreach($resultado1 as $obj1){

						if(!$this->parents->gn->verifica_valor($obj1->titulo))
							$msj[] = "El título del examen está vacia.";

						$query = "SELECT idPregunta,descripcion,img,tipo,orden,idExamen FROM pregunta WHERE idExamen=".$idExamen." ORDER BY orden ASC;";

						if($this->parents->sql->consulta($query)){
							$i=0;

							$resultado2 = $this->parents->sql->resultado;

							foreach($resultado2 as $obj2){

								$cont++;

								if(!$this->parents->gn->verifica_valor($obj2->descripcion))	
									$msj[] = "El título de la pregunta ".$cont." está vacia.";
								if(in_array($obj2->tipo,$cad)){

									if(($obj2->img == 'default.png'))
									$msj[] = "En la pregunta ".$cont.", falta una imagen.";
								}


								$query = "SELECT idAlternativa,descripcion,idPregunta,respuesta FROM alternativa WHERE idPregunta=".$obj2->idPregunta." ORDER BY orden ASC;";

								if($this->parents->sql->consulta($query)){

									$resultado3 = $this->parents->sql->resultado;

									$j         = 0;
									$cont_rpta = 0;

									foreach($resultado3 as $obj3){

										if(!$this->parents->gn->verifica_valor_length($obj3->descripcion))
											$msj[] = "En la pregunta ".$cont.", se encotró una alternativa vacia.";
										if($obj3->respuesta == 'SI')
											$cont_rpta++;

										$j++;
									}
									//verificar num min de alternativas								
									if($j < 2)								
										$msj[] = "En la pregunta ".$cont.", el mínimo de alternativas es dos.";						
									//verificar num min de respuestas
									if($cont_rpta < 1)
										$msj[] = "En la pregunta ".$cont.", el mínimo de respuestas es uno.";
								}
								$i++;
							}
								
						}							

					}

				}

			}else{
				$msj[] = "No se encontraron preguntas.";
			}

			return $msj;	
		}

		function verificar_todo_exam($idex,$estilo=null,$idUsuario=0){
			//////////////////////////////////////// falta dearrololar ///////////////

			//VERIFICAR POR estilo de examen algunos examen piden más datos o quizas nó

			//verificar pregunta por pregunta 
			//mostrar mensaje de error preguntas vacias
			//mostrar mensaje de error alternativas vacias
			//mostrar mensaje de error imagen vacia
			//min  de alternativas por pregunta 2

			if($estilo == 'classic')
			{

			}
			if($estilo == 'link')
			{

			}
			if($estilo =='fixed')
			{

			}

			$msj       = array();		
			$idExamen  = $this->parents->gn->rtn_id($idex);
			$idUsuario = ($idUsuario!=0)? $idUsuario:$this->idUsuario;

			if($this->parents->gn->rtn_num_preguntas($idExamen) > 0){

				$cont = 0;

				//tipo pregunta array
				$cad = 	$this->parents->interfaz->rtn_array_tipo_pregunta_img();

				//Mostrar Examen
				$query = "SELECT idExamen,idex,titulo,estilo FROM examen WHERE idex = '".$idex."' AND idUsuario=".$idUsuario.";";

				if($this->parents->sql->consulta($query)){

					$resultado1 = $this->parents->sql->resultado;

					foreach($resultado1 as $obj1){

						if(!$this->parents->gn->verifica_valor($obj1->titulo))
							$msj[] = "El título del examen está vacia.";

						$query = "SELECT idPregunta,descripcion,img,tipo,orden,idExamen FROM pregunta WHERE idExamen=".$idExamen." ORDER BY orden ASC;";

						if($this->parents->sql->consulta($query)){
							$i=0;

							$resultado2 = $this->parents->sql->resultado;

							foreach($resultado2 as $obj2){

								$cont++;

								if(!$this->parents->gn->verifica_valor($obj2->descripcion))	
									$msj[] = "El título de la pregunta ".$cont." está vacia.";
								if(in_array($obj2->tipo,$cad)){

									if(($obj2->img == 'default.png'))
									$msj[] = "En la pregunta ".$cont.", falta una imagen.";
								}


								$query = "SELECT idAlternativa,descripcion,idPregunta,respuesta FROM alternativa WHERE idPregunta=".$obj2->idPregunta." ORDER BY orden ASC;";

								if($this->parents->sql->consulta($query)){

									$resultado3 = $this->parents->sql->resultado;

									$j         = 0;
									$cont_rpta = 0;

									foreach($resultado3 as $obj3){

										if(!$this->parents->gn->verifica_valor_length($obj3->descripcion))
											$msj[] = "En la pregunta ".$cont.", se encotró una alternativa vacia.";
										if($obj3->respuesta == 'SI')
											$cont_rpta++;

										$j++;
									}
									//verificar num min de alternativas								
									if($j < 2)								
										$msj[] = "En la pregunta ".$cont.", el mínimo de alternativas es dos.";						
									//verificar num min de respuestas
									if($cont_rpta < 1)
										$msj[] = "En la pregunta ".$cont.", el mínimo de respuestas es uno.";
								}
								$i++;
							}
								
						}							

					}

				}

			}

			return $msj;
		}

		function verificar_exam_clave($clave='',$idExamen){
			if($this->existe_registro("examen","clave='".$clave."' AND idExamen = '".$idExamen."'")){
				return true;
			}
			return false;
		}

		function verificar_url_id_usuario(){
			$idUsuario = $this->extraer_ultima_str($str1);

		}

		function verifica_url_public_usuario($str1){

			$idUsuario = $this->extraer_ultima_str($str1);
			$seudonimo = '';
			$str2      = '';
			$rtn       = array();

			if($this->existe_registro("usuario","idUSuario=".$idUsuario." AND estado <> 'INACTIVA'")){
				foreach($this->rtn_consulta("*","usuario","idUsuario=".$idUsuario) as $obj){
							
					$str2 = $this->post_slug($obj->seudonimo." ".$obj->idUsuario);

					if($str2 == $str1){
						$rtn=array(
							"success" => true,
							"value"   => array("seudonimo"=>$seudonimo,"idUsuario"=>$idUsuario)
						);
					}else{
						$rtn=array(
							"success" => false							
						);
					}
				}				
			}else{
				$rtn=array(
					"success" => false							
				);
			}
			return $rtn;
		}

		function verifica_selec_grupo_test($idGrupo,$idExamen){
			return $this->existe_registro("examen_subgrupo","idGrupo=".$idGrupo." AND idExamen=".$idExamen);
		}

		function extrae_ultima_str($str,$separate=' '){
		 	return substr($str,strrpos($str,$separate)+1);
		}

		function seleccion($valor,$select=''){
			if($select == 'tipo-list')
			{
				$array = array("box","list");

				if(in_array($valor,$array)){
					return true;
				}

			}
			if($select == '...'){

			}
			return false;
		}

		function guardar_img($datos,$FILES){
			//antes:
			//-indicar extensiones permitidas
			//-indicar nombre
			//-indicar destino
			//-indicar el tamaño máximo

			$rtn             = array();
			$tamanioCorrecta = (isset($datos["largoMaxima"]) && isset($datos["alturaMaxima"]))? true:false;

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
					if($tamanioCorrecta){
						$tamanio = getimagesize($datos["file_tmp"]);
						if($datos["largoMaxima"] >= $tamanio[0] && $datos["alturaMaxima"] >= $tamanio[1]){
							//carpeta destino
							$rtn = $this->guardar_destino($datos,$FILES);
						}else{
							$rtn=array(
								"success"    => false,
								"msj"        => "La imagen es demasiado grande ".$tamanio[0]."x".$tamanio[1]." (px).<br>Tamaño máximo permitido ".$datos["largoMaxima"]."x".$datos["alturaMaxima"]." (px).",							
								"CODE_ERROR" => '200'
							);
						}
					}else{
						$rtn = $this->guardar_destino($datos,$FILES);
					}

				}else{				

					$rtn=array(
						"success"    => false,
						"msj"        => "Sólo se acepta los siguientes formatos: ".$extPermitidas,
						"CODE_ERROR" => '201'
					);
				}

			}else{
				$rtn=array(
					"success"    => false,
					"msj"        => "Elija un archivo.",
					"CODE_ERROR" => '202'
				);
			}

			return $rtn;

		}



		function guardar_destino($datos,$FILES){
			//carpeta destino
			$nombreArch   = $datos["nombreArch"].".".$datos["extension"];
			$destino      = $datos["destino"].$nombreArch;

			//eliminar img actual y todos aquellos que tenga el mismo nombre para cambiarlo.
			//No se aplica prevención;que pasa si hay un error o 
			//un apagón en la subida del archivo
			//Será ineficiente cuando haya más 10 000 000 de usuarios.

			$this->eliminar_arch_anterior($datos["destino"],$datos["nombreArch"]);

			//guardar archivo img nuevo
			if($FILES["archivo"]["error"] <= 0){
				if(!file_exists($destino)){
					//mover archivo
					move_uploaded_file($datos["file_tmp"],$destino);

					$rtn=array(
						"success"    => true,
						"nombreArch" => $nombreArch,
						"destino"    => $destino
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

		function guardar_resultado_exam($cad = array()){

			$idad = $cad["idAdmision"];

			//existe id admisión
			if($this->existe_registro("admision","idAdmision=".$idad)){

				//existe en examen_resultado
				if(!$this->existe_registro("examen_resultado","idAdmision=".$idad)){
					//Guardar
					if($this->parents->sql->insertar("examen_resultado",$cad)) return true;
				}else{
					//Actualizar
					if($this->parents->sql->modificar("examen_resultado",$cad,array("idAdmision"=>$idad))) return true;
				}
				return false;
			}

			return false;
		}

		function validar($tipo,$valor=array()){

			//validar datos relevantes
			//-correo
			//-contraseñas

			$rtn=array();

			switch ($tipo){
				case 'name':
					$msj    = "";
					$patron = "/^([ ]+)?[a-zA-ZÑñáéíóúÁÉÍÓÚ]+([ ]+)?([a-zA-ZÑñáéíóúÁÉÍÓÚ]+)([ ]+)?$/";
					$pm     = preg_match($patron,$valor);

					if($pm!=true){
						$msj="¿Cómo te llamas?";
					}	

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;
				
				case 'lastname':
					$msj    = "";
					$patron = "/^([ ]+)?[a-zA-ZÑñáéíóúÁÉÍÓÚ]+([ ]+)?([a-zA-ZÑñáéíóúÁÉÍÓÚ]+)([ ]+)?$/";
					$pm     = preg_match($patron,$valor);

					if($pm!=true){
						$msj="¿Cuál es tu apellido(s)?";
					}	

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;
				
				case "option":
					$msj = "";
					$pm  = false;					

					if($valor!='Sexo' && $valor!='Motivo' && $valor!='Elige' && $this->verifica_valor($valor)){
						$pm = true;
					}else{
						$msj = "Elige una opción.";
					}
					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'user':

					$msj = "";
				
					$patron = "/^(([a-z]+){4,27}|([a-z]+){1,27}([0-9]+))$/";//resuelto a medias con ayuda strlen

					$pm     = false;
					if($this->verifica_valor($valor)){
						$pm = preg_match($patron,$valor);
						if($pm && strlen($valor)>=4){
							if(count($this->rtn_consulta('usuario','usuario',"usuario LIKE '".$valor."'"))>0){
								$msj = "Existe un usuario con ese nombre, invente otra.";
								$pm  = false;						
							}else{
								$pm  = true;
							}
						}else{
							$msj ="El nombre de usuario debe estar en minúsculas ,entre 4 y 27 caracteres y no sepermite carácteres especiales. Ejm: washi, washi1, washi123";							
							$pm = false;
						}

					}else{
						$msj = "Campo vacio.";
						$pm  = false;
					}

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'email':
					$msj    = "";
					$patron = "/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/";
					$pm     = preg_match($patron,$valor);

					if($pm!=true){
						$msj="Ingrese un e-mail válido.";
					}	

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'code':
					$msj  = "";
					$msj1 = "No coincide las contraseñas.";
					$msj2 = "Para que su contraseña sea segura se recomienda entre 8 y 24 caracteres,si es posible incluya mayúsculas y minúsculas ,y también puede utilizar números y carácteres especiales (! @ # $ % ^ & * ? _ ~ /).";

					$patron = "/^[a-zA-ZÁáÀàÉéÈèÍíÌìÓóÒòÚúÙùÑñüÜ0-9!@#\$%\^&\*\?_~\/]{8,24}$/";
					$pm     = false;

					$pm1    = preg_match($patron,$valor[0]);
					$pm2    = preg_match($patron,$valor[1]);

					if($pm1==true && $pm2==true){
						if($valor[0]==$valor[1]){
							$pm=true;
							$msj="";
						}else{
							$pm=false;
							$msj=$msj1;
						}
					}else{						
						$pm=false;
						$msj=$msj2;			
					}
					$rtn=array(
						"success" => $pm,
						"msj"     => $msj
					);	
					return $rtn;
				break;

				case 'current_code':
					$msj = "";
					$pm  = false;
					if($this->verifica_valor($valor)){
						$clave=$this->rtn_consulta('clave','usuario','idUsuario='.$this->idUsuario);
						if(md5($valor)!=$clave[0]->clave){
							$msj = "No coincide con la contraseña actual.";
							$pm  = false;						
						}else{
							$pm  = true;
						}
					}else{
						$msj = "Campo vacio.";
						$pm  = false;
					}

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'code_activate':								

					$patron = "/^[a-zA-Z0-9@#\$%]{9}$/";
					$pm     = preg_match($patron,$valor);
					$msj    = "";						

					if($pm == true){
						$pm  = true;
						$msj = "";
					}else{						
						$pm  = false;
						$msj = "Código de activación invalida.";			
					}

					$rtn=array(
						"success" => $pm,
						"msj"     => $msj
					);	
					return $rtn;

				break;

				case 'captcha':
					$msj    = "";
					//$patron = "/^".$valor[0]."$/";
					//$pm     = preg_match($patron,$valor[1]);
					$pm=false;
					if($valor[0]==$valor[1]){
						$pm  = true;
						$msj = "";
					}else{
						$msj="No coincide con el valor del Botón Captcha.";
					}											

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'anonym':
					$msj    = "";
					$patron = "/^([ ]+)?[a-zA-ZÑñáéíóúÁÉÍÓÚ]+([ ]+)?([a-zA-ZÑñáéíóúÁÉÍÓÚ]+)([ ]+)?$/";
					$pm     = preg_match($patron,$valor);

					if($pm!=true){
						$msj="¿Cambia tu Seudónimo?";
					}	

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'biography':
		
					$msj = "";					
					$pm  = $this->verifica_valor($valor["biografia"]);
					
					$numCaract = strlen($valor["biografia"]);
					if($pm){
						if($numCaract > $valor["maxCaract"]){
							$msj = "Excedió el número máximo de caracteres permitidos: ".$numCaract." caract.";
							$pm  = false;
						}else{
							$pm = true;
						}
					}else{
						$msj="¿Cómo es tu biografía?";
					}	

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'value':

					$msj = "";
					$pm  = false;
					if(is_array($valor)){
						if($this->verifica_valor($valor[0])){
							$pm = true;
						}else{
							if($valor){

							}
							$msj = $valor[1];
							$pm  = false;
						}
					}else{
						if($this->verifica_valor($valor)){
							$pm = true;
						}else{
							$msj = "Se encontró Valor vacio.";
							$pm  = false;
						}
					}

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);

					return $rtn;
				break;

				case 'img':
					//Falta verificar su funcionamiento

					$msj = "";
					$pm  = false;

					if(sizeof($valor)>0 && $valor["archivo"]["size"]>0){
						$pm = true;
					}else{
						$msj = "Elija una imagen.";
						$pm  = false;
					}

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);

					return $rtn;
				break;

				case 'time':
					$msj    = "";
					//$patron = "/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/";
					$patron = "/^([01]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/";
					$pm     = preg_match($patron,$valor);

					if($pm!=true){
						$msj="00:00:00";
					}	

					$rtn=array(
						"success" => (bool)$pm,
						"msj"     => $msj
					);
					return $rtn;
				break;

				case 'gn':
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
				break;

				default:
					return $rtn;	
			}			
		}

		function captcha(){
			$cad = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789#%&";			
			$lng = 5;
			$cadCaptcha='';
			for($i=0;$i<$lng;$i++){
				$aleatorio=rand(0,strlen($cad)-1);//0...n
				$cadCaptcha.=substr($cad,$aleatorio,1);
			}				
			define("CAPTCHA",$cadCaptcha);
			return;
		}

		function clave_confirm(){
			$cad = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789#%&";
			$lng = 7;
			$clave_confirm='';
			for($i=0;$i<$lng;$i++){
				$aleatorio=rand(0,strlen($cad)-1);//0...n
				$clave_confirm.=substr($cad,$aleatorio,1);
			}				
			return $clave_confirm;
		}

		function nueva_contrasena(){
			$cad     = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789#%&";			
			$cadBase = "Cp1";
			$passw   = "";

			$longPassw  = 8;//config
			$lng_cadena = strlen($cad);

			for($i=0;$i<$longPassw;$i++){
				$aleatorio   = mt_rand(0,$lng_cadena-1);
				$passw.= substr($cad,$aleatorio,1);
			}
			return $cadBase.$passw;
		}

		function nueva_clave_activar(){
			$cad     = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#%&";			
			$passw   = "";

			$longPassw  = 9;//config
			$lng_cadena = strlen($cad);

			for($i=0;$i<$longPassw;$i++){
				$aleatorio   = mt_rand(0,$lng_cadena-1);
				$passw.= substr($cad,$aleatorio,1);
			}
			return $passw;
		}

		function login($datos){
			
			$rtn   = array();

			$query = "SELECT u.idUsuario,u.nombre_publico,u.estado,tu.idTipoUsuario FROM usuario u INNER JOIN tipo_usuario tu  ON u.idTipoUsuario=tu.idTipoUsuario  WHERE (u.correo LIKE ? OR u.usuario LIKE ?) AND u.clave = ?;";

			$usuario    = $datos["usuario"];
			$contrasena = md5($datos["clave"]);

			$datos_seguros = array($usuario,$usuario,$contrasena);

			if($this->parents->sql->consulta_segura($query,$datos_seguros)){

				$resultado = $this->parents->sql->resultado;

				if(count($resultado)>0){

					foreach($resultado as $obj){

						if($obj->estado == 'ACTIVA' || $obj->estado == 'PREACTIVA'){

							//$this->idUsuario = $obj->idUsuario;//actual

							$login_session= array(
								"idUser"       => $obj->idUsuario,
								"public_name"  => $obj->nombre_publico,
								"type_user"    => $obj->idTipoUsuario
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
			$clave    = (isset($datos['clave']))? $datos['clave'] : '';

			$redirect = (isset($datos['redirect']))? $datos['redirect'] : '';


			if($this->verifica_valor($usuario) && $this->verifica_valor($clave)){
 
				$rtn = $this->login($datos);

				if($rtn["success"]){

					$rtn = array(
						"success" => true,
						"update"  => array(
							array(
								"id"     => "modalSubmit",
								"action" => "closeModal"
							)
						)
					);

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
					$rtn = array(
						"success" => false,
						"update"  => array(
							array(
								"id"     => "formMsj",
								"action" => "html",
								"value"  => $rtn["msj"]
							)
						)
	 				);
				}
			}else{

				$rtn = array(
					"success" => false,
					"update"  => array(
						array(
							"id"     => "formMsj",
							"action" => "html",
							"value"  => $this->parents->interfaz->msj("warning","Se encontrarón campo(s) vacio(s).")
						)
					)
 				);

			}

			return $rtn;
		}

		function login_remota($resultado){
			
			$rtn   = array();

			if(count($resultado)>0){
				foreach($resultado as $obj){

					if($obj->estado == 'ACTIVA'){

						$this->idUsuario = $obj->idUsuario;//actual

						$login_session= array(
							"idUser"       => $obj->idUsuario,
							"public_name"  => $obj->nombres,
							"type_user"    => $obj->idTipoUsuario
						);

						$this->parents->session->put_login($login_session);					

						$rtn=array(
							"success" => true
						);
						return $rtn;
					}
					elseif($obj->estado == 'INACTIVA'){
						$msj = $this->parents->interfaz->msj("success","Su cuenta está inactiva en '...' <br>Actívela para ingresar a EVIS.");
					}
					elseif($obj->estado == 'BLOQUEADA'){
						$msj = $this->parents->interfaz->msj("danger","Su cuenta está BLOQUEADA.<br>contactar desde '...' ");
					}
					else{
						$msj = $this->parents->interfaz->msj("danger","Correo electrónico o Usuario o Contraseña incorrectas.");
					}

				}
			}else{
				$msj = $this->parents->interfaz->msj("danger","Usuario o clave incorrecto.");
			}

		

			$rtn = array(
					"success" => false,
					"update"  => array(
						array(
							"id"     => "form-success",
							"action" => "html",
							"value"  => $msj
						)
					)
				);
			
			return $rtn;
		}

		//-------------------------------------------------------------//
		//                    Login y registro
		//-------------------------------------------------------------//

 		function modal_login($datos){

 			$destine       = "";
			$datos["load"] = "formLoad_bottom";


			if(isset($datos["url"])){
 				unset($datos["url"]);
 			}

 			if(isset($datos["redirect"])){
 				$datos["redirect"] = $datos["redirect"];
 			}

 			if(isset($datos["destine"])){
 				$destine = $datos["destine"];
 				unset($datos["destine"]);
 			}

 			if(isset($datos["edit"])){
 				if($datos["edit"] == 'active'){
 					$datos["edit"] = true;
 				}
 			}

 			$data = htmlspecialchars(json_encode($datos));

			$btn = '<button type="submit" class="btn btn-success mx-auto px-5 send" data-destine="'.$destine.'" data-data="'.$data.'" data-serialize="formLogin">Ingresar</button>';

			$form = $this->parents->interfaz->get_str_login($btn);

			$rtn = array(
				"success"=>true,
				"update"=>array(
					array(
						"id"	 => "modalSubmit",
						"type"   => "submit",
						"action" => "showModal"
					),
					array(
						"id"     => "modalTitleSubmit",
						"action" => "html",
						"value"  => "Iniciar sesión"
					),
					array(
						"id"     => "modalBodySubmit",
						"action" => "html",
						"value"  => $form
					),
					array(
						"id"	 => "modalSubmit",
						"action" => "openModal"
					)
				)
			);

			return $rtn;

		}

 		function modal_registro($datos){

 			$destine       = "";
			$datos["load"] = "formLoad_bottom";


			if(isset($datos["url"])) 
				unset($datos["url"]);

 			if(isset($datos["redirect"]))
 				$datos["redirect"] = $datos["redirect"];

 			if(isset($datos["destine"])){
 				$destine = $datos["destine"];
 				unset($datos["destine"]);
 			}

 			$data = htmlspecialchars(json_encode($datos));

			$btn  = '<button type="submit" class="btn btn-success mx-auto send" data-destine="'.$destine.'" data-data="'.$data.'" data-serialize="formRegistrar">Registrarse</button>';

			$form = $this->parents->interfaz->str_registrarse($btn);

			$rtn = array(
				"success"=>true,
				"update"=>array(
					array(
						"id"	 => "modalStatic",
						"type"   => "static",
						"action" => "showModal"
					),
					array(
						"id"     => "modalContentStatic",
						"action" => "html",
						"value"  => $form
					),
					array(
						"id"	 => "modalStatic",
						"action" => "openModal"
					)
				)
			);

			return $rtn;

		}

		//-------------------------------------------------------------//
		//                             init
		//-------------------------------------------------------------//

		function examen_config($campo,$idExamen){

			$rc = $this->rtn_consulta($campo,"examen_config","idExamen=".$idExamen." AND eliminar='NO'");

			if($rc[0]->$campo == 'SI'){
				return true;
			}else{
				return false;
			}
			return false;
		}

		function comparar_valores_array($array1,$array2){
			//Comparar si los valores de dos arrays son iguales,no importa el orden
			$num = 0;

			if(count($array1) == count($array2) && count($array1) > 0){
				foreach($array1 as $val1){
					foreach($array2 as $val2){
		 				if($val1 == $val2){
		 					$num+=1;
		 				}else{
		 					$num+=0;
		 				}
					}
				}

				if(count($array1) == $num){
					return true;//Los valores de array son iguales, no importa el orden
				}
				return false;//Si no coinciden los
			}
			return false;//Si los array son diferentes
		}

		function dif_fechas($fecha1,$fecha2){
			date_default_timezone_set("America/Lima");

			$fecha1 = strtotime($fecha1);
			$fecha2 = strtotime($fecha2);

			$dif = $fecha1-$fecha2;

			//echo date("d/m/Y H:i:s",$dif);
			//echo ($dif%(60*60))."<br>";

			$r1 = $dif%(60*60); //min
			$r2 = $r1%60;//seg

			$hora = $dif/(60*60);//hora
			$min  = $r1/60;//min
			$seg  = $r2;//seg

			$hora = intval($hora);
			$min  = intval($min);
			$seg  = intval($seg);

			if($hora<10){
				$hora = '0'.$hora;
			}

			if($min<10){
				$min = '0'.$min;
			}
			if($seg<10){
				$seg = '0'.$seg;
			}

			return $hora.":".$min.":".$seg;
		}

		function get_elapsed_time($datetime)
		{
		      if( empty($datetime) )
		      {
		            return;
		      }

		      date_default_timezone_set("America/Lima");
		      
		      // check datetime var type
		      $strTime = ( is_object($datetime) ) ? $datetime->format('Y-m-d H:i:s') : $datetime;
		 
		      $time = strtotime($strTime);
		      $time = time() - $time;
		      $time = ($time<1)? 1 : $time;
		 
		      $tokens = array (
		            31536000 => 'año',
		            2592000 => 'mes',
		            604800 => 'semana',
		            86400 => 'día',
		            3600 => 'hora',
		            60 => 'minuto',
		            1 => 'segundo'
		      );
		 
		      foreach ($tokens as $unit => $text)
		      {
		            if ($time < $unit) continue;
		            $numberOfUnits = floor($time / $unit);
		            $plural = ($unit == 2592000) ? 'es' : 's';
		            return $numberOfUnits . ' ' . $text . ( ($numberOfUnits > 1) ? $plural : '' );
		      }
		}
		
		function get_current_time($datetime)
		{
			date_default_timezone_set("America/Lima");
			if( empty($datetime) )
			{
				return;
			}

			// check datetime var type
			$strTime = ( is_object($datetime) ) ? $datetime->format('Y-m-d H:i:s') : $datetime;

			$time = strtotime($strTime);

			$pretext = ((time() - $time) > 0)? "Hace":"En";
			$time    = ((time() - $time) > 0)? time()-$time: $time - time();

			$tokens = array (
				31536000 => 'año',
				2592000 => 'mes',
				604800 => 'semana',
				86400 => 'día',
				3600 => 'hora',
				60 => 'minuto',
				1 => 'segundo'
			);

			foreach ($tokens as $unit => $text)
			{
				if ($time < $unit) continue;
				$numberOfUnits = floor($time / $unit);
				$plural = ($unit == 2592000) ? 'es' : 's';
				return $pretext.' '.$numberOfUnits . ' ' . $text . ( ($numberOfUnits > 1) ? $plural : '' );
			}
		}

		//-------------------------------------------------------------//
		//                        archivo
		//-------------------------------------------------------------//

		function crear_carpeta_vacia($uri){
			//Si no existe carpeta lo crea
			if(!file_exists($uri)){
				mkdir($uri,0777,true);
				return true;
			}
			return false;
		}

		function crear_img_usuario($datos){

			$nombre = $datos['nombre'];
			$letra  = substr($nombre,0,1);
			$letra  = strtoupper($letra);

			$w = 250;
			$h = 250;
			
			header('Content-Type: image/png');

			//crear imagen
			$img = imagecreatetruecolor($w,$h);

			//crear colores
			$fondo  = imagecolorallocate($img,40,210,199);//#28d2c7
			$figura = imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));

			//crear figuras geometricas básicas
			imagefilledrectangle ($img,0,0,$w,$h,$fondo);        // cuadro imagen
			imagefilledellipse   ($img,125,220,162,162,$figura); // cuerpo
			imagefilledellipse   ($img,125,80,140,140,$fondo);   // anillo
			imagefilledellipse   ($img,125,80,100,100,$figura);  // cabeza
			imagefilledrectangle ($img,20,250,220,220,$fondo);   // cuadro de fondo


			//el texto a dibujar
			$texto = $letra;

			//reemplace la ruta por la de su propia fuente
			$fuente = URI_THEME."/font/VeraBd.ttf";

			//posisión de las letras
			$posx = 140;
			$posy = 190;

			//añadir el texto
			imagettftext($img,20,0,$posx,$posy,$fondo,$fuente,$texto);

			//mostrar imagen
			//imagepng($img);

			//directorio
			$carpeta = $datos['uri'];

			//permisos
			//chmod($carpeta,0755);

			//guardar imagen en un directorio
			imagepng($img,$carpeta,0,NULL);

			//liberar memoria
			imagedestroy($img);

			return true;
		}

		function crear_img_exam($obj){

			$rgb1 = [40,210,199];  //color fondo #28d2c7 
			$rgb2 = [255,255,255]; //color letra

			$color_fondo = (isset($obj->hex))? $this->hex_to_rgb($obj->hex,'array'):$rgb1;
			$color_fondo = (isset($obj->rgb))? $obj->rgb:$rgb1;
			$color_letra = $rgb2;
			
			$letra1  = strtoupper($this->rtn_letra_valida($obj->titulo));
			$letra2  = APP_NAME;

			$destino = $obj->destino;

			$w = 200; $h = 200; //config
			
			//header('Content-Type: image/png');//sólo  se usa para mostrar la imagen en este script ,no como valor de retorno

			//crear imagen
			$img = imagecreatetruecolor($w,$h);

			//crear colores
			$color_fondo  = imagecolorallocate($img,$color_fondo[0],$color_fondo[1],$color_fondo[2]);
			$color_letra1 = imagecolorallocatealpha($img,$color_letra[0],$color_letra[1],$color_letra[2],38);//para transparencia
			$color_letra2 = imagecolorallocate($img,$color_letra[0],$color_letra[1],$color_letra[2]);

			//crear el fondo
			imagefilledrectangle ($img,0,0,$w,$h,$color_fondo);// cuadro imagen

			//reemplace la ruta por la de su propia fuente URI
			$fuente1 = URI_THEME."/font/VeraBd.ttf";
			//$fuente2 = URI_THEME."/font/arial.ttf";

			//posisión de las letras
			$posx = 78; $posy = 120;

			if($letra1 =='W' || $letra1 == 'M'){
				$posx = 71; $posy = 120;
			}

			//añadir el texto
			imagettftext($img,40,0,$posx,$posy,$color_letra1,$fuente1,$letra1);
			imagettftext($img,10,0,127,190,$color_letra2,$fuente1,$letra2);

			//mostrar imagen
			//imagepng($img);

			//guardar imagen en un directorio
			imagepng($img,$destino,0,NULL);

			//liberar memoria
			imagedestroy($img);

			return true;

		}

		function crear_arch_json($uri,$json){
			$fo = fopen($uri,'w+');
				  fwrite($fo,$json);
				  fclose($fo);
		}

		function abrir_arch_json($uri){
			$fgc = file_get_contents($uri);
			return json_decode($fgc,true);
		}

		function eliminar_arch_anterior($fuente,$nombArch){

			//fuente   : (URI)
			//nombArch : img_123 (sin formato o extensión)

			$odir = opendir($fuente);

			while($archivo = readdir($odir)){
				//hace un recorrido por todo los nombres de archivo
				if($archivo!="." && $archivo!=".."){

					$valor = explode(".",$archivo);

					if($valor[0] == $nombArch){
						unlink($fuente.$archivo);															
					}
				}
			}

		}

		function guardar_img_base64($destino,$img64){

			$imagen64 = $img64;

			//eliminamos data:image/png; y base64, de la cadena que tenemos
			list(, $imagen64) = explode(";", $imagen64);
			list(, $imagen64) = explode(",", $imagen64);

			$idImg = fopen($destino,"w+"); 
			fwrite($idImg,base64_decode($imagen64)); 
			fclose($idImg);

			$rtn = array(
				"success"=>true
			);
			return $rtn;
	
		}
		function copiar($fuente,$destino){

			if(is_dir($fuente)){
				$dir=opendir($fuente);
				while($archivo=readdir($dir)){
					if($archivo!="." && $archivo!=".."){
						if(is_dir($fuente."/".$archivo)){
							if(!is_dir($destino."/".$archivo)){
								mkdir($destino."/".$archivo);
							}
							copiar($fuente."/".$archivo, $destino."/".$archivo);
						}
						else{
							copy($fuente."/".$archivo, $destino."/".$archivo);
						}
					}
				}

				closedir($dir);

			}else{

				copy($fuente, $destino);
			}

		}

		function copiar_excepto($fuente,$destino,$excepto=array()){

			$excepto_array = array(".","..");
			$excepto       = array_unique(array_merge($excepto_array,$excepto));

			if(is_dir($fuente)){
				$dir=opendir($fuente);
				while($archivo=readdir($dir)){

					if(!in_array($archivo,$excepto)){
						if(is_dir($fuente."/".$archivo)){
							if(!is_dir($destino."/".$archivo)){
								mkdir($destino."/".$archivo);
							}
							copiar_excepto($fuente."/".$archivo, $destino."/".$archivo,$excepto);
						}else{
							copy($fuente."/".$archivo, $destino."/".$archivo);
						}
					}

				}

				closedir($dir);

			}else{

				copy($fuente, $destino);
			}

		}
		
		//-------------------------------------------------------------//
		//                        extras
		//-------------------------------------------------------------//

		function object_to_array($result)
		{	//un nivel
			//$a = ['name' => 'cp','age' => '2','new' => 'no'];

			$array = array();
			foreach ($result as $key=>$value)
			{
				if (is_object($value))
				{
					$array[$key]=$this->object_to_array($value);
				}
				if (is_array($value))
				{
					$array[$key]=$this->object_to_array($value);
				}
				else
				{
					$array[$key]=$value;
				}
			}
			return $array;
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

		function hex_to_rgb($hex,$modifier=null){

			//input  #eeeeee
			//return 255,255,255 ó array

			if (strlen($hex) != 7) return null;

			$hex = substr($hex,1,strlen($hex)-1);

			$rtn = [hexdec(substr($hex,0,2)),hexdec(substr($hex,2,2)),hexdec(substr($hex,4,2))];

			$str = $rtn[0].','.$rtn[1].','.$rtn[2];

			return ($modifier != 'array')? $str:$rtn;
		}

		//-------------------------------------------------------------//
		//                        sql str
		//-------------------------------------------------------------//

		function add_op($cad,$atrib,$op='=',$op_logica){
			// id=1 AND id=2 AND ...
			// id=1 OR id=2 OR ...
			// var='holas' AND var='que'...
			$str = '';
			foreach($cad as $ind=>$val){
				$str=" ".$str.$atrib.$op.$cad[$ind]." ".$op_logica." ";
			}
			$sl  = strlen($op_logica);
			$str = substr($str,0,-($sl+1));

			return $str;
		}

	}

	/*
		CODE_ERROR
		- 200 imagen muy grande
		- 201 Aceptar formatos
		- 202 Elige un archivo
	*/

	// paralel
?>