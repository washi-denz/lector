<?php

	$this->content->add_css(URL_THEME."/resource/plugins/pushbar/pushbar.css");
	$this->content->add_css(URL_THEME."/css/mod/admin/admin.css");

	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";

	echo "\n";
	$pag  = (isset($_GET["pag"]))? $_GET["pag"] : 1;
?>
		<main class="container-main">

			<div class="main-header">

				<div class="container-nav-view">
					<div class="container">
						<?php echo $this->interfaz->str_container_nav_view(4,'list',5);?>
					</div>
				</div>

				<div class="bar-header">
					<div class="container">
						<strong>Mis admisiones :</strong>
						<?php
						
							$array = array(
								"active" => 0,
								"item"   => array(
									array(
										"titulo" => "mejor puntuación",
										"url"    => URL."admin/list_test/resolve?view=me"			
									),
									array(
										"titulo" => "peor puntuación",
										"url"    => URL."admin/list_test/resolve?view=pints",							
										"active" => false
									),
									array(
										"divide" => true
									),
									array(
										"titulo" => "ver estadística",
										"url"    => URL."admin/list_test/resolve?view=me"			
									)
								)
							);

							$ato = $this->gn->array_to_object($array,'n');
							echo $this->interfaz->str_dropdown($ato); 

						?>	
					</div>
				</div>

			</div><!--/main-header-->
			<div class="main-body">
				<div class="container">

					<table class="table">
						<thead>
							<tr>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</thead>
						<tbody id="listExam">
							<?php 
								if(false): 
									//echo $fn->listaAlumnos($tipo,$pag,false);
								else:
									$cad = array();
									$cad = '¡¡ BIENVENIDO A '.APP_NAME.' !!<br> En este espacio se mostrará todo los estudiantes.';

									echo "<tr><td colspan=4>".$cad."</td></tr>";
								endif;
							?>
						</tfoot>
					</table>


					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center" id="pagination">
							<?php 					
								echo $this->interfaz->paginacion($pag);					
							?>
						</ul>
					</nav>

					<?php 
						//echo $this->interfaz->str_aside_search($pag,$tipo);
					?>

					<div class="container-button list">
						<a data-pushbar-target="right-center" title="filtrar"><i class="icon-filter"></i></a>
					</div>

				</div>
			</div><!--/main-body-->
			<div class="main-footer"></div><!--/main-footer-->

		</main><!--/container-main-->
<?php

	$script = '
		const pushbar = new Pushbar({
			blur:false,
			overlay:false,
		});
	';

	$this->content->add_script($script);
	$this->content->add_js(URL_THEME."/resource/plugins/pushbar/pushbar.js");
	$this->content->add_js(URL_THEME."/js/mod/admin/admin.js");

	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";

?>