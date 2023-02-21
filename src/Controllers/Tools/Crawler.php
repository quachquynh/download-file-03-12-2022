<?php 
namespace App\Controllers\Tools;
use App\Core\Controller;
use App\Models\Product;
use App\Core\DB;
use Sunra\PhpSimple\HtmlDomParser;

class Crawler extends Controller {

	public function foreach_data($cat) {
		$data_arr = array();
		foreach($cat as $item) {
			$exp = explode('>', $item);
			$data_arr[] = $exp;
		};
		return $data_arr;
		
	}
    public function crawler()
    {
        $data = curl('https://nhaccutienmanh.vn/trung-go-son-dau-chicken-shake-msd/');
        preg_match_all('/(product_title.*<\/h1>)/i', $data, $matches);
        foreach($matches[0] as $m) {
			$exp = explode('>', $m);
        	//echo $exp[1].'<br/>';
        }

        preg_match_all('/<table\sclass\=\"variations-tableInfo(.*?)<\/table>/s', $data, $matches);

		//HTML array in $matches[1]
		//var_dump($matches[1]);

		// product_meta
		$meta = regex_class($data, 'product_meta');
		$meta_for = foreach_data($meta);
		var_dump($meta_for[0][5]);
		var_dump($meta_for[0][7]);

		preg_match_all('/<div\sclass\=\"tab-description(.*?)<\/div>/s', $data, $match_desc);
		
		// Description
		$rex = regex_class($data, 'tab-description');
		$desc = foreach_data($rex);
		var_dump($desc[0][1]);
        
        // Thumb
		$thumb = regex_class($data, 'iconic-woothumbs-images__slide');
		$thumb_for = foreach_data($thumb);
		var_dump($thumb_for);
    }

}