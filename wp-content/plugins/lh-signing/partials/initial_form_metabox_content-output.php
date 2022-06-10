<table>
<tbody>
<tr>
<td>
<label id="<?php  echo $this->login_link_field_name;  ?>" for="<?php  echo $this->login_link_field_name;  ?>">Logged the confirmed User in:</label>
</td>
<td>
<select name="<?php echo $this->login_link_field_name; ?>" id="<?php echo $this->login_link_field_name; ?>">
<option value="1" <?php  if ($login_link == 1){ echo 'selected="selected"'; }  ?>>Yes</option>
<option value="0" <?php  if ($login_link == 0){ echo 'selected="selected"';}  ?>>No</option>
</select>
(<a href="https://lhero.org/portfolio/lh-signing/#<?php echo $this->login_link_field_name; ?>">What does this mean?</a>)
<?php wp_nonce_field( $this->namespace."-save_post-backend-nonce", $this->namespace."-save_post-backend-nonce" ); ?>
</td>
</tr>
<tr>
<td>
<label id="<?php  echo $this->allow_unconfirmed_transition_field_name;  ?>" for="<?php  echo $this->allow_unconfirmed_transition_field_name;  ?>">Allow unconfirmed transition:</label>
</td>
<td>
<select name="<?php echo $this->allow_unconfirmed_transition_field_name; ?>" id="<?php echo $this->allow_unconfirmed_transition_field_name; ?>">
<option value="1" <?php  if ($allow_unconfirmed_transition == 1){ echo 'selected="selected"'; }  ?>>Yes</option>
<option value="0" <?php  if ($allow_unconfirmed_transition == 0){ echo 'selected="selected"';}  ?>>No</option>
</select>
(<a href="https://lhero.org/portfolio/lh-signing/#<?php echo $this->allow_unconfirmed_transition_field_name; ?>">What does this mean?</a>)
</td>
</tr>
<tr>
<td>
<label id="<?php  echo $this->allow_confirmed_email_field_name;  ?>" for="<?php  echo $this->allow_confirmed_email_field_name;  ?>">Email Confirmed Users:</label>
</td>
<td>
<select name="<?php echo $this->allow_confirmed_email_field_name; ?>" id="<?php echo $this->allow_confirmed_email_field_name; ?>">
<option value="1" <?php  if ($allow_confirmed_email == 1){ echo 'selected="selected"'; }  ?>>Yes</option>
<option value="0" <?php  if ($allow_confirmed_email == 0){ echo 'selected="selected"';}  ?>>No</option>
</select>
(<a href="https://lhero.org/portfolio/lh-signing/#<?php echo $this->allow_confirmed_email_field_name; ?>">What does this mean?</a>)
</td>
</tr>
</tbody>
</table>