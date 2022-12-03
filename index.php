<?php
require_once './vendor/autoload.php';
require_once './src/Helpers/helpers.php';
require_once './config.php';
$base_url = getcwd();
define('BASE_URL', $base_url);
define('APP_URL', 'http://localhost/download-file/public');
define('PATH_URL', 'http://localhost/download-file');
$root = $_SERVER['DOCUMENT_ROOT'];

use App\Routes\Router;
$router = new \App\Routes\Router();

$router->get('/', 'App\Controllers\Admin@index');

$router->get('/api-fb', 'App\Controllers\Api@api_fb');
$router->get('/api-yt', 'App\Controllers\Api@api_yt');

$router->get('/admin', 'App\Controllers\Admin@index');
$router->get('/index', 'App\Controllers\Frontend\Blogs@index');

$router->get('/insert', 'App\Controllers\Backend\Products@add_product');
$router->post('/insert', 'App\Controllers\Backend\Products@add_product');

$router->get('/crawl', 'App\Controllers\Tools\Tool@crawl_web');

// Youtube Download - oke
$router->get('/youtube-download/{id}', 'App\Controllers\Tools\Youtube@youtube_download');
$router->get('/youtube/get', 'App\Controllers\Tools\Youtube@get_data');

$router->get('/facebook-api', 'App\Controllers\Tools\FacebookApi@facebook_api');
$router->get('/facebook-api/callback', 'App\Controllers\Tools\FacebookApi@callback');

$router->get('/google-drive/auth', 'App\Controllers\Tools\GoogleDrive@auth');
$router->get('/google-drive/token', 'App\Controllers\Tools\GoogleDrive@token');
$router->get('/google-drive/list', 'App\Controllers\Tools\GoogleDrive@listFiles');
$router->get('/google-drive/upload', 'App\Controllers\Tools\GoogleDrive@upload');
$router->post('/google-drive/upload', 'App\Controllers\Tools\GoogleDrive@upload');
$router->get('/google-drive/create', 'App\Controllers\Tools\GoogleDrive@createFolder');
$router->get('/gphoto', 'App\Controllers\Tools\GoogleDrive@googlePhoto');

// Onedrive
$router->get('/onedrive/{url}', 'App\Controllers\Tools\OneDrive@onedrive');

$router->get('/hls', 'App\Controllers\Tools\Download@hls');

// Download
//$router->get('/download/{url}', 'App\Controllers\Tools\Download@download');
$router->get('/downloadfile', 'App\Controllers\Tools\Download@downloadfile');
$router->post('/downloadfile', 'App\Controllers\Tools\Download@downloadfile');
$router->get('/delete', 'App\Controllers\Tools\Download@delete');

// FFmpeg
$router->get('/ffmpeg/{foldername}/{filename}/{extension}', 'App\Controllers\Tools\FFmpeg@run');
$router->get('/capture/{filename}', 'App\Controllers\Tools\FFmpeg@capture');
$router->get('/ffm/convert', 'App\Controllers\Tools\FFmpeg@convert_video');
$router->get('/ffm/stream', 'App\Controllers\Tools\FFmpeg@hls_stream');
$router->get('/dash/{filename}', 'App\Controllers\Tools\FFmpeg@dash');

// FPT
$router->get('/fpt/upload/{folder}', 'App\Controllers\Tools\FPT@upload');
$router->get('/fpt/list/{folder}', 'App\Controllers\Tools\FPT@list');

$router->get('/resize1', 'App\Controllers\Tools\Resize@resize');

// Wordpress create folder
$router->get('/getfiles', 'App\Controllers\Tools\GetFiles@getfiles');
$router->post('/getfiles', 'App\Controllers\Tools\GetFiles@getfiles');

$router->get('/get-data', 'App\Controllers\Tools\Stream@get_data');
$router->get('/stream', 'App\Controllers\Tools\Stream@stream');

$router->get('/ocr', 'App\Controllers\Tools\OCRTool@read');

$router->get('/user/login', 'App\Controllers\Users\Login@login');
$router->post('/user/login', 'App\Controllers\Users\Login@login');

$router->get('/user/register', 'App\Controllers\Users\Register@create_user');
$router->post('/user/register', 'App\Controllers\Users\Register@create_user');

$router->run();

