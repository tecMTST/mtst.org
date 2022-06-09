<?php
/**
 * Register upgrade section, settings and controls for Theme Customizer
 *
 */

// Add Theme Colors section to Customizer
add_action( 'customize_register', 'anderson_customize_register_upgrade_settings' );

function anderson_customize_register_upgrade_settings( $wp_customize ) {
	
	// Add Upgrade / More Features Section
	$wp_customize->add_section( 'anderson_section_upgrade', array(
        'title'    => esc_html__( 'More Features', 'anderson-lite' ),
        'priority' => 60,
		'panel' => 'anderson_panel_options' 
		)
	);
	
	// Add custom Upgrade Content control
	$wp_customize->add_setting( 'anderson_theme_options[upgrade]', array(
        'default'           => '',
		'type'           	=> 'option',
        'transport'         => 'refresh',
        'sanitize_callback' => 'esc_attr'
        )
    );
    $wp_customize->add_control( new Anderson_Customize_Upgrade_Control(
        $wp_customize, 'anderson_control_upgrade', array(
            'section' => 'anderson_section_upgrade',
            'settings' => 'anderson_theme_options[upgrade]',
            'priority' => 1
            )
        )
    );

}