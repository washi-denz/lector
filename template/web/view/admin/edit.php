<?php
	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";

	$pag          = (isset($_GET["pag"]))? $_GET["pag"] : 1;
    $encriptar_id = $this->gn->encriptar_id($uniqid);

    $data1 = htmlspecialchars(json_encode(array('uniqid'=>$uniqid)));

?>
	<div class="container-main">
        <div class="container">

			<div class="container-nav-view">
				<div class="container">
					<?php echo $this->interfaz->str_container_nav_view(3,'edit'); ?>
				</div>
			</div><br>

            <div class="flex">
                <h1 class="text-center text-lg font-medium h_<?php echo $encriptar_id;?>">
                    <?php echo $this->gn->rtn_titulo_lectura($uniqid); ?>
                </h1>
                <button class="icon-pencil send" data-destine="admin/modalModificarTitulo" data-data="<?php echo $data1; ?>"></button>
            </div>

            <div class="flex">
                <textarea class="w-full ta_<?php echo $encriptar_id;?>">
                    <?php echo $this->gn->rtn_descripcion_lectura($uniqid) ?>
                </textarea>
                <button class="icon-pencil send" data-destine="admin/modalModificarDescripcion" data-data="<?php echo $data1; ?>"></button>
            </div>

            <iframe src="<?php echo $this->gn->rtn_src_lectura($uniqid); ?>" class="w-full border mb-3 iframe_<?php echo $encriptar_id;?>"></iframe>
            <button class="icon-pencil"></button><br></br>

		    Agregar pregunta: <button class="border-2 px-2 send" data-destine="admin/modalCrearLectura">+</button>

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
							//echo $fn->mostrarLista('crear-lectura',$pag,false);// lec lista de examnes credas
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