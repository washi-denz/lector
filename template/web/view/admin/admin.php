<?php
	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
	$pag  = (isset($_GET["pag"]))? $_GET["pag"] : 1;

	$numReg = $this->gn->rtn_num_pdfs();
?>
		<main>

			<div class="main-header">

				<div class="bg-gray-100 mb-3">
					<div class="container mx-auto">
						<ul>
							<?php echo $this->interfaz->nav_list('admin',1);?>
						</ul>    
					</div>
				</div>

			</div><!--/main-header-->

			<div class="main-body">			
				<div class="container mx-auto">

					<button class="outline-none bg-green-500 text-white text-sm rounded-sm px-2 py-1 icon-plus-4 send" data-destine="admin/modalCrearLectura">Nueva lectura</button>

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
								echo $fn->mostrarLista('crear-lectura',$pag,false);
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

				</div>
			</div><!--main-body-->

		</main>

<?php

	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
    
	// Paralel
?>