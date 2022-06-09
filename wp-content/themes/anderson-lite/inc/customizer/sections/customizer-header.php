<?php
/**
 * Register Header Content section, settings and controls for Theme Customizer
 *
 */

// Add Theme Fonts section to Customizer
add_action( 'customize_register', 'anderson_customize_register_header_settings' );

function anderson_customize_register_header_settings( $wp_customize ) {

	// Add Section for Theme Fonts
	$wp_customize->add_section( 'anderson_section_header', array(
        'title'    => esc_html__( 'Header Content', 'anderson-lite' ),
        'priority' => 20,
		'panel' => 'anderson_panel_options'
		)
	);
	
	// Add Header Content Headline
	$wp_customize->add_setting( 'anderson_theme_options[header_content]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_header_content', array(
            'label' => esc_html__( 'Header Content', 'anderson-lite' ),
            'section' => 'anderson_section_header',
            'settings' => 'anderson_theme_options[header_content]',
            'priority' => 2
            )
        )
    );
	$wp_customize->add_setting( 'anderson_theme_options[header_content_description]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Description_Control(
        $wp_customize, 'anderson_control_header_content_description', array(
            'label' =>  esc_html__( 'The Header Content will be displayed on the right hand side of the header area.', 'anderson-lite' ),
            'section' => 'anderson_section_header',
            'settings' => 'anderson_theme_options[header_content_description]',
            'priority' => 3
            )
        )
    );

	// Add Settings and Controls for Header
	$wp_customize->add_setting( 'anderson_theme_options[header_icons]', array(
        'default'           => false,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_header_icons', array(
        'label'    => esc_html__( 'Display Social Icons on header area', 'anderson-lite' ),
        'section'  => 'anderson_section_header',
        'settings' => 'anderson_theme_options[header_icons]',
        'type'     => 'checkbox',
		'priority' => 4
		)
	);
	$wp_customize->add_setting( 'anderson_theme_options[header_ad_code]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_header_ad_code'
		)
	);
    $wp_customize->add_control( 'anderson_control_header_ad_code', array(
        'label'    => esc_html__( 'Ad Banner Code (768x90)', 'anderson-lite' ),
        'section'  => 'anderson_section_header',
        'settings' => 'anderson_theme_options[header_ad_code]',
        'type'     => 'textarea',
		'priority' => 5
		)
	);
	
}


?>