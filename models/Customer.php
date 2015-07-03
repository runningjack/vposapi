<?php
/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 7/2/15
 * Time: 7:31 AM
 */

namespace models;
use system\library\Model;

require_once("./system/library/Model.php");
class Customer extends  Model{
    protected  static $db_fields=array('id','firstname','lastname','phone','email','username','password','address','city','state','created_at','updated_at');
    protected static $table ="customers";

    public  $id;
    public  $firstname;
    public  $lastname;
    public  $phone;
    public  $email;
    public  $username;
    public  $password;
    public  $address;
    public  $city;
    public  $state;
    public  $created_at;
    public  $updated_at;

    protected   function attributes(){
        //return get_object_vars($this);
        $instance = new static;
        $attributes = array();
        foreach(self::$db_fields as $field){
            if(property_exists($this,$field)){
                $attributes[$field] =$this->$field;
            }
        }

        return $attributes;
    }
} 