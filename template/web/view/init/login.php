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
		<div class="container-main">
			<div class="container">

				<div class="row justify-content-center p-3 container-login">
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
		</div>

<?php
	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
?>