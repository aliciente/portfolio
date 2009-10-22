<?php get_header(); ?>

<!-- BEGIN main -->
<div id="main"><div id="main-inner">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<h2 class="page-title"><?php the_title(); ?></h2>

<div class="page-body">
<?php the_content(); ?>
</div>

<?php endwhile; endif; ?>

</div></div>
<!-- END #main -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>