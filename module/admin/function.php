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

					<div class="mb-3">
						<label class="form-label">Título:</label>
						<input type="text" name="titulo" class="form-control">
					</div>
					<div class="mb-3">
						<label class="form-label">Descripción:</label>
						<textarea rows="3" name="descripcion" class="form-control"></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">Subir PDF:</label>
						<input type="file" name="archivo" class="form-control">
					</div>

				</form>
				<div class="form-error"></div>
			';

			$btn = '<button class="btn btn-primary send" data-destine="admin/guardarCrearLectura" data-serialize="formCrearLectura">Crear lectura<button>';

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

		public function listaEntregar($datos,$ajax=true){

			// odtener la lista de usuarios del recurso enviado
			// comparar con respuestas y renerar dos lista de idUsuarios entregados y no entregados aún

			// mostrar resulttados por separado...

			$uniqid     = $datos['uniqid'];
			$id_pdf     = $this->parents->gn->rtn_id($uniqid);
			$id_usuario = $this->parents->gn->rtn_id_usuario($uniqid);

			$str  = null;
			$str1 = null;
			$str2 = null;

			$rtn =  array(
				'success' => true,
				'update'  => array()
			);

			// lista de los ids de los alumnos
			$id_alumnos = $this->parents->gn->rtn_consulta('id,nombres,apellidos','alumnos','idUsuario='.$id_usuario);

			// lista de ids de los alumnos que respondieron las preguntas
			$id_alumnos_rpta = $this->parents->gn->rtn_ids_alumnos_respuestas($id_pdf);

			// comparamos los ids de los alumnos con los idAlumno  de la tabla respuestas
			foreach($id_alumnos as $obj){
				if(in_array($obj->id,$id_alumnos_rpta)){
					// alumnos que entregaron respuestas					
					//$str1 .= $obj->id.'entregado<br> '.$obj->nombres;
					$str1 .= $this->parents->interfaz->mostrar_lista('card-entrega',$obj);

				}else{
					// alumnos que no la entregaron
					$str2 .= $this->parents->interfaz->mostrar_lista('card-entrega',$obj);
				}

			}

			// respuesta ajax
			$rtn['update'][] = array(
				'id'     => 'entregar',
				'action' => 'html',
				'value'  => $str1
			);

			$rtn['update'][] = array(
				'id'     => 'faltaEntregar',
				'action' => 'html',
				'value'  => $str2
			);

			// respuesta no ajax
			$str = (isset($datos['type']) && $datos['type']=='entregar')? $str1:$str2;

			
			return ($ajax)? json_encode($rtn):$str;
		}

		public function modalVerRespuestaEntrega($datos){

			$uniqid     = $datos['uniqid'];
			$id_alumno  = $datos['id_alumno'];

			$id_pdf     = $this->parents->gn->rtn_id($uniqid);
			
			$form = '
				<form>
					Link: <input type="text" value="'.$link.'" class="w-full"><br>
				</form>			
			';

			$modalTitle  = "Compartir recurso PDF";
			$modalBody   = $form;
			$modalFooter = null;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);
		}

		public function modalDetalleEntrega($datos){

			$uniqid     = $datos['uniqid'];
			$id_alumno  = $datos['id_alumno'];

			$id_pdf     = $this->parents->gn->rtn_id($uniqid);
			
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

		public function modalModificarPDF($datos){

			$uniqid = $datos['uniqid'];

			// crear modal para modificar pfd
			$form = '
				<form id="formModificarPdf">
					<div>
						<input type="file" name="archivo" class="form-control">
					</div>
					<input type="hidden" name="uniqid" value="'.$uniqid.'">
				</form>
				<div class="form-error"></div>
			';

			$btn = '<button class="btn btn-primary send" data-destine="admin/actualizarModificarPDF" data-serialize="formModificarPdf">Cambiar PDF<button>';
		
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
					<div>
						<textarea rows="2" class="form-control" name="preg" placeholder="Nueva pregunta"></textarea>
					</div>
					<input type="hidden" name="uniqid" value="'.$uniqid.'">
				</form>
				<div class="form-error"></div>
			';

			$btn = '<button class="btn btn-primary send" data-destine="admin/guardarAgregarPregunta" data-serialize="formAgregarPregunta">Agregar pregunta<button>';
		
			$modalTitle  = "Agregar pregunta";
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

		public function modalActualizarCampo($datos){

			// modal sólo para actualizar un campo

			$rtn  = array();
			$tipo = $datos['type'];

			$form  = null;
			$title = null;
			$btn   = null;


			if($tipo == 'titulo-pdf'){

				$uniqid = $datos['uniqid'];
	
				$titulo = $this->parents->gn->rtn_consulta_unica('titulo','pdfs',"uniqid='".$uniqid."'");
	
				$form = '
					<form id="formActualizarCampo">
						<div>
							<input type="text" name="titulo" class="form-control" value="'.$titulo.'">
						</div>
					</form>
				';

				$title = "Modificar título";
				$data = htmlspecialchars(json_encode($datos));
				$btn = '<button class="btn btn-primary send" data-destine="admin/actualizarCampo" data-serialize="formActualizarCampo" data-data="'.$data.'">Guardar<button>';	

			}

			if($tipo == 'descripcion-pdf'){

				$uniqid = $datos['uniqid'];
	
				$descripcion = $this->parents->gn->rtn_consulta_unica('descripcion','pdfs',"uniqid='".$uniqid."'");
	
				$form = '
					<form id="formActualizarCampo">						
						<div>
							<textarea rows="4" class="form-control" name="descripcion" placeholder="Descripción...">'.$descripcion.'</textarea>
						</div>
					</form>					
				';

				$title = "Modificar descripción";
					
				$data = htmlspecialchars(json_encode($datos));
				$btn = '<button class="btn btn-primary send" data-destine="admin/actualizarCampo" data-serialize="formActualizarCampo" data-data="'.$data.'">Guardar<button>';	

			}

			if($tipo == 'pregunta'){

				$id_preg = $datos['id_preg'];
	
				$descripcion = $this->parents->gn->rtn_consulta_unica('descripcion','preguntas','id='.$id_preg);
	
				$form = '
					<form id="formActualizarCampo">						
						<div>
							<textarea rows="2" class="form-control" name="descripcion" placeholder="Descripción">'.$descripcion.'</textarea>
						</div>
					</form>					
				';

				$title = 'Modificar descripción <span class="text-form-top">Pregunta</span>';

				unset($datos['url']);
				$data = htmlspecialchars(json_encode($datos));
				$btn = '<button class="btn btn-primary send" data-destine="admin/actualizarCampo" data-serialize="formActualizarCampo" data-data="'.$data.'">Guardar<button>';	

			}
			
			// crear modal

			$modalTitle  = $title;
			$modalBody   = $form;
			$modalFooter = $btn;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);
		}

		public function actualizarCampo($datos){

			$tipo = $datos['type'];

			$rtn = array(
				'success' => true,
				'update'  => array()
			);

			$msj =null;

			if($tipo == 'titulo-pdf')
			{
				$titulo = $datos['titulo'];
				$uniqid = $datos['uniqid'];

				$id = $this->parents->gn->rtn_id($uniqid);

				if($this->parents->gn->verifica_valor($titulo)){
					if($this->parents->sql->modificar('pdfs',array('titulo'=>$titulo),array('id'=>$id))){

						$encriptar_id = $this->parents->gn->encriptar_id($uniqid);
	
						// mostrar título
						$rtn['update'][] = array(
							'selector' => '.t_'.$encriptar_id,
							'action'   => 'html',
							'value'    => $titulo
						);

						// respuesta
						$rtn['success'] = true;
	
						$msj = "El título se cambió correctamente.";
					}
				}else{
					$msj = "Ingrese un valor";
					// respuesta
					$rtn['success'] = false;
				}
					
			}

			if($tipo == 'descripcion-pdf'){

				$descripcion = $datos['descripcion'];
				$uniqid      = $datos['uniqid'];

				$id = $this->parents->gn->rtn_id($uniqid);

				if($this->parents->gn->verifica_valor($descripcion)){
					if($this->parents->sql->modificar('pdfs',array('descripcion'=>$descripcion),array('id'=>$id))){

						$encriptar_id = $this->parents->gn->encriptar_id($uniqid);
	
						// mostrar título
						$rtn['update'][] = array(
							'selector' => '.d_'.$encriptar_id,
							'action'   => 'html',
							'value'    => $descripcion
						);

						// respuesta
						$rtn['success'] = true;
	
						$msj = "La descripción se cambió correctamente.";
					}
				}else{
					$msj = "Ingrese un valor";
					// respuesta
					$rtn['success'] = false;
				}	
			}

			if($tipo == 'pregunta'){

				$uniqid      = $datos['uniqid'];
				$descripcion = $datos['descripcion'];
				$id_preg     = $datos['id_preg'];

				if($this->parents->gn->verifica_valor($descripcion)){
					if($this->parents->sql->modificar('preguntas',array('descripcion'=>$descripcion),array('id'=>$id_preg))){
						
						$encriptar_id = $this->parents->gn->encriptar_id($uniqid,$id_preg);

						// mostrar título
						$rtn['update'][] = array(
							'selector' => '.d_'.$encriptar_id,
							'action'   => 'html',
							'value'    => $descripcion
						);

						// respuesta
						$rtn['success'] = true;
	
						$msj = "La descripción se cambió correctamente.";
					}
				}else{
					$msj = "Ingrese un valor";
					// respuesta
					$rtn['success'] = false;
				}	
			}

			if($rtn['success']){
				// close modal
				$rtn['update'][] = array(
					'id'     => 'modalPrincipal',
					'action' => 'closeModal'
				);
			}

			// mensaje con delay
			$rtn['update'][] = array(
				'action'   => 'notification',
				'delay'    => 1000,
				'value'    => $msj
			);

			return json_encode($rtn);
		}
		
		public function modalEliminarRegistro($datos){

			$rtn  = array();
			$tipo = $datos['type'];

			$form  = null;
			$title = null;
			$btn   = null;

			if($tipo == 'lectura'){
				//...
			}

			if($tipo == 'pregunta'){

				$form = ' 
					<h3 class="text-lg">Confirme si desea eliminar el registro actual.</h3>
					<p class="text-sm text-gray-700">Ojo: Los registros eliminados no podrán ser recuperados.</p>
				';

				$title = 'Eliminar registro <span class="text-form-top">Pregunta</span>';

				// eliminamos url
				unset($datos['url']);

				// añadiendo confirmación
				$datos['confirm'] = 'on';

				$data = htmlspecialchars(json_encode($datos));
				$btn = '<button class="btn btn-primary send" data-destine="admin/eliminarRegistro" data-data="'.$data.'">Confirmar<button>';	
			}
		
			// crear modal

			$modalTitle  = $title;
			$modalBody   = $form;
			$modalFooter = $btn;

			$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);

			return json_encode($rtn);

		}
		
		public function eliminarRegistro($datos){

			$tipo    = $datos['type'];
			$confirm = (isset($datos['confirm']) && $datos['confirm'] == 'on')? true:false;

			$rtn = array(
				'success' => true,
				'update'  => array()
			);

			$msj =null;

			if($tipo == 'lectura'){
				//...
			}

			if($tipo == 'pregunta'){

				$uniqid      = $datos['uniqid'];
				$id_preg     = $datos['id_preg'];

				if($confirm && $this->parents->sql->eliminar('preguntas',array('id'=>$id_preg))){						
					
					// mostrar lista				
					$rtn['update'][] = array(
						"id"     => "listaEditPreguntas",
						"action" => "html",
						"value"  => $this->mostrarListaEditPreguntas(array('uniqid' => $uniqid),1,false)
					);

					// respuesta
					$rtn['success'] = true;

					$msj = "Se eliminó correctamente.";
					
				}else{
					// respuesta
					$rtn['success'] = false;
				}	
			}
			
			if($rtn['success']){

				// close modal
				$rtn['update'][] = array(
					'id'     => 'modalPrincipal',
					'action' => 'closeModal'
				);
				
				// mensaje con delay
				$rtn['update'][] = array(
					'action'   => 'notification',
					'delay'    => 1000,
					'value'    => $msj
				);
			}

			return json_encode($rtn);
		}

	}
?>