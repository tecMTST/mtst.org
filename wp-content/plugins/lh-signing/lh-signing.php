<?php
/*
 Plugin Name: LH Signing
 Plugin URI: https://lhero.org/portfolio/lh-signing/
 Description: Adds signing functionality, create petitions, validated lists etc
 Author: Peter Shaw
 Author URI: https://shawfactor.com
 Version: 2.83
 License: GPL v3 (http://www.gnu.org/licenses/gpl.html)
*/

class LH_Signing_plugin {

var $opt_name = 'lh_signing-options';
var $hidden_field_name = 'lh_signing-submit_hidden';
var $login_link_field_name = '_lh_signing-login_link';
var $allow_confirmed_email_field_name  = '_lh_signing-allow_confirmed_email';
var $allow_unconfirmed_transition_field_name = '_lh_signing-allow_unconfirmed_transition';
var $email_title_field_name = 'lh_signing-email_title';
var $email_bcc_field_name = 'lh_signing-email_bcc';
var $message_field_name = 'lh_signing-message';
var $page_id_field = 'lh_signing-page_id';
var $password_reset_page_id_field = 'lh_signing-password_reset_page_id';
var $namespace = 'lh_signing';
var $signing_states = array('signing_sign_confirmed','signing_sign_unconfirmed');
var $plugin_version = '2.79';

var $filename;
var $options;

private function areValidEmails($email_list) {
    $emails = explode(",", $email_list);

$sendEmail = array();

foreach($emails as $email) {
$email = trim($email);
   if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {

unset($sendEmail);
       $sendEmail = FALSE;
       continue;
   } else {

if (is_array($sendEmail)){

$sendEmail[] = $email;

}
}

}

return $sendEmail;
}


private function ensure_user_is_added($email, $first_name = NULL, $last_name = NULL){

$user = get_user_by( 'email', $email);

if (!$user){

$user_id = $this->handle_new_user( $email, $first_name, $last_name );

$user = get_user_by( 'ID', $user_id);

} else {

if (!$user->roles[0]){

wp_update_user(array(
    'ID' => $user->ID,
    'role' => 'unclaimed'
));

}

}


return $user;
}

private function queue_email($user, $post, $title, $message, $headers){


if (class_exists('LH_Email_queue_class')) {


if (!$lh_email_queue_instance){

$lh_email_queue_instance = new LH_Email_queue_class();


}

$lh_email_queue_instance->queue_email($user, $post, $title, $message, $headers);

} else {

wp_mail( $user->user_email, $title, $message, $headers);


}


}




private function arrayToCsv( $fields, $delimiter = ';', $enclosure = '"', $encloseAll = false, $nullToMysqlNull = false ) {
    $delimiter_esc = preg_quote($delimiter, '/');
    $enclosure_esc = preg_quote($enclosure, '/');

    $output = array();
    foreach ( $fields as $field ) {
        if ($field === null && $nullToMysqlNull) {
            $output[] = 'NULL';
            continue;
        }

        // Enclose fields containing $delimiter, $enclosure or whitespace
        if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
            $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
        }
        else {
            $output[] = $field;
        }
    }

    return implode( $delimiter, $output );
}


private function generate_csv_string($vararray){
	
$csvString = '';

$bar = array_flip((array)$vararray[0]);

$csvString .= $this->arrayToCsv($bar,",")."\n";

foreach ($vararray as $fields) {

$csvString .= $this->arrayToCsv($fields,",")."\n";

 }

return $csvString;


}


private function domain_exists($email, $record = 'MX'){
$pieces = explode("@", $email);

return checkdnsrr($pieces[1], $record);

}

private function maybe_upgrade_user($user){

//only run this process if the current user has an unclaimed role or no role (this should not be the case)
if (($user->roles[0] == 'unclaimed') || !$user->roles[0]){


$default_role = get_option( 'default_role' );

wp_update_user(array(
    'ID' => $user->ID,
    'role' => $default_role
));


}




}


private function return_signing_button($user, $post){


$button = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
<td><a class="confirm_button" href="'.$this->generate_url( $user, $post ).'">'.$this->return_email_button_text($post).'</a></td>
        </tr>
      </table>
    </td>
  </tr>
</table>';

return $button;



}

private function get_connection_id_by_type($type){

global $wpdb;

$sql = "SELECT p2p_id FROM ".$wpdb->prefix."p2p WHERE p2p_type = '" .$type. "'";



$from = $wpdb->get_var($sql);

if (is_numeric($from)){


return $from;

} else {


return false;

}




}



private function get_connection_from_by_type($type){

global $wpdb;

$sql = "SELECT p2p_from FROM ".$wpdb->prefix."p2p WHERE p2p_type = '" .$type. "'";



$from = $wpdb->get_var($sql);


return $from;




}

private function get_connection_type_by_id($id){

global $wpdb;

$sql = "SELECT p2p_type FROM ".$wpdb->prefix."p2p WHERE p2p_id = '" .$id. "'";

$type = $wpdb->get_var($sql);

if (is_string($type)){

return $type;

} else {

return false;

}




}


private function get_connection_from_by_id($id){

global $wpdb;

$sql = "SELECT p2p_from FROM ".$wpdb->prefix."p2p WHERE p2p_id = '" .$id. "'";

$from = $wpdb->get_var($sql);

return $from;

}

private function get_connection_to_by_id($id){

global $wpdb;

$sql = "SELECT p2p_to FROM ".$wpdb->prefix."p2p WHERE p2p_id = '" .$id. "'";

$from = $wpdb->get_var($sql);

return $from;

}


private function personalise_message($message,$post,$user){

$message = str_replace('%post_title%', $post->post_title, $message);
$message = str_replace('%first_name%', $user->first_name, $message);
$message = str_replace('%last_name%', $user->last_name, $message);
$message = str_replace('%user_email%', $user->user_email, $message);
$message = str_replace('%user_login%', $user->user_login, $message);
$message = str_replace('%bloginfo_name%',get_bloginfo('name','display'), $message);

return $message;

}


private function list_users($users){

echo "<ul>";

foreach ( $users as $user ) { 

echo '<li><a href="'.get_edit_user_link( $user->ID ).'">'.get_the_author_meta( 'display_name', $user->ID ).'</a></li>';

}

echo "</ul>";




}

