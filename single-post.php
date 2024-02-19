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
    <!--<div class="author-info">
        <p class="p-style"><?php
			/*$content = get_post_field( 'post_content', get_the_ID() );
			$word_count = str_word_count( strip_tags( $content ) );
			$reading_time = ceil( $word_count / 200 );*/
		  ?>
		  <span class="post-reading-time"><?php /*echo $reading_time;*/ ?> min read</span>
		</p>
    </div>-->
    <div class="post-content">
		<?php the_content(); ?>
	</div>
	<div class="sign-col">
		<img class="alignnone size-full wp-image-722" src="https://smithservicesaz.com/wp-content/uploads/2023/11/smith-plumbing-logo.png" alt="smith-plumbing-logo" width="244" height="153" />

		<p><strong>SMITH PLUMBING, HEATING &amp; COOLING</strong></p>
		<p><a href="https://maps.app.goo.gl/JJoXtgtT7D7J65XdA" target="_blank" rel="noopener">5135 E Ingram St #8,<br>
		Mesa, AZ 85205</a></p>
		<p>Phone: <a href="tel:+14808279111">480-827-9111</a></p>
		<p>Email: <a href="mailto:service@smithservicesaz.com">service@smithservicesaz.com</a></p>
		<p>Website: <a href="https://smithservicesaz.com/">www.smithservicesaz.com</a></p>
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