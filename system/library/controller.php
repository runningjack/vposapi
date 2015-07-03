<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 6/5/15
 * Time: 4:09 PM
 */

namespace system\library;
class Controller{
    protected $registry;
    public function __construct(){
        $this->view 	= new View();
        $this->uri 		= new Url("");

    }
}
?>