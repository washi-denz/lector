<?php

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
						<?php echo $this->interfaz->str_container_nav_view(3,'list');?>
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
							</tr>
						</thead>
						<tbody id="listExam">
							<?php 
								if(true):

									$alumnos = $fn->listaAlumnos($pag,false);
                                    $numReg  = $this->gn->rtn_num_alumnos();

                                    foreach($alumnos as $alumno)
                                    {
                            ?>
                                        <tr>
                                            <td>
                                                <h6 class="item-title text-gray-800 font-medium"><?php echo $alumno->nombres;?></h6>
                                                <div class="item-subtitle">
                                                   
                                                </div>
                                            </td>
                                            <td>
                                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="icon-ellipsis-vert"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right" style="z-index:1100;">
                                                    <?php //'.$links2.' ?>
                                                </di>
                                            </td>
                                        </tr>

                                <?php 
                                    } else: 
                                ?>
                                        <tr>
                                            <td colspan=4>
                                                No se encontraron nad
                                            </td>
                                        </tr>

                                <?php endif; ?>
						</tfoot>
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