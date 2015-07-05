<?php
/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 7/2/15
 * Time: 7:46 AM
 */

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
//namespace controllers;

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
        $customer->verified = 0;

        if($customer->create()){
            //return $customer; //success
        }else{
            throw new \Exception("Customer could not be created"); //return "error"; //unsuccessful
        }
    }

    public function updateAction(){
        $input  = $this->_params;

        unset($input['controller']);
        unset($input['action']);

        $customer = models\Customer::find($input['id']);
        foreach($input as $key=>$value){
            $customer->$key = $value;
        }
        $customer->updated_at   =   date("Y-m-d H:i:s");



        if($customer->update()){
            $verify = new system\library\Verify("","","",$input);

            return $verify->post(array("number"=>$input['number']));
            //return $customer; //success
        }else{
            throw new \Exception("Customer record could not be updated"); //return "error"; //unsuccessful
        }
    }

    public function pinAction(){
        $input = $this->_params;
        $verify = new system\library\Verify("","","",$input);
        return $verify->post(array("number"=>$input['number']));
    }

    public function verifyAction(){
        $input = $this->_params;
        $verify = new system\library\Verify("","","",$input);
        $myobj = (\system\library\Database\DB::find_by_sql("SELECT * FROM hashes WHERE number=".$input['number']));

        if($myobj){
            $myobj = new \ArrayObject(array_shift($myobj));
            if($verify->get($myobj->offsetGet("hashed"),$input)){
                return "valid";
            }else{
                return "invalid";
            }
        }

    }
} 