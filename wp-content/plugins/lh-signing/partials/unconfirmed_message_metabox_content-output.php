<h3>Submission Message</h3>
<?php wp_editor( $content, $this->namespace."-unconfirmed_message", $settings); ?>
<p>This is the message a visitor will see when they submit the form to sign or join.</p>
<p>Available placeholders: %first_name% %last_name%, %bloginfo_name%, and %user_email% </p>
<h3>Unconfirmed but logged in message</h3>
<?php wp_editor($unconfirmed_logged_in_form_text, $this->namespace."-unconfirmed_logged_in_form_text", $settings); ?>
<p>This is the message a logged in visitor will see when they visit the page the form is on, if they have already signed but have not yet confirmed via email.</p>



