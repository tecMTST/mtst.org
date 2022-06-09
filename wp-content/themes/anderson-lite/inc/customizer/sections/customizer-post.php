<?php
/**
 * Register Post Settings section, settings and controls for Theme Customizer
 *
 */

// Add Theme Fonts section to Customizer
add_action( 'customize_register', 'anderson_customize_register_post_settings' );

function anderson_customize_register_post_settings( $wp_customize ) {

	// Add Section for Theme Fonts
	$wp_customize->add_section( 'anderson_section_post', array(
        'title'    => esc_html__( 'Post Settings', 'anderson-lite' ),
        'priority' => 30,
		'panel' => 'anderson_panel_options'
		)
	);

		// Add Settings and Controls for Posts
	$wp_customize->add_setting( 'anderson_theme_options[posts_length]', array(
        'default'           => 'excerpt',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_post_length'
		)
	);
    $wp_customize->add_control( 'anderson_control_posts_length', array(
        'label'    => esc_html__( 'Post length on archives', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[posts_length]',
        'type'     => 'radio',
		'priority' => 1,
        'choices'  => array(
            'index' => esc_html__( 'Show full posts', 'anderson-lite' ),
            'excerpt' => esc_html__( 'Show post excerpts', 'anderson-lite' )
			)
		)
	);
	
	// Add Post Images Headline
	$wp_customize->add_setting( 'anderson_theme_options[post_images]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_post_images', array(
            'label' => esc_html__( 'Post Images', 'anderson-lite' ),
            'section' => 'anderson_section_post',
            'settings' => 'anderson_theme_options[post_images]',
            'priority' => 	2
            )
        )
    );
	$wp_customize->add_setting( 'anderson_theme_options[post_thumbnails_index]', array(
        'default'           => true,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_posts_thumbnails_index', array(
        'label'    => esc_html__( 'Display featured images on archive pages', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[post_thumbnails_index]',
        'type'     => 'checkbox',
		'priority' => 3
		)
	);

	$wp_customize->add_setting( 'anderson_theme_options[post_thumbnails_single]', array(
        'default'           => true,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_posts_thumbnails_single', array(
        'label'    => esc_html__( 'Display featured images on single posts', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[post_thumbnails_single]',
        'type'     => 'checkbox',
		'priority' => 4
		)
	);
	
	// Add Excerpt Text setting
	$wp_customize->add_setting( 'anderson_theme_options[excerpt_text_headline]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_excerpt_text_headline', array(
            'label' => esc_html__( 'Text after Excerpts', 'anderson-lite' ),
            'section' => 'anderson_section_post',
            'settings' => 'anderson_theme_options[excerpt_text_headline]',
            'priority' => 5
            )
        )
    );
	$wp_customize->add_setting( 'anderson_theme_options[excerpt_text]', array(
        'default'           => false,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_excerpt_text', array(
        'label'    => esc_html__( 'Display [...] after excerpts', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[excerpt_text]',
        'type'     => 'checkbox',
		'priority' => 6
		)
	);
	
	
	// Add Post Meta Settings
	$wp_customize->add_setting( 'anderson_theme_options[postmeta_headline]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_postmeta_headline', array(
            'label' => esc_html__( 'Post Meta', 'anderson-lite' ),
            'section' => 'anderson_section_post',
            'settings' => 'anderson_theme_options[postmeta_headline]',
            'priority' => 7
            )
        )
    );
	$wp_customize->add_setting( 'anderson_theme_options[meta_date]', array(
        'default'           => true,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_meta_date', array(
        'label'    => esc_html__( 'Display post date', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[meta_date]',
        'type'     => 'checkbox',
		'priority' => 8
		)
	);
	$wp_customize->add_setting( 'anderson_theme_options[meta_author]', array(
        'default'           => true,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_meta_author', array(
        'label'    => esc_html__( 'Display post author', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[meta_author]',
        'type'     => 'checkbox',
		'priority' => 9
		)
	);
	$wp_customize->add_setting( 'anderson_theme_options[meta_tags]', array(
        'default'           => true,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_meta_tags', array(
        'label'    => esc_html__( 'Display post tags', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[meta_tags]',
        'type'     => 'checkbox',
		'priority' => 10
		)
	);
	
	// Add Post Footer Settings
	$wp_customize->add_setting( 'anderson_theme_options[post_footer_headline]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Header_Control(
        $wp_customize, 'anderson_control_post_footer_headline', array(
            'label' => esc_html__( 'Post Footer', 'anderson-lite' ),
            'section' => 'anderson_section_post',
            'settings' => 'anderson_theme_options[post_footer_headline]',
            'priority' => 12
            )
        )
    );
	$wp_customize->add_setting( 'anderson_theme_options[post_navigation]', array(
        'default'           => false,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_post_navigation', array(
        'label'    => esc_html__( 'Display post navigation on single posts', 'anderson-lite' ),
        'section'  => 'anderson_section_post',
        'settings' => 'anderson_theme_options[post_navigation]',
        'type'     => 'checkbox',
		'priority' => 13
		)
	);
	
}