private function return_approved_posttypes() {

$posttypes = array('post','page');

$posttypes = apply_filters('lh_signing_posttypes_filter', $posttypes);

return $posttypes;


}


private function use_email_template( $message ) {

if (file_exists(get_stylesheet_directory().'/'.$this->namespace.'-template.php')){

ob_start();

include( get_stylesheet_directory().'/'.$this->namespace.'-template.php');

$message = ob_get_contents();

ob_end_clean();


} else {

ob_start();

include( plugin_dir_path( __FILE__ ).'/'.$this->namespace.'-template.php');

$message = ob_get_contents();

ob_end_clean();


}

if (!class_exists('LH_Css_To_Inline_Styles')) {


require_once('includes/lh-css-to-inline-styles-class.php');


}

//turn off error reporting as templates may not always be perfect


error_reporting(0);
  
$doc = new DOMDocument();

$doc->loadHTML($message);

$header_nodevalue = $doc->getElementsByTagName('style')->item(0)->nodeValue;

$header_style = $doc->getElementsByTagName('style')->item(0);

$header_style->parentNode->removeChild($header_style);

$output = $doc->saveHTML();




// create instance of inline css class
  
$lh_css_to_inline_styles = new LH_Css_To_Inline_Styles();


$lh_css_to_inline_styles->setHTML($output);


$lh_css_to_inline_styles->setCSS($header_nodevalue);

// output

$message = $lh_css_to_inline_styles->convert(); 
  


error_reporting(1);

return $message;


}


private function create_token( $user, $post ) {

// random salt
$token = wp_generate_password( 20, false );

// we're sending this to the user
$hash  = wp_hash($token);

$p2p_id = p2p_type( 'signing_sign_unconfirmed' )->get_p2p_id( $user->ID, $post->ID );

if ( $p2p_id ) {

$key = $this->namespace."-confirmation_token";

p2p_update_meta( $p2p_id, $key, $hash);

return $token;

} else {

return false;

}

}


private function curpageurl() {
	$pageURL = 'http';

	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
		$pageURL .= "s";
}

	$pageURL .= "://";

	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

}

	return $pageURL;
}

private function generate_url( $user, $post ) {

if ($token = $this->create_token( $user, $post )){

	$url =  ' '.preg_replace('/\?.*/', '', $this->curpageurl());
	$url .= "?".$this->namespace."-action=sign&".$this->namespace."-uid=".$user->ID."&".$this->namespace."-token=".$token;


if (get_post_meta($post->ID, $this->login_link_field_name, true )){

$url = add_query_arg( $this->namespace.'-action', 'login', $url);

}

return $url;

} else {


return false;

}

}

private function send_initial_email( $user, $post ) {

$title = get_post_meta( $post->ID, $this->namespace."-email_title", true );

$title = $this->personalise_message($title,$post,$user);

$title = apply_filters( 'lh_signing_email_title_filter', $title, $user, $post );

$carbon_copy_emails = get_post_meta( $post->ID, $this->namespace."-carbon_copy_emails", true );

$blind_copy_emails = get_post_meta( $post->ID, $this->namespace."-blind_copy_emails", true );

$headers = array('Content-Type: text/html; charset=UTF-8');

$headers[] = 'Cc: '.$carbon_copy_emails;

$headers[] = 'Bcc: '.$blind_copy_emails; // note you can just use a simple email addres

$message = wpautop(do_shortcode(get_post_meta( $post->ID, $this->namespace."-email_message", true )));

$message = apply_filters( 'lh_signing_email_message_filter', $message, $user, $post );

$message = $this->personalise_message($message,$post,$user);



$headers = apply_filters( 'lh_signing_headers_filter', $headers, $user, $post );

//default add_url is true
$add_url = true;
$add_url = apply_filters( 'lh_signing_add_url_filter', $add_url, $user, $post );

if ($add_url){


//ensure the email contains the signing url
if (strpos($message, '%lh_signing_sign_url%') !== false) {

$message = str_replace('%lh_signing_sign_url%', $this->return_signing_button($user, $post), $message);

} else {

$message .= $this->return_signing_button($user, $post);


}

}

$message = $this->use_email_template( $message );

$use_email_queu = get_post_meta( $post->ID, $this->use_email_queu_field_name, true );

if ($use_email_queu == 1){

$this->queue_email($user, $post, $title, $message, $headers);

} else {

wp_mail( $user->user_email, $title, $message, $headers);


}


}


private function send_final_email( $user, $post ) {

$title = get_post_meta( $post->ID, "_".$this->namespace."-confirmed_email_title", true );

$title = $this->personalise_message($title,$post,$user);

$message = wpautop(do_shortcode(get_post_meta( $post->ID, "_".$this->namespace."-confirmed_email_message", true )));

$message = $this->personalise_message($message,$post,$user);

$headers = array('Content-Type: text/html; charset=UTF-8');

$message = $this->use_email_template( $message );

$this->queue_email($user, $post, $title, $message, $headers);


}



private function handle_new_user( $email, $first_name = NULL, $last_name = NULL ) {

global $wpdb;

$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );

$userdata = array(
    'user_login'  =>  $email,
    'user_email' => $email,
    'role' => 'unclaimed',
    'user_pass'   =>  $random_password 
);



$user_id = wp_insert_user( $userdata ) ;

$sql = "update ".$wpdb->users." set user_login = user_email where ID = '".$user_id."'";

$result = $wpdb->get_results($sql);

wp_update_user( array( 'ID' => $user_id, 'first_name' => $first_name, 'last_name' => $last_name, 'display_name' => $first_name." ".$last_name ));

apply_filters( 'lh_signing_http_post_filter', $user_id);

return $user_id;

}




