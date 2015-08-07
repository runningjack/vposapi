<?php
/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 8/6/15
 * Time: 8:56 AM
 */

class TransactionController {
    private $_params;
    public function __construct($params){
        $this->_params = $params;
    }

    public function createAction(){
        $input  = $this->_params;
        $transaction = new \models\Transaction();

        $mCusData = explode(";",$input["merchData"]);
        var_dump(($mCusData));
       //$d = [];
        $d = explode(":",$mCusData);
        echo "<br>";
        var_dump($d);
        foreach($mCusData as $cdata){
            foreach(get_obkect_vars($transaction) as $name=>$value){
                if($name == "trans_amount"){
                    $input[$name] = preg_replace("/AMOUNT:?/","",$cdata);
                }

            }

            echo $cdata."<br>";
        }
        $mMerchData = $input['merchData'];



        system\library\Database\DB::insert("transactions",array("trans_id"=>$input["merchData"],"trans_type"=>$input["cusData"],"narration"=>$input["merchData"],"merch_app_id"=>$input["merchData"]));
        $result                 =   array();
        $result['success']      =   true;
        $result['msg']          =   "Record Created";
        $result['id']           =
        $result['code']         =   "200";
        return $result;

        exit;
        unset($input['controller']);
        unset($input['action']);

        foreach($input as $key=>$value){
            $transaction->$key = $value;
        }
        $transaction->created_at = date("Y-m-d H:i:s");
        // $transaction->verified = 0;
        //$merchant->verified = 0;
        $v =    new system\library\Validator\Validator( array(
            //new system\library\Validator\Validate\Unique("tran"," is already existing","transactions"),
            new system\library\Validator\Validate\Required('trans_id'," is required"),
            new system\library\Validator\Validate\Required('merch_app_id'," is required"),
            new system\library\Validator\Validate\Required("cus_app_id"," is required"),
            new system\library\Validator\Validate\Required("trans_amount"," is required"),
            new system\library\Validator\Validate\Required('cus_bank_name'," is required"),
            new system\library\Validator\Validate\Required("merch_bank_acc"," is required"),
            new system\library\Validator\Validate\Required('cus_bank_acc'," is required"),
            new system\library\Validator\Validate\Required("merch_bank_acc"," is required"),
            new system\library\Validator\Validate\Required('cus_bank_acc'," is required"),
            new system\library\Validator\Validate\Required("merch_bank_code"," is required"),
            new system\library\Validator\Validate\Required('cus_bank_code'," is required")
        ),$input);
        if($v->execute() == true){

            if($transaction->create()){

                $result                 =   array();
                $result['success']      =   true;
                $result['msg']          =   "Record Created";
                $result['id']           =   $transaction->id;
                $result['code']         =   "200";
                return $result;
            }else{
                $result                 =   array();
                $result['success']      =   false;
                $result['errmsg']       =   "Transaction could not be created";
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
        $transaction = \models\Customer::find($input['id']);
        foreach($input as $key=>$value){
            $transaction->$key = $value;
        }
        $transaction->updated_at   =   date("Y-m-d H:i:s");
        if($transaction->update()){
            $verify = new system\library\Verify("","","",$input);
            //return $customer; //success
        }else{
            throw new \Exception("Transaction could not be updated"); //return "error"; //unsuccessful
        }
    }

    public function deleteAction($id){

    }
} 