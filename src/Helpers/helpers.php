<?php

function getCurl($page_url){
    $ch = curl_init(); 
    // Step 2
    curl_setopt($ch,CURLOPT_URL,$page_url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    //curl_setopt($ch,CURLOPT_HEADER, false);
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13'); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
    // Step 3
    $result=curl_exec($ch);
    // Step 4
    curl_close($ch);
    return $result;
}

function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
    curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
    //https
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML,like Gecko) Chrome/27.0.1453.94 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000000000000000);
    $output = curl_exec($ch); 
    if(curl_exec($ch) === false)
    {
        echo 'Curl error: ' . curl_error($ch);
    }
    else
    {
        //echo 'Operation completed without any errors';
    }
    curl_close($ch);  
    return $output;
}

function getDomain($url) {
    $parse = parse_url($url);
    $domain = $parse["scheme"].'://'.$parse["host"].'/';
    return $domain;
}

function resize_image($file, $newwidth = 500) {
    $exp = explode("\\", $file);
    $name = end($exp);
    // Get sizes
    list($width, $height) = getimagesize($file);
    $newheight = ($height/$width)*$newwidth;
    // Load
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($file);
    // Resize
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, 
    $height);
    // Output
    imagejpeg($thumb, 'photos/resize/'.$name.".jpg", 100);
    return $source;
}

function resize_run($folder) {
    //$images = scandir($folder);
    $images = glob($folder."*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    foreach($images as $image) {
        //$img = $folder.$image;
        $filesize = filesize($image);
        if($filesize < 10000) {
        }
        else {
            echo $image.'<br/>';
            // Hoat dong khi dung return
            if(resize_image($image)) {
                unlink_file($image);
            }
        } 
    }
}

function watermark_image($file, $watermark, $name) {
    $new_image_name = ROOTPATH.'\\photos\\w\\'.$name;
    $image_path = $watermark;
    list($owidth,$oheight) = getimagesize($file);
    $width = 900;
    $height = 900;
    $aspect = $width / $height;
    $newHeight = $width / $aspect;
    $im = imagecreatetruecolor($width, $height);
    $img_src = imagecreatefromjpeg($file);
    imagecopyresampled($im, $img_src, 0, 0, 0, 0, $width, $height, $owidth, $oheight);
    $watermark = imagecreatefrompng($image_path);
    list($w_width, $w_height) = getimagesize($image_path);
    $pos_x = $width - $w_width;
    $pos_y = $height - $w_height;
    imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
    imagejpeg($im, $new_image_name, 100);
    imagedestroy($im);
    //unlink($oldimage_name);//
    return true;
}

function unlink_file($file) {
    return unlink($file);
}

function ffmpeg_run($foldername, $filename, $extension) {
    shell_exec("C:\\ffmpeg\\bin\\ffmpeg.exe -i C:\\ffmpeg\\video_in\\".$filename.".".$extension." -codec: copy -start_number 0 -hls_time 2 -hls_list_size 0 -f hls C:\\ffmpeg\\video_out\\".$foldername."\\".$filename.".m3u8");
}

// Create folder by Url path
function createFolderByPath($fullUrl, $folderPath) {
    $path = parse_url( $fullUrl, PHP_URL_PATH );
    $dir = $folderPath . dirname($path) . '/';

    if ( !file_exists( $dir ) ) {
        mkdir( $dir, 0777, true );
    }
}

// Put content to folder
function put_content( $fullUrl, $folderPath) {
    $data = curl($fullUrl);
    $remove_array = explode("/", $fullUrl);
    unset($remove_array[0]);
    unset($remove_array[1]);
    unset($remove_array[2]);
    $imp = implode('/', $remove_array);
    return file_put_contents($folderPath.$imp, $data);
}

