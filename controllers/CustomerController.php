<?php
/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 7/2/15
 * Time: 7:46 AM
 */

/*function __autoload($classname) {
    $classname = ltrim($classname, '\\');
    $filename  = '';
    $namespace = '';
    if ($lastnspos = strripos($classname, '\\')) {
        $namespace = substr($classname, 0, $lastnspos);
        $classname = substr($classname, $lastnspos + 1);
        $filename  = str_replace('\\', '/', $namespace) . '/';
    }
    $filename .= str_replace('_', '/', $classname) . '.php';
    require $filename;
}*/

//namespace controllers;
require_once("./models/Customer.php");
class CustomerController {
    private $_params;

    public function __construct($params){
        $this->_params = $params;
    }

    public function createAction(){
        $input  = $this->_params;

        unset($input['controller']);
        unset($input['action']);


        $customer = new models\Customer();
        foreach($input as $key=>$value){
            $customer->$key = $value;
        }

        if($customer->create()){
            return $customer; //success
        }else{
            throw new \Exception("Customer could not be created"); //return "error"; //unsuccessful
        }
    }

    public function updateAction(){
        $input  = $this->_params;

        unset($input['controller']);
        unset($input['action']);

        var_dump($input);
        $customer = models\Customer::find($input['id']);
        foreach($input as $key=>$value){
            $customer->$key = $value;
        }

        if($customer->update()){
            return $customer; //success
        }else{
            throw new \Exception("Customer record could not be updated"); //return "error"; //unsuccessful
        }
    }

    public function phonevalidateAction(){
        // generate "random" 6-digit verification code
        $code = rand(100000, 999999);
    }
} 