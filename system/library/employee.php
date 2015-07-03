<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 6/4/15
 * Time: 11:09 AM
 */
namespace system\library;
//use system\library\Model;
require_once("system/library/model.php");
class Employee extends Model {
    protected  static $db_fields=array('id','name','phone','email','username','password','created_at','updated_at');
    protected static $table ="employee";

    public  $id;
    public  $name;
    public  $phone;
    public  $email;
    public  $username;
    public  $password;
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