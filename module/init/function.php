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
			$nombre    = $datos['nombre'];
			$preguntas = $datos['preg'];
			
			$confirm   = (isset($datos['confirm']))? $datos['confirm']:'off';

			$msj = null;

			$rtn = array(
				'success' => true,
				'update'  => array()
			);

			// si todas las preguntas están vacías enviar mensaje			
			if($this->parents->gn->verificar_respuestas_total_vacias($uniqid,$preguntas)){

				$rtn['update'][] = array(
					'action' => 'notification',
					'value'  => "Se encontró 0 preguntas resueltas.\r Intente resolver algunas."
				);

				return json_encode($rtn);
			}

			// mientras no se confirma el envio se mostrará el modal
			if($confirm == 'on'){				
				// confirmar selección nombre del alumno
				if($nombre != '-Elija-'){
					
					// guardar todo los datos

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

	}

	// Paralel
?>