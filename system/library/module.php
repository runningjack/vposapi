<?php
/**
 * Created by PhpStorm.
 * User: Amedora
 * Date: 6/17/15
 * Time: 4:58 PM
 */

namespace system\library;
require_once("system/library/model.php");

class Module extends Model {
//database fields in array
    //all field that the class require should
    //specified here
    protected  static $db_fields=array('id','name','description','status','created_at','updated_at');
    protected static $table ="module";


    //all database field should be declared here
    //including other non database fields
    public  $id;
    public  $name;
    public  $description;
    public  $status;
    public  $created_at;
    public  $updated_at;


    protected   function attributes(){
        // get attributes of the class
        $attributes = array();
        foreach(self::$db_fields as $field){
            if(property_exists($this,$field)){
                $attributes[$field] =$this->$field;
            }
        }

        return $attributes;
    }
} 