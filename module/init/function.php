<?php
	class fnInit{
		var $idUsuario = 0;

		function __construct(&$parents){
			$this->parents   = $parents;

			if($this->parents->session->check_login()){
				$this->idUsuario = $this->parents->session->get("id_user");
			}
		}

		public function listaPreguntas($id,$ajax=true){

			$rc = $this->parents->gn->rtn_consulta('*','preguntas','idPdf='.$id);
			
            return $rc;
		}

		public function enviarRespuestas($datos){

			$uniqid    = $datos['uniqid'];
			$id_alumno = ($datos['id_alumno']!='-Elija-')? $datos['id_alumno']:0;
			$preguntas = $datos['preg'];

			$id_pdf     =  $this->parents->gn->rtn_id($uniqid);
			
			$confirm   = (isset($datos['confirm']))? $datos['confirm']:'off';

			$msj = null;

			$rtn = array(
				'success' => true,
				'update'  => array()
			);

			// si todas las preguntas están vacías enviar mensaje			
			if($this->parents->gn->verificar_respuestas_total_vacias($id_pdf,$preguntas)){

				$rtn['update'][] = array(
					'action' => 'notification',
					'type'   => 'notific-top',
					'value'  => "Se encontró 0 preguntas resueltas.\r Intente resolver algunas."
				);

				return json_encode($rtn);
			}

			// el envio de respuestas es una sola vez		
			if($this->parents->gn->verificar_envio_respuestas($id_pdf,$id_alumno)){

				$rtn['update'][] = array(
					'action' => 'notification',
					'type'   => 'notific-top',
					'value'  => "Las respuestas para este recurso ya fueron enviados."
				);

				return json_encode($rtn);
			}

			// mientras no se confirma el envio se mostrará el modal
			if($confirm == 'on'){				
				// confirmar selección nombre del alumno
				if($id_alumno != 0 ){//mejorarrrrrr

					// guardar todo los datos de las respuestas
					foreach($preguntas as $descripcion){

						$this->parents->sql->insertar('respuestas',
												array(
													'idPdf'       => $id_pdf,
													'descripcion' => $descripcion,
													'idAlumno'    => $id_alumno
													)
												);
					}

					// redirección con delay
					$rtn['update'][] = array(
						'action' => 'notification',
						'value'  => "Redireccionando..."
					);

					$rtn['update'][] = array(
						'action' => 'redirection',
						'delay'  => 2000,
						'value'  => URL.'/init/send'
					);

				}else{
					$rtn['update'][] = array(
						'action' => 'notification',
						'value'  => "Elija su nombre correctamente."
					);
				}

			}else{

				$data = htmlspecialchars(json_encode(array('confirm'=>'on')));
				$btn = '<button class="bg-blue-500 text-white font-medium send" data-destine="init/enviarRespuestas" data-data="'.$data.'" data-serialize="formEnviarRespuestas">Confirmar envío</button>';

				$msj1 = 'Se encontrarón respuestas vacías. <br> ¿ Enviar de todas formas ?';
				$msj2 = 'Confirme el envío de las respuestas.';

				// verificar respuestas vacias
				if($this->parents->gn->verificar_respuestas_vacias($preguntas))
					$msj = $msj1;
				else
				    $msj = $msj2;


				$modalTitle  = "datos";
				$modalBody   = $msj;
				$modalFooter = $btn;
	
				$rtn = $this->parents->interfaz->rtn_array_modal_principal($modalTitle,$modalBody,$modalFooter);
			}


			return json_encode($rtn);
	
		}

		public function listaAlumnos(){

			$idUsuario = 1; // configurar

			// se mostrará toda la lista de alumnos del usuario 1

			$rc = $this->parents->gn->rtn_consulta('*','alumnos','idUsuario='.$idUsuario);
            return $rc;
		}

	}

	// Paralel
?>