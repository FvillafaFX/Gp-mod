<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array(  ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 11 );

// END ENQUEUE PARENT ACTION

// Remove auto <p> tags from the contact forms
add_filter('wpcf7_autop_or_not', '__return_false');

//Remove auto <p> tags from the content on pages exclude posts
function add_auto_p_tags_to_posts($content) {
    if (is_singular('post')) {
        return wpautop($content);
    }
    return $content;
}
add_filter('the_content', 'add_auto_p_tags_to_posts');
remove_filter( 'the_content', 'wpautop' );

 //Menu shortcode
function custom_menu_shortcode($atts) {
    $atts = shortcode_atts( array(
        'location' => '',
    ), $atts );
    ob_start();
    wp_nav_menu( array( 'theme_location' => $atts['location'] ) );
    return ob_get_clean();
}
add_shortcode('menu', 'custom_menu_shortcode');

//Custom Menu
function custom_theme_setup() {
    register_nav_menus( array(
        'top-menu' => esc_html__( 'Top Menu', 'generatepress' ),
		'footer-top-menu' => esc_html__( 'Footer Top menu', 'generatepress' ),
		'footer-menu' => esc_html__( 'Footer menu', 'generatepress' ),
		'mobile-menu' => esc_html__( 'Mobile Menu', 'generatepress' ),
    ) );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );


//Logo Shortcode
function custom_logo_shortcode() {
    $logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src($logo_id, 'full');
    return '<a href="/" class="site_logo"><img width="311" height="215" src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '"></a>';
}
add_shortcode('site_logo', 'custom_logo_shortcode');

// recent post
function custom_recent_posts_shortcode($atts) {
	
	$atts = shortcode_atts( array(
        'count' => 2,
        'order' => 'DESC',
        'exclude_current' => false,
    ), $atts );
	
    $query_args = array(
        'post_type' => 'post',
        'posts_per_page' => intval($atts['count']),
        'order' => $atts['order'],
    );

    if ($atts['exclude_current']) {
        global $post;
        $query_args['post__not_in'] = array($post->ID);
    }

    $recent_posts = new WP_Query($query_args);

    $output = '';

    if ($recent_posts->have_posts()) {
        while ($recent_posts->have_posts()) {
            $recent_posts->the_post();
            $output .= '<li><div class="text"><h4 class="subtitle"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
			
			$content = get_the_content();
            $trimmed_content = wp_trim_words($content, 17);
            $output .= '<p class="post-content">' . $trimmed_content . '</p>';
			$output .= '<a class="read-more" href="' . get_permalink() . '">Continue Reading</a></div>';
			
			if (has_post_thumbnail()) {
                $output .= '<div class="featured-image">' . get_the_post_thumbnail(null, 'medium') . '</div>';
            }
            $output .= '</li>';
        }
        wp_reset_postdata();
    }
    $output = strip_shortcodes($output);
    return '<ul class="recent-post-list">' . $output . '</ul>';
}

add_shortcode('recent-posts', 'custom_recent_posts_shortcode');

// Recent post list
function custom_recent_posts_shortcode_list() {
    $args = array(
        'post_type' => 'post', 
        'posts_per_page' => 6,
        'order' => 'DESC',
        'offset' => 2
    );
    
    $queryprop = new WP_Query($args);
	
    if ($queryprop->have_posts()) : 
        while ($queryprop->have_posts()) : $queryprop->the_post();
            $output .= "<li>" . "<h4 class='blog-subtitle'><a href='" . get_permalink() . "'>" . get_the_title() . "</a></h4></li>";
        endwhile; 
        wp_reset_query(); // Reset the query
		
    endif; 
	$output = strip_shortcodes($output);
	return '<ul class="recent-post-list-tle">' . $output . '</ul>';
}

add_shortcode('recent-posts-list', 'custom_recent_posts_shortcode_list');


//Add details to sub menu mobile
function my_custom_menu_class( $classes, $item, $args, $depth ) {
    // Check if the item is a submenu
    if ( 'primary' === $args->theme_location && $depth > 0 ) {
        $classes[] = 'mobile-details-menu';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'my_custom_menu_class', 10, 4 );

function my_enqueue_scripts() {
    wp_enqueue_script( 'my-mobile-menu', get_stylesheet_directory_uri() . '/js/mobile-menu.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts' );


//Open Details in desktop

function details_desktop() {
   if ( wp_is_mobile() ) {
		//echo 'Mobile';
		
		} else {
		//echo 'Desktop';	
		return 'open';
	}
}

add_shortcode('open-dtls', 'details_desktop');
