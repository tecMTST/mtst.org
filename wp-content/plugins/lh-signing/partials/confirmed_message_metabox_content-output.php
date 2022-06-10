<h3>Confirmation Message</h3>
<?php
$settings = array( 'media_buttons' => false, 'textarea_rows' => 8 );
wp_editor( $content, $this->namespace."-confirmed_message", $settings); ?>
<p>Available placeholders: %first_name% %last_name%, %bloginfo_name%, and %user_email% </p>
<h3>Confirmed but logged in message</h3>
<?php wp_editor( $confirmed_logged_in_form_text, $this->namespace."-confirmed_logged_in_form_text", $settings); ?>
<p>This is the message a logged in visitor will see when they visit the page the form is on, if they have already signed and confirmed via email. e.g You have already registered and confirmed" </p>

