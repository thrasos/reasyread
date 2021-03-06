<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package easyread
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function easyread_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
}
add_filter( 'wp_page_menu_args', 'easyread_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function easyread_body_classes( $classes ) {
  // Adds a class of group-blog to blogs with more than 1 published author.
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }
	
	if ( get_theme_mod( 'easyread_sidebar_position' ) == "pull-right" ) {
		$classes[] = 'has-sidebar-left';
	} else if ( get_theme_mod( 'easyread_sidebar_position' ) == "no-sidebar" ) {
		$classes[] = 'has-no-sidebar';
	} else if ( get_theme_mod( 'easyread_sidebar_position' ) == "full-width" ) {
		$classes[] = 'has-full-width';
	} else {
		$classes[] = 'has-sidebar-right';
	}

  return $classes;
}
add_filter( 'body_class', 'easyread_body_classes' );


// Mark Posts/Pages as Untiled when no title is used
add_filter( 'the_title', 'easyread_title' );

function easyread_title( $title ) {
  if ( $title == '' ) {
    return __( 'Untitled', 'easyread' );
  } else {
    return $title;
  }
}

/**
 * Password protected post form using Boostrap classes
 */
add_filter( 'the_password_form', 'easyread_custom_password_form' );

function easyread_custom_password_form() {
  global $post;
  $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
  $o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
  <div class="row">
    <div class="col-lg-10">
        ' . esc_html__( "<p>This post is password protected. To view it please enter your password below:</p>" ,'easyread') . '
        <label for="' . $label . '">' . esc_html__( "Password:" ,'easyread') . ' </label>
      <div class="input-group">
        <input class="form-control" value="' . get_search_query() . '" name="post_password" id="' . $label . '" type="password">
        <span class="input-group-btn"><button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="' . esc_attr__( "Submit",'easyread' ) . '">' . esc_html__( "Submit" ,'easyread') . '</button>
        </span>
      </div>
    </div>
  </div>
</form>';
  return $o;
}

// Add Bootstrap classes for table
add_filter( 'the_content', 'easyread_add_custom_table_class' );
function easyread_add_custom_table_class( $content ) {
    return str_replace( '<table>', '<table class="table table-hover">', $content );
}

if ( ! function_exists( 'easyread_header_menu' ) ) :
/**
 * Header menu (should you choose to use one)
 */
function easyread_header_menu() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'menu'              => 'primary',
    'theme_location'    => 'primary',
    'depth'             => 2,
    'container'         => 'div',
    'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
    'menu_class'        => 'nav navbar-nav',
    'fallback_cb'       => 'easyread_wp_bootstrap_navwalker::fallback',
    'walker'            => new easyread_wp_bootstrap_navwalker()
  ));
} /* end header menu */
endif;

if ( ! function_exists( 'easyread_featured_slider' ) ) :
/**
 * Featured image slider, displayed on front page for static page and blog
 */
function easyread_featured_slider() {
  if ( ( is_home() || is_front_page() ) && get_theme_mod( 'easyread_featured_hide' ) == 1 ) {
		
		wp_enqueue_style( 'flexslider-css' );
		wp_enqueue_script( 'flexslider-js' );
		
    echo '<div class="flexslider">';
      echo '<ul class="slides">';

        $count = 4;
        $slidecat = get_theme_mod( 'easyread_featured_cat' );

        $query = new WP_Query( array( 'cat' => $slidecat,'posts_per_page' => $count ) );
        if ($query->have_posts()) :
          while ($query->have_posts()) : $query->the_post();
                
            if ( (function_exists( 'has_post_thumbnail' )) && ( has_post_thumbnail() ) ) :

                echo '<li>';
                      echo get_the_post_thumbnail( get_the_ID(), 'easyread-slider' );

                    echo '<div class="flex-caption">';
                      echo get_the_category_list();
                        if ( get_the_title() != '' ) echo '<a href="' . get_permalink() . '"><h2 class="entry-title">'. get_the_title().'</h2></a>';
                        echo '<div class="read-more"><a href="' . get_permalink() . '">' . __( 'Read More', 'easyread' ) .'</a></div>';
                    echo '</div>';

                echo '</li>';
            endif;

        endwhile; 
        wp_reset_query();
        endif;

      echo '</ul>';
    echo ' </div>';
  }
}
endif;

/**
 * function to show the footer info, copyright information
 */
function easyread_footer_info() {
global $easyread_footer_info;
  printf( esc_html__( 'Theme by %1$s Powered by %2$s', 'easyread' ) , '<a href="http://thrasos.net/" target="_blank">Thrasos</a>', '<a href="http://wordpress.org/" target="_blank">WordPress</a>');
}


