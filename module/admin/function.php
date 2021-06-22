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

			$uniqid = uniqid(); // generar un id único

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
			$datos['repositorio']   = $uniqid;
			$datos['destino']       = URI.'/data/pdfs/'.$datos['repositorio'];
			
			// guardar el archivo pdf
			$ga = $this->parents->gn->guardar_pdf($datos,$FILES);
			
			if($ga['success']){

				$this->parents->sql->insertar('pdfs',array('uniqid' => $uniqid,'nombre' =>$nombreArch,'titulo'=>$titulo,'descripcion'=>$descripcion,'idUsuario'=>$this->idUsuario));

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

		public function verLectura($datos){

			$uniqid = $datos['uniqid'];

			// redireccionar

			$rtn = array(
				'success' => true,
				'update'  => array(
					array(
						'action' => 'redirection',
						'value'  => URL.'/admin/edit/'.$uniqid
					)
				)
			);

			return json_encode($rtn);

		}

		public function listaPdf($pag=1,$ajax=true){

			$num = ($pag<=0)? 0 :($pag-1) * REG_MAX;

			$rc = $this->parents->gn->rtn_consulta('*','alumnos','idUsuario='.$this->idUsuario.' LIMIT '.$num.','.REG_MAX);
			
            return $rc;
		}

		public function modalCompartir($datos){

			$uniqid = $datos['uniqid'];
			$id_pdf = $this->parents->gn->rtn_id($uniqid);

			$rtn = array();

			// crear link para compartir
			$titulo      = $this->parents->gn->rtn_consulta_unica('titulo','pdfs','id='.$id_pdf);
			$titulo_slug = $this->parents->gn->post_slug($titulo);
			$link = URL.'/init/view/'.$titulo_slug.'/'.$uniqid;

			$form = '
				<form>
					Link: <input type="text" value="'.$link.'" class="w-full"><br>
				</form>			
			';

			$modalTitle  = "Compartir recurso PDF";
			$modalBody   = $form;
			$modalFooter = null;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);

		}

		//-------------------------------------------------------------//
		//                           editar
		//-------------------------------------------------------------//

		public function modalModificarTitulo($datos){
			
			$uniqid = $datos['uniqid'];

			$rtn = array();

			$titulo = $this->parents->gn->rtn_consulta_unica('titulo','pdfs',"uniqid='".$uniqid."'");

			$form = '
				<form id="formModificarTitulo">
					<input type="text" value="'.$titulo.'" name="titulo">
				</form>
			';

			$data = htmlspecialchars(json_encode(array('uniqid'=>$uniqid)));
			$btn = '<button class="send" data-destine="admin/guardarModificarTitulo" data-serialize="formModificarTitulo" data-data="'.$data.'">Guardar<button>';

			$modalTitle  = "Modificar título";
			$modalBody   = $form;
			$modalFooter = $btn;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);
		}

		public function guardarModificarTitulo($datos){

			$uniqid       = $datos['uniqid'];
			$encriptar_id = $this->parents->gn->encriptar_id($uniqid);

			$rtn = array(
				'success' => true,
				'update'  => array()
			);
			
			$mr = $this->modificarRegistro('titulo',$datos);

			if(true){

				// mostrar título
				$rtn['update'][] = array(
					'selector' => '.h_'.$encriptar_id,
					'action'   => 'html',
					'value'    => $datos['titulo']
				);

				// close modal
				$rtn['update'][] = array(
					'id'     => 'modalPrincipal',
					'action' => 'closeModal'
				);

				// mensaje con delay
				$rtn['update'][] = array(
					'action'   => 'notification',
					'delay'    => 1000,
					'value'    => "El título se cambió correctamente."
				);

			}

			return json_encode($rtn);
		}

		public function modalModificarDescripcion($datos){
			
			$uniqid = $datos['uniqid'];

			$rtn = array();

			$descripcion = $this->parents->gn->rtn_consulta_unica('descripcion','pdfs',"uniqid='".$uniqid."'");

			$form = '
				<form id="formModificarDescripcion">
					<input type="text" value="'.$descripcion.'" name="descripcion">
				</form>
			';

			$data = htmlspecialchars(json_encode(array('uniqid'=>$uniqid)));
			$btn = '<button class="send" data-destine="admin/guardarModificarDescripcion" data-serialize="formModificarDescripcion" data-data="'.$data.'">Guardar<button>';

			$modalTitle  = "Modificar descripción";
			$modalBody   = $form;
			$modalFooter = $btn;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);
		}

		public function guardarModificarDescripcion($datos){

			$uniqid       = $datos['uniqid'];
			$encriptar_id = $this->parents->gn->encriptar_id($uniqid);

			$rtn = array(
				'success' => true,
				'update'  => array()
			);
			
			$mr = $this->modificarRegistro('descripcion',$datos);

			if(true){

				// mostrar título
				$rtn['update'][] = array(
					'selector' => '.ta_'.$encriptar_id,
					'action'   => 'html',
					'value'    => $datos['descripcion']
				);

				// close modal
				$rtn['update'][] = array(
					'id'     => 'modalPrincipal',
					'action' => 'closeModal'
				);

				// mensaje con delay
				$rtn['update'][] = array(
					'action'   => 'notification',
					'delay'    => 1000,
					'value'    => "La descripción se cambió correctamente."
				);

			}

			return json_encode($rtn);
		}

		public function modalModificarPDF($datos){

			$uniqid = $datos['uniqid'];

			// crear modal para modificar pfd
			$form = '
				<form id="formModificarPdf">
					Subir PDF: <input type="file" name="archivo"><br>
					<input type="hidden" name="uniqid" value="'.$uniqid.'">
				</form>
				<div class="form-error"></div>
			';

			$btn = '<button class="send" data-destine="admin/actualizarModificarPDF" data-serialize="formModificarPdf">Cambiar PDF<button>';
		
			$modalTitle  = "Cambiar PDF";
			$modalBody   = $form;
			$modalFooter = $btn;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);
		}

		public function actualizarModificarPDF($datos,$FILES){

			// verificar si se subió el archivo
			// luego modifica pdf

			// [aquí verificar subida de archivo...]

			$uniqid      = $datos['uniqid'];
			$id_pdf      = $this->parents->gn->rtn_id($uniqid);
			$nombreArch  = $this->parents->gn->rtn_nombre_arch($FILES['archivo']['name']);

			$encriptar_id = $this->parents->gn->encriptar_id($uniqid);

			$rtn = array(
				"success" => true,
				"update"  => array()
			);

			// agregar más datos
			$datos['extPermitidas'] = array('pdf');
			$datos['nombreArch']    = $nombreArch;
			$datos['repositorio']   = $uniqid;
			$datos['destino']       = URI.'/data/pdfs/'.$datos['repositorio'];
			
			// guardar el archivo pdf
			$gp = $this->parents->gn->guardar_pdf($datos,$FILES);
			
			if($gp['success']){

				// eliminar el archivo anterior del actual directorio
				$nombreArchActual = $this->parents->gn->rtn_consulta_unica('nombre','pdfs','id='.$id_pdf);
				$this->parents->gn->eliminar_arch_directorio($datos['destino'],$nombreArchActual);

				// modificar datos
				$this->parents->sql->modificar('pdfs',
												array(
													'nombre'    => $nombreArch,
												),
												array(
													'id' => $id_pdf
												)
											);

				// close modal
				$rtn['update'][] = array(
					'id'     => 'modalPrincipal',
					'action' => 'closeModal' 
				);

				// mostrar pdf
				$rtn['update'][] = array(
					'selector' => '.iframe_'.$encriptar_id,
					'action'   => 'attr',
					'value1'   => 'src',
					'value2'   => $this->parents->gn->rtn_src_lectura($uniqid)
				);

				// notificar
				$rtn['update'][] = array(
					'action'  => 'notification',
					'value'   => "Se modificó correctamente."
				);			

			}else{
				// mostrar error en la subida del archivo
				$rtn['update'][] = array(
					'selector' => '.form-error',
					'action'   => 'html',
					'value'    => $gp['msj']
				);
			}

			return json_encode($rtn);
		}

		public function modalAgregarPregunta($datos){
			
			$uniqid = $datos['uniqid'];

			// crear modal para modificar pfd
			$form = '
				<form id="formAgregarPregunta">
					Pregunta: <input type="text" name="preg" class="w-full border"><br>
					<input type="hidden" name="uniqid" value="'.$uniqid.'">
				</form>
				<div class="form-error"></div>
			';

			$btn = '<button class="send" data-destine="admin/guardarAgregarPregunta" data-serialize="formAgregarPregunta">Agregar pregunta<button>';
		
			$modalTitle  = "Cambiar PDF";
			$modalBody   = $form;
			$modalFooter = $btn;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);

		}

		public function guardarAgregarPregunta($datos){

			$uniqid = $datos['uniqid'];
			$preg   = $datos['preg'];

			$id_pdf = $this->parents->gn->rtn_id($uniqid);

			$rtn = array(
				'success' => true,
				'update'  => array()
			);

			//verificamos valor
			if($this->parents->gn->verifica_valor($preg)){

				//agregar dato
				$this->parents->sql->insertar('preguntas',array('idPdf'=>$id_pdf,'descripcion'=>$preg));

				// notificar
				$rtn['update'][] = array(
					'action'  => 'notification',
					'delay'   => 1000,
					'value'   => "Se agregó correctamente."
				);

				// close modal
				$rtn['update'][] = array(
					'id'     => 'modalPrincipal',
					'action' => 'closeModal' 
				);

				// mostrar lista				
				$rtn['update'][] = array(
					"id"     => "listaEditPreguntas",
					"action" => "html",
					"value"  => $this->mostrarListaEditPreguntas(array('uniqid' => $uniqid),1,false)
				);
					

			}else{
				// notificar
				$rtn['update'][] = array(
					'action' => 'notification',
					'value'  => "Ingrese un valor"
				);
			}

			return json_encode($rtn);
		}

		public function mostrarListaEditPreguntas($datos,$pag=1,$ajax=true){

			$uniqid = $datos['uniqid'];

			$str    = null;
			$id_pdf = $this->parents->gn->rtn_id($uniqid);

			$num = 0;

			$rc = $this->parents->gn->rtn_consulta('*','preguntas','idPdf='.$id_pdf.' ORDER BY registro DESC');

			foreach($rc as $obj){
				$num++;
				$obj->uniqid = $uniqid;				
				$str .= $this->parents->interfaz->mostrar_lista('edit-preguntas',$obj,['num'=>$num]);
			}

			$rtn = array(
				'success' => true,
				'update'  => array(
					array(
						'id'     => 'listaEditPreguntas',
						'action' => 'html',
						'value'  => $str
					)
				)
			);

			return ($ajax)? json_encode($rtn) : $str;

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
					SELECT uniqid,nombre,titulo,descripcion FROM pdfs 
						WHERE idUsuario=".$this->idUsuario." 
					ORDER BY registro DESC LIMIT ".$num.",".REG_MAX.";
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
						$str .= $this->parents->interfaz->mostrar_lista('crear-lectura',$obj,['num'=>$num]);					
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

		function modificarRegistro($tipo,$cad=[]){

			$rtn = array(
				'success' => false
			);

			if($tipo == 'titulo')
			{
				$id = $this->parents->gn->rtn_id($cad['uniqid']);

				if($this->parents->sql->modificar('pdfs',array('titulo'=>$cad['titulo']),array('id'=>$id))){
					$rtn['success'] = true;
				}
			}
			if($tipo == 'descripcion')
			{
				$id = $this->parents->gn->rtn_id($cad['uniqid']);

				if($this->parents->sql->modificar('pdfs',array('descripcion'=>$cad['descripcion']),array('id'=>$id))){
					$rtn['success'] = true;
				}
			}

			return $rtn;
		}
		

	}
?>