<?php get_header(); ?>
    <section class="subpage">
    <?php if (have_posts()) : the_post(); global $post; ?>
        <div class="grid_8">
            <h1><?php the_title(); ?></h1>
        	<?php the_content(); ?>
        </div>
	<?php endif; ?>
    </section>
    <aside id="sidebar">
    	<?php get_sidebar(); ?>
    </aside>
<?php get_footer(); ?>