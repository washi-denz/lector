		<header>

			<?php $nofixed = (isset($nofixed) && $nofixed == true)? 'nofixed':''; ?>

			<div class="container-nav <?php echo $nofixed;?>" >
				<div class="container">
					<div class="nav">
						<div class="nav-left">
							<div class="nav-left-img">
								<a href="<?php echo URL;?>/init"><img src="<?php echo URL_THEME;?>/img/logo_testwink.png"></a>
							</div>
							<div class="nav-left-title">
							</div>
						</div>
						<div class="nav-right">
							<ul class="nav-right-icons">
								<li>
									<a class="send" data-destine="init/mostrarModalCategoria">
										Test <i class="icon-down-open"></i>
									</a>
								</li>

								<?php if(ACTION!= ''): ?>
								<li>
									<a class="icon-search send" data-destine="init/mostrarModalBusqueda"></a>
								</li>
								<?php endif;?>

								<?php

									if($this->session->check_login()){										
										echo $this->interfaz->str_nav_user();									
									}
								?>

								<li id="iconUser">
									<a href="#" class="icon-ellipsis-vert" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="notify"><span class="notify-adm-pub"></span></i>
									</a>
									<div class="dropdown-menu" id="dropdownmenu">
										<?php
											//Mostrar dropdown 
											echo $this->interfaz->str_nav();
										?>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</header>