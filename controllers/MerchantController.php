<?php
/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 7/5/15
 * Time: 12:21 PM
 */

namespace controller;
use models\Merchant;

function controller__autoload($classname) {
    $classname = ltrim($classname, '\\');
    $filename  = '';
    $namespace = '';
    if ($lastnspos = strripos($classname, '\\')) {
        $namespace = substr($classname, 0, $lastnspos);
        $classname = substr($classname, $lastnspos + 1);
        $filename  = preg_replace('#\/\/#', '/', $namespace) . '/';
    }
    $filename .= preg_replace('/_/', '/', $classname) . '.php';
    require $filename;
    //echo "use ".$namespace;
}

spl_autoload_register("controller__autoload");
class MerchantController {
    private $_params;

    public function __construct($params){
        $this->_params = $params;
    }

    public function createAction(){
        $input  = $this->_params;

        unset($input['controller']);
        unset($input['action']);

        $merchant = new Merchant();
        foreach($input as $key=>$value){
            $merchant->$key = $value;
        }
        $merchant->verified = 0;

        if($merchant->create()){
            return $merchant; //success
        }else{
            throw new \Exception("Merchant could not be created"); //return "error"; //unsuccessful
        }
    }
} 