/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function easyread_caption($output, $attr, $content) {
  if (is_feed()) {
    return $output;
  }

  $defaults = array(
    'id'      => '',
    'align'   => 'alignnone',
    'width'   => '',
    'caption' => ''
  );

  $attr = shortcode_atts($defaults, $attr);

  // If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
  if ($attr['width'] < 1 || empty($attr['caption'])) {
    return $content;
  }

  // Set up the attributes for the caption <figure>
  $attributes  = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '' );
  $attributes .= ' class="thumbnail wp-caption ' . esc_attr($attr['align']) . '"';
  $attributes .= ' style="width: ' . (esc_attr($attr['width']) + 10) . 'px"';

  $output  = '<figure' . $attributes .'>';
  $output .= do_shortcode($content);
  $output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
  $output .= '</figure>';

  return $output;
}
add_filter('img_caption_shortcode', 'easyread_caption', 10, 3);



/*
 * This display blog description from wp customizer setting.
 */
function easyread_cats() {
	$cats = array();
	$cats[0] = "All";
	
	foreach ( get_categories() as $categories => $category ) {
		$cats[$category->term_id] = $category->name;
	}

	return $cats;
}

/**
 * Custom comment template
 */
function easyread_cb_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	 <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>

	<div class="comment-author vcard">
  	<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
  	<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>', 'easyread' ), get_comment_author_link() ); ?>
  	<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

    <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
      <?php
        /* translators: 1: date, 2: time */
        sprintf( __( '%1$s at %2$s', 'easyread' ), get_comment_date(), get_comment_time() ); ?></a><?php edit_comment_link( __( 'Edit', 'easyread' ), '  ', '' );
      ?>
    </div>

  </div>

	<?php if ( $comment->comment_approved == '0' ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'easyread' ); ?></em>
		<br />
	<?php endif; ?>

	<?php comment_text(); ?>

	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php
}

/**
 * Get custom CSS from Theme setting panel and output in header
 */
if (!function_exists('get_easyread_theme_setting'))  {
  function get_easyread_theme_setting(){

    echo '<style type="text/css">';

    if ( get_theme_mod('accent_color')) {
      echo 'a:hover, a:focus,article.post .post-categories a:hover,
          .entry-title a:hover, .entry-meta a:hover, .entry-footer a:hover,
          .read-more a:hover,
          .flex-caption .post-categories a:hover, .flex-caption .read-more a:hover,
          .flex-caption h2:hover, .comment-meta.commentmetadata a:hover,
          .post-inner-content .cat-item a:hover,.navbar-default .navbar-nav > .active > a,
          .navbar-default .navbar-nav > .active > a:hover,
          .navbar-default .navbar-nav > .active > a:focus,
          .navbar-default .navbar-nav > li > a:hover,
          .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a,
          .navbar-default .navbar-nav > .open > a:hover, blockquote:before,
          .navbar-default .navbar-nav > .open > a:focus, .cat-title a,
          .single .entry-content a, .site-info a:hover {color:' . esc_html(get_theme_mod('accent_color')) . '}';

      echo 'article.post .post-categories:after, .post-inner-content .cat-item:after, #secondary .widget-title:after {background:' . esc_html(get_theme_mod('accent_color')) . '}';

      echo '.btn-default:hover, .label-default[href]:hover,
          .label-default[href]:focus, .btn-default:hover,
          .btn-default:focus, .btn-default:active,
          .btn-default.active, #image-navigation .nav-previous a:hover,
          #image-navigation .nav-next a:hover, .woocommerce #respond input#submit:hover,
          .woocommerce a.button:hover, .woocommerce button.button:hover,
          .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover,
          .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover,
          .woocommerce input.button.alt:hover, .input-group-btn:last-child>.btn:hover, .scroll-to-top:hover,
          button, html input[type=button]:hover, input[type=reset]:hover, .comment-list li .comment-body:after, .page-links a:hover span, .page-links span,
          input[type=submit]:hover, .comment-form #submit:hover, .tagcloud a:hover,
          .single .entry-content a:hover, .dropdown-menu > li > a:hover, 
          .dropdown-menu > li > a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
          .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus{background-color:' . esc_html( get_theme_mod('accent_color') ) . '; }';
    }
    
    if ( get_theme_mod('custom_css')) {
      echo html_entity_decode( esc_html( get_theme_mod( 'custom_css', 'no entry' ) ) );
    }

    echo '</style>';
  }
}
add_action('wp_head','get_easyread_theme_setting',10);