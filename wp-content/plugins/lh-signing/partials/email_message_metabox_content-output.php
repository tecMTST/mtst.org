<p>
<label class="screen-reader-text" id="<?php  echo $this->namespace."-email_title-prompt-text";  ?>" for="<?php  echo $this->namespace."-email_title";  ?>">Enter title here</label>
<input type="text" name="<?php  echo $this->namespace."-email_title";  ?>" id="<?php  echo $this->namespace."-email_title";  ?>"  size="50" value="<?php echo $title;  ?>" placeholder="Enter Email title here" />
</p>

<p>
<label class="screen-reader-text" id="<?php  echo $this->namespace."-carbon_copy_emails-prompt-text";  ?>" for="<?php  echo $this->namespace."-carbon_copy_emails";  ?>">Enter Carbon Copy emails</label>
<input type="email" multiple="multiple" name="<?php  echo $this->namespace."-carbon_copy_emails";  ?>" id="<?php  echo $this->namespace."-carbon_copy_emails";  ?>"  size="50" value="<?php echo $carbon_copy_emails;  ?>" placeholder="your carbon copy email addresses" />
</p>

<p>
<label class="screen-reader-text" id="<?php  echo $this->namespace."-blind_copy_emails-prompt-text";  ?>" for="<?php  echo $this->namespace."-blind_copy_emails";  ?>">Enter Blind Carbon Copy emails</label>
<input type="email" multiple="multiple" name="<?php  echo $this->namespace."-blind_copy_emails";  ?>" id="<?php  echo $this->namespace."-blind_copy_emails";  ?>"  size="50" value="<?php echo $blind_copy_emails;  ?>" placeholder="your blind carbon copy email addresses" />
</p>
<?php



$settings = array( 'media_buttons' => false );

wp_editor( $content, $this->namespace."-email_message");

?>
<p>Available placeholders: %first_name% %last_name%, %bloginfo_name%, and %user_email% </p>
<label id="<?php  echo $this->namespace."-email_button_text-prompt-text";  ?>" for="<?php  echo $this->namespace."-email_button_text";  ?>">Email confirmation button text:</label>
<input type="text" name="<?php  echo $this->namespace."-email_button_text";  ?>" id="<?php  echo $this->namespace."-email_button_text";  ?>"  size="50" value="<?php echo $this->return_email_button_text(get_the_ID());  ?>" placeholder="e.g Sign me up or Join the list" />