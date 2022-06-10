<?php
/**
 * Register Theme Font section, settings and controls for Theme Customizer
 *
 */

// Add Theme Fonts section to Customizer
add_action( 'customize_register', 'anderson_pro_customize_register_font_settings' );

function anderson_pro_customize_register_font_settings( $wp_customize ) {

	// Add Section for Theme Fonts
	$wp_customize->add_section( 'anderson_pro_section_fonts', array(
        'title'    => __( 'Theme Fonts', 'anderson-pro' ),
        'priority' => 70,
		'panel' => 'anderson_panel_options'
		)
	);

	// Get Default Fonts from settings
	$default_options = anderson_pro_default_options();

	// Add settings and controls for theme fonts
	$wp_customize->add_setting( 'anderson_theme_options[text_font]', array(
        'default'           => $default_options['text_font'],
		'type'           	=> 'option',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'esc_attr'
		)
	);
	$wp_customize->add_control( new Anderson_Pro_Customize_Font_Control(
		$wp_customize, 'text_font', array(
			'label'      => __( 'Base Font', 'anderson-pro' ),
			'section'    => 'anderson_pro_section_fonts',
			'settings'   => 'anderson_theme_options[text_font]',
			'priority' => 1
		) )
	);

	$wp_customize->add_setting( 'anderson_theme_options[title_font]', array(
        'default'           => $default_options['title_font'],
		'type'           	=> 'option',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'esc_attr'
		)
	);
	$wp_customize->add_control( new Anderson_Pro_Customize_Font_Control(
		$wp_customize, 'title_font', array(
			'label'      => _x( 'Headings', 'font setting', 'anderson-pro' ),
			'section'    => 'anderson_pro_section_fonts',
			'settings'   => 'anderson_theme_options[title_font]',
			'priority' => 2
		) )
	);

	$wp_customize->add_setting( 'anderson_theme_options[navi_font]', array(
        'default'           => $default_options['navi_font'],
		'type'           	=> 'option',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'esc_attr'
		)
	);
	$wp_customize->add_control( new Anderson_Pro_Customize_Font_Control(
		$wp_customize, 'navi_font', array(
			'label'      => _x( 'Navigation', 'font setting', 'anderson-pro' ),
			'section'    => 'anderson_pro_section_fonts',
			'settings'   => 'anderson_theme_options[navi_font]',
			'priority' => 3
		) )
	);

	$wp_customize->add_setting( 'anderson_theme_options[widget_title_font]', array(
        'default'           => $default_options['widget_title_font'],
		'type'           	=> 'option',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'esc_attr'
		)
	);
	$wp_customize->add_control( new Anderson_Pro_Customize_Font_Control(
		$wp_customize, 'widget_title_font', array(
			'label'      => _x( 'Widget Titles', 'font setting', 'anderson-pro' ),
			'section'    => 'anderson_pro_section_fonts',
			'settings'   => 'anderson_theme_options[widget_title_font]',
			'priority' => 4
		) )
	);
}
