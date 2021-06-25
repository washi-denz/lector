<?php

	$this->content->add_css(URL_THEME."/css/mod/admin/admin.css");

	require URI_THEME."/section/head.php";
	//require URI_THEME."/section/navbar.php";

	echo "\n";

	$pag    = (isset($_GET["pag"]))? $_GET["pag"] : 1;

	$numReg = $this->gn->rtn_num_alumnos();
?>
		<main class="container-main">

			<div class="main-header">

				<div class="container-nav-view">
					<div class="container">
						<?php echo $this->interfaz->str_container_nav_view(2,'list');?>
					</div>
				</div>

				<div class="bar-header">
					<div class="container">
						<strong>...</strong>
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

		    		<button class="border-2 px-2 send" data-destine="admin/modalAgregarAlumno">+</button>

					<table class="table">
						<thead>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</thead>
						<tbody id="mostrarLista">
							<?php 								 
								echo $fn->mostrarLista('alumno',$pag,false);
							?>
						</tbody>
					</table>

					<nav aria-label="Page navigation example">
						<ul class="pagination justify-content-center" id="pagination">
							<?php 					
								echo $this->interfaz->paginacion($pag,['numReg'=>$numReg]);
							?>
						</ul>
					</nav>

					<?php 
						//echo $this->interfaz->str_aside_search($pag,$tipo);
					?>

				</div>
			</div><!--/main-body-->
			<div class="main-footer"></div><!--/main-footer-->

		</main><!--/container-main-->
<?php

	$this->content->add_js(URL_THEME."/js/mod/admin/admin.js");

	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";

?>