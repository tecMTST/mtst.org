<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}


echo '<div id="cbxpetition_meta_letter" class="group"><div class="cbxpetition_letter_section">';
//template for new recipient
echo '<script id="cbx_recipientlists_template" type="x-tmpl-mustache">
            <li class="cbxpetition_repeat_field_recipient recipientlist_wrap recipientlist_{{index}}">
                <div class="field recipientlist_name">
                    <input type="text" value="" name="cbxpetitionmeta[recipients][{{index}}][name]" class="regular-text" 
                        placeholder=" ' . esc_html__( 'Recipient', 'cbxpetition' ) . '" id="recipientlist_name_{{index}}" class="form-control half" />
                </div>
                <div class="field recipientlist_designation">
                    <input type="text" value="" name="cbxpetitionmeta[recipients][{{index}}][designation]" class="regular-text" 
                        placeholder=" ' . esc_html__( 'Recipient\'s Designation', 'cbxpetition' ) . '" id="recipientlist_designation_{{index}}" class="form-control half" />
                </div>
                <div class="field recipientlist_email">
                    <input type="email" value="" name="cbxpetitionmeta[recipients][{{index}}][email]" class="regular-text" 
                        placeholder=" ' . esc_html__( 'Recipient\'s Email', 'cbxpetition' ) . '" id="recipientlist_email_{{index}}" class="form-control half" />
                </div>
                <div class="field recipientlist_actions">
                	<a href="#" title="' . esc_html__( 'Move Recipient', 'cbxpetition' ) . '" class="dashicons dashicons-menu move-recipient"></a>
              		<a href="#" title="' . esc_html__( 'Delete Recipient', 'cbxpetition' ) . '" class="dashicons dashicons-post-trash trash-repeat recipient_delete_icon"></a>
				</div>
            </li>
    </script>';


$letter          = get_post_meta( $petition_id, '_cbxpetition_letter', true );
$letter          = is_array( $letter ) ? $letter : array();
$petition_letter = isset( $letter['letter'] ) ? $letter['letter'] : '';


echo '<div class="petition_letter_wrapper">';
wp_editor( $petition_letter,
	'petition_letter',
	array(
		'wpautop'       => true,
		'media_buttons' => false,
		'textarea_name' => 'cbxpetitionmeta[letter]',
		'textarea_rows' => 10,
		'teeny'         => true,
	) );
echo '</div>';

$recipients     = array();
$recipients     = isset( $letter['recipients'] ) ? $letter['recipients'] : array();
$recipients     = is_array( $recipients ) ? $recipients : array();
$recipients_len = sizeof( $recipients );

$index = 0;


echo '<ul id="cbxpetition_repeat_fields_recipient">';
foreach ( $recipients as $recipient ) {
	echo '<li class="cbxpetition_repeat_field_recipient recipientlist_wrap recipientlist_' . $index . '">
                <div class="field recipientlist_name">
                    <input type="text" value="' . esc_html( $recipient['name'] ) . '"
                           name="cbxpetitionmeta[recipients][' . intval( $index ) . '][name]"
                           placeholder="' . esc_html__( 'Recipient Name', 'cbxpetition' ) . '"
                           id="recipientlist_name_' . intval( $index ) . '" class="regular-text half" />
                </div>
                <div class="field recipientlist_designation">
                    <input type="text" value="' . esc_html( $recipient['designation'] ) . '"
                           name="cbxpetitionmeta[recipients][' . intval( $index ) . '][designation]"
                           placeholder="' . esc_html__( 'Recipient\'s Designation', 'cbxpetition' ) . '"
                           id="recipientlist_designation_' . intval( $index ) . '" class="regular-text half" />
                </div>
                <div class="field recipientlist_email">
                    <input type="text" value="' . esc_html( $recipient['email'] ) . '"
                           name="cbxpetitionmeta[recipients][' . intval( $index ) . '][email]"
                           placeholder="' . esc_html__( 'Recipient\'s Email', 'cbxpetition' ) . '"
                           id="recipientlist_email_' . intval( $index ) . '" class="regular-text half" />
                </div>
                <div class="field recipientlist_actions">
                    <a href="#" title="' . esc_html__( 'Move Recipient', 'cbxpetition' ) . '" class="dashicons dashicons-menu move-recipient"></a>
                    <a href="#" title="' . esc_html__( 'Delete Recipient', 'cbxpetition' ) . '" class="dashicons dashicons-post-trash trash-repeat recipient_delete_icon"></a>
				</div>
			</li>';
	$index ++;
}

echo '</ul>';


echo '<p><a href="#" class="button button-primary cbx_recipient_add" data-recipientcount="' . $recipients_len . '">' . esc_html__( 'Add Recipient', 'cbxpetition' ) . '</a></p>';

echo '</div></div>';