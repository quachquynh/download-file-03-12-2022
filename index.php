<?php
require_once './vendor/autoload.php';
require_once './src/Helpers/helpers.php';
require_once './src/Helpers/functions.php';
require_once './config.php';

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
$router->get('/png-to-jpeg', 'App\Controllers\Tools\Images@pngToJpeg');

// Youtube Download - oke
$router->get('/youtube-download/{id}', 'App\Controllers\Tools\Youtube@youtube_download');
$router->get('/youtube/get', 'App\Controllers\Tools\Youtube@get_data');

$router->get('/facebook-api', 'App\Controllers\Tools\FacebookApi@facebook_api');
$router->get('/facebook-api/callback', 'App\Controllers\Tools\FacebookApi@callback');

$router->get('/google-drive/auth', 'App\Controllers\Tools\GoogleDrive@auth');
$router->get('/google-drive/token', 'App\Controllers\Tools\GoogleDrive@token');
$router->get('/google-drive/list/{id}', 'App\Controllers\Tools\GoogleDrive@listFiles');
$router->get('/google-drive/download/{id}/{name}', 'App\Controllers\Tools\GoogleDrive@download');
$router->get('/google-drive/upload', 'App\Controllers\Tools\GoogleDrive@upload');
$router->post('/google-drive/upload', 'App\Controllers\Tools\GoogleDrive@upload');
$router->get('/google-drive/create', 'App\Controllers\Tools\GoogleDrive@createFolder');
$router->get('/gphoto', 'App\Controllers\Tools\GoogleDrive@googlePhoto');

$router->get('/google-drive/stream', 'App\Controllers\Tools\GoogleDrive@stream');

// Onedrive
$router->get('/onedrive/{url}', 'App\Controllers\Tools\OneDrive@onedrive');

$router->get('/hls', 'App\Controllers\Tools\Download@hls');

// Download image
$router->get('/download/{url}', 'App\Controllers\Tools\Download@download');
//$router->post('/download/{url}', 'App\Controllers\Tools\Download@download');
/*
 * Get file wordpress
 */
$router->get('/downloadfile', 'App\Controllers\Tools\Download@downloadfile');
$router->post('/downloadfile', 'App\Controllers\Tools\Download@downloadfile');

$router->get('/delete', 'App\Controllers\Tools\Download@delete');
$router->get('/download-thumb/{url}', 'App\Controllers\Tools\Download@download_image');

$router->get('/download-video/{url}', 'App\Controllers\Tools\Download@download_video');
//$router->get('/curl', 'App\Controllers\Tools\Download@download');

// FFmpeg
$router->get('/ffmpeg/{foldername}/{filename}/{extension}/{root}', 'App\Controllers\Tools\FFmpeg@run');
$router->get('/capture/{filename}', 'App\Controllers\Tools\FFmpeg@capture');
$router->get('/ffm/convert', 'App\Controllers\Tools\FFmpeg@convert_video');
$router->get('/ffm/stream', 'App\Controllers\Tools\FFmpeg@hls_stream');
$router->get('/dash/{filename}', 'App\Controllers\Tools\FFmpeg@dash');

// FPT
//$router->get('/fpt/upload/{folder}', 'App\Controllers\Tools\FPT@upload');
$router->get('/fpt/list/{folder}', 'App\Controllers\Tools\FPT@list');

$router->get('/fpt/upload/{number}', 'App\Controllers\Tools\FPT@fpt_upload');
//$router->post('/fpt/upload', 'App\Controllers\Tools\FPT@fpt_upload');

$router->get('/resize1', 'App\Controllers\Tools\Resize@resize');
$router->get('/resize02/{newwidth}', 'App\Controllers\Tools\Resize@resize02');
$router->get('/watermark', 'App\Controllers\Tools\Resize@watermark');

// Wordpress create folder
$router->get('/getfiles', 'App\Controllers\Tools\GetFiles@getfiles');
$router->post('/getfiles', 'App\Controllers\Tools\GetFiles@getfiles');

$router->get('/get-data', 'App\Controllers\Tools\Stream@get_data');
$router->get('/stream', 'App\Controllers\Tools\VideoStream@play');

$router->get('/ocr', 'App\Controllers\Tools\OCRTool@read');

$router->get('/user/login', 'App\Controllers\Users\Login@login');
$router->post('/user/login', 'App\Controllers\Users\Login@login');

$router->get('/user/register', 'App\Controllers\Users\Register@create_user');
$router->post('/user/register', 'App\Controllers\Users\Register@create_user');

$router->get('/api/getfile', 'App\Controllers\Tools\Api@getfile');
$router->get('/api/get-token', 'App\Controllers\Tools\Api@request');
$router->post('/api/request', 'App\Controllers\Tools\Api@request');

$router->get('/api/getdata', 'App\Controllers\Tools\Api@getData');
$router->get('/api/play', 'App\Controllers\Tools\Api@play_mp4');
$router->get('/api/getvideo', 'App\Controllers\Tools\Api@getVideo');
$router->get('/api/getfile', 'App\Controllers\Tools\Api@getfile');
$router->get('/api/stream-m3u8', 'App\Controllers\Tools\Api@stream_m3u8');
$router->get('/api/parseHLS', 'App\Controllers\Tools\Api@parseHLS');

$router->get('/api/getip', 'App\Controllers\Tools\Api@getip');

$router->get('/api/register', 'App\Controllers\Tools\Api@register');
$router->post('/api/register', 'App\Controllers\Tools\Api@register');

// Video stream
$router->get('/play', 'App\Controllers\Tools\VideoStream@play');

// WORDPRESS API
$router->get('/wordpress/insert', 'App\Controllers\Tools\Wordpress@insert_post');
$router->post('/wordpress/insert', 'App\Controllers\Tools\Wordpress@insert_post');

$router->get('/excel/read', 'App\Controllers\Tools\Excel@read');

$router->get('/crawler', 'App\Controllers\Tools\Crawler@crawler');

$router->run();

