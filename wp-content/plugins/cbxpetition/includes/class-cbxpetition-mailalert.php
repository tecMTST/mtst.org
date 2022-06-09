<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<?php

class CBXPetitionMailAlert {
	/**
	 * Send email alert to admin on new signature submit
	 *
	 * @param $data
	 *
	 * @return mixed
	 * @throws \Html2TextPetition\Html2TextException
	 */
	public static function sendSignAdminEmailAlert( $data ) {
		$settings_api = new CBXPetition_Settings();
		$settings     = get_option( 'cbxpetition_email_admin' );


		$html = $admin_email_body = $user_email_body = $message = '';
		//$return = array();

		//send email to admin

		$id          = intval( $data['id'] );
		$petition_id = intval( $data['petition_id'] );

		$mail_format = $settings['format'];
		//$mail_from_address = ( $settings['from'] != '' ) ? $settings['from'] : get_bloginfo( 'admin_email' );
		//$mail_from_name    = $settings['name'];

		$to       = $settings['to'];
		$cc       = $settings['cc'];
		$bcc      = $settings['bcc'];
		$reply_to = $settings['reply_to'];

		$reply_to = str_replace( '{user_email}', $data['email'], $reply_to ); //replace email
		//$header   = $this->email_header( $cc, $bcc, $reply_to );

		$subject             = $settings['subject'];
		$admin_email_heading = $settings['heading'];
		$admin_email_body    = $settings['body'];

		//email body syntax parsing
		$admin_email_body = str_replace( '{first_name}', $data['f_name'], $admin_email_body ); //replace first name
		$admin_email_body = str_replace( '{last_name}', $data['l_name'], $admin_email_body ); //replace last name
		$admin_email_body = str_replace( '{user_email}', $data['email'], $admin_email_body ); //replace email
		$admin_email_body = str_replace( '{comment}', stripslashes( $data['comment'] ), $admin_email_body ); //replace message

		$sign_status      = CBXPetitionHelper::getPetitionSignStates();
		$admin_email_body = str_replace( '{status}',
			$sign_status[ $data['state'] ],
			$admin_email_body ); //replace status

		$petition_sign_url           = admin_url( 'edit.php?post_type=cbxpetition&page=cbxpetitionsigns&view=addedit&id=' . $id );
		$petition_sign_url_formatted = sprintf( __( '<p>To Edit <a href="%s">click this url.</a></p>',
			'cbxpetition' ),
			$petition_sign_url );

		$admin_email_body = str_replace( '{signature_edit_url}',
			$petition_sign_url_formatted,
			$admin_email_body ); //replace sign url for admin

		$admin_email_body = str_replace( '{petition}',
			'<a href="' . get_permalink( $petition_id ) . '">' . get_the_title( $petition_id ) . '</a>',
			$admin_email_body ); //replace sign url for admin

		//esp = email syntax parse
		$admin_email_body = apply_filters( 'cbxpetition_new_sign_admin_email_alert_esp', $admin_email_body, $data );

		//end email body syntax parsing

		$emailTemplate = new CBXPetitionMailTemplate();
		$message       = $emailTemplate->getHtmlTemplate();
		$message       = str_replace( '{mainbody}', $admin_email_body, $message ); //replace mainbody
		$message       = str_replace( '{emailheading}', $admin_email_heading, $message ); //replace emailbody

		if ( $mail_format == 'html' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
		} elseif ( $mail_format == 'plain' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
			$message = Html2TextPetition\Html2Text::convert( $message );
			$message = Html2TextPetition\Html2Text::fixNewlines( $message );
		}


		$mail_helper        = new CBXPetitionMailHelper( $mail_format );
		$header             = $mail_helper->email_header( $cc, $bcc, $reply_to );
		$admin_email_status = $mail_helper->wp_mail( $to, $subject, $message, $header );


		return $admin_email_status;
	}//end method sendSignAdminEmailAlert