private function format_results($post){

if( is_wp_error( $GLOBALS[$this->namespace.'-insert_result']) ) {


$error = $GLOBALS[$this->namespace.'-insert_result'];

$message = '<p>There was an error</p><p><strong>'.$error->get_error_code() .'</strong>: '.$error->get_error_message() .'</p>';


} else {

$type = $this->get_connection_type_by_id($GLOBALS[$this->namespace.'-insert_result']);

$from = $this->get_connection_from_by_id($GLOBALS[$this->namespace.'-insert_result']);

$user = get_user_by( 'ID', $from);


if ($type == "signing_sign_unconfirmed"){

$message = get_post_meta( $post->ID, $this->namespace."-unconfirmed_message", true );

} elseif ($type == "signing_sign_confirmed"){

$message = get_post_meta( $post->ID, $this->namespace."-confirmed_message", true );

} else {

$message = "something went wrong";

}


$message = $this->personalise_message(wpautop(do_shortcode($message)),$post,$user);

}


return $message;


}

private function return_submit_text($post) {


if (($submit_text = get_post_meta( $post->ID, $this->namespace."-submit_text", true )) != ""){

return $submit_text;

} else {



return false;


}




}

private function return_email_button_text($post) {

if (is_object($post)){

$id = $post->ID;

} else {

$id = $post;

}

if (($email_button_text = get_post_meta( $id, "_".$this->namespace."-email_button_text", true )) != ""){

return $email_button_text;

} elseif (($email_button_text = get_post_meta( $id, $this->namespace."-email_button_text", true )) != ""){

return $email_button_text;

$email_button_text = "Sign me Up!";

} else {


return "Sign me Up!";


}

return $email_button_text;


}


private function return_unconfirmed_logged_in_form_text($post) {

if (($unconfirmed_logged_in_form_text = get_post_meta( $post->ID, "_".$this->namespace."-unconfirmed_logged_in_form_text", true ))  == ""){

$unconfirmed_logged_in_form_text = "You have already registered but you have yet to confirm, please check your email to confirm your signature";

}

return $unconfirmed_logged_in_form_text;


}
  
private function return_confirmed_logged_in_form_text($post){

if (($confirmed_logged_in_form_text = get_post_meta( $post->ID, $this->namespace."-confirmed_logged_in_form_text", true )) == ""){

$confirmed_logged_in_form_text = "You have already added your signature";

}

return $confirmed_logged_in_form_text;


}



private function logged_out_form($atts, $post) {

   extract( shortcode_atts( array (
        'first_name' => 1,
	'last_name' => 1,
        'first_name_placeholder' => 'Your First Name',
        'last_name_placeholder' => 'Your Last Name',
        'email_placeholder' => 'Your Email',
        'submit_value' => 'Add your Signature'
), $atts ) );


if ($check_submit_value = $this->return_submit_text($post)){

$submit_value = $check_submit_value;

} 

wp_enqueue_script('lh_signing-script', plugins_url( '/scripts/lh-signing.js' , __FILE__ ), array(), $this->plugin_version, true  );
  
$content = '';

$content .= "\n<form name=\"lh_signing-form\" class=\"lh_signing-form\" action=\"".get_permalink($post)."#".$this->namespace."-content_message-div\" method=\"post\" data-".$this->namespace."-nonce=\"".wp_create_nonce($this->namespace."-nonce")."\"  >";

$content .= "\n<noscript>Please switch on Javascript to enable this registration</noscript>\n\n";

if (isset($first_name)){
$content .= "\n<p><input name=\"".$this->namespace."-first_name\" class=\"".$this->namespace."-first_name\" value=\"\" type=\"text\" placeholder=\"".$first_name_placeholder."\" autocomplete=\"fname\" required=\"required\" /></p>";
}

if (isset($last_name)){
$content .= "\n<p><input name=\"".$this->namespace."-last_name\" class=\"".$this->namespace."-last_name\" value=\"\" type=\"text\" placeholder=\"".$last_name_placeholder."\" autocomplete=\"lname\" required=\"required\" /></p>";
}

$content = apply_filters( 'lh_signing_before_email_logged_out_form_filter',$content, $atts, $post);

$content .= "\n<p><input name=\"".$this->namespace."-email\" class=\"".$this->namespace."-email\" value=\"\" type=\"email\" placeholder=\"".$email_placeholder."\" autocomplete=\"email\" required=\"required\" /></p>";


$content = apply_filters( 'lh_signing_intermediate_logged_out_form_filter',$content, $atts, $post);


$this_page_id = get_queried_object_id();

if (isset($this->options[ $this->page_id_field ]) and ($this->options[ $this->page_id_field ] == $this_page_id)){

ob_start();

apply_filters( 'register_form' , $errors );

$content .= ob_get_contents();

ob_end_clean();

}


$content .= "\n<input name=\"".$this->namespace."-nonce\"  class=\"".$this->namespace."-nonce\" value=\"\" type=\"hidden\" />";

$content .= "\n<p><input type=\"submit\" name=\"".$this->namespace."-submit\" class=\"".$this->namespace."-submit\" value=\"".$submit_value."\"/></p>";

$content .= "\n</form>
";

$content = apply_filters( 'lh_signing_final_logged_out_form_filter',$content, $atts, $post);

return $content;



}



private function logged_in_form($atts, $post) {


extract( shortcode_atts( array (
        'submit_value' => 'Add your Signature'
), $atts ) );
  

$content = '';


if ($check_submit_value = $this->return_submit_text($post)){

$submit_value = $check_submit_value;

} 


$user = wp_get_current_user();

if (p2p_connection_exists( 'signing_sign_unconfirmed', array( 'from' => $user->ID, 'to' => $post->ID ) )){


$content .= "\n<p>".$this->return_unconfirmed_logged_in_form_text($post)."</p>";


} elseif  (p2p_connection_exists( 'signing_sign_confirmed', array( 'from' => $user->ID, 'to' => $post->ID ) )){


$content .= "\n<p>".$this->return_confirmed_logged_in_form_text($post)."</p>";



} else {

$content .= "\n<form name=\"lh_signing-form\" id=\"lh_signing-form\" action=\"".get_permalink($post)."#".$this->namespace."-content_message-div\" method=\"post\" >";



$content .= "\n<input id=\"".$this->namespace."-nonce\" name=\"".$this->namespace."-nonce\" value=\"".wp_create_nonce($this->namespace."-nonce")."\" type=\"hidden\" />";

$content .= "\n<p><input type=\"submit\" id=\"".$this->namespace."-submit\" name=\"".$this->namespace."-submit\" value=\"".$submit_value."\"/></p>";

$content .= "\n</form>";



}

return $content;

}


