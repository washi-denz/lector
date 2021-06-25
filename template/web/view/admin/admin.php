<?php
	require URI_THEME."/section/head.php";
	//require URI_THEME."/section/navbar.php";
	echo "\n";
	$pag  = (isset($_GET["pag"]))? $_GET["pag"] : 1;
?>
	<div class="container-main">
        <div class="container">

			<div class="container-nav-view">
				<div class="container">
					<?php echo $this->interfaz->str_container_nav_view(1,'list');?>

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
			</div>

		    <button class="border-2 px-2 send" data-destine="admin/modalCrearLectura">+</button>

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
						if($this->gn->rtn_num_pdfs() > 0): 
							echo $fn->mostrarLista('crear-lectura',$pag,false);
						else:
							echo "nad...";
						endif;
					?>
				</tbody>
			</table>

        </div>		
	</div>

<?php

	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
    
	// Paralel
?>