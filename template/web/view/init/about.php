<?php

	$this->content->add_class('body','bg-gray-50');

	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
?>	
		<div class="container-main">
			<div class="container">
				<div class="d-flex justify-content-center pt-4">
					<div class="container-register box-shadow">
						<h2 class="text-gray-800 font-semibold text-2xl py-3"><?php echo APP_NAME;?></h2>
						<p class="lead">
							Es más que un repositorio de exámenes virtuales ,desarrollado con el objetivo mejorar los conocimientos del 'todo' a través de los test.
						</p>
						<h3 class="font-semibold text-lg py-3">Sobre el autor <small class="font-weight-normal text-gray-400">author</small></h3>

						<div class="biography">
							<h5 class="text-gray-800 font-semibold text-xl pb-3">Washington Llacsa M.</h5>
							<paralel class="text-blue-500">@IngWashi</paralel>
							<p>
								Me describo como un emprendedor informático , constante , creativo, 
								visionario , apasionado y abierto a las nuevas ideas. <a event="control" event-type="more" event-id="#morebio" class="text-blue-500 cursor-pointer">más</a>
							</p>
							<p class="hidden" id="morebio">
								Soy peruano, nací en Macusani y actualmente resido en Puno, capital de departamento.
								Estudié ingeniería de Sistema en la Universidad Nacional Altiplano - Puno , en el cuál , me especialicé 
								en Sistemas de Información. Y donde comprendí que podemos influir 
								en casi todas las disciplinas , sean científicas o culturales y que la informática solo es una parte 
								de ella. Lo genial de la Informática es que es ‘la herramienta’ que utilizamos para cumplir con 
								los propósitos sistémicos como la optimización de procesos y resolver diversas problemáticas de la sociedad.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
?>