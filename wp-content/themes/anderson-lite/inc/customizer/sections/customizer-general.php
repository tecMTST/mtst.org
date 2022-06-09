<?php
/**
 * Register General section, settings and controls for Theme Customizer
 *
 */

// Add Theme Colors section to Customizer
add_action( 'customize_register', 'anderson_customize_register_general_settings' );

function anderson_customize_register_general_settings( $wp_customize ) {

	// Add Section for Theme Options
	$wp_customize->add_section( 'anderson_section_general', array(
        'title'    => esc_html__( 'General Settings', 'anderson-lite' ),
        'priority' => 10,
		'panel' => 'anderson_panel_options'
		)
	);

	// Add Settings and Controls for Theme Layout
	$wp_customize->add_setting( 'anderson_theme_options[layout]', array(
        'default'           => 'right-sidebar',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_layout'
		)
	);
    $wp_customize->add_control( 'anderson_control_layout', array(
        'label'    => esc_html__( 'Theme Layout', 'anderson-lite' ),
        'section'  => 'anderson_section_general',
        'settings' => 'anderson_theme_options[layout]',
        'type'     => 'radio',
		'priority' => 1,
        'choices'  => array(
            'left-sidebar' => esc_html__( 'Left Sidebar', 'anderson-lite' ),
            'right-sidebar' => esc_html__( 'Right Sidebar', 'anderson-lite' )
			)
		)
	);

	// Add Image Grayscale Headline
    $wp_customize->add_setting( 'anderson_theme_options[grayscale_filter_headline]', array(
        'default'           => '',
        'type'               => 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_grayscale_filter_headline', array(
            'label' => esc_html__( 'Image Grayscale', 'anderson-lite' ),
            'section' => 'anderson_section_general',
            'settings' => 'anderson_theme_options[grayscale_filter_headline]',
            'priority' => 2
            )
        )
    );
    $wp_customize->add_setting( 'anderson_theme_options[grayscale_filter]', array(
        'default'           => false,
        'type'               => 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
        )
    );
    $wp_customize->add_control( 'anderson_control_image_grayscale', array(
        'label'    => esc_html__( 'Enable grayscale filter for featured images', 'anderson-lite' ),
        'section'  => 'anderson_section_general',
        'settings' => 'anderson_theme_options[grayscale_filter]',
        'type'     => 'checkbox',
        'priority' => 3
        )
    );

}

?>
