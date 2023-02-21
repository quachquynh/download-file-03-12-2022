<?php 
function play($url) {?>
	<video width="640" height="360" controls>
	  <source src="<?php echo $url;?>" type="video/mp4">
	Your browser does not support the video tag.
	</video>
<?php }

function copyfile_chunked($infile, $outfile) {
    $chunksize = 10 * (1024 * 1024); // 10 Megs

    /**
     * parse_url breaks a part a URL into it's parts, i.e. host, path,
     * query string, etc.
     */
    $parts = parse_url($infile);
    $i_handle = fsockopen($parts['host'], 80, $errstr, $errcode, 5);
    $o_handle = fopen($outfile, 'wb');

    if ($i_handle == false || $o_handle == false) {
        return false;
    }

    if (!empty($parts['query'])) {
        $parts['path'] .= '?' . $parts['query'];
    }

    /**
     * Send the request to the server for the file
     */
    $request = "GET {$parts['path']} HTTP/1.1\r\n";
    $request .= "Host: {$parts['host']}\r\n";
    $request .= "User-Agent: Mozilla/5.0\r\n";
    $request .= "Keep-Alive: 115\r\n";
    $request .= "Connection: keep-alive\r\n\r\n";
    fwrite($i_handle, $request);

    /**
     * Now read the headers from the remote server. We'll need
     * to get the content length.
     */
    $headers = array();
    while(!feof($i_handle)) {
        $line = fgets($i_handle);
        if ($line == "\r\n") break;
        $headers[] = $line;
    }

    /**
     * Look for the Content-Length header, and get the size
     * of the remote file.
     */
    $length = 0;
    foreach($headers as $header) {
        if (stripos($header, 'Content-Length:') === 0) {
            $length = (int)str_replace('Content-Length: ', '', $header);
            break;
        }
    }

    /**
     * Start reading in the remote file, and writing it to the
     * local file one chunk at a time.
     */
    $cnt = 0;
    while(!feof($i_handle)) {
        $buf = '';
        $buf = fread($i_handle, $chunksize);
        $bytes = fwrite($o_handle, $buf);
        if ($bytes == false) {
            return false;
        }
        $cnt += $bytes;

        /**
         * We're done reading when we've reached the conent length
         */
        if ($cnt >= $length) break;
    }

    fclose($i_handle);
    fclose($o_handle);
    return $cnt;
}

function uploadFTP($root, $folder, $local_file, $name){
    $ftp_server = FPT_SERVER;
    $ftp_username = FPT_USER;
    $ftp_userpass = FPT_PASS;
    $remote_file = 'public_html/videos/'.$root.'/'.$folder.'/'.$name;

    $ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
    $login = ftp_login($ftp_conn, $ftp_username, $ftp_userpass);

    // login
    if ($login){
        echo 'successfully<br/>';
    }else{
        return false;
    }

    ftp_put($ftp_conn, $remote_file, $local_file, FTP_BINARY);
    // get file list of current directory
    $file_list = ftp_nlist($ftp_conn, "");
    //var_dump($file_list);
    ftp_close($ftp_conn);
    return true;
}

function regex_class($data, $class_name) {
    preg_match_all('/<div\sclass\=\"'.$class_name.'(.*?)<\/div>/s', $data, $match);
    return $match[1];
}

function foreach_data($cat) {
    $data_arr = array();
    foreach($cat as $item) {
        $exp = explode('>', $item);
        $data_arr[] = $exp;
    };
    return $data_arr;
    
}