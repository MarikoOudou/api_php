<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: *");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding");


    require_once 'app/config/config.php';
    require_once 'app/config/cnx.php';
    require_once 'app/config/constants.php';
    require_once 'app/config/functions.php';
    require_once 'app/models/model.class.php';
    require_once 'app/models/autoloader.class.php';

    Autoloader::autoload();

    $action = Constant::$ACTION_MISSING;

    if(isset($_GET['controller'])){
        $controller = $_GET['controller'];

        if(isset($_GET['action'])){
            if(in_array($action, $config['action'])){
                $action = $_GET['action'];
            }else{
                $action = Constant::$ACTION_MISSING;
            }
        }
    }
    else{ $controller = 'error'; }

    if(in_array($controller, $config['controller'])){
        include('app/controllers/'.$controller.'.controller.php');
    }else{
        include('app/controllers/error.controller.php');
    }

?>