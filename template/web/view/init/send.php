<?php

	$this->content->add_class('body','bg-primary-100');

	require URI_THEME."/section/head.php";
	echo "\n";

    $redirect = isset($_GET['redirect'])? $_GET['redirect']: URL.'/init/send';
?>

        <div class="flex justify-center items-center h-screen">
            <div class="text-center">
                <div class="flex justify-center">
                    <img src="<?php echo URL_THEME; ?>/img/lector.png" class="w-16 h-16">
                    <span class="text-3xl text-white font-medium pt-2">ector</span>
                </div>
                
                <p class="uppercase text-white text-lg font-medium mb-3">Sus respuestas fueron enviados correctamente</p>
                <p class="text-green-300 mb-6">¡ Su pedido se realizó con exito !</p>
                <a href="<?php  echo $redirect; ?>" class="cursor-pointer border-2 border-white text-white px-3 py-1 hover:bg-blue-800">Atrás</a>
            </div>
        </div>