private function is_allowable_state_transition( $user, $post, $type) {


if (($allow_unconfirmed_transition = get_post_meta( $post->ID, $this->allow_unconfirmed_transition_field_name, true )) == 1){

return true;

} elseif ( $p2p_id = p2p_type( 'signing_sign_confirmed' )->get_p2p_id( $user->ID, $post->ID )){

$error = new WP_Error( 'error', __( "Transition not allowed, already confirmed", $this->namespace ) );

return $error;


} else {

return true;

}




}

private function transition_signing_state( $user, $post, $type) {

if ($result = p2p_type( $type )->connect( $user->ID, $post->ID, array(
    'date' => current_time('mysql')
) )){

$array_to_remove = array($type);

$states = array_diff($this->signing_states,$array_to_remove);

foreach ($states as $state){


$foo = p2p_type( $state )->disconnect( $user->ID, $post->ID );

}

return $result;

} else {

$error = new WP_Error( 'error', __( "Something went wrong", $this->namespace ) );

return $error;

}

}

private function move_state_if_required( $user, $post, $type) {

if ( $p2p_id = p2p_type( $type )->get_p2p_id( $user->ID, $post->ID )) {

//the post already has this state return the p2p_id

return $p2p_id;

} elseif ($transition_status = $this->is_allowable_state_transition( $user, $post, $type) ){

if (is_wp_error( $transition_status ) ) {

return $transition_status;

} else {

return $this->transition_signing_state( $user, $post, $type);

}


}

}
private function action_signing( $user, $post, $type) {

$result = $this->move_state_if_required( $user, $post, $type);

if ( !is_wp_error( $result ) ) {


if ($type == 'signing_sign_unconfirmed'){

$this->send_initial_email( $user, $post );

}

$allow_confirmed_email = get_post_meta( $post->ID, $this->allow_confirmed_email_field_name, true );

if (($type == 'signing_sign_confirmed') and $allow_confirmed_email){

$this->send_final_email( $user, $post );

}


if ($type == 'signing_sign_confirmed'){

$this->maybe_upgrade_user($user);

}

}

return $result;


}


private function return_token($user,$post,$token){

do_action("p2p_init");


$p2p_id = p2p_type( 'signing_sign_unconfirmed' )->get_p2p_id( $user->ID, $post->ID );

if (($p2pmeta = p2p_get_meta( $p2p_id, $this->namespace."-confirmation_token", true)) != ""){

return $p2pmeta;


} elseif (($usermeta = get_user_meta($user->ID, $this->namespace."_".$post->ID."-confirmation_token", true)) != ""){

return $usermeta;

} else {

return false;

}



}


private function validate_token($user,$post,$token) {

if ($meta = $this->return_token($user,$post,$token)){

$hash  = wp_hash($token);

if ($meta == $hash){

return true;

} else {


return false;


}

} else {

return false;

}

}

private function validate_signing_token($user,$post,$token) {

if ($meta = $this->return_token($user,$post,$token)){

$hash  = wp_hash($token);

if ($meta == $hash){

$p2p_id = p2p_type( 'signing_sign_unconfirmed' )->get_p2p_id( $user->ID, $post->ID );

p2p_delete_meta( $p2p_id, $this->namespace."-confirmation_token");

return true;

} else {


return false;


}

} else {

return false;

}

}




public function autologin_via_url(){

if (isset($_GET[$this->namespace.'-action']) and isset($_GET[$this->namespace.'-uid']) and($_GET[$this->namespace.'-action'] == "login") and ($user = get_user_by('ID', $_GET[$this->namespace.'-uid']))){


$postdata = get_post(url_to_postid(strtok($this->curpageurl(), '?')));

$token = $_GET[$this->namespace.'-token'];



if ($this->validate_token($user,$postdata,$token)){



wp_set_auth_cookie( $user->ID );

do_action( 'wp_login', $user->user_login);
do_action( 'lh_signing_after_login_action', $user, $postdata);


} 


wp_redirect( add_query_arg( $this->namespace.'-action', 'sign', $this->curpageurl() )."#".$this->namespace."-content_message-div" );
exit;


}

}





public function list_attached_users($id, $list)  {

if (!is_numeric($id)){

$id = get_queried_object_id();

} 

$users = get_users( array(
  'connected_type' => array($list),
  'connected_items' => $id,
  'fields' => 'all' 
) );

$return_string = "<ul>";

foreach ( $users as $user ) {

$return_string .= '<li>'. $user->{'display_name'} .'</li>';

}

$return_string .= "</ul>";

return $return_string;

}

public function count_attached_users($id, $list) {

if (!is_numeric($id)){

$id = get_queried_object_id();

} 

$users = get_users( array(
  'connected_type' => array($list),
  'connected_items' => $id,
  'fields' => 'all'
) );


$user_count = 0;

foreach ( $users as $user ) {

$user_count++;

}

$return_string = $user_count;

return $return_string;

}


public function the_content_filter( $content ) {

global $post;


if (isset( $post->post_content) and has_shortcode( $post->post_content, 'lh_signing_form' )){

if (isset($GLOBALS[$this->namespace.'-insert_result'])){

$content = '<div id="'.$this->namespace.'-content_message-div">'.$this->format_results($post).'</div>';

} 



}




// Returns the content.





return $content;


}










public function register_p2p_connection_types() {

if ( current_user_can( 'edit_users' ) ) {
    /* A user with admin privileges */
$admin_box = true;

} else {
    /* A user without admin privileges */
$admin_box = false;
}

  p2p_register_connection_type( array(
	'title' => 'Confirmed Signature',
        'name' => 'signing_sign_confirmed',
        'from' => 'user',
        'to' => $this->return_approved_posttypes(),
'admin_column' => 'from',
        'admin_box' => $admin_box,
'admin_dropdown' => 'from'
    ) );

  p2p_register_connection_type( array(
	'title' => 'Unconfirmed Signature',
        'name' => 'signing_sign_unconfirmed',
        'from' => 'user',
        'to' => $this->return_approved_posttypes(),
'admin_column' => 'from',
       'admin_box' => $admin_box,
'admin_dropdown' => 'from'
    ) );




}



