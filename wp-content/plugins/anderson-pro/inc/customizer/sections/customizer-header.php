<?php
/**
 * Register Header Content section, settings and controls for Theme Customizer
 *
 */

// Add Theme Colors section to Customizer
add_action( 'customize_register', 'anderson_pro_customize_register_header_settings' );

function anderson_pro_customize_register_header_settings( $wp_customize ) {

	// Add Upload logo image setting for WordPress 4.4 and earlier
	if ( ! current_theme_supports( 'custom-logo'  ) ) :
	
		$wp_customize->add_setting( 'anderson_theme_options[header_logo]', array(
			'default'           => '',
			'type'           	=> 'option',
			'transport'         => 'refresh',
			'sanitize_callback' => 'esc_url'
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize, 'anderson_control_header_logo', array(
				'label'    => __( 'Logo Image (replaces Site Title)', 'anderson-pro' ),
				'section'  => 'anderson_section_header',
				'settings' => 'anderson_theme_options[header_logo]',
				'priority' => 1,
				)
			)
		);
		
	endif;

}

?>