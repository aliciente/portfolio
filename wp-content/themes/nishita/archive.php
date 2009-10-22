<?php get_header(); ?>

<!-- BEGIN main -->
<div id="main"><div id="main-inner">

<?php if (have_posts()) : ?>

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>
<h2 class="page-title">Trabajos de la categoria '<?php echo single_cat_title(); ?>'</h2>

<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
<h2 class="page-title">Trabajos del mes '<?php the_time('F, Y'); ?>'</h2>

<?php /* If this is an author archive */ } elseif (is_author()) { ?>
<h2 class="page-title">Trabajos de</h2>

<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
<h2 class="page-title">Trabajos Grandes</h2>

<?php } ?>

<?php while (have_posts()) : the_post(); ?>
<!-- BEGIN post -->
<div id="post-<?php the_ID(); ?>" class="post clearfix">
<div class="post-body">
<a href="<?php the_permalink(); ?>" title=""><?php 
 

	$pattern = "/\< *[img][^\>]*[src] *= *[\"\']{0,1}([^\"\'\ >]*)/i";
	preg_match_all($pattern, $post->post_content, $images);
	if(!$images[1][0]) {?>
		<img src='<?php bloginfo("template_url");?>/i/sorry-no-photo.png' width='75px' />
	<?
	}
	else 
		echo "<img src='".$images[1][0]."' width='75px' />";
	
?></a>
</div>
<h3 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Link permanente al trabajo '<?php the_title(); ?>'"><?php the_title(); ?></a></h3>
</div>
<!-- END post -->	
<?php endwhile; ?>

<?php else : ?>

<h3>No encontrado</h3>

<?php endif; ?>

</div></div>
<!-- END #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