public function save_data(){

if (!is_admin()){

global $post;


if (isset($_GET[$this->namespace.'-action']) and ($_GET[$this->namespace.'-action'] == "sign") and isset($_GET[$this->namespace.'-uid']) and ($user = get_user_by('ID', $_GET[$this->namespace.'-uid']))){



$token = $_GET[$this->namespace.'-token'];

if ($this->validate_signing_token($user,$post,$token)){

//hashes match so the documents can be signed
$GLOBALS[$this->namespace.'-insert_result'] = $this->action_signing( $user, $post, 'signing_sign_confirmed');

do_action( 'lh_signing_after_signing_action', $user, $post );


}

} elseif (isset($_POST[$this->namespace.'-submit'])) {

if ( wp_verify_nonce( $_POST[$this->namespace.'-nonce'], $this->namespace.'-nonce') ) {




if ( is_user_logged_in() ){

$user = wp_get_current_user();

$GLOBALS[$this->namespace.'-insert_result'] = $this->action_signing( $user, $post, 'signing_sign_confirmed');



} else {

$test = trim(sanitize_user($_POST[$this->namespace.'-email']));

if (is_email($test) and ($this->domain_exists($test))){

$email = trim(wp_filter_nohtml_kses($_POST[$this->namespace.'-email']));

} else {


$error = new WP_Error( 'error', __( "Invalid email", $this->namespace ) );

}


if ( isset( $_POST[$this->namespace.'-full_name'])){

$full_name = sanitize_text_field($_POST[$this->namespace.'-full_name']);

$pieces = explode(" ", $full_name);

$first_name = $pieces[0];

if (isset($pieces[1])){

$last_name = $pieces[1];

} else {

$last_name = " ";

}



} elseif ( isset( $_POST[$this->namespace.'-first_name']) &&  isset( $_POST[$this->namespace.'-last_name']) ){

$first_name = sanitize_text_field($_POST[$this->namespace.'-first_name']);

$last_name = sanitize_text_field($_POST[$this->namespace.'-last_name']);

} else {

$first_name = "Not";

$last_name = "Supplied";

}

if ($user = $this->ensure_user_is_added($email, $first_name, $last_name)){

$GLOBALS[$this->namespace.'-insert_result'] = $this->action_signing( $user, $post, 'signing_sign_unconfirmed');


} 

}

}

} elseif (isset($post->ID) and isset($this->options[ $this->page_id_field ]) and ($this->options[ $this->page_id_field ] == $post->ID) and is_user_logged_in() ){


//They are registered, logged in and this is the registration page so redirect them to home
wp_redirect( home_url() );
exit;



}

}
  
}



public function wp_user_logged_in_shortcode( $atts, $content = null ) {
	if ( is_user_logged_in() ) {
		return do_shortcode($content);
	}
}




public function wp_user_logged_out_shortcode( $atts, $content = null ) {
	if ( !is_user_logged_in() ) {
		return do_shortcode($content);
	}
}



public function register_shortcodes(){

add_shortcode($this->namespace.'_form', array($this,"form_shortcode_output"));
add_shortcode($this->namespace.'_unconfirmed_count', array($this,"unconfirmed_count_shortcode_output"));
add_shortcode($this->namespace.'_confirmed_count', array($this,"confirmed_count_shortcode_output"));
add_shortcode($this->namespace.'_total_count', array($this,"total_count_shortcode_output"));
add_shortcode($this->namespace.'_unconfirmed_list', array($this,"unconfirmed_list_shortcode_output"));
add_shortcode($this->namespace.'_confirmed_list', array($this,"confirmed_list_shortcode_output"));
add_shortcode($this->namespace.'_userloggedin', array($this,"wp_user_logged_in_shortcode"));
add_shortcode($this->namespace.'_userloggedout', array($this,"wp_user_logged_out_shortcode"));

}



public function return_html_form($atts, $post)  {


if ( is_user_logged_in() ){

$return_string = $this->logged_in_form($atts, $post);

} else {

$return_string = $this->logged_out_form($atts, $post);


}

return $return_string;


}


public function form_shortcode_output($atts,$content = null)  {

global $post;


if (in_array ( $post->post_type , $this->return_approved_posttypes() )){

$return_string = $this->return_html_form($atts, $post);


wp_enqueue_script('lh_signing-endpoint_script', site_url( '/lh_signing_endpoint/' ), array(), $this->plugin_version, true  );
  
  return $return_string;


} else {
  
  
  return '';
  
  
  
}






}


public function confirmed_list_shortcode_output($atts,$content = null)  {

extract( shortcode_atts( array (
'id' => false,
'redirect' => false ), $atts )
);

$return_string = $this->list_attached_users($id, 'signing_sign_confirmed');



return $return_string;

}


public function confirmed_count_shortcode_output($atts,$content = null) {

extract( shortcode_atts( array (
'id' => false,
'fields' => 'display_name' ), $atts )
);

$return_string = '<span class="confirmed_count">' . $this->count_attached_users($id, 'signing_sign_confirmed') . '</span>';


return $return_string;

}

public function unconfirmed_count_shortcode_output($atts,$content = null) {

extract( shortcode_atts( array (
'id' => false,
'fields' => 'display_name' ), $atts )
);

$return_string = '<span class="confirmed_count">' . $this->count_attached_users($id, 'signing_sign_unconfirmed') . '</span>';


return $return_string;

}

public function total_count_shortcode_output($atts,$content = null) {

extract( shortcode_atts( array (
'id' => false,
'fields' => 'display_name' ), $atts )
);

$return_string = '<span class="confirmed_count">';
$return_string .= $this->count_attached_users($id, 'signing_sign_unconfirmed') + $this->count_attached_users($id, 'signing_sign_confirmed');
$return_string .= '</span>';


return $return_string;

}



public function unconfirmed_list_shortcode_output($atts,$content = null)  {

extract( shortcode_atts( array (
'id' => false,
'fields' => 'display_name' ), $atts )
);

$return_string = $this->list_attached_users($id, 'signing_sign_unconfirmed');



return $return_string;

}

