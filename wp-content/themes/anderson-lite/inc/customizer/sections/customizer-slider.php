<?php
/**
 * Register Post Slider section, settings and controls for Theme Customizer
 *
 */

// Add Theme Colors section to Customizer
add_action( 'customize_register', 'anderson_customize_register_slider_settings' );

function anderson_customize_register_slider_settings( $wp_customize ) {

	// Add Sections for Slider Settings
	$wp_customize->add_section( 'anderson_section_slider', array(
        'title'    => esc_html__( 'Post Slider', 'anderson-lite' ),
        'priority' => 50,
		'panel' => 'anderson_panel_options' 
		)
	);

	// Add settings and controls for Post Slider
	$wp_customize->add_setting( 'anderson_theme_options[slider_active_header]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_slider_active_header', array(
            'label' => esc_html__( 'Activate Post Slider', 'anderson-lite' ),
            'section' => 'anderson_section_slider',
            'settings' => 'anderson_theme_options[slider_active_header]',
            'priority' => 	1
            )
        )
    );
	
	$wp_customize->add_setting( 'anderson_theme_options[slider_active_magazine]', array(
        'default'           => false,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_slider_active_magazine', array(
        'label'    => esc_html__( 'Show Slider on Magazine Homepage', 'anderson-lite' ),
        'section'  => 'anderson_section_slider',
        'settings' => 'anderson_theme_options[slider_active_magazine]',
        'type'     => 'checkbox',
		'priority' => 2
		)
	);
	
	$wp_customize->add_setting( 'anderson_theme_options[slider_active]', array(
        'default'           => false,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_slider_active', array(
        'label'    => esc_html__( 'Show Slider on posts page', 'anderson-lite' ),
        'section'  => 'anderson_section_slider',
        'settings' => 'anderson_theme_options[slider_active]',
        'type'     => 'checkbox',
		'priority' => 3
		)
	);
	
	// Select Featured Posts
	$wp_customize->add_setting( 'anderson_theme_options[featured_posts_header]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_featured_posts_header', array(
            'label' => esc_html__( 'Select Featured Posts', 'anderson-lite' ),
            'section' => 'anderson_section_slider',
            'settings' => 'anderson_theme_options[featured_posts_header]',
            'priority' => 4,
			'active_callback' => 'anderson_slider_activated_callback'
            )
        )
    );
	$wp_customize->add_setting( 'anderson_theme_options[featured_posts_description]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Description_Control(
        $wp_customize, 'anderson_control_featured_posts_description', array(
			'label'    => esc_html__( 'The slideshow displays all your featured posts. You can easily feature posts by a tag of your choice.', 'anderson-lite' ),
            'section' => 'anderson_section_slider',
            'settings' => 'anderson_theme_options[featured_posts_description]',
            'priority' => 5,
			'active_callback' => 'anderson_slider_activated_callback'
            )
        )
    );

	$wp_customize->add_setting( 'anderson_theme_options[slider_animation]', array(
        'default'           => 'slide',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_slider_animation'
		)
	);
    $wp_customize->add_control( 'anderson_control_slider_animation', array(
        'label'    => esc_html__( 'Slider Animation', 'anderson-lite' ),
        'section'  => 'anderson_section_slider',
        'settings' => 'anderson_theme_options[slider_animation]',
        'type'     => 'radio',
		'priority' => 9,
		'active_callback' => 'anderson_slider_activated_callback',
        'choices'  => array(
            'slide' => esc_html__( 'Slide Effect', 'anderson-lite' ),
            'fade' => esc_html__( 'Fade Effect', 'anderson-lite' )
			)
		)
	);
	
	// Add Setting and Control for Slider Speed
	$wp_customize->add_setting( 'anderson_theme_options[slider_speed]', array(
        'default'           => 7000,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint'
		)
	);
    $wp_customize->add_control( 'anderson_theme_options[slider_speed]', array(
        'label'    => esc_html__( 'Slider Speed (in ms)', 'anderson-lite' ),
        'section'  => 'anderson_section_slider',
        'settings' => 'anderson_theme_options[slider_speed]',
        'type'     => 'number',
		'active_callback' => 'anderson_slider_activated_callback',
		'priority' => 10,
		'input_attrs' => array(
			'min'   => 1000,
			'step'  => 100,
		),
		)
	);
	
}