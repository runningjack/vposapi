<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 6/5/15
 * Time: 4:13 PM
 */
namespace system\library;
//use system\library\Model;
require_once("system/library/model.php");
class Changelog extends Model {
    protected  static $db_fields=array('id','log_type','ticket_number','version_id','version_name','description','note','status','employee_id','employee','module_id','module','created_at','updated_at');
    protected static $table ="changelog";



    public  $id;
    public  $log_type;
    public  $ticket_number;
    public  $version_id;
    public  $version_name;
    public  $description;
    public  $note;
    public  $status;
    public  $employee_id;
    public  $employee;
    public  $module_id;
    public  $module;
    public  $created_at;
    public  $updated_at;


    protected   function attributes(){

        $attributes = array();
        foreach(self::$db_fields as $field){
            if(property_exists($this,$field)){
                $attributes[$field] =$this->$field;
            }
        }

        return $attributes;
    }
} 