// Redirect
function redirect($uri) {
    $url = ROOTURL. '/';
    header('Location: '.$url.$uri);
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// Clean xss
function xss_clean($data)
{
// Fix &entity\n;
$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

// Remove any attribute starting with "on" or xmlns
$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

// Remove javascript: and vbscript: protocols
$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

// Remove namespaced elements (we do not need them)
$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

do
{
    // Remove really unwanted tags
    $old_data = $data;
    $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
}
while ($old_data !== $data);

// we are done...
return $data;
}

function getlink2() {

        $domain = 'https://seofy.wgl-demo.net/';
        $data_string = file_get_contents($domain);

        // ?????nh d???ng kh??ng c?? domain ?????u
        $pattern = '/((js)|(css)\/.*\.(css|js))/i';
        //$pattern = '~(http.*\.)(css|js)~i';

        $m = preg_match_all($pattern,$data_string,$matches);
        foreach($matches[0] as $string) {
            //echo $string.'<br/>';

            // ?????nh d???ng kh??ng c?? domain ?????ufile_get_contents($string);
            $file_url = $domain.$string;
            $data = file_get_contents($file_url);

            echo $file_url;
        
        $array = explode('/', $string);

        $file_name = end($array);

        var_dump('<pre>');

        $current_path = getcwd();

        unset($array[0]);
        unset($array[1]);
        unset($array[2]);

        $string2 = implode('/', $array);

        // Path folder
        $file_put_content = $current_path."\\".$string2;

        echo file_put_contents($file_put_content,$data);

        $array2 = explode('/', $string);
        $parent_path = $current_path."\\".$array2[0];
        var_dump($array2);

        echo 'Ph??n t???ng th?? m???c: '.count($array2).'<br/>';

        $parent_folder = $array2[0];
        $folder_child1 = $parent_folder.'/'.$array2[1];
        $folder_child2 = $folder_child1.'/'.$array2[2];
        $folder_child3 = $folder_child2.'/'.$array2[3];
        $folder_child4 = $folder_child3.'/'.$array2[4];
        $folder_child5 = $folder_child4.'/'.$array2[5];
        $folder_child6 = $folder_child5.'/'.$array2[6];
        $folder_child7 = $folder_child6.'/'.$array2[7];
        $folder_child8 = $folder_child7.'/'.$array2[8];
        $folder_child9 = $folder_child8.'/'.$array2[9];
        $folder_child10 = $folder_child9.'/'.$array2[10];

        echo $folder_child1.'<br/>';
        echo $folder_child2.'<br/>';
        echo $folder_child3.'<br/>';
        echo $folder_child4.'<br/>';
        echo $folder_child5.'<br/>';
        echo $folder_child6.'<br/>';
        echo $folder_child7.'<br/>';
        echo $folder_child8.'<br/>';
        echo $folder_child9.'<br/>';
        echo $folder_child10.'<br/>';

        for($i = 0; $i < count($array2); $i++) {

            //echo $i.'<br/>';
            
        }

        if (!file_exists($parent_folder) && !preg_match('/(\.css)|(\.js)/', $parent_folder) ) {
            mkdir($parent_folder, 0777, true);
        }
        if (!file_exists($folder_child1) && !preg_match('/(\.css)|(\.js)/', $folder_child1) ) {
            mkdir($folder_child1, 0777, true);
        }
        if (!file_exists($folder_child2) && !preg_match('/(\.css)|(\.js)/', $folder_child2) ) {
            mkdir($folder_child2, 0777, true);
        }
        if (!file_exists($folder_child3) && !preg_match('/(\.css)|(\.js)/', $folder_child3) ) {
            mkdir($folder_child3, 0777, true);
        }
        if (!file_exists($folder_child4) && !preg_match('/(\.css)|(\.js)/', $folder_child4) ) {
            mkdir($folder_child4, 0777, true);
        }
        if (!file_exists($folder_child5) && !preg_match('/(\.css)|(\.js)/', $folder_child5) ) {
            mkdir($folder_child5, 0777, true);
        }
        if (!file_exists($folder_child6) && !preg_match('/(\.css)|(\.js)/', $folder_child6) ) {
            mkdir($folder_child6, 0777, true);
        }
        if (!file_exists($folder_child7) && !preg_match('/(\.css)|(\.js)/', $folder_child7) ) {
            mkdir($folder_child7, 0777, true);
        }
        if (!file_exists($folder_child8) && !preg_match('/(\.css)|(\.js)/', $folder_child8) ) {
            mkdir($folder_child8, 0777, true);
        }
        if (!file_exists($folder_child9) && !preg_match('/(\.css)|(\.js)/', $folder_child9) ) {
            mkdir($folder_child9, 0777, true);
        }
        if (!file_exists($folder_child10) && !preg_match('/(\.css)|(\.js)/', $folder_child10) ) {
            mkdir($folder_child10, 0777, true);
        }

        var_dump('</pre>');
        }
    }
    function getlink3() {

        // ?????nh d???ng kh??ng c?? domain ?????u
        $domain = 'https://seofy.wgl-demo.net/';
        $data_string = file_get_contents($domain);
        var_dump($data_string);
        $pattern = '/((css\/.*css)|(js\/.*js)|(images\/.*(\.jpg|
        .png|\.jpeg)))/i';

        $m = preg_match_all($pattern,$data_string,$matches);
        foreach($matches[0] as $string) {

            // ?????nh d???ng kh??ng c?? domain ?????ufile_get_contents($string);
            $file_url = $domain.$string;
            $array = explode('/', $file_url);

            $file_name = end($array);

            $current_path = getcwd();

            unset($array[0]);
            unset($array[1]);
            unset($array[2]);

            $string2 = implode("\\", $array);

            $array2 = explode("\\", $string2);

            
            // Implode folder 
            $folder_path = implode("\\", $array2);

            $first_el = reset($array2);
            
            // Path folder
            $file_put_content = $current_path."\\".$string2;
            $data_url = file_get_contents($file_url);
            echo file_put_contents($file_put_content,$data_url);
            var_dump($array2);

            $parent_path = $current_path."\\".$first_el;

            $parent_folder = $array2[0];
            $folder_child1 = $parent_folder.'/'.$array2[1];
            $folder_child2 = $folder_child1.'/'.$array2[2];
            $folder_child3 = $folder_child2.'/'.$array2[3];
            $folder_child4 = $folder_child3.'/'.$array2[4];
            $folder_child5 = $folder_child4.'/'.$array2[5];
            $folder_child6 = $folder_child5.'/'.$array2[6];
            $folder_child7 = $folder_child6.'/'.$array2[7];
            $folder_child8 = $folder_child7.'/'.$array2[8];
            $folder_child9 = $folder_child8.'/'.$array2[9];
            $folder_child10 = $folder_child9.'/'.$array2[10];

            if (!file_exists($parent_folder) && !preg_match($pattern, $parent_folder) ) {
                mkdir($parent_folder, 0777, true);
            }
            if (!file_exists($folder_child1) && !preg_match($pattern, $folder_child1) ) {
                mkdir($folder_child1, 0777, true);
            }
            if (!file_exists($folder_child2) && !preg_match($pattern, $folder_child2) ) {
                mkdir($folder_child2, 0777, true);
            }
            if (!file_exists($folder_child3) && !preg_match($pattern, $folder_child3) ) {
                mkdir($folder_child3, 0777, true);
            }
            if (!file_exists($folder_child4) && !preg_match($pattern, $folder_child4) ) {
                mkdir($folder_child4, 0777, true);
            }
            if (!file_exists($folder_child5) && !preg_match($pattern, $folder_child5) ) {
                mkdir($folder_child5, 0777, true);
            }
            if (!file_exists($folder_child6) && !preg_match($pattern, $folder_child6) ) {
                mkdir($folder_child6, 0777, true);
            }
            if (!file_exists($folder_child7) && !preg_match($pattern, $folder_child7) ) {
                mkdir($folder_child7, 0777, true);
            }
            if (!file_exists($folder_child8) && !preg_match($pattern, $folder_child8) ) {
                mkdir($folder_child8, 0777, true);
            }
            if (!file_exists($folder_child9) && !preg_match($pattern, $folder_child9) ) {
                mkdir($folder_child9, 0777, true);
            }
            if (!file_exists($folder_child10) && !preg_match($pattern, $folder_child10) ) {
                mkdir($folder_child10, 0777, true);
            }

        }
    }
