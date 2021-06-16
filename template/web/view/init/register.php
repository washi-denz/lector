<?php

	$this->content->add_class('body','bg-gray-50');

	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
?>	
		<div class="container-main">
			<div class="container">
				<div class="d-flex justify-content-center">
					<div class="container-register box-shadow">
						<?php 
							echo $this->interfaz->str_registrarse();
						?>
					</div>
				</div>
			</div>
		</div>
<?php
	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
?>