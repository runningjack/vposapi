<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 6/15/15
 * Time: 1:32 PM
 */

namespace system\library;

require_once("system/library/model.php");
class Logstatus extends Model {
    protected  static $db_fields=array('id','name','description','status','created_at','updated_at');
    protected static $table ="logstatus";

    public  $id;
    public  $name;
    public  $description;
    public  $status;
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