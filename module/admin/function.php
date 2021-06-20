<?php
	class fnAdmin{

		var $idUsuario = 0;

		function __construct(&$parents){

			$this->parents   = $parents;
			$this->idUsuario = $this->parents->session->get("id_user");

		}

		public function listaAlumnos($pag=1,$ajax=true){

			$num = ($pag<=0)? 0 :($pag-1) * REG_MAX;

			$rc = $this->parents->gn->rtn_consulta('*','alumnos','idUsuario='.$this->idUsuario.' LIMIT '.$num.','.REG_MAX);
			
            return $rc;
		}

		public function modalCrearLectura(){

			$rtn = array();

			$form = '
				<form id="formCrearLectura">
					Titulo: <input type="text" name="titulo"><br>
					Descripción: <input type="text" name="descripcion"><br>
					Subir PDF: <input type="file" name="pdf"><br>
				</form>
				<div class="form-error"></div>
			';

			$btn = '<button class="send" data-destine="admin/guardarCrearLectura" data-serialize="formCrearLectura">Crear<button>';

			$modalTitle  = "Crear lectura";
			$modalBody   = $form;
			$modalFooter = $btn;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);

		}

		public function guardarCrearLectura($datos,$FILES){

			// verificar si se subió el archivo
			// luego guardar los datos restantes

			$rtn   = array();
			$idpdf = uniqid(); // generar un id único

			$titulo      = $datos['titulo'];
			$descripcion = $datos['descripcion'];
			$nombreArch  = "pdf_".$idpdf;

			/*
			$titulo = $datos['titulo'];

			$input = [
				'titulo'  => ['value',['msj'=>'El título está vacío.']],
				'archivo' => ['file',['msj'=>'Elija un archivo.',$FILES]]
			];

			$validar = $this->parents->gn->validar($input,$datos);

			if(!$validar['success'])
				return json_encode($validar);
			*/

			// agregar mas datos
			$datos["extPermitidas"] = array("pdf");
			$datos["nombreArch"]    = $nombreArch;
			$datos["destino"]       = URI."/data/pdfs";

			// crear carpeta vacía
			$this->parents->gn->crear_carpeta_vacia($datos['destino']."/".$idpdf);
			
			// guardar el archivo pdf
			$ga = $this->parents->gn->guardar_arch($datos,$FILES);
			
			if($ga['success']){
				$this->parents->sql->insertar("pdfs",array("idpdf" => $idpdf,"nombre" =>$nombreArch,"titulo"=>$titulo,"descripcion"=>$descripcion));
			}

			$rtn = array(
				"success" => true,
				"update"  => array(
					array(
						"selector" => ".form-error",
						"action"   => "html",
						"value"    => $ga['msj']
					)
				)
			);

			return json_encode($datos);
		}


		//-------------------------------------------------------------//
		//                 grupos
		//-------------------------------------------------------------//

		
		

	}
?>