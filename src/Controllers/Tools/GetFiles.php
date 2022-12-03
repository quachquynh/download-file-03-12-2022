<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;

class GetFiles extends Controller {
    public function getfiles() {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
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

                    // Xóa phần tử
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
        else {
            $this->view('/tools/getfiles');
        }
    }
}