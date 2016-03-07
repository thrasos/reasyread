<?php
/**
 * easyread Theme Customizer
 *
 * @package easyread
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function easyread_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'easyread_customize_register' );

/**
 * Options for WordPress Theme Customizer.
 */
function easyread_customizer( $wp_customize ) {

	// logo
	$wp_customize->add_setting( 'header_logo', array(
		'default' => '',
		'transport'   => 'refresh',
                'sanitize_callback' => 'easyread_sanitize_number'
	) );
        $wp_customize->add_control(new WP_Customize_Media_Control( $wp_customize, 'header_logo', array(
    		'label' => __( 'Logo', 'easyread' ),
    		'section' => 'title_tagline',
    		'mime_type' => 'image',
    		'priority'  => 10,
    	) ) );


    global $header_show;
    $wp_customize->add_setting('header_show', array(
            'default' => 'logo-text',
            'sanitize_callback' => 'easyread_sanitize_radio_header'
        ));
        $wp_customize->add_control('header_show', array(
            'type' => 'radio',
            'label' => __('Show', 'easyread'),
            'section' => 'title_tagline',
            'choices' => $header_show
        ));

        /* Main option Settings Panel */
    $wp_customize->add_panel('easyread_main_options', array(
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __('Easyread Options', 'easyread'),
        'description' => __('Panel to update easyread theme options', 'easyread'), // Include html tags such as <p>.
        'priority' => 10 // Mixed with top-level-section hierarchy.
    ));

	// add "Content Options" section
	$wp_customize->add_section( 'easyread_content_section' , array(
		'title'      => esc_html__( 'Content Options', 'easyread' ),
		'priority'   => 50,
                'panel' => 'easyread_main_options'
	) );

	// add setting for excerpts/full posts toggle
	$wp_customize->add_setting( 'easyread_excerpts', array(
		'default'           => 1,
		'sanitize_callback' => 'easyread_sanitize_checkbox',
	) );

	// add checkbox control for excerpts/full posts toggle
	$wp_customize->add_control( 'easyread_excerpts', array(
		'label'     => esc_html__( 'Show post excerpts?', 'easyread' ),
		'section'   => 'easyread_content_section',
		'priority'  => 10,
		'type'      => 'checkbox'
	) );

	$wp_customize->add_setting( 'easyread_page_comments', array(
		'default' => 1,
		'sanitize_callback' => 'easyread_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'easyread_page_comments', array(
		'label'		=> esc_html__( 'Display Comments on Static Pages?', 'easyread' ),
		'section'	=> 'easyread_content_section',
		'priority'	=> 20,
		'type'      => 'checkbox',
	) );


	// add "Featured Posts" section
	$wp_customize->add_section( 'easyread_featured_section' , array(
		'title'      => esc_html__( 'Slider Option', 'easyread' ),
		'priority'   => 60,
                'panel' => 'easyread_main_options'
	) );

	$wp_customize->add_setting( 'easyread_featured_cat', array(
		'default' => 0,
		'transport'   => 'refresh',
                'sanitize_callback' => 'easyread_sanitize_slidecat'
	) );

	$wp_customize->add_control( 'easyread_featured_cat', array(
		'type' => 'select',
		'label' => 'Choose a category',
		'choices' => easyread_cats(),
		'section' => 'easyread_featured_section',
	) );

	$wp_customize->add_setting( 'easyread_featured_hide', array(
		'default' => 0,
		'transport'   => 'refresh',
                'sanitize_callback' => 'easyread_sanitize_checkbox'
	) );

	$wp_customize->add_control( 'easyread_featured_hide', array(
		'type' => 'checkbox',
		'label' => 'Show Slider',
		'section' => 'easyread_featured_section',
	) );


	// add "Sidebar" section
        $wp_customize->add_section('easyread_layout_section', array(
            'title' => __('Layout options', 'easyread'),
            'priority' => 31,
            'panel' => 'easyread_main_options'
        ));
            // Layout options
            global $site_layout;
            $wp_customize->add_setting('easyread_sidebar_position', array(
                 'default' => 'side-right',
                 'sanitize_callback' => 'easyread_sanitize_layout'
            ));
            $wp_customize->add_control('easyread_sidebar_position', array(
                 'label' => __('Website Layout Options', 'easyread'),
                 'section' => 'easyread_layout_section',
                 'type'    => 'select',
                 'description' => __('Choose between different layout options to be used as default', 'easyread'),
                 'choices'    => $site_layout
            ));

            $wp_customize->add_setting('accent_color', array(
                    'default' => '',
                    'sanitize_callback' => 'easyread_sanitize_hexcolor'
                ));
            $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', array(
                'label' => __('Accent Color', 'easyread'),
                'description'   => __('Default used if no color is selected','easyread'),
                'section' => 'easyread_layout_section',
            )));

	// add "Footer" section
	$wp_customize->add_section( 'easyread_footer_section' , array(
		'title'      => esc_html__( 'Footer', 'easyread' ),
		'priority'   => 90,
	) );

	$wp_customize->add_setting( 'easyread_footer_copyright', array(
		'default' => '',
		'transport'   => 'refresh',
                'sanitize_callback' => 'easyread_sanitize_strip_slashes'
	) );

	$wp_customize->add_control( 'easyread_footer_copyright', array(
		'type' => 'textarea',
		'label' => 'Copyright Text',
		'section' => 'easyread_footer_section',
	) );

        /* Easyread Other Options */
        $wp_customize->add_section('easyread_other_options', array(
            'title' => __('Other', 'easyread'),
            'priority' => 70,
            'panel' => 'easyread_main_options'
        ));
            $wp_customize->add_setting('custom_css', array(
                'default' => '',
                'sanitize_callback' => 'easyread_sanitize_strip_slashes'
            ));
            $wp_customize->add_control('custom_css', array(
                'label' => __('Custom CSS', 'easyread'),
                'description' => sprintf(__('Additional CSS', 'easyread')),
                'section' => 'easyread_other_options',
                'type' => 'textarea'
            ));

}
add_action( 'customize_register', 'easyread_customizer' );