public function add_meta_boxes($post_type, $post) {

if (has_shortcode( $post->post_content, 'lh_signing_form' )){


add_meta_box($this->namespace."-initial_form-div", "Overall Configuration", array($this,"initial_form_metabox_content"), $post_type, "normal", "high");

add_meta_box($this->namespace."-unconfirmed_message-div", "Unconfirmed Stage Configuration", array($this,"unconfirmed_message_metabox_content"), $post_type, "normal", "high");
add_meta_box($this->namespace."-email_message-div", "Email Confirmation Stage Configuration", array($this,"email_message_metabox_content"), $post_type, "normal", "high");
add_meta_box($this->namespace."-confirmed_message-div", "Confirmed Stage Configuration", array($this,"confirmed_message_metabox_content"), $post_type, "normal", "high");


$allow_confirmed_email = get_post_meta( $post->ID, $this->allow_confirmed_email_field_name, true );


if ($allow_confirmed_email){


add_meta_box($this->namespace."-confirmed_email-div", "Final Confirmation Email", array($this,"confirmed_email_message_metabox_content"), $post_type, "normal", "high");

}


  
  
add_meta_box($this->namespace."-unconfirmed_users-div", "Unconfirmed Users", array($this,"unconfirmed_users_metabox_content"), $post_type, "normal", "high");

add_meta_box($this->namespace."-confirmed_users-div", "Confirmed Users", array($this,"confirmed_users_metabox_content"), $post_type, "normal", "high");




} 

}

public function initial_form_metabox_content(){

$signing_type = get_post_meta( get_the_ID(), $this->namespace."-signing_type", true );

$submit_text = get_post_meta( get_the_ID(), $this->namespace."-submit_text", true );

$login_link = get_post_meta( get_the_ID(), $this->login_link_field_name, true );

$allow_unconfirmed_transition = get_post_meta( get_the_ID(), $this->allow_unconfirmed_transition_field_name, true );

$use_email_queu = get_post_meta( get_the_ID(), $this->use_email_queu_field_name, true );
 
$allow_confirmed_email = get_post_meta( get_the_ID(), $this->allow_confirmed_email_field_name, true );


include ('partials/initial_form_metabox_content-output.php');


}


public function unconfirmed_message_metabox_content(){

$content = get_post_meta( get_the_ID(), $this->namespace."-unconfirmed_message", true );

$unconfirmed_logged_in_form_text = get_post_meta( get_the_ID(), "_".$this->namespace."-unconfirmed_logged_in_form_text", true );

$settings = array( 'media_buttons' => false, 'textarea_rows' => 5 );

include ('partials/unconfirmed_message_metabox_content-output.php');





}

public function email_message_metabox_content(){

$title = get_post_meta( get_the_ID(), $this->namespace."-email_title", true );

$carbon_copy_emails = get_post_meta( get_the_ID(), $this->namespace."-carbon_copy_emails", true );

$blind_copy_emails = get_post_meta( get_the_ID(), $this->namespace."-blind_copy_emails", true );

$content = get_post_meta( get_the_ID(), $this->namespace."-email_message", true );

include ('partials/email_message_metabox_content-output.php');



}

public function confirmed_message_metabox_content(){

$content = get_post_meta( get_the_ID(), $this->namespace."-confirmed_message", true );

$confirmed_logged_in_form_text = get_post_meta( get_the_ID(), "_".$this->namespace."-confirmed_logged_in_form_text", true );

include ('partials/confirmed_message_metabox_content-output.php');


}
  
  
  
public function confirmed_email_message_metabox_content(){

$title = get_post_meta( get_the_ID(), "_".$this->namespace."-confirmed_email_title", true );

$content = get_post_meta( get_the_ID(), "_".$this->namespace."-confirmed_email_message", true );


include ('partials/confirmed_email_message_metabox_content-output.php');

$settings = array( 'media_buttons' => false );

wp_editor( $content, $this->namespace."-confirmed_email_message");


  
  
}
  


public function unconfirmed_users_metabox_content(){

global $post;

$users = get_users( array(
  'connected_type' => array('signing_sign_unconfirmed','foobar'),
  'connected_items' => $post->ID
) );



$this->list_users($users);

if ($users){
?>

<strong><a href="<?php echo add_query_arg( 'lh_signing-export_users', 'signing_sign_unconfirmed'); ?>">Export these Users</a></strong>

<?php
}




}
  

  

public function confirmed_users_metabox_content(){

global $post;

$users = get_users( array(
  'connected_type' => array('signing_sign_confirmed','foobar'),
  'connected_items' => $post->ID
) );


$this->list_users($users);

if ($users){
?>

<strong><a href="<?php echo add_query_arg( 'lh_signing-export_users', 'signing_sign_confirmed'); ?>">Export these Users</a></strong>

<?php
}


}



