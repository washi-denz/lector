<?php
	require URI_THEME."/section/head.php";
	//require URI_THEME."/section/navbar.php";
	echo "\n";

?>
	<div class="container-main">
        <div class="container">

			<div class="container-nav-view">
				<div class="container">
					<?php echo $this->interfaz->str_container_nav_view(2,'deliver'); ?>

					<!---->
					<div class="dropdown float-end">
						<a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-down-open-1"></i></a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
							<a class="dropdown-item"><i class="icon-user"></i></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item send" data-destine="user/salir">Salir</a>
						</div>
					</div>	
					<!----->

				</div>
			</div><br>

			NOMBRE  DE LA LECTURA " ..."<br><br>

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