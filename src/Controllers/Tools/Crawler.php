<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use App\Models\Product;
use App\Core\DB;
use Sunra\PhpSimple\HtmlDomParser;

class Crawler extends Controller {

    public function crawler()
    {
        $data = curl('https://nhaccutienmanh.vn/trong-cam-tay-vien-kim-loai-hd6/');
        preg_match_all('/(product_title.*<\/h1>)/i', $data, $matches);
        foreach($matches[0] as $m) {
			$exp = explode('>', $m);
        	echo $exp[1].'<br/>';
        }

        preg_match_all('/<table\sclass\=\"variations-tableInfo(.*?)<\/table>/s', $data, $matches);

		//HTML array in $matches[1]
		var_dump($matches[1]);

		preg_match_all('/<div\sclass\=\"product_meta(.*?)<\/div>/s', $data, $matches);

		//HTML array in $matches[1]
		var_dump($matches[1]);
        
    }

}