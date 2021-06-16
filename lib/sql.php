<?php
	class sql
	{
		var $resultado;
		var $db;
		var $id=0;
		
		public function __construct()
		{
			$this->Conectar();
			//$this->conexion_remota();
		}
		
		private function Conectar()
		{
			try{

				$this->db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET, DB_USER, DB_PASS);

				$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ); //$resultado un array en formato Object
				//$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				//$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			}
			catch(Exception $e)
			{
				echo "SERVIDOR DE BASE DE DATOS NO DISPONIBLE";
				exit();
			}
		}

		private function conexion_remota(){
			try{
				$this->db = new PDO(DB_TYPE_EXTERNAL.':host='.DB_HOST_EXTERNAL.';dbname='.DB_NAME_EXTERNAL.';charset='.DB_CHARSET_EXTERNAL,DB_USER_EXTERNAL,DB_PASS_EXTERNAL);
				$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);	

			}catch(Exception $e){
				echo "SERVIDOR DE BASE DE DATOS DE CLASSPIECE NO DISPONIBLE";
				exit();
			}
		}
		
		function insertar($tabla,$datos)//$datos es de Tipo Array array(idUnidad=>1,nombre=>Josue)
		{
			$keystr = "";
			$valstr = "";
			$sql    = "";

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				foreach($query->fetchAll() as $obj)
				{
					if(isset($datos[$obj->Field]))
					{
						$keystr.=$obj->Field.",";
						$valstr.="'".$datos[$obj->Field]."'".",";
					}
				}
			}
			$keystr=substr($keystr,0,-1);
			$valstr=substr($valstr,0,-1);

			$sql="INSERT INTO $tabla ($keystr) VALUES ($valstr);";
			$query = $this->db->prepare($sql);
			if( $query->execute() )
			{
				$query = $this->db->prepare("SELECT LAST_INSERT_ID() id;");
				if( $query->execute() )
				{
					$this->id = $query->fetch()->id;
				}
				return true;
			}
			return false;
		}
		
		function insertarAll( $tabla, $datos )//$datos es de Tipo Array array( array(idUnidad=>1,nombre=>Josue),array(....))
		{
			$keystr = array();
			$valstr = array();
			$values = array();
			$sql="";

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				$i=0;
				foreach($query->fetchAll() as $obj)
				{
					///$keystr.=$obj->Field.",";
					$keystr[]=$obj->Field;
					foreach( $datos as $i => $dato )
					{
						if( isset($dato[$obj->Field]) )
						{
							$valstr[$i][]="'".$dato[$obj->Field]."'";
						}
						else
						{
							$valstr[$i][]="null";
						}
					}
				}
				foreach( $valstr as $v )
				{
					$values[] = implode(",", $v);
				}
			}
			if( count($values) <= 0 )
			{
				return false;
			}
			$keystr = implode( ",", $keystr );
			$valstr = implode( "),(", $values );

			$sql="INSERT INTO $tabla ($keystr) VALUES ($valstr);";
			
			$query = $this->db->prepare($sql);
			if( $query->execute() )
			{

				return true;
			}
			return false;
		}

		function modificar($tabla,$datos,$condicion,$op='AND')
		{
			$keystr="";
			$valstr="";
			$sql="UPDATE $tabla SET ";
			$where="";

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				foreach($query->fetchAll() as $obj)
				{
					if(isset($datos[$obj->Field]))
					{
						$keystr.=$obj->Field.",";
						$valstr.="'".$datos[$obj->Field]."'".",";
					}
					if(isset($datos[$obj->Field]))
					{
						$sql.=$obj->Field."='".$datos[$obj->Field]."', ";
					}
					if(isset($condicion[$obj->Field]))
					{
						$where.=$obj->Field."='".$condicion[$obj->Field]."' ".$op." ";
					}
				}
			}
			$sql = substr($sql,0,-2);
			$sql.= " WHERE ".$where;
			$sql = substr( $sql, 0, -1*(strlen($op)+2) );
			$sql = $sql.";"; 
							
			$query = $this->db->prepare($sql);

			if($query->execute())
			{
				return true;
			}
			return false;
		}

		function eliminar($tabla,$condicion,$op='OR')
		{
			$sql="DELETE FROM $tabla WHERE ";

			$query = $this->db->prepare("DESCRIBE $tabla;");
			if($query->execute())
			{
				foreach($query->fetchAll() as $obj)
				{
					if(isset($condicion[$obj->Field]))
					{
						$sql.=$obj->Field."='".$condicion[$obj->Field]."' ".$op." ";
					}
				}
			}

			$sql = substr( $sql, 0, -1*(strlen($op)+2) );

			$query = $this->db->prepare($sql);
			if($query->execute())
			{
				return true;
			}
			return false;
		}

		function consulta($sql)
		{
			$query = $this->db->prepare($sql);
			if($query->execute())
			{
				$this->resultado = $query->fetchAll();
				return true;
			}
			return false;
		}

		function consulta_remota($sql){

			$query = $this->db->prepare($sql);

			if($query->execute()){
				$this->resultado = $query->fetchAll();
				return true;
			}
			return false;
		}

		function consulta_segura($sql,$datos=array()){

			//Para consultas con inyeccion SQL

			$query = $this->db->prepare($sql);

			if($query->execute($datos)){
				$this->resultado = $query->fetchAll();
				return true;
			}
			return false;
		}

		function LAST_INSERT_ID()
		{
			return  $this->id;
		}
	}
/*
	Nota:
	Contar los resultados con mysql_num_rows($query) > 0 para controlar los resultados vacios.
*/