<?php

/** Tell WordPress to run discover_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'discover_setup' );

if ( ! function_exists( 'discover_setup' ) ):

function discover_setup() {

	 global $content_width;
	 
     if (!isset($content_width))
            $content_width = 620;

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );	
	
	// Add support for custom backgrounds
	$args = array(
	'default-color' => 'ffffff',
	'wp-head-callback' => '_custom_background_cb'
);
add_theme_support( 'custom-background', $args );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'discover', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
		
	// This theme uses wp_nav_menu() in two location.	
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'discover' ),
	) );

}
endif;
?>
<?php
/**
 * Setup the WordPress core custom header feature.
 *
 * Use add_theme_support to register support for WordPress 3.4+
 * as well as provide backward compatibility for previous versions.
 * Use feature detection of wp_get_theme() which was introduced
 * in WordPress 3.4.
 *
 * @todo Rework this function to remove WordPress 3.4 support when WordPress 3.6 is released.
 *
 * @uses discover_s_header_style()
 * @uses discover_s_admin_header_style()
 * @uses discover_s_admin_header_image()
 *
 * @package discover_s
 */
function discover_s_custom_header_setup() {
	$args = array(
		'default-image'          => '',
		'default-text-color'     => 'fd7800',
		'width'                  => 220,
		'height'                 => 75,
		'flex-height'            => true,
		'wp-head-callback'       => 'discover_s_header_style',
		'admin-head-callback'    => 'discover_s_admin_header_style',
		'admin-preview-callback' => 'discover_s_admin_header_image',
	);

	$args = apply_filters( 'discover_s_custom_header_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-header', $args );
	} else {
		// Compat: Versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR',    $args['default-text-color'] );
		define( 'HEADER_IMAGE',        $args['default-image'] );
		define( 'HEADER_IMAGE_WIDTH',  $args['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $args['height'] );
		add_custom_image_header( $args['wp-head-callback'], $args['admin-head-callback'], $args['admin-preview-callback'] );
	}
}
add_action( 'after_setup_theme', 'discover_s_custom_header_setup' );


/**
 * Shiv for get_custom_header().
 *
 * get_custom_header() was introduced to WordPress
 * in version 3.4. To provide backward compatibility
 * with previous versions, we will define our own version
 * of this function.
 *
 * @todo Remove this function when WordPress 3.6 is released.
 * @return stdClass All properties represent attributes of the curent header image.
 *
 * @package discover_s
 */

if ( ! function_exists( 'get_custom_header' ) ) {
	function get_custom_header() {
		return (object) array(
			'url'           => get_header_image(),
			'thumbnail_url' => get_header_image(),
			'width'         => HEADER_IMAGE_WIDTH,
			'height'        => HEADER_IMAGE_HEIGHT,
		);
	}
}

if ( ! function_exists( 'discover_s_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see _s_custom_header_setup().
 */
function discover_s_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		.site-title,
		.site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo get_header_textcolor(); ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // _s_header_style

if ( ! function_exists( 'discover_s_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see _s_custom_header_setup().
 */
function discover_s_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
	}
	#headimg h1,
	#desc {
	}
	#headimg h1 {
	}
	#headimg h1 a {
	}
	#desc {
	}
	#headimg img {
	}
	</style>
<?php
}
endif; // _s_admin_header_style

if ( ! function_exists( 'discover_s_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see discover_s_custom_header_setup().
 */
function discover_s_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		?>
		<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div class="displaying-header-text" id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
	</div>
<?php }
endif; // discover_s_admin_header_image

?>
<?php
function discover_list_pings($comment, $args, $depth) { 
	$GLOBALS['comment'] = $comment; ?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php } ?>
<?php
add_filter('get_comments_number', 'discover_comment_count', 0);
function discover_comment_count( $count ) {
	if ( ! is_admin() ) {
	global $id;
	$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
	return count($comments_by_type['comment']);
} else {
return $count;
}
}
?>
<?php
if ( ! function_exists( 'discover_comment_callback' ) ) :
function discover_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s', 'discover' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'discover' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'discover' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'discover' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'discover' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'discover'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override discover_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 */
function discover_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'discover' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'discover' ),
		'before_widget' => '<div id="%1$s" class="widget-container-primary %2$s">',
		'before_title' => '<h3 class="widget-title"><span>',
		'after_title' => '</span></h3>',
	) );
	
	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'discover' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'discover' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'discover' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'discover' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'discover' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'discover' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
