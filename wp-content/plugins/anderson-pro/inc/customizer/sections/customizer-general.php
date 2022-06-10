<?php
/**
 * Register General section, settings and controls for Theme Customizer
 *
 */

// Add Theme Colors section to Customizer
add_action( 'customize_register', 'anderson_pro_customize_register_general_settings' );

function anderson_pro_customize_register_general_settings( $wp_customize ) {

	// Add Footer Settings
	$wp_customize->add_setting( 'anderson_theme_options[footer_text]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_pro_sanitize_footer_text'
		)
	);
    $wp_customize->add_control( 'anderson_control_footer_text', array(
        'label'    => __( 'Footer Text', 'anderson-pro' ),
        'section'  => 'anderson_section_general',
        'settings' => 'anderson_theme_options[footer_text]',
        'type'     => 'textarea',
		'priority' => 4
		)
	);
	$wp_customize->add_setting( 'anderson_theme_options[credit_link]', array(
        'default'           => true,
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'anderson_pro_sanitize_checkbox'
		)
	);
    $wp_customize->add_control( 'anderson_control_credit_link', array(
        'label'    => __( 'Display Credit Link to ThemeZee on footer line.', 'anderson-pro' ),
        'section'  => 'anderson_section_general',
        'settings' => 'anderson_theme_options[credit_link]',
        'type'     => 'checkbox',
		'priority' => 5
		)
	);
	
	// Remove default Deactivate Google Fonts Setting
	$wp_customize->remove_control('anderson_control_default_fonts');
	$wp_customize->remove_control('anderson_control_deactivate_google_fonts');
	
}

?>