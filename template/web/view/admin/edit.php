<?php
	require URI_THEME."/section/head.php";
	//require URI_THEME."/section/navbar.php";
	echo "\n";

	$pag          = (isset($_GET["pag"]))? $_GET["pag"] : 1;
    $encriptar_id = $this->gn->encriptar_id($uniqid);
	$id_pdf       = $this->gn->rtn_id($uniqid);

    $data1 = htmlspecialchars(json_encode(array('uniqid'=>$uniqid)));
	$data2 = htmlspecialchars(json_encode(array("redirect"=>"auto")));
	$data3 = htmlspecialchars(json_encode(array('uniqid'=>$uniqid,'type'=>'titulo-pdf')));
	$data4 = htmlspecialchars(json_encode(array('uniqid'=>$uniqid,'type'=>'descripcion-pdf')));

?>
	<div class="container-main">
        <div class="container">

			<div class="container-nav-view">
				<div class="container">
					<?php echo $this->interfaz->str_container_nav_view(2,'edit'); ?>

					<!---->
					<div class="dropdown float-end">
						<a class="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icon-down-open-1"></i></a>
						<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
							<a class="dropdown-item"><i class="icon-user"></i></a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item send" data-destine="user/salir" data-data="<?php echo $data2; ?>">Salir</a>
						</div>
					</div>	
					<!----->

				</div>
			</div><br>

            <div class="flex">
                <h1 class="text-center text-lg font-medium t_<?php echo $encriptar_id;?>">
                    <?php echo $this->gn->rtn_titulo_lectura($uniqid); ?>
                </h1>
                <button class="icon-pencil send" data-destine="admin/modalActualizarCampo" data-data="<?php echo $data3; ?>"></button>
            </div>

            <div class="flex">
                <textarea class="w-full d_<?php echo $encriptar_id;?>">
                    <?php echo $this->gn->rtn_descripcion_lectura($uniqid) ?>
                </textarea>
                <button 
					class        = "icon-pencil send" 
					data-destine = "admin/modalActualizarCampo" 
					data-data    = "<?php echo $data4; ?>"
				></button>
            </div>

            <iframe src="<?php echo $this->gn->rtn_src_lectura($uniqid); ?>" class="w-full border mb-3 iframe_<?php echo $encriptar_id;?>"></iframe>
            <button class="icon-pencil send" data-destine="admin/modalModificarPDF" data-data="<?php echo $data1; ?>"></button><br></br>

		    Agregar preguntas: <button class="border-2 px-2 send" data-destine="admin/modalAgregarPregunta" data-data="<?php echo $data1; ?>">+</button>

			<table class="table">
				<thead>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</thead>
				<tbody id="listaEditPreguntas">
					<?php 
						if($this->gn->rtn_num_preguntas($id_pdf) > 0): 
							echo $fn->mostrarListaEditPreguntas(['uniqid' => $uniqid],$pag,false);
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