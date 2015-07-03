<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 6/5/15
 * Time: 4:14 PM
 */
namespace system\library;
//use system\library\Model;
require_once("system/library/model.php");
class Version extends Model {
    //database fields in array
    //all field that the class require should
    //specified here
    protected  static $db_fields=array('id','release_date','version_name','version_status','comment','created_at','updated_at');
    protected static $table ="version";


    //all database field should be declared here
    //including other non database fields
    public  $id;
    public  $release_date;
    public  $version_name;
    public  $version_status;
    public  $created_at;
    public  $updated_at;
    public  $comment;


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