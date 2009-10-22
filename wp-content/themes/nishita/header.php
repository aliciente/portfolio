<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Archives <?php } ?> <?php wp_title(); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
<meta name="description" content="<?php bloginfo('description'); ?>" />
 
<link rel="stylesheet" type="text/css" media="screen" href="<?php bloginfo('stylesheet_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />

<script src="http://www.aliciaalvarez.eu/js/js-global/FancyZoom.js" type="text/javascript"></script>
<script src="http://www.aliciaalvarez.eu/js/js-global/FancyZoomHTML.js" type="text/javascript"></script>

<?php comments_popup_script(); ?>
<?php wp_head(); ?>

</head>

<body id="<?php echo (is_page()) ? get_query_var('name') : ((is_home()) ? "home" : ((is_single()) ? "archives": ((is_category()) ? "archives" : ((is_archive()) ? "archives" : "")))); ?>" onload="setupZoom()">

<!-- BEGIN #container -->
<div id="container">

<!-- BEGIN #header -->
<div id="header"><div id="header-inner" class="clearfix">
<div id="title">
<h1><a href="<?php echo get_settings('home'); ?>/" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></h1>
<p id="tagline"><?php bloginfo('description'); ?></p>
</div>
<!-- BEGIN #nav -->
<ul id="nav">
    <li id="nav-home"><a href="<?php bloginfo('url'); ?>" title="Inicio">Inicio</a></li>
    <?php wp_list_pages("depth=1&exclude=$avatar_page_id->ID,$tags_page_id->ID,'21',&title_li="); ?>
    <li id="nav-contact"><a href="http://www.aliciaalvarez.eu/?page_id=21" title="Contacto">Contacto</a></li>
</ul>
<!-- BEGIN #nav -->
</div>
</div>
<!-- END #header -->
