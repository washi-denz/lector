		<link href="<?php echo URL_THEME; ?>/resource/plugins/animate/animate.min.css" rel="stylesheet">
		<script src="<?php echo URL_THEME; ?>/resource/plugins/alpine/alpine.min.js"></script>
		<script src="<?php echo URL_THEME; ?>/resource/plugins/jquery/jquery-3.3.1.min.js"></script>
		<script src="<?php echo URL_THEME; ?>/resource/plugins/bootstrap/bootstrap-4.2.1/js/popper.min.js"></script>
		<script src="<?php echo URL_THEME; ?>/resource/plugins/bootstrap/bootstrap-5.0.0/js/bootstrap.min.js"></script>
		<script src="<?php echo URL_THEME; ?>/js/ajaxview.js"></script>
		<script src="<?php echo URL_THEME; ?>/js/struct.js"></script>
		
		<script>
			var _URL      = "";
			var _IDUSER   = 0;

			(function($){
				
				_URL    = <?php echo "'".URL.SEPARATOR."'";?>;
				_IDUSER = <?php echo ($this->session->get("id_user"))? $this->session->get("id_user"):0;?>;

			})(jQuery);
		</script>

		<?php  
			// Pull cad 4segundos
			if($this->session->check_login()){
				//$this->content->add_js(URL_THEME."/js/notify.js");	
			}
		?>
		<?php echo $this->content->get_js();?>
		<?php echo $this->content->get_script();?>

	</body>
</html>