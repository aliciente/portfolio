<?php get_header(); ?>

<?php if (is_home()) { query_posts('showposts=1'); } ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<!-- BEGIN #photo -->
<h2 class="photo-title"><span><?php the_title(); ?></span></h2>
<div id="photo">
    <div id="photo-inner">
    <?php the_content(); ?>
    </div>
</div>
<!-- END #photo -->

<!-- BEGIN photo meta -->
<div id="photo-meta">
    <div id="photo-meta-inner">
        <ul>
        <li><?php the_category(', ') ?></li>
        <?php $wp_query->is_single = false; ?>
        <?php edit_post_link(__('Edit'), '<li>', '</li>'); ?>
        </ul>
    </div>
</div>
<!-- END photo meta -->

<!-- BEGIN navigate -->
<div id="navigate">
    <div id="navigate-inner" class="clearfix">
        <?php $wp_query->is_single = true; ?>
        <span class="previous"><?php next_post( '%', 'Anterior', '' ) ?></span>
        <span class="next"><?php previous_post( '%', 'Siguiente', '' ) ?></span>
    </div>
</div>
<!-- END navigate -->
<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>
