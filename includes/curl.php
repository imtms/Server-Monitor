<?php
function is_curl_installed() {
	if  (in_array  ('curl', get_loaded_extensions())) {
		return true;
	} else {
		return false;
	}
}
function curl_seasson($url) {
	$c = curl_init($url);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	$response = curl_exec($c);
	curl_close($c);
	return $response;
}
?>