public function update_post_meta(){

global $post;

if (isset($post->post_content) and has_shortcode( $post->post_content, 'lh_signing_form' ) and (wp_verify_nonce( $_POST[$this->namespace.'-save_post-backend-nonce'], $this->namespace.'-save_post-backend-nonce'))){



if ($_POST[$this->namespace."-signing_type"]){

$signing_type = sanitize_text_field($_POST[$this->namespace."-signing_type"]);

update_post_meta($post->ID, $this->namespace."-signing_type", $signing_type);

$submit_text = sanitize_text_field($_POST[$this->namespace."-submit_text"]);

update_post_meta($post->ID, $this->namespace."-submit_text", $submit_text);


}

if (($_POST[$this->login_link_field_name] == "0") || ($_POST[$this->login_link_field_name] == "1")){

update_post_meta($post->ID, $this->login_link_field_name, $_POST[$this->login_link_field_name]);


}



if (($_POST[$this->allow_unconfirmed_transition_field_name] == "0") || ($_POST[$this->allow_unconfirmed_transition_field_name] == "1")){

update_post_meta($post->ID, $this->allow_unconfirmed_transition_field_name, $_POST[$this->allow_unconfirmed_transition_field_name]);


}



if (($_POST[$this->use_email_queu_field_name] == "0") || ($_POST[$this->use_email_queu_field_name] == "1")){

update_post_meta($post->ID, $this->use_email_queu_field_name, $_POST[$this->use_email_queu_field_name]);


}

if (($_POST[$this->allow_confirmed_email_field_name] == "0") || ($_POST[$this->allow_confirmed_email_field_name] == "1")){

update_post_meta($post->ID, $this->allow_confirmed_email_field_name, $_POST[$this->allow_confirmed_email_field_name]);


}



if ($_POST[$this->namespace."-unconfirmed_message"]){

$content = wp_kses_post($_POST[$this->namespace."-unconfirmed_message"]);

update_post_meta($post->ID, $this->namespace."-unconfirmed_message", $content);


}


if ($_POST[$this->namespace."-unconfirmed_logged_in_form_text"]){

$unconfirmed_logged_in_form_text = sanitize_text_field($_POST[$this->namespace."-unconfirmed_logged_in_form_text"]);

update_post_meta($post->ID, "_".$this->namespace."-unconfirmed_logged_in_form_text", $unconfirmed_logged_in_form_text);


}



if ($_POST[$this->namespace."-email_title"]){

$title = sanitize_text_field($_POST[$this->namespace."-email_title"]);

update_post_meta($post->ID, $this->namespace."-email_title", $title);


}

if (is_array($carbon_copy_emails = $this->areValidEmails($_POST[$this->namespace."-carbon_copy_emails"]))){


update_post_meta($post->ID, $this->namespace."-carbon_copy_emails", implode(",", $carbon_copy_emails));


}

if (is_array($blind_copy_emails = $this->areValidEmails($_POST[$this->namespace."-blind_copy_emails"]))){

update_post_meta($post->ID, $this->namespace."-blind_copy_emails", implode(",", $blind_copy_emails));


}


if ($_POST[$this->namespace."-email_message"]){

$content = wp_kses_post($_POST[$this->namespace."-email_message"]);

update_post_meta($post->ID, $this->namespace."-email_message", $content);


}


if ($_POST[$this->namespace."-email_button_text"]){

$email_button_text = sanitize_text_field($_POST[$this->namespace."-email_button_text"]);

update_post_meta($post->ID, "_".$this->namespace."-email_button_text", $email_button_text);


}



if ($_POST[$this->namespace."-confirmed_message"]){

$content = wp_kses_post($_POST[$this->namespace."-confirmed_message"]);

update_post_meta($post->ID, $this->namespace."-confirmed_message", $content);


}

if ($_POST[$this->namespace."-confirmed_logged_in_form_text"]){

$confirmed_logged_in_form_text = sanitize_text_field($_POST[$this->namespace."-confirmed_logged_in_form_text"]);

update_post_meta($post->ID, "_".$this->namespace."-confirmed_logged_in_form_text", $confirmed_logged_in_form_text);


}
  
  
if ($_POST[$this->namespace."-confirmed_email_title"]){

$title = sanitize_text_field($_POST[$this->namespace."-confirmed_email_title"]);

update_post_meta($post->ID, "_".$this->namespace."-confirmed_email_title", $title);


}

  
if ($_POST[$this->namespace."-confirmed_email_message"]){

$content = wp_kses_post($_POST[$this->namespace."-confirmed_email_message"]);

update_post_meta($post->ID, "_".$this->namespace."-confirmed_email_message", $content);


}




}

}



public function plugin_menu() {

add_options_page('Signing Options', 'Signing Options', 'manage_options', $this->filename, array($this,"plugin_options")); 


}



public function plugin_options() {

if (!current_user_can('manage_options')){

wp_die( __('You do not have sufficient permissions to access this page.') );

}


if( isset($_POST[ $this->hidden_field_name ]) && $_POST[ $this->hidden_field_name ] == 'Y' ) {

if (($_POST[ $this->page_id_field ] != "") and ($page = get_page(sanitize_text_field($_POST[ $this->page_id_field ])))){

if ( has_shortcode( $page->post_content, 'lh_signing_form' ) ) {

$options[ $this->page_id_field ] = sanitize_text_field($_POST[ $this->page_id_field ]);

} else {

echo "The shortcode was not found in registration page";


}

}

if (($_POST[ $this->password_reset_page_id_field ] != "") and ($page = get_page(sanitize_text_field($_POST[ $this->password_reset_page_id_field ])))){

if ( has_shortcode( $page->post_content, 'lh_signing_form' ) ) {

$options[ $this->password_reset_page_id_field ] = sanitize_text_field($_POST[ $this->password_reset_page_id_field ]);

} else {

echo "The shortcode not found in password reset page";


}

}

if (update_option( $this->opt_name, $options )){


$this->options = get_option($this->opt_name);


?>
<div class="updated"><p><strong><?php _e('Values saved', $this->namespace ); ?></strong></p></div>
<?php

    } 

}


// Now display the settings editing screen

include ('partials/option-settings.php');



}

public function register_url( $register_url ) {


if ($page = get_page($this->options[ $this->page_id_field ])){

return get_permalink($page);

} else {


return $register_url;


}

}

public function lostpassword_url( $lostpassword_url ) {


if ($page = get_page($this->options[ $this->password_reset_page_id_field ])){

return get_permalink($page);

} else {


return $lostpassword_url;


}

}

public function export_users(){
    global $pagenow;

if( is_admin() && ('post.php' == $pagenow) && isset($_GET['lh_signing-export_users']) ) {

do_action("p2p_init");

$users = get_users( array(
  'connected_type' => array($_GET['lh_signing-export_users']),
  'connected_items' => $_GET['post']
) );

$object = get_post($_GET['post']);

$i = 0;
foreach ($users as $user){

$var[$i]['ID'] = $user->ID;
$var[$i]['user_email'] = $user->user_email;
$var[$i]['first_name'] = get_the_author_meta( 'first_name', $user->ID );
$var[$i]['last_name'] = get_the_author_meta( 'last_name', $user->ID );
$var[$i]['display_name'] = get_the_author_meta( 'display_name', $user->ID );


$var = apply_filters( 'lh_signing_csv_loop_filter', $var, $i, $user);

$i++; 


}

$filename = sanitize_title($object->post_name."-".$_GET['lh_signing-export_users']).".csv";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=".$filename);

echo $this->generate_csv_string($var);

die;



}

}

