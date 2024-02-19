<?php
get_header();
?>
<style>
.site-content{
    justify-content:center
}
.blog-sctn .recent-post-list {
    flex-wrap:wrap;
    justify-content: space-between;
}
.recent-post-list>li{
    width: 29%;
    justify-content: flex-end;
}
.blog-sctn {
    padding-top: clamp(32px, 4%, 72px);
    padding-bottom: clamp(32px, 4%, 72px);
}
@media(max-width: 900px){
    .recent-post-list>li{
        width: 46%;
    }
}
@media(max-width: 600px){
    .recent-post-list>li{
        width: 100%;
    }
}
</style>
<section class="blog-sctn section">
<div class="inner-col section-wrap">
<?php
if ( have_posts() ) :
    echo do_shortcode('[recent-posts count="-1"]');
    the_posts_pagination();
else :
    echo '<p>' . __( 'No posts found.', 'textdomain' ) . '</p>';
endif;
?>
</div>
</section>
<?php
get_footer();
?>