/**
 * Adds sanitization callback function: Strip Slashes
 * @package Easyread
 */
function easyread_sanitize_strip_slashes($input) {
    return wp_kses_stripslashes($input);
}

/**
 * Sanitzie checkbox for WordPress customizer
 */
function easyread_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}
/**
 * Adds sanitization callback function: Sidebar Layout
 * @package Easyread
 */
function easyread_sanitize_layout( $input ) {
    global $site_layout;
    if ( array_key_exists( $input, $site_layout ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: colors
 * @package Easyread
 */
function easyread_sanitize_hexcolor($color) {
    if ($unhashed = sanitize_hex_color_no_hash($color))
        return '#' . $unhashed;
    return $color;
}

/**
 * Adds sanitization callback function: Slider Category
 * @package Easyread
 */
function easyread_sanitize_slidecat( $input ) {

    if ( array_key_exists( $input, easyread_cats()) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Radio Header
 * @package Easyread
 */
function easyread_sanitize_radio_header( $input ) {
   global $header_show;
    if ( array_key_exists( $input, $header_show ) ) {
        return $input;
    } else {
        return '';
    }
}

/**
 * Adds sanitization callback function: Number
 * @package Easyread
 */
function easyread_sanitize_number($input) {
    if ( isset( $input ) && is_numeric( $input ) ) {
        return $input;
    }
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function easyread_customize_preview_js() {
	wp_enqueue_script( 'easyread_customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), '002', true );
}
add_action( 'customize_preview_init', 'easyread_customize_preview_js' );

/**
 * Add CSS for custom controls
 */
function easyread_customizer_custom_control_css() {
	?>
    <style>
        #customize-control-easyread-main_body_typography-size select, #customize-control-easyread-main_body_typography-face select,#customize-control-easyread-main_body_typography-style select { width: 60%; }
    </style><?php
}
add_action( 'customize_controls_print_styles', 'easyread_customizer_custom_control_css' );
