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
					Subir PDF: <input type="file" name="archivo"><br>
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

			$idpdf = uniqid(); // generar un id único

			$titulo      = $datos['titulo'];
			$descripcion = $datos['descripcion'];
			$nombreArch  = $this->parents->gn->rtn_nombre_arch($FILES['archivo']['name']);

			$rtn = array(
				"success" => true,
				"update"  => array()
			);

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

			// agregar más datos
			$datos['extPermitidas'] = array('pdf');
			$datos['nombreArch']    = $nombreArch;
			$datos['repositorio']   = $idpdf;
			$datos['destino']       = URI.'/data/pdfs/'.$datos['repositorio'];
			
			// guardar el archivo pdf
			$ga = $this->parents->gn->guardar_pdf($datos,$FILES);
			
			if($ga['success']){

				$this->parents->sql->insertar('pdfs',array('idpdf' => $idpdf,'nombre' =>$nombreArch,'titulo'=>$titulo,'descripcion'=>$descripcion,'idUsuario'=>$this->idUsuario));

				// close modal
				$rtn['update'][] = array(
					'id'     => 'modalPrincipal',
					'action' => 'closeModal' 
				);
				// notificar
				$rtn['update'][] = array(
					'action'  => 'notification',
					'value'   => "Se creó correctamente."
				);

				// mostrar lista de crear lectura
				
				$rtn['update'][] = array(
					"id"     => "listaCrearLectura",
					"action" => "html",
					"value"  => $this->mostrarLista('crear-lectura',1,false)
				);				

			}else{
				// mostrar error en la subida del archivo
				$rtn['update'][] = array(
					'selector' => '.form-error',
					'action'   => 'html',
					'value'    => $ga['msj']
				);
			}

			return json_encode($rtn);
		}


		//-------------------------------------------------------------//
		//                      generalidades
		//-------------------------------------------------------------//

		function mostrarLista($tipo,$pag=1,$ajax=true){

			$rtn = array();
			$str = '';
			$num = ($pag<=0)? 0 :($pag-1)*REG_MAX;
			
			if($tipo == 'crear-lectura')
			{
				$query = "
					SELECT idpdf,nombre,titulo,descripcion FROM pdfs 
						WHERE idUsuario=".$this->idUsuario." 
					ORDER BY modific DESC LIMIT ".$num.",".REG_MAX.";
				";
			}

			if($tipo == 'alumnos')
			{
				$query = "
					SELECT e.idExamen,e.idex,e.titulo,e.img,e.estilo,e.estado,e.nivel,e.nota_base,e.idUsuario,ec.publicar FROM examen e 
						INNER JOIN examen_config ec ON e.idExamen=ec.idExamen 
					WHERE ( ec.publicar='SI' AND ec.eliminar='NO') AND e.idUsuario=".$this->idUsuario." ORDER BY e.publicacion DESC LIMIT ".$num.",".REG_MAX.";
				";
			}

			if($this->parents->sql->consulta($query)){

				$resultado = $this->parents->sql->resultado;

				if(count($resultado) > 0){

					foreach($resultado as $obj){
						$num++;					
						$str .= $this->parents->interfaz->mostrar_lista('crear-lectura',$obj,['num'=>$num,'tipo'=>$tipo]);					
					}

					$rtn = array(
						"success" => true,
						"update"  => array(
							array(
								"id"     => "listaCrearLectura",
								"action" => "html",
								"value"  => $str
							)
						)
					);

				}else{

					$msj = "0 No se encontraron registros para mostrar.";
					$str = '
						<tr>
							<td colspan=4>
								'.$this->parents->interfaz->gn('registro-vacio',null,['titulo' =>$msj]).'
							</td>
						</tr>
					';

					$rtn = array(
						"success"=>true,
						"update"=>array(
							array(
								"action" => "notification",
								"type"   => "notific-bottom",
								"value"  => $str
							)
						)
					);
				}		
			}

			return ($ajax)? json_encode($rtn) : $str;
		}
		

	}
?>