<?php
if (!function_exists("mp_ssv_add_mp_ssv_google_apps_menu")) {
	function mp_ssv_add_mp_ssv_google_apps_menu() {
		add_submenu_page( 'mp_ssv_settings', 'Google Apps Options', 'Google Apps', 'manage_options', "mp-ssv-google-apps-options", 'mp_ssv_google_apps_settings_page' );
	}
	function mp_ssv_google_apps_settings_page() {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			mp_ssv_google_apps_settings_page_general_save();
		}
		?>
		<h1>MP-SSV Google Apps Options</h1>
		<?php
		mp_ssv_google_apps_settings_page_general();
	}
	add_action('admin_menu', 'mp_ssv_add_mp_ssv_google_apps_menu');
	
	function mp_ssv_google_apps_settings_page_general() {
		?>
		<form method="post" action="#" enctype="multipart/form-data">
			<table class="form-table">
				<tr>
					<th scope="row">Private Key</th>
					<td>
						<input type="text" class="regular-text" name="mp_ssv_google_apps_private_key_location" value="<?php echo substr(get_option('mp_ssv_google_apps_private_key_location'), strrpos(get_option('mp_ssv_google_apps_private_key_location'), '/') + 1); ?>" disabled/>
						<input id="mp_ssv_google_apps_private_key_location" type="file" name="mp_ssv_google_apps_private_key_location" <?php if (!file_exists(get_option('mp_ssv_google_apps_private_key_location'))) { echo "required"; } ?>/>
					</td>
				</tr>
				<tr>
					<th scope="row">Client Email</th>
					<td>
						<input type="text" class="regular-text" name="mp_ssv_google_apps_client_email" value="<?php echo get_option('mp_ssv_google_apps_client_email'); ?>"/>
					</td>
				</tr>
				<tr>
					<th scope="row">Admin Enabled Email</th>
					<td>
						<input type="text" class="regular-text" name="mp_ssv_google_apps_admin_enabled_email" value="<?php echo get_option('mp_ssv_google_apps_admin_enabled_email'); ?>"/>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}
	
	function mp_ssv_google_apps_settings_page_general_save() {
		if (!function_exists( 'wp_handle_upload')) {
			require_once(ABSPATH.'wp-admin/includes/file.php');
		}
		$file_location = wp_handle_upload($_FILES['mp_ssv_google_apps_private_key_location'], array('test_form' => FALSE), "mp_ssv_handle_upload");
		if ($file_location && !isset($file_location['error'])) {
			update_option('mp_ssv_google_apps_private_key_location', $file_location["file"]);
		}
		update_option('mp_ssv_google_apps_client_email', $_POST['mp_ssv_google_apps_client_email']);
		update_option('mp_ssv_google_apps_admin_enabled_email', $_POST['mp_ssv_google_apps_admin_enabled_email']);
	}
}

function mp_ssv_extended_mime_types($mime_types) {
	$mime_types['p12'] = 'application/x-pkcs12';
	return $mime_types;
}
add_filter('upload_mimes', 'mp_ssv_extended_mime_types');
?>