<?php
//TO-DO: Iznest šo bloku ārpusē, jo to nākas bieži izmantot atkārtoti
require_once 'get/vendor/autoload.php';

$client = new Google_Client();
$client->setAuthConfig('get/client_credentials.json');
$client->setAccessType ("offline");
$client->setApprovalPrompt ("force");
$client->setIncludeGrantedScopes(true);
$client->addScope("https://www.googleapis.com/auth/photoslibrary.readonly");

$accessFile = 'get/accessToken.json';
$refreshFile = 'get/refreshToken.json';

if (file_exists($accessFile)) {
	$accessToken = json_decode(file_get_contents($accessFile), true);
	if(isset($accessToken["access_token"]))
		$accessToken = $accessToken["access_token"];
	// echo $accessToken;
	$client->setAccessToken($accessToken);
}
if ($client->isAccessTokenExpired() && file_exists($refreshFile)) {
	$refreshToken = json_decode(file_get_contents($refreshFile), true);
	$client->fetchAccessTokenWithRefreshToken($refreshToken);
	
	file_put_contents($accessFile, json_encode($client->getAccessToken()));
	file_put_contents($refreshFile, json_encode($client->getRefreshToken()));
}else{
	$authUrl = $client->createAuthUrl();
	echo $authUrl, "\n";
	// $code = rtrim(fgets(STDIN));
	$client->authenticate($code);
	
	file_put_contents($accessFile, json_encode($client->getAccessToken()));
	file_put_contents($refreshFile, json_encode($client->getRefreshToken()));
}


function getImage($imgID, $accessToken){
	
	try {
		$handle = curl_init();
		$url = "https://photoslibrary.googleapis.com/v1/mediaItems/".$imgID."?access_token=".$accessToken;
		
		curl_setopt($handle, CURLOPT_URL, $url);
		curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($handle);
		curl_close($handle);
		$json = json_decode($output);

		return $json->baseUrl."=w2048-h1024";
	} catch (Exception $e) {
		// echo 'Caught exception: ',  $e->getMessage(), "\n";
		return "https://lielakeda.lv/wp-content/themes/emo-10/images/thumb.png";
	}
}