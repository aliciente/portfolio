<?php
/*
Template Name: Archives Template
*/
?>

<?php get_header(); ?>

<!-- BEGIN main -->
<div id="main"><div id="main-inner">

<h2 class="page-title">Trabajos</h2>

<h3 class="post-title">Anteriores Proyectos</h3>
<?php
$posts = get_posts('numberposts=25');
foreach($posts as $post) :
setup_postdata($post);
?>
<!-- BEGIN post -->
<div id="post-<?php the_ID(); ?>" class="post clearfix">
<div class="post-body">
<a href="<?php the_permalink(); ?>" title="">
    <?php 

	$pattern = "/\< *[img][^\>]*[src] *= *[\"\']{0,1}([^\"\'\ >]*)/i";
	preg_match_all($pattern, $post->post_content, $images);
	if(!$images[1][0]) {?>
		<img src='<?php bloginfo("template_url");?>/i/sorry-no-photo.png' alt='No existe la imagen, lo siento' width='75px' />
	<?
	}
	else 
		echo "<img src='".$images[1][0]."' width='75px' />";
	
?></a>
</div>
<h3 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Link permanente a este trabajo '<?php the_title(); ?>'"><?php the_title(); ?></a></h3>
<?php/*
<h4 class="post-meta"><?php the_time('F jS, Y') ?></h4>
*/?>
<h4 class="post-meta">Texto descriptivo</h4>
</div>
<!-- END post -->
<?php endforeach; ?>
</div>
</div>
<!-- END #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
