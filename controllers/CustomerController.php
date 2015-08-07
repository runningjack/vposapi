<?php
/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 7/2/15
 * Time: 7:46 AM
 */


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

        $customer = new \models\Customer();
        foreach($input as $key=>$value){
            $customer->$key = $value;
        }

        //$customer->password = system\library\Hashing\Bycrypt::make($input['password']); /**Todo commented out because server PHP on Ver 5.4*/
        $input['number']=$input['phone'];
        $input['key_salt']="";
        $customer->password = system\library\Hashing\Shahash::make($input['password'],$input);
        $customer->verified = 0;
        $v =    new system\library\Validator\Validator( array(
                new system\library\Validator\Validate\Unique("email","is already existing","customers"),
                new system\library\Validator\Validate\Required('email'," is required"),
                new system\library\Validator\Validate\Unique("phone","is already existing","customers"),
                new system\library\Validator\Validate\Unique("app_id","is already existing","customers"),
                new system\library\Validator\Validate\Required('phone'," is required")
        ),$input);

        if($v->execute() == true){

            if($customer->create()){
                if(isset($input['phone'])){
                    $this->pinAction($customer->id);
                }

                $result                 =   array();
                $result['success']      =   true;
                $result['msg']          =   "Record Created";
                $result['id']           =   $customer->id;
                $result['code']         =   "200";

                return $result;
            }else{
                $result                 =   array();
                $result['success']      =   false;
                $result['errmsg']       =   "Customer could not be created";
                $result['code']         =   "501";
                //throw new \Exception("Customer could not be created"); //return "error"; //unsuccessful
                return $result;
            }
        }else{

             $v_result = $v->getErrors();

            $result                 =   array();
            $result['success']      =   false;
            $result['errmsg']       =   $v_result;
            $result['code']         =   "501";
            return $result;
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

            return $verify->post(array("number"=>$input['phone']));
            //return $customer; //success
        }else{
            throw new \Exception("Customer record could not be updated"); //return "error"; //unsuccessful
        }
    }

    public function pinAction($id){
        $input = $this->_params;
        $verify = new system\library\Verify("","","",$input);
        return $verify->post(array("number"=>$input['phone']),"customers",$id);
    }

    public function verifyAction(){
        $input = $this->_params;
        $verify = new system\library\Verify("","","",$input);
        $myobj = (\system\library\Database\DB::find_by_sql("SELECT * FROM customers WHERE number=".$input['number']));

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