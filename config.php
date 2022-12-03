<?php
$ftp_server = "162.220.164.98";
$ftp_username = 'admin@st27850.ispot.cc';
$ftp_userpass = '@@Hoquynh1234??';


define('HOSTNAME', 'localhost');
define('HOSTUSER', 'root');
define('HOSTPASS', '');
define('HOSTDB', 'phpframework');

define('FPT_SERVER',$ftp_server);
define('FPT_USER',$ftp_username);
define('FPT_PASS',$ftp_userpass);
define('ROOTPATH',getcwd());
define('ROOTURL', 'http://localhost/download-file');
define('REGEX_HTTP', '/(((http|https)\:\/\/)?[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?\:@\-_=#]))*/');
define('REGEX_IMG', '/((?!\/)([\/|\-|\_]|[0-9]|[a-zA-Z])*\.(jpg|png))/i');