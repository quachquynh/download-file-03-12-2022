<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use SohaibIlyas\FacebookPhpSdk\Facebook;
// https://viblo.asia/p/dang-nhap-bang-facebook-voi-php-Eb85op80K2G

class FacebookApi extends Controller {

	public function facebook_api() {
		$fb = new \Facebook\Facebook([
		  	'app_id' => '342899987783363',
		    'app_secret' => '2e1fbe2774ded715e6abf153ccec43f8',
		    'redirect_url' => 'http://localhost/download-file/facebook-api'
		]);

		$helper = $fb->getRedirectLoginHelper();

		$permissions = ['email']; // Optional permissions
		$loginUrl = $helper->getLoginUrl('http://localhost/download-file/facebook-api/callback', $permissions);

		echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
	}

	public function callback() {

	}
}