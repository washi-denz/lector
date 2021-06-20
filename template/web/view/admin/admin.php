<?php
	require URI_THEME."/section/head.php";
	require URI_THEME."/section/navbar.php";
	echo "\n";
?>
	<div class="container-main">
        <div class="container">

		    <button class="send" data-destine="admin/modalCrearLectura">+</button>

			<table class="table">
				<thead>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</thead>
				<tbody id="listaCrearLectura">
	
				</tbody>
			</table>

        </div>		
	</div>

<?php

	require URI_THEME."/section/footer.php";
	require URI_THEME."/section/foot.php";
    
	// Paralel
?>