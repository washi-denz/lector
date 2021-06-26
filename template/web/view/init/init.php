<?php

	$this->content->add_class('body','bg-gray-100');

	require URI_THEME."/section/head.php";
	//require URI_THEME."/section/navbar.php";
	
	echo "\n";
?>

	<section class="bg-primary-100">
		<div class="container mx-auto lg:px-32">
			
			<nav class="pt-3">
				<div class="flex">
					<img src="<?php echo URL_THEME; ?>/img/lector.png" class="w-16 h-16">
					<span class="hidden lg:block lg:text-3xl text-white font-medium pt-2 lg:pr-3">ector</span>
					<h3 class="font-medium text-yellow-300 text-md md:text-lg pt-4">Hola estudiante, la lectura de hoy es:</h3>
				</div>
			</nav>
			
			<div class="text-center px-3">
				<h3 class="font-medium text-white text-2xl mb-3">
					<?php echo $this->gn->rtn_titulo_lectura($uniqid); ?>
				</h3>
				<p class="font-light text-gray-100 text-sm mb-3">
					<?php 
						$descripcion = $this->gn->rtn_descripcion_lectura($uniqid);
						echo (strlen($descripcion) > 0)? $descripcion : '';
					?>
				 </p>
			</div>

			<div class="py-3 md:py-6 mx-2">
				<iframe src="<?php echo $this->gn->rtn_src_lectura($uniqid); ?>" class="w-full h-screen"></iframe>
			</div>
			
		</div>
	</section>

	<section>
		<div class="container mx-auto lg:px-32">
			<form id="formEnviarRespuestas">

				<div class="title font-semibold text-gray-500 text-2xl text-center px-3 py-4">Responda las preguntas</div>
				
				<div class="lg:mx-40 md:mx-8 sm:mx-16 mx-3">

				<?php 

					$id   = $this->gn->rtn_id($uniqid);
					$cont = 0;

					if($this->gn->rtn_num_preguntas($id) > 0):

						$preguntas = $fn->listaPreguntas($id,false);

						foreach($preguntas as $pregunta)
						{
							$cont++;
				?>
							<div class="bg-white shadow-sm rounded-md p-2 mb-3" x-data="{openAccordion:false}">
								<div class="flex relative">
									<div>
										<span class="text-yellow-400 text-lg font-bold pr-2 md:pl-2"><?php echo $cont; ?></span>
									</div>
									<div class="w-full mr-6 cursor-pointer" >
										<div class="relative" @click="openAccordion=!openAccordion">
											<h3 class="font-medium pt-0.5">
												<?php echo $pregunta->descripcion; ?>
											</h3>
										</div>
										<div x-show="openAccordion">
											<div class="py-2">
												<textarea class="w-full border" name="preg[<?php echo $pregunta->id; ?>]" placeholder="..."></textarea>
											</div>    
										</div>
										<i class="absolute top-0 right-0 cursor-pointer text-gray-400" @click="openAccordion=!openAccordion" :class="{'icon-up-open-big':!openAccordion,'icon-down-open-big':openAccordion}"></i>
									</div>
								</div>
							</div>

				<?php 
						} 
					else: 
				?>
							<tr>
								<td colspan=4>
									0 No hay preguntas que mostrar.
								</td>
							</tr>

				<?php endif; ?>								
					
				</div>
				
				<div class="title font-semibold text-gray-500 text-2xl text-center px-3 py-4">Enviar respuestas</div>
				
				<div class="lg:mx-40 md:mx-8 sm:mx-16 bg-yellow-300 rounded-md shadow-md text-center p-3 mx-3 mb-8">
					
					<div>
						<!--
						<p class="text-yellow-700 font-light text-sm mb-3">Todas las preguntas fueron resueltas</p>

						<ul>
							<li class="text-yellow-600 cursor-pointer font-medium inline-block bg-yellow-400 rounded-sm mb-3 px-2 py-1">1</li>
							<li class="text-yellow-600 cursor-pointer font-medium inline-block bg-yellow-400 rounded-sm mb-3 px-2 py-1">2</li>
							<li class="text-yellow-600 cursor-pointer border border-yellow-400 font-medium inline-block rounded-sm mb-3 px-2 py-1">3</li>
						</ul>
						--> 
					</div>

					<label class="text-yellow-600 font-medium text-lg block mb-2">Su nombre:</label>
					
					<select class="focus:outline-none border-4 border-yellow-400 rounded-sm px-3 py-2 mx-auto mb-8 block" name="id_alumno">
						<option value="-Elija-">-Elija-</option>
						<?php 
							$alumnos = $fn->listaAlumnos($uniqid); 
							foreach($alumnos as $alumno){
						?>
							<option value="<?php echo $alumno->id; ?>"><?php echo $alumno->nombres.' '.$alumno->apellidos; ?></option>
						<?php }; ?>
					</select>

					<input type="hidden" value="<?php echo $uniqid; ?>" name="uniqid">
					
					<button class="bg-green-500 text-white font-medium focus:outline-none hover:bg-green-600 cursor-pointer transition duration-500 rounded-md px-6 py-2 mb-3 send" data-destine="init/enviarRespuestas" data-serialize="formEnviarRespuestas">ENVIAR</button>

				</div>
				
			</form>
		</div>
	</section>

<?php
	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
	
	// Paralel
?>