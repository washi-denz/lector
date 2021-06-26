<?php
	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
?>
	<main>
		
		<div class="main-header">
			
			<div class="bg-gray-100 mb-3">
				<div class="container mx-auto lg:px-32">
					<ul>
						<?php echo $this->interfaz->nav_list('deliver',2);?>
					</ul>    
				</div>
			</div>

		</div>

		<div class="main-body mb-10">
			<div class="container mx-auto lg:px-32">

				NOMBRE  DE LA LECTURA <strong>"<?php echo $this->gn->rtn_titulo_lectura($uniqid); ?>"</strong><br><br>

				<div class="">
					<div class="font-medium text-center md:text-left text-lg mb-3">ENTREGADO</div>				
				</div>

				<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4" id="entregar">
					<?php echo $fn->listaEntregar(['uniqid'=>$uniqid,'type'=>'entregar'],false); ?>
				</div>

				<div class="">
					<div class="font-medium text-center md:text-left text-lg mb-3 mt-3">FALTA ENTREGAR</div>
				</div>

				<div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 mb-3" id="faltaEntregar">
					<?php echo $fn->listaEntregar(['uniqid'=>$uniqid,'type'=>'falta-entregar'],false); ?>
				</div>

			</div>
		</div>

	</main>

<?php
	$script = '
		$(function(){
			updateDeliver(\''.$uniqid.'\');
		});
	';

	$this->content->add_script($script);
	$this->content->add_js(URL_THEME."/js/deliver.js");
	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
    
	// Paralel
?>