if ( ! function_exists( 'discover_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 */
function discover_posted_on() {
	printf( __( '%2$s <span class="meta-sep">by</span> %3$s', 'discover' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'discover' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;
/** Register sidebars by running discover_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'discover_widgets_init' );

/** Excerpt */
function discover_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'discover_excerpt_length' );

function discover_auto_excerpt_more( $more ) {
	return ' &hellip;' ;
}
add_filter( 'excerpt_more', 'discover_auto_excerpt_more' );


/** filter function for wp_title */
function discover_filter_wp_title( $old_title, $sep, $sep_location ){
 
// add padding to the sep
$ssep = ' ' . $sep . ' ';
 
// find the type of index page this is
if( is_category() ) $insert = $ssep . 'Category';
elseif( is_tag() ) $insert = $ssep . 'Tag';
elseif( is_author() ) $insert = $ssep . 'Author';
elseif( is_year() || is_month() || is_day() ) $insert = $ssep . 'Archives';
else $insert = NULL;
 
// get the page number we're on (index)
if( get_query_var( 'paged' ) )
$num = $ssep . 'page ' . get_query_var( 'paged' );
 
// get the page number we're on (multipage post)
elseif( get_query_var( 'page' ) )
$num = $ssep . 'page ' . get_query_var( 'page' );
 
// else
else $num = NULL;
 
// concoct and return new title
return get_bloginfo( 'name' ) . $insert . $old_title . $num;
}

// call our custom wp_title filter, with normal (10) priority, and 3 args
add_filter( 'wp_title', 'discover_filter_wp_title', 10, 3 );

// custom function

function discover_page_menu_args( $args ) {
$args['show_home'] = true;
return $args;
}
add_filter( 'wp_page_menu_args', 'discover_page_menu_args' );

// custom function
function discover_head_css() {
		$output = '';
		$theme_options_styles='';
		$custom_css = of_get_option('custom_css');
		if ($custom_css <> '') {
			$output .= $custom_css . "\n";
		}
		
		$link_color = esc_attr(of_get_option('link_color'));
		if ($link_color) {
			$theme_options_styles = '
			a{ 
				color: ' . $link_color . '; 
			}';
		}	

		$side_link_color = esc_attr(of_get_option('side_link_color'));
		if ($side_link_color) {
			$theme_options_styles .= '
			.widget-container-primary ul li a{ 
				color: ' . $side_link_color . '; 
			}';
		}
		
		$footer_link_color = esc_attr(of_get_option('footer_link_color'));
		if ($footer_link_color) {
			$theme_options_styles .= '
			#footer-widget .widget-container ul li a{ 
				color: ' . $footer_link_color . '; 
			}';
		}	
		
		$tb_color = esc_attr(of_get_option('tb_color'));
		if ($tb_color) {
			$theme_options_styles .= '
			body{ 
				border-top-color: ' . $tb_color . '; 
			}';
		}
		
		$bmeta_link_color = esc_attr(of_get_option('bmeta_link_color'));
		if ($bmeta_link_color) {
			$theme_options_styles .= '
			.meta-data a{ 
				color: ' . $bmeta_link_color . '; 
			}';
		}				
		
		$hbut_color = esc_attr(of_get_option('hbut_color'));
		if ($hbut_color) {
			$theme_options_styles .= '
			.button{ 
				background: ' . $hbut_color . '; 
			}';
		}				
		
		$hbuthov_color = esc_attr(of_get_option('hbuthov_color'));
		if ($hbuthov_color) {
			$theme_options_styles .= '
			.button:hover{ 
				background-color: ' . $hbuthov_color . '; 
			}
			.button{ 
				border-color: ' . $hbuthov_color . '; 
			}';
		}			
		
		$mli_color = esc_attr(of_get_option('mli_color'));
		if ($mli_color) {
			$theme_options_styles .= '
			#nav li:hover, #nav li.sfHover, #nav li:hover > a,
#nav a:focus, #nav a:hover, #nav a:active, #nav li li a{ 
				background: ' . $mli_color . '; 
			}';
		}								
		
		$mliul_color = esc_attr(of_get_option('mliul_color'));
		if ($mliul_color) {
			$theme_options_styles .= '
#nav li ul li:hover, #nav li ul li.sfHover,
#nav li ul li a:focus, #nav li ul li a:hover, #nav li ul li a:active, #nav li.current_page_item > a,
#nav li.current-menu-item > a,
#nav li.current-cat > a{ 
				background: ' . $mliul_color . '; 
			}';
		}														
		
		// Output styles
		if ($output <> '') {
			$output = "<!-- Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
		
		if($theme_options_styles){
			echo '<style>' 
			. $theme_options_styles . '
			</style>';
		}
	
}

add_action('wp_head', 'discover_head_css');

function discover_of_analytics(){
$googleanalytics= of_get_option('go_code');
echo stripslashes($googleanalytics);
}
add_action( 'wp_footer', 'discover_of_analytics' );

// custom function
/*-----------------------------------------------------------------------------------*/
/* Exclude categories from displaying on the "Blog" page template.
/*-----------------------------------------------------------------------------------*/

// Exclude categories on the "Blog" page template.
add_filter( 'discover_blog_template_query_args', 'discover_exclude_categories_blogtemplate' );

function discover_exclude_categories_blogtemplate ( $args ) {

	if ( ! function_exists( 'discover_prepare_category_ids_from_option' ) ) { return $args; }

	$excluded_cats = array();

	// Process the category data and convert all categories to IDs.
	$excluded_cats = discover_prepare_category_ids_from_option( 'exclude_cat' );


	if ( count( $excluded_cats ) > 0 ) {

		// Setup the categories as a string, because "category__not_in" doesn't seem to work
		// when using query_posts().

		foreach ( $excluded_cats as $k => $v ) { $excluded_cats[$k] = '-' . $v; }
		$cats = join( ',', $excluded_cats );

		$args['cat'] = $cats;
	}

	return $args;

}

/*-----------------------------------------------------------------------------------*/
/* discover_prepare_category_ids_from_option()
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'discover_prepare_category_ids_from_option' ) ) {

	function discover_prepare_category_ids_from_option ( $option ) {

		$cats = array();

		$stored_cats = of_get_option( $option );

		$cats_raw = explode( ',', $stored_cats );

		if ( is_array( $cats_raw ) && ( count( $cats_raw ) > 0 ) ) {
			foreach ( $cats_raw as $k => $v ) {
				$value = trim( $v );

				if ( is_numeric( $value ) ) {
					$cats_raw[$k] = $value;
				} else {
					$cat_obj = get_category_by_slug( $value );
					if ( isset( $cat_obj->term_id ) ) {
						$cats_raw[$k] = $cat_obj->term_id;
					}
				}

				$cats = $cats_raw;
			}
		}

		return $cats;

	}

}

function discover_date_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s', 'discover' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		)
	);
}

function discover_of_register_js() {
	if (!is_admin()) {
		
		wp_register_script('superfish', get_template_directory_uri() . '/js/superfish.js', 'jquery', '1.0', TRUE);
		wp_register_script('discover_custom', get_template_directory_uri() . '/js/jquery.custom.js', 'jquery', '1.0', TRUE);
		wp_register_script('fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', 'jquery', '1.0', TRUE);
		wp_register_script('selectnav', get_template_directory_uri() . '/js/selectnav.js', 'jquery', '0.1', TRUE);
		wp_register_script('flexslider', get_template_directory_uri() . '/js/jquery.flexslider.js', 'jquery', '2.1', TRUE);
		wp_register_script('modernizr', get_template_directory_uri() . '/js/modernizr.js', 'jquery', '2.6.1', false);
		wp_register_script('responsive', get_template_directory_uri() . '/js/responsive-scripts.js', 'jquery', '1.2.1', TRUE);
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('superfish');
		wp_enqueue_script('discover_custom');
		wp_enqueue_script('fitvids');
		wp_enqueue_script('flexslider');		
		wp_enqueue_script('selectnav');
		wp_enqueue_script('modernizr');
		wp_enqueue_script('responsive');
	}
}
add_action('init', 'discover_of_register_js');

function discover_of_single_scripts() {
	if(is_singular()) wp_enqueue_script( 'comment-reply' ); // loads the javascript required for threaded comments 
}
add_action('wp_print_scripts', 'discover_of_single_scripts');

function discover_of_styles() {
		wp_register_style( 'superfish', get_template_directory_uri() . '/css/superfish.css' );
		wp_register_style( 'flexslider', get_template_directory_uri() . '/css/flexslider.css' );
		wp_register_style( 'foundation', get_template_directory_uri() . '/css/foundation.css' );

		
		wp_enqueue_style( 'superfish' );
		wp_enqueue_style( 'flexslider' );		
		wp_enqueue_style( 'foundation' );		
		
}
add_action('wp_print_styles', 'discover_of_styles');

/** redirect */
if ( is_admin() && isset($_GET['activated'] ) && $pagenow ==	"themes.php" )
	wp_redirect( 'themes.php?page=options-framework');

// include panel file.
if ( !function_exists( 'optionsframework_init' ) ) {

	/*-----------------------------------------------------------------------------------*/
	/* Options Framework Theme
	/*-----------------------------------------------------------------------------------*/

	/* Set the file path based on whether the Options Framework Theme is a parent theme or child theme */

	if ( get_stylesheet_directory() == get_template_directory_uri() ) {
		define('OPTIONS_FRAMEWORK_URL', get_template_directory() . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/');
	} else {
		define('OPTIONS_FRAMEWORK_URL', get_template_directory() . '/admin/');
		define('OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/admin/');
	}

	require_once (OPTIONS_FRAMEWORK_URL . 'options-framework.php');

}