<?php
	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";

	$pag          = (isset($_GET["pag"]))? $_GET["pag"] : 1;
    $encriptar_id = $this->gn->encriptar_id($uniqid);
	$id_pdf       = $this->gn->rtn_id($uniqid);

    $data1 = htmlspecialchars(json_encode(array('uniqid'=>$uniqid)));
	$data2 = htmlspecialchars(json_encode(array("redirect"=>"auto")));
	$data3 = htmlspecialchars(json_encode(array('uniqid'=>$uniqid,'type'=>'titulo-pdf')));
	$data4 = htmlspecialchars(json_encode(array('uniqid'=>$uniqid,'type'=>'descripcion-pdf')));

?>
	<main>

		<div class="main-header">

			<div class="bg-gray-100 mb-3">
				<div class="container mx-auto">
					<ul>
						<?php echo $this->interfaz->nav_list('edit',2);?>
					</ul>    
				</div>
			</div>

		</div><!--/main-header-->
		<div class="main-body">
			<div class="container mx-auto">

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
		</div><!--/main-body-->

	</main>

<?php

	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
    
	// Paralel
?>