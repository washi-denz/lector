<?php
	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
?>
		<main class="container-main">
		   <div class="container">
		   		<br>
				<div class="alert alert-danger mt-4" role="alert">
					<h4 class="alert-heading text-lg">¡ ERROR 404 ,Página no encontrada !</h4>
					<p class="mb-3">El archivo que está buscando no existe o fue borrada.</p>
					<hr>
					<p class="mt-3">
						<a href="<?php echo URL;?>/init" class="btn btn-success">Ir a Inicio</a>
					</p>
				</div>
		    </div>		    		 
		</main>
<?php
	//require URI_THEME."section/footer.php";
	require URI_THEME."/section/foot.php";
?>