<?php
namespace App\Core;
use App\Controllers\Users\Login;
    /*
        * Base controller
        * Load model and view
    */
    class Controller {

        public function __construct() {
            Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
            Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
            Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed
            // Enable us to use Headers
            ob_start();
            // Set sessions
            if(!isset($_SESSION)) {
                session_start();
            }
            $session = new Login();
        }

        //LOAD MODEL
        public function model($model){
            require_once '../app/models/' . $model . '.php';
            //instantiate model
            return new $model();
            //Eg: if Post was passed in, then it will return new Post();
        }

        //LOAD VIEW
        /*public function view($view, $data = []){
            //check for view file
            if(file_exists('../app/views/' . $view . '.php')){
                require_once '../app/views/' . $view . '.php';
            }else{
                die('View does not exists');
            }
        }*/

        public function view($view, $data = []) {
            include(__DIR__.'/../Views/'.$view.'.php');
        }

    }