<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie"> <![endif]-->
<!--[if IE 9]>    <html class="no-js ie9"> <![endif]-->
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php global $page, $paged, $SPT; wp_title( '|', true, 'right' ); bloginfo('name'); $site_description = get_bloginfo('description', 'display'); if ( $site_description && ( is_home() || is_front_page() ) ) echo " | $site_description"; ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?=SPT_TEMPLATE_DIR;?>/grid.css" type="text/css" />
<link rel="stylesheet" href="<?=SPT_TEMPLATE_DIR;?>/style.css" type="text/css" />

<?php wp_head(); ?>
</head>

<body>
<div class="container">
	<?php 
	$feature_setting = get_option(SPT_OPTION_FEATURE); 
	$layout_class = (1 == $feature_setting['featurebanner_enabled'])? 'grid_3' : 'grid_12'; 
	$menu_id = (1 == $feature_setting['featurebanner_enabled'])? 'mainnav_verticle' : 'mainnav';
	?>
	<header id="header" class="<?php echo $layout_class; ?>">
		<?php echo $SPT->get_site_logo();?>
		<?php echo $SPT->get_menu($menu_id);?>
	</header>
