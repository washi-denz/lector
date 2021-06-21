<?php
	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
	$pag  = (isset($_GET["pag"]))? $_GET["pag"] : 1;
?>
	<div class="container-main">
        <div class="container">

			<div class="container-nav-view">
				<div class="container">
					<?php echo $this->interfaz->str_container_nav_view(2,'list');?>
				</div>
			</div>

            <iframe><iframe>

		    <button class="send" data-destine="admin/modalCrearLectura">+</button>

			<table class="table">
				<thead>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</thead>
				<tbody id="listaCrearLectura">
					<?php 
						if($this->gn->rtn_num_pdfs() > 0): 
							echo $fn->mostrarLista('crear-lectura',$pag,false);// lec lista de examnes credas
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