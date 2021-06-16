<!DOCTYPE html>
<html lang="en" <?php echo $this->content->get_attr();?> >
	<head>	
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, maximum-scale=3.0, minimum-scale=1.0">

		<title><?php echo $this->content->get_title();?></title>

		<link rel="icon" href="<?php echo URL_THEME; ?>/img/lector.png">
		
		<link href="<?php echo URL_THEME; ?>/resource/plugins/bootstrap/bootstrap-5.0.0-reduced/bootstrap.css" rel="stylesheet">
		<link href="<?php echo URL_THEME; ?>/css/bootstrap-modif.css" rel="stylesheet">
		<link href="<?php echo URL_THEME; ?>/resource/plugins/tailwind/tailwind-2.0.2/tailwind.css" rel="stylesheet">

		<link href="<?php echo URL_THEME; ?>/css/general.css" rel="stylesheet">
		<link href="<?php echo URL_THEME; ?>/css/struct.css" rel="stylesheet">		
		<link href="<?php echo URL_THEME; ?>/resource/plugins/fontello/font/fontello.css" rel="stylesheet">
		<link href="<?php echo URL_THEME; ?>/resource/plugins/fontello/font/animation.css" rel="stylesheet">
		
		<?php echo $this->content->get_js_top();?>
		<?php echo $this->content->get_css();?>
		<?php echo $this->content->get_style()."\n";?>
	</head>
	<body class="text-gray-700 <?php echo $this->content->get_class('body'); ?>">
	<?php echo "\n";?>