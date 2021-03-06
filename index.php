<?php
//require_once (__DIR__."/bootstrap/config.php");

//echo $data['database']['username'];
require(__DIR__."/config.php");
//require_once(__DIR__."/routes.php");
//use \models\Customer;

try{
    $params = $_REQUEST;
    if(count($params) > 0){
        $controller     =   ucfirst(strtolower($params['controller']))."Controller";
        $action         =   strtolower($params['action']).'Action';
        if(file_exists("controllers/{$controller}.php")){
            //echo true;
            require_once("controllers/{$controller}.php");
            $controller = new $controller($params);
            if(method_exists($controller,$action) === false){
                throw new Exception("Action is invalid");
            }
            //$action = $$action;
            $result['data'][] =  $controller->$action() ;


        }else{
            throw new Exception("Controller is invalid");
        }
    }else{
        throw new Exception("Error 404");

    }

}catch(Exception $e){
    //Database Error
    $result                 =   array();
    $result['success']      =   false;
    $result['errormsg']     =   $e->getMessage();
    $result['code']         =   "501";
}catch(PDOException $e){
    //Database Error
    $result                 =   array();
    $result['code']         =   "515";
    $result['success']      =   false;
    $result['errormsg']     =   $e->getMessage();
}
echo json_encode($result);

?>
                                                                                                                                                                             