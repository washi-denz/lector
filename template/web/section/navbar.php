		<header>
			<div class="bg-primary-100">
				<div class="container mx-auto">
					
					<div class="grid grid-cols-3">
						<div>
							<div class="flex">
								<img src="<?php echo URL_THEME; ?>/img/lector.png" class="w-16 h-16">
								<span class="hidden lg:block lg:text-3xl text-white font-medium pt-2 lg:pr-3">ector</span>
							</div>
						</div>
						<div class="col-span-2">
							<ul class="flex justify-end text-yellow-300 mt-3">
								<li><a class="icon-user">Rogelio</a></li>
								<li class="mx-3">
									<div x-data="{ dropdownOpen: false }" @click.away="dropdownOpen = false" class="relative">
										
										<a @click="dropdownOpen = ! dropdownOpen" class="icon-open-down-1 cursor-pointer icon-ellipsis-vert"></a>
										
										<div x-show = "dropdownOpen"
											class   = "absolute right-0 mt-2 w-32 bg-white rounded-md overflow-hidden shadow-xl z-10"
											style   = "display: none;">
											
											<a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Crear lectura</a>
											<a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Estudiantes</a>
											<hr>
											<a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
											<a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Salir</a>
											
										</div>
										
									</div>
								</li>
							</ul>
						</div>
					</div>
					
				</div>
			</div>			
		</header>