public function restrict_p2p_box_display( $show, $ctype, $post ) {


if (in_array($ctype->name, $this->signing_states)) {

if (has_shortcode( $post->post_content, 'lh_signing_form' )){

return $show;

} else {

return false;

}

} else {

return $show;


}



}

// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == $this->filename ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).$this->filename.'">Settings</a>';
	}
	return $links;
}


public function on_activate($network_wide) {


    if ( is_multisite() && $network_wide ) { 

        global $wpdb;

        foreach ($wpdb->get_col("SELECT blog_id FROM $wpdb->blogs") as $blog_id) {
            switch_to_blog($blog_id);

wp_clear_scheduled_hook( 'lh_signing_generate' );
wp_schedule_single_event(time(), 'lh_signing_initial_run'); 
wp_schedule_event( time(), 'threeminutes', 'lh_signing_generate' );

            restore_current_blog();
        } 

    } else {


wp_clear_scheduled_hook( 'lh_signing_generate' );
wp_schedule_single_event(time(), 'lh_signing_initial_run'); 
wp_schedule_event( time(), 'threeminutes', 'lh_signing_generate' );


}


}

public function deactivate_hook() {

wp_clear_scheduled_hook( 'lh_signing_generate' ); 


}




public function add_threeminutes( $schedules ) {
	// add a 'weekly' schedule to the existing set
	$schedules['threeminutes'] = array(
		'interval' => 180,
		'display' => __('Once every 3 minutes')
	);
	return $schedules;
}





public function run_initial_processes(){

//Add the various LH roles

if (!class_exists('LH_User_roles_class')) {

require_once('includes/lh-user-roles-class.php');

}

LH_User_roles_class::add_all_roles();


}




public function plugins_loaded(){

//if required change the registration url
add_filter('register_url', array($this,"register_url"),10,1);

//if required change the lost password url
add_filter('lostpassword_url', array($this,"lostpassword_url"),10,1);

}



public function __construct() {

$this->options = get_option($this->opt_name);
$this->filename = plugin_basename( __FILE__ );

add_action( 'plugins_loaded', array($this,"plugins_loaded"));





add_action('add_meta_boxes', array($this,"add_meta_boxes"),10,2);
add_action('save_post', array($this,"update_post_meta"));
add_action('init', array($this,"register_shortcodes"));
add_action('init', array($this,"autologin_via_url"));
add_action('init', array($this,"export_users"));
add_filter('the_content', array($this,"the_content_filter"),100);
add_action('p2p_init', array($this,"register_p2p_connection_types"));
add_filter( 'p2p_admin_box_show', array($this,"restrict_p2p_box_display"), 10, 3 );
add_action('wp', array($this,"save_data"));
add_action('admin_menu', array($this,"plugin_menu"));

add_filter('plugin_action_links', array($this,"add_settings_link"), 10, 2);

//Hook to attach processes to recurring cron job
add_filter( 'cron_schedules', array($this,"add_threeminutes"), 10, 1);

//Hook to attach processes to initial cron job
add_action('lh_signing_initial_run', array($this,"run_initial_processes"));

}

}





$lh_signing_instance = new LH_Signing_plugin();
register_activation_hook(__FILE__, array($lh_signing_instance, 'on_activate'), 10, 1);
register_deactivation_hook( __FILE__, array($lh_signing_instance,'deactivate_hook') );



add_action( 'widgets_init', 'lh_signing_widget_init' );
 
function lh_signing_widget_init() {
    register_widget( 'lh_signing_widget' );
}
 
class lh_signing_widget extends WP_Widget {
 
    public function __construct()    {
        $widget_details = array(
            'classname' => 'lh_signing_widget',
            'description' => 'My plugin description'
        );
 
        parent::__construct( 'lh_signing_widget', 'LH Signing Widget', $widget_details );
 
    }
 
    public function form( $instance ) {
        // Backend Form

$title = '';
    if( !empty( $instance['title'] ) ) {
        $title = $instance['title'];
    }

   $text = '';
    if( !empty( $instance['text'] ) ) {
        $text = $instance['text'];
    }
 

    $postid = '';
    if( !empty( $instance['postid'] ) ) {
        $postid = $instance['postid'];
    }
 
    ?>
 
    <p>
        <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>

    <p>
        <label for="<?php echo $this->get_field_name( 'text' ); ?>"><?php _e( 'Text:' ); ?></label>
        <textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" ><?php echo esc_attr( $text ); ?></textarea>
    </p>
 
 

<p>
<?php  echo "the post id is ".$postid; ?>

<label for="<?php  echo $this->get_field_name( 'postid' );  ?>"><?php _e( 'Post:' ); ?></label>

<select name="<?php echo $this->get_field_name( 'postid' ); ?>" id="<?php echo $this->get_field_id( 'postid' ); ?>" >

<?php

global $wpdb;

$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_content LIKE '%[lh_signing_form%'";

$result = $wpdb->get_results($sql);
  if ($result){
    foreach($result as $pageThing){


?><option value="<?php echo $pageThing->ID; ?>" <?php  if ($postid == $pageThing->ID){ echo 'selected="selected"'; } ?> ><?php echo $pageThing->post_title; ?></option><?php

    

	}
  }

?>

 </select>
    <div class='mfc-text'>
         
    </div>
 
    <?php
 
    echo $args['after_widget'];


    }
 
    public function update( $new_instance, $old_instance ) {  
        return $new_instance;
    }
 
    public function widget( $args, $instance ) {
        // Frontend display HTML

$title = apply_filters( 'widget_title', $instance['title'] );

	// before and after widget arguments are defined by themes

	echo $args['before_widget'];

	if ( ! empty( $title ) ){

	echo $args['before_title'] . $title . $args['after_title'];

}

$text = $instance['text'];

	// This is where you run the code and display the output

	echo __( $text, 'wpb_widget_domain' );

if ($instance['postid']){

$postobject = get_post($instance['postid']);

$atts = null;

$foo = new LH_Signing_plugin();

echo $foo->return_html_form($atts, $postobject);


}

	echo $args['after_widget'];




}
 
}



?>