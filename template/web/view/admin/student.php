<?php

	$this->content->add_css(URL_THEME."/css/mod/admin/admin.css");

	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";

	echo "\n";

	$pag    = (isset($_GET["pag"]))? $_GET["pag"] : 1;

	$numReg = $this->gn->rtn_num_alumnos();
?>
		<main>

			<div class="main-header">

				<div class="bg-gray-100 mb-3">
					<div class="container mx-auto lg:px-32">
						<ul>
							<?php echo $this->interfaz->nav_list('admin',2);?>
						</ul>    
					</div>
				</div>

			</div><!--/main-header-->

			<div class="main-body mb-12">
				
				<div class="container mx-auto lg:px-32">
							    		
					<button class="outline-none bg-green-500 text-white text-sm rounded-sm px-2 py-1 icon-plus-4 send" data-destine="admin/modalAgregarAlumno">Agregar nuevo alumno</button>

					<table class="table">
						<thead>
							<tr>
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

		</main>
<?php

	$this->content->add_js(URL_THEME."/js/mod/admin/admin.js");

	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";

?>