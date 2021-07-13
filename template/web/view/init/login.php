<?php

	$this->content->add_class('body','bg-gray-50');

	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
?>

<?php

	//redirecionar
	$redirect = URL."/admin";

?>
		<main>

			<div class="main-header">
			</div><!--main-header-->

			<div class="main-body mb-16">
				
				<div class="container mx-auto">
					<div class="row justify-content-center p-3">
						<div class="col col-sm-8 col-md-5 col-lg-3 bg-white rounder shadow-sm rounded">
							<div class="d-flex justify-content-between p-3">
								<h3 class="text-gray-800 font-semibold text-2xl pt-2">Iniciar sesión</h3>
								<img src="<?php echo URL_THEME;?>/img/default/user.svg">
							</div>
							<div class="px-3">
								<?php
									$data = htmlspecialchars(json_encode(array("redirect"=>$redirect,"load"=>"formLoad_bottom")));

									$btn = '<button type="submit" class="btn btn-primary bg-color:2 mx-auto px-5 send" data-destine="user/login" data-data="'.$data.'" data-serialize="formLogin">Ingresar</button>';

									echo $this->interfaz->gn('login',(object)['btn'=>$btn]);
								?>
							</div>
							<div class=""></div>
						</div>
					</div>
				</div>

			</div><!--main-body-->

			<div class="main-footer">

				Acerca de 'LECTOR'<br>
				Es una pequeña herramienta para mejorar la lectura y escritura, que a travéz de ella,el usuario(admin) podrá determinar el aprendizaje de otros usuarios.

				<br>
				Manual de usuario:<br>

				La idea es simple: primero tiene que registrar usuarios a quien vadirigido dicha lectura ,luego crear la lectura subiendo el PDF contendrá el material de lectura.
				En seguida, asociar las preguntas de dicha lectura ,y finalmente compartir el link.

				1.Registrar usuarios
				2.crear lecturar
				3.Agregar PDF con el material de lectura
				4.Agregar preguntas al PDF
				5.Compartir el Link con otros usuarios.

			</div><!--main-footer-->

		</main>

<?php
	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
?>