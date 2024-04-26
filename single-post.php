<?php get_header(); ?>

<link rel="stylesheet" href="/wp-content/themes/generatepress-child/single-post-style.css">


<!--<section class="min-site-width section all-post">
    <a href="/blog/" class="link-style" target="_blank" rel="noopener noreferrer">All Posts</a>
</section>-->

<?php
while ( have_posts() ) :
    the_post();
?>

<section class="section post-card min-site-width">
    <div class="post-content">
		<?php the_content(); ?>
	</div>
</section>
<section class="related-post section">
	<div class="inner-col section-wrap">
		<h3 class="h3-style">Related Posts:</h3>
		<?php echo do_shortcode('[recent-posts count="2"]'); ?>
	</div>
</section>
<?php
endwhile;
?>

<?php get_footer(); ?>
