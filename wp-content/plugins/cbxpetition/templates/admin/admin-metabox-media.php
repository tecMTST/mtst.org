<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

$dir_info   = CBXPetitionHelper::checkUploadDir();
$media_info = get_post_meta( $petition_id, '_cbxpetition_media_info', true );


echo '<div id="cbxpetition_meta_media" data-petition_id="' . intval( $petition_id ) . '" class="group">';
// check petition photos
$petition_photos = array();

$petition_photos = isset( $media_info['petition-photos'] ) ? $media_info['petition-photos'] : array();

if ( ! is_array( $petition_photos ) ) {
	$petition_photos = array();
}

$photo_file_count          = sizeof( $petition_photos );
$photo_max_number_of_files = intval( $setting->get_option( 'max_photo_limit', 'cbxpetition_general', 10 ) );

echo '<p><strong>' . esc_html__( 'Petition Photos', 'cbxpetition' ) . '</strong></p>';
?>
    <p>
		<span class="cbxpetition_photo_add" style="display: inline-block;">
			<input type="file" name="cbxpetitionmeta_photo_files" id="cbxpetition_photo_file_browser" class="cbxpetition_photo_file_browser cbxpetition_display_none" data-maxcount="<?php echo intval( $photo_max_number_of_files ); ?>" data-count="<?php echo intval( $photo_file_count ); ?>" multiple=""/>
			<a href="#" title="Upload photos" class="cbxpetition_photo_upload"><?php esc_html_e( 'Upload photos', 'cbxpetition' ); ?></a>
		</span>
    </p>
    <div class="cbxpetition_photos_wrap">
        <p class="cbxpetition_photos_error"></p>
        <div class="cbxpetition_photos">
			<?php
			foreach ( $petition_photos as $filename ) {
				?>
                <div class="cbxpetition_photo">
                    <img class="cbxpetition_photo_preview" src="<?php echo esc_url( $dir_info['cbxpetition_base_url'] . $petition_id . '/thumbnail/' . $filename ) ?>" alt="Preview"/> <input class="cbxpetition_photo_hidden" type="hidden" name="cbxpetitionmeta[petition-photos][]" value="<?php echo esc_attr( $filename ); ?>"> <a class="cbxpetition_photo_delete" title="<?php esc_html_e( 'Delete', 'cbxpetition' ); ?>" data-busy="0" data-name="<?php echo esc_attr( $filename ); ?>"></a>
                    <a class="cbxpetition_photo_drag" title="<?php esc_html_e( 'Sort', 'cbxpetition' ); ?>"></a>
                </div>
				<?php
			}
			?>

        </div>

    </div> <!-- .cbxpetition_photos_wrap -->
<?php
$petition_banner_url = '';
$petition_banner     = '';


if ( isset( $media_info['banner-image'] ) && $media_info['banner-image'] != '' ) {

	$petition_banner     = sanitize_text_field( $media_info['banner-image'] );
	$petition_banner_url = $dir_info['cbxpetition_base_url'] . $petition_id . '/' . $petition_banner;
}


$banner_file_count = ( $petition_banner != '' ) ? 1 : 0;
//$banner_max_number_of_files    = 1;
// banner handled in cbxpetition-admin.js file
echo '<p><strong>' . esc_html__( 'Featured Banner', 'cbxpetition' ) . '</strong></p>';
echo '<p class="cbxpetition_banner_error" style="display: none;"></p>';
echo '<div id="petition_banner_wrapper" style="background-image: url(' . ( ( $petition_banner != '' ) ? esc_url( $petition_banner_url ) : '' ) . ');">';
echo '<input type="file" name="cbxpetitionmeta_banner_file" id="cbxpetition_banner_file_browser" class="cbxpetition_banner_file_browser cbxpetition_display_none" data-maxcount="1" data-count="' . intval( $banner_file_count ) . '" />';
echo '<input type="hidden" name="cbxpetitionmeta[banner-image]" id="cbxpetition_banner" value="' . esc_attr( $petition_banner ) . '">';
echo '<a href="#" class="button cbxpetition-add-banner" style="' . ( ( $petition_banner == '' ) ? ' display: block; ' : ' display: none; ' ) . '">' . esc_html__( 'Add Banner', 'cbxpetition' ) . '</a>';
echo '<a href="#" data-busy="0" data-name="' . esc_attr( $petition_banner ) . '" class="button cbxpetition-remove-banner" style="' . ( ( $petition_banner == '' ) ? ' display: none;' : ' display: block; ' ) . '">' . esc_html__( 'Remove Banner', 'cbxpetition' ) . '</a>';
echo '</div>';


$video_url = $video_title = $video_description = '';


// check video url
if ( isset( $media_info['video-url'] ) && $media_info['video-url'] != null ) {
	$video_url = $media_info['video-url'];
}
// check video title
if ( isset( $media_info['video-title'] ) && $media_info['video-title'] != null ) {
	$video_title = $media_info['video-title'];
}

// check video description
if ( isset( $media_info['video-description'] ) && $media_info['video-description'] != null ) {
	$video_description = $media_info['video-description'];
}

echo '<p><strong>' . esc_html__( 'Video Url(Youtube)', 'cbxpetition' ) . '</strong></p>';
echo '<input type="text" name="cbxpetitionmeta[video-url]" class="regular-text" value="' . esc_url( $video_url ) . '" />';

echo '<p><strong>' . esc_html__( 'Video Title', 'cbxpetition' ) . '</strong></p>';
echo '<input type="text" name="cbxpetitionmeta[video-title]" class="regular-text" value="' . esc_attr( $video_title ) . '" />';

echo '<p><strong>' . esc_html__( 'Video Description', 'cbxpetition' ) . '</strong></p>';
echo '<div class="petition_video_wrapper">';
wp_editor( $video_description,
	'video_description',
	array(
		'wpautop'       => true,
		'media_buttons' => false,
		'textarea_name' => 'cbxpetitionmeta[video-description]',
		'textarea_rows' => 10,
		'teeny'         => true,
	) );
echo '</div>';

echo '</div>'; //#cbxpetition_meta_media