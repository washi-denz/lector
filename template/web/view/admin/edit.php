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
				<div class="container mx-auto lg:px-32">
					<ul>
						<?php echo $this->interfaz->nav_list('edit',2);?>
					</ul>    
				</div>
			</div>

		</div><!--/main-header-->
		<div class="main-body mb-20">
			<div class="container mx-auto lg:px-48">

				<div class="relative mb-3">
					<h1 class="text-center text-2xl font-medium t_<?php echo $encriptar_id;?>">
						<?php echo $this->gn->rtn_titulo_lectura($uniqid); ?>
					</h1>
					<button class="focus:outline-none absolute right-0 top-0 bg-green-100 text-green-600 bg-opacity-25 rounded-full p-2 icon-pencil send" data-destine="admin/modalActualizarCampo" data-data="<?php echo $data3; ?>"></button>
				</div>

				<div class="relative text-center mb-3">
					<p class="w-full d_<?php echo $encriptar_id;?>">
						<?php echo $this->gn->rtn_descripcion_lectura($uniqid) ?>
					</p>
					<button 
						class        = "focus:outline-none absolute right-0 top-0 bg-green-100 text-green-600 bg-opacity-25 rounded-full p-2 icon-pencil send" 
						data-destine = "admin/modalActualizarCampo" 
						data-data    = "<?php echo $data4; ?>"
					></button>
				</div>

				<div class="relative">
					<iframe src="<?php echo $this->gn->rtn_src_lectura($uniqid); ?>" class="w-full h-96 border iframe_<?php echo $encriptar_id;?>"></iframe>
					<button class="focus:outline-none absolute top-16 right-6 bg-green-100 text-green-600 bg-opacity-25_ rounded-full p-2 icon-pencil send" data-destine="admin/modalModificarPDF" data-data="<?php echo $data1; ?>"></button><br></br>
				</div>				

				<button class="outline-none bg-green-500 text-white text-sm rounded-sm px-2 py-1 icon-plus-4 send" data-destine="admin/modalAgregarPregunta" data-data="<?php echo $data1; ?>">Agregar pregunta</button>

				<table class="table">
					<thead>
						<tr>
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
								//echo "0 No se encontrarón preguntas...";
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