function create_folder() {
    $url_list = ['https://seofy.wgl-demo.net/wp-content/themes/seofy/js/theme.js?ver=5.7.8', 'https://seofy.wgl-demo.net/wp-content/plugins/mailchimp-for-wp/assets/js/forms.js?ver=4.8.6'];
    foreach($url_list as $url) {
        $pattern = '/((.*css)|(.*js)|(images\/.*(\.jpg|
        .png|\.jpeg)))/i';

        preg_match_all($pattern,$url,$matches);
        var_dump($matches[0]);
        foreach($matches[0] as $url_file) {
            $path = parse_url( $url_file, PHP_URL_PATH );
            $dir = __DIR__ . dirname($path) . '/';

            if ( !file_exists( $dir ) ) {
                mkdir( $dir, 0777, true );
            }

            $array = explode('/', $url_file);

            // X??a ph???n t???
            unset($array[0]);
            unset($array[1]);
            unset($array[2]);

            $string = implode('\\', $array);

            $current_path = getcwd();

            $path_file1 = $current_path.'\\'.$string;
            $file_name = end($array);

            array_pop($array);

            $string2 = implode('\\', $array);
            $path_file2 = $current_path.'\\'.$string2;


            var_dump($path_file1);

            $data = file_get_contents($url);
            echo file_put_contents($path_file1,$data);
        }

        

    }

    
}

function download_input() {

    /*if(isset($_POST['btn-submit'])) {
        $form_data = $_POST['form-data'];

        $domain = 'https://hanoitv.vn';
        //$data = curl($domain);

        $pattern = "/\b(.*(\.css|\.js).*)/i";
        preg_match_all($pattern, $form_data, $matches);

        $pattern_http = '#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';

        foreach ($matches[0] as $value) {
            
            if( preg_match_all($pattern, $value, $matches_http)) {
                var_dump("<pre>");
                var_dump($matches_http[0]);
                var_dump("</pre>");
            }
            
        

}}*/?>
<!DOCTYPE html>
<html>
   <head>
      <title>Bootstrap Forms</title>
      <meta name = "viewport" content="width = device-width, initial-scale = 1">
      <link rel = "stylesheet" href = "https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
      <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src = "https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
   </head>
   <body>
      <form role = "form" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
        <div class="form-group">
            <label for="comment">Domain *:</label>
            <input class="form-control" rows="5" id="comment" name="domain" placeholder="https://demo.oceanthemes.site/sandbox/home-2" required>
          </div>
        <div class="form-group">
            <label for="comment">Domain Link File*:</label>
            <input class="form-control" rows="5" id="comment" name="domain-link" placeholder="https://demo.oceanthemes.site/sandbox">
          </div>
         <div class = "form-group">
            <label for = "name">Player Details</label>
            <textarea class = "form-control" rows = "3" placeholder = "Player Details" name="form-data"></textarea>
         </div>
         <button type="submit" name="btn-submit">Submit</button>
      </form>
   </body>
</html>
<?php }
//getlink2();
// ?????nh d???ng kh??ng c?? domain
//getlink3();
//create_folder();