<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Enqueue styles for the child theme
if ( !function_exists( 'child_theme_enqueue_styles' ) ) :
    function child_theme_enqueue_styles() {
        // Enqueue parent theme styles
        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

        // Enqueue child theme styles
       // wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_styles' );

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

// Disable visual editor sitewide
add_filter('user_can_richedit', '__return_false');

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
            $output .= '<li><div class="text"><h4 class="subtitle no-toc"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
			
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
            $output .= "<li>" . "<h4 class='blog-subtitle no-toc'><a href='" . get_permalink() . "'>" . get_the_title() . "</a></h4></li>";
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

// Table of Contents
function custom_toc_shortcode($atts) {
    $atts = shortcode_atts([
        'title' => 'h3,h4',
        'exclude' => '.no-toc',
        'container' => 'body',
    ], $atts, 'toc');

    $headings_selector = $atts['title'];
    $exclude_selectors = array_filter(array_map('trim', explode(',', $atts['exclude'])));
    $container_selector = $atts['container'];
	$uid = uniqid('toc_');

    ob_start();
    ?>
    <details class="table-of-contents"  id="<?php echo esc_attr($uid); ?>">
		<summary>
			<p class="table-title"><span class="text">Table Of Contents</span><svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 6H20M4 12H20M4 18H20" stroke="var(--white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg></p>
		</summary>
		<div class="content-col">
        	<ul></ul>
        </div>
	</details>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tocRoot = document.getElementById('<?php echo esc_js($uid); ?>');
        const tocContainer = tocRoot.querySelector('ul');
        const mainContainer = document.querySelector('<?php echo esc_js($container_selector); ?>');
        const excludeSelectors = <?php echo json_encode($exclude_selectors); ?>;

        if (!mainContainer || !tocContainer) return;

        const headings = mainContainer.querySelectorAll('<?php echo esc_js($headings_selector); ?>');

        headings.forEach(function (heading, index) {
            if (excludeSelectors.some(sel => heading.matches(sel))) return;

            let rawText = heading.textContent.trim();
            let id = rawText
                .toLowerCase()
                .replace(/&/g, 'and')
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');

            let originalId = id;
            let counter = 1;
            while (document.getElementById(id)) {
                id = originalId + '-' + counter;
                counter++;
            }

            heading.id = id;

            const link = document.createElement('a');
            link.href = '#' + id;
            link.textContent = rawText;

            const li = document.createElement('li');
            li.appendChild(link);

            tocContainer.appendChild(li);
        });
    });
    </script>

    <style>
		.table-of-contents{padding-bottom: clamp(32px, 4%, 72px);}
		.table-of-contents p.table-title {background-color:var(--primary);color:var(--white);display:flex;align-items: center;justify-content: center;width: fit-content;padding: 12px 28px;margin-bottom: 0;gap: 20px;white-space:nowrap;}
		.table-of-contents p.table-title svg{ fill: var(--white)!important;stroke: var(--white);width: 40px;height: 40px;}
		.table-title-col {display: flex;flex-direction: column;}
		.table-of-contents .content-col {border-bottom: 2px solid var(--primary);}
		.table-of-contents .content-col ul{margin-bottom: 48px;columns: 2 250px;padding-left:0}
		.table-of-contents .content-col ul li{ margin-bottom: 20px;margin-left: 8px;margin-right: 8px;}
		.table-of-contents .content-col ul li a{color: var(--primary);font-weight: 600;font-size: clamp(16px, 1.5vw, 18px)!important;}
		.table-title-col{width:fit-content}
		@media(max-width:800px){
			.table-title-col,.table-of-contents .content-col ul{margin-bottom: 32px}
		}
		@media(max-width:600px){
			.table-of-contents .content-col ul li{width: 100%;}
			.table-of-contents .content-col ul{padding-left: 20px}
		}
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('toc', 'custom_toc_shortcode');
