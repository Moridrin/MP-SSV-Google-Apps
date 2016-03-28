<?php
/**
* Plugin Name: SSV Google Apps
* Plugin URI: http://studentensurvival.com/plugins/mp-ssv-google-apps
* Description: SSV Google Apps is a plugin that can be used to connect the SSV Frontend Members plugin to your Google Apps domain.
* Version: 1.0
* Author: Jeroen Berkvens
* Author URI: http://nl.linkedin.com/in/jberkvens/
* License: WTFPL
* License URI: http://www.wtfpl.net/txt/copying/
*/

include_once "options/options.php";

function mp_ssv_register_mp_ssv_google_apps(){
	if (!is_plugin_active('mp-ssv-frontend-members/mp-ssv-frontend-members.php')) {
		wp_die('Sorry, but this plugin requires <a href="http://studentensurvival.com/plugins/mp-ssv-frontend-members">SSV Frontend Members</a> to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	}
}
register_activation_hook( __FILE__, 'mp_ssv_register_mp_ssv_google_apps' );

function mp_ssv_add_google_member() {
	include_once "google-api-php-client/src/Google/autoload.php";
	ob_start();
	$private_key_file = get_option('mp_ssv_google_apps_private_key_location');
	$private_key = file_get_contents($private_key_file);
	$client_email = get_option('mp_ssv_google_apps_client_email');
	$scopes = array('https://www.googleapis.com/auth/admin.directory.group', 'https://www.googleapis.com/auth/admin.directory.group.member', 'https://www.googleapis.com/auth/admin.directory.user');
	$credentials = new Google_Auth_AssertionCredentials(
		$client_email,
		$scopes,
		$private_key
	);
	$credentials->sub = get_option('mp_ssv_google_apps_admin_enabled_email');
	$client = new Google_Client();
	$client->setAssertionCredentials($credentials);
	if ($client->getAuth()->isAccessTokenExpired()) {
		$client->getAuth()->refreshTokenWithAssertion();
	}
	$service = new Google_Service_Directory($client);
	$optParams = array(
	);
	$results = $service->members->listMembers('members@allterrain.nl', $optParams)->getMembers();
	foreach ($results as $result) {
		print_r($result->email);
		echo "<br/>";
	}
	return ob_get_clean();
}
?>