	/**
	 * Send email to user after form submit based on form setting
	 *
	 * @param $data
	 *
	 * @return bool|void
	 * @throws \Html2TextPetition\Html2TextException
	 */
	public static function sendSignUserEmailAlert( $data ) {

		$settings_api = new CBXPetition_Settings();
		$settings     = get_option( 'cbxpetition_email_user' );

		$html = $user_email_body = $message = '';

		$id          = intval( $data['id'] );
		$petition_id = intval( $data['petition_id'] );

		$mail_format = $settings['new_format'];
		//$mail_from_address = ( $settings['new_from'] != '' ) ? $settings['new_from'] : get_bloginfo( 'admin_email' );
		//$mail_from_name    = $settings['new_name'];

		$to       = str_replace( '{user_email}', $data['email'], $settings['new_to'] );
		$reply_to = isset( $settings['new_reply_to'] ) ? $settings['new_reply_to'] : '';

		//$header = $email_header( '', '', $reply_to );

		$subject            = $settings['new_subject'];
		$user_email_heading = $settings['new_heading'];
		$user_email_body    = $settings['new_body'];

		//email body syntax parsing
		$user_email_body = str_replace( '{user_name}', $data['f_name'], $user_email_body ); //replace user_name
		$user_email_body = str_replace( '{first_name}', $data['f_name'], $user_email_body ); //replace first_name
		$user_email_body = str_replace( '{last_name}', $data['l_name'], $user_email_body ); //replace last_name
		$user_email_body = str_replace( '{user_email}', $data['email'], $user_email_body ); //replace user_email
		$user_email_body = str_replace( '{comment}', stripslashes( $data['comment'] ), $user_email_body ); //replace sign_comment

		$sign_status     = CBXPetitionHelper::getPetitionSignStates();
		$user_email_body = str_replace( '{status}', $sign_status[ $data['state'] ], $user_email_body ); //replace status
		$user_email_body = str_replace( '{petition}',
			'<a href="' . get_permalink( $petition_id ) . '">' . get_the_title( $petition_id ) . '</a>',
			$user_email_body ); //replace sign url for admin

		$activation_link = '';

		if ( $data['activation'] != null ) {
			$activation_link = add_query_arg(
				array(
					'cbxpetitionsign_verification' => 1,
					'activation_code'              => $data['activation'],
				),
				home_url( '/' )
			);

			$activation_link_formatted = sprintf( __( '<p>To confirm your signature request, please verify your email address by <a href="%s">clicking this url.</a></p>', 'cbxpetition' ), $activation_link );

			$user_email_body = str_replace( '{activation_link}', $activation_link_formatted, $user_email_body );
		} else {
			$user_email_body = str_replace( '{activation_link}', $activation_link, $user_email_body );
		}

		//esp = email syntax parse
		$user_email_body = apply_filters( 'cbxpetition_new_sign_user_email_alert_esp', $user_email_body, $data );
		//email body syntax parsing

		$emailTemplate = new CBXPetitionMailTemplate();
		$message       = $emailTemplate->getHtmlTemplate();
		$message       = str_replace( '{mainbody}', $user_email_body, $message ); //replace mainbody
		$message       = str_replace( '{emailheading}', $user_email_heading, $message ); //replace emailbody

		if ( $mail_format == 'html' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
		} elseif ( $mail_format == 'plain' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
			$message = Html2TextPetition\Html2Text::convert( $message );
			$message = Html2TextPetition\Html2Text::fixNewlines( $message );
		}

		$mail_helper       = new CBXPetitionMailHelper( $mail_format );
		$header            = $mail_helper->email_header( '', '', $reply_to );
		$user_email_status = $mail_helper->wp_mail( $to, $subject, $message, $header );


		return $user_email_status;
	}//end method sendSignUserEmailAlert

	/**
	 * Send email to user after sign approved based on setting
	 *
	 * @param $data
	 * @param $old_status
	 * @param $new_status
	 *
	 * @return bool
	 * @throws \Html2TextPetition\Html2TextException
	 */
	public static function sendSignApprovedEmailAlert( $data, $old_status, $new_status ) {

		$settings_api = new CBXPetition_Settings();
		$settings     = get_option( 'cbxpetition_email_user' );

		$html              = $user_email_body = $message = '';
		$user_email_status = false;

		$id          = intval( $data['id'] );
		$petition_id = intval( $data['petition_id'] );


		$mail_format = $settings['sign_approve_user_format'];
		//$mail_from_address = ( $settings['confirmed_from'] != '' ) ? $settings['confirmed_from'] : get_bloginfo( 'admin_email' );
		//$mail_from_name    = $settings['confirmed_name'];

		$to       = str_replace( '{user_email}', $data['email'], $settings['sign_approve_user_to'] );
		$reply_to = isset( $settings['sign_approve_user_reply_to'] ) ? $settings['sign_approve_user_reply_to'] : '';

		//$header = $email_header( '', '', $reply_to );

		$subject            = $settings['sign_approve_user_subject'];
		$user_email_heading = $settings['sign_approve_user_heading'];
		$user_email_body    = $settings['sign_approve_user_body'];

		//email body syntax parsing
		$user_email_body = str_replace( '{user_name}', $data['f_name'], $user_email_body ); //replace first_name
		$user_email_body = str_replace( '{first_name}',
			$data['f_name'],
			$user_email_body ); //replace first_name
		$user_email_body = str_replace( '{last_name}', $data['l_name'], $user_email_body ); //replace last_name
		$user_email_body = str_replace( '{user_email}', $data['email'], $user_email_body ); //replace user_email
		$user_email_body = str_replace( '{comment}', stripslashes( $data['comment'] ), $user_email_body ); //replace sign_comment

		$sign_status     = CBXPetitionHelper::getPetitionSignStates();
		$user_email_body = str_replace( '{status}', $sign_status[ $new_status ], $user_email_body ); //replace status

		$user_email_body = str_replace( '{petition}',
			'<a href="' . get_permalink( $petition_id ) . '">' . get_the_title( $petition_id ) . '</a>',
			$user_email_body ); //replace sign url for admin


		//esp = email syntax parse
		$user_email_body = apply_filters( 'cbxpetition_sign_approve_user_alert_esp', $user_email_body, $data );
		//email body syntax parsing

		$emailTemplate = new CBXPetitionMailTemplate();
		$message       = $emailTemplate->getHtmlTemplate();
		$message       = str_replace( '{mainbody}', $user_email_body, $message ); //replace mainbody
		$message       = str_replace( '{emailheading}', $user_email_heading, $message ); //replace emailbody

		if ( $mail_format == 'html' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
		} elseif ( $mail_format == 'plain' ) {
			$message = $emailTemplate->htmlEmeilify( $message );
			$message = Html2TextPetition\Html2Text::convert( $message );
			$message = Html2TextPetition\Html2Text::fixNewlines( $message );
		}

		$mail_helper       = new CBXPetitionMailHelper( $mail_format );
		$header            = $mail_helper->email_header( '', '', $reply_to );
		$user_email_status = $mail_helper->wp_mail( $to, $subject, $message, $header );

		return $user_email_status;
	}//end method sendSignApprovedEmailAlert


}//end class CBXPetitionMailAlert