<?php

/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 7/5/15
 * Time: 12:21 PM
 */

class MerchantController {
    private $_params;
    public function __construct($params){
        $this->_params = $params;
    }

    public function createAction(){
        $input  = $this->_params;
        unset($input['controller']);
        unset($input['action']);
        $merchant = new \models\Merchant();
        foreach($input as $key=>$value){
            $merchant->$key = $value;
        }
        $merchant->created_at = date("Y-m-d H:i:s");
        $merchant->verified = 0;
        $input['number']=$input['phone'];
        $input['key_salt']="";
        $merchant->password = system\library\Hashing\Shahash::make($input['password'],$input);
        $merchant->verified = 0;
        $v =    new system\library\Validator\Validator( array(
            new system\library\Validator\Validate\Unique("email","is already existing","merchants"),
            new system\library\Validator\Validate\Required('email'," is required"),
            new system\library\Validator\Validate\Unique("phone","is already existing","merchants"),
            new system\library\Validator\Validate\Unique("app_id","is already existing","merchants"),
            new system\library\Validator\Validate\Required('phone'," is required")
        ),$input);
        if($v->execute() == true){

            if($merchant->create()){
                if(isset($input['phone'])){
                    $this->pinAction($merchant->id);
                }
                $result                 =   array();
                $result['success']      =   true;
                $result['msg']          =   "Record Created";
                $result['id']           =   $merchant->id;
                $result['code']         =   "200";
                return $result;
            }else{
                $result                 =   array();
                $result['success']      =   false;
                $result['errmsg']       =   "Merchant could not be created";
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

    public function pinAction($id){
        $input = $this->_params;
        $verify = new system\library\Verify("","","",$input);
        return $verify->post(array("number"=>$input['phone']),"merchants",$id);
    }

    public function verifyAction(){
        $input = $this->_params;
        $verify = new system\library\Verify("","","",$input);
        $myobj = (\system\library\Database\DB::find_by_sql("SELECT * FROM merchants WHERE number=".$input['number']));

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