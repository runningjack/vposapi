<?php
/**
 * Created by PhpStorm.
 * User: Ahmed
 * Date: 6/8/15
 * Time: 9:52 PM
 */

require_once("system/library/view.php");
require_once("system/library/employee.php");
require_once("system/library/version.php");
require_once("system/library/changelog.php");
require_once("system/library/logstatus.php");
require_once("system/library/module.php");

class HomeController  {

    public function __construct(){
        $this->view 	= new \system\library\View();
    }
    public function getIndex(){
        $this->view->render("home",false);
    }
    public function getEmployeeIndex(){
        $this->view->title = "Employee Listing";
        $this->view->employee = system\library\Employee::all();
        $this->view->render("employee/index");
    }
    public function getStatusList(){
        $this->view->title  =   "Log Status";
        $this->view->logstatus = system\library\Logstatus::all();
        $this->view->render("logstatus/index");
    }
    public function getModuleList(){
        $this->view->title  =   "Module Listing";
        $this->view->modules = system\library\Module::all();
        $this->view->render("module/index");
    }
    public function getAddLog(){
        $this->view->title = "Add New Log";
        $this->view->employee = system\library\Employee::all();
        $this->view->versions = system\library\Version::all();
        $this->view->modules = system\library\Module::all();
        $this->view->render("changelog/add",false);
    }
    public function getAddStatus(){
        $this->view->title = "Add New Status";
        $this->view->render("logstatus/add",false);
    }
    public function getAddModule(){
        $this->view->title = "Add New Module";
        $this->view->render("module/add",false);
    }
    public function postAddLog(){


        if(isset($_POST['changelog']) && $_POST['changelog']=="changelog"){

            $logs = system\library\Changelog::find_by_sql("SELECT * FROM changelog GROUP BY version_name ORDER BY version_name DESC");

            if($logs){
              // print_r($logs);

                foreach($logs as $log){
                    $versionlogs = system\library\Changelog::find_by_sql("SELECT * FROM changelog WHERE version_name ='".$log->version_name."' ORDER BY id ASC");
                    $logtype ="";
                    foreach($versionlogs as $versionlog){
                        if($versionlog->version_name === $log->version_name ){
                            if($versionlog->log_type == 1){
                                $logtype = "Bug Fix";
                            }elseif($versionlog->log_type == 2){
                                $logtype ="Change";
                            }elseif($versionlog->log_type == 3){
                                $logtype = "Feature";
                            }else{}

                            $version_details [] = array(
                                "description"=> $versionlog->description,
                                "logtype"=>$logtype,
                                "module"=>""
                            );
                        }


                    }

                    $log_data[] = array(
                        "version_name" =>$log->version_name,
                        "log_details"=>$version_details
                    ) ;

                    echo json_encode($log_data);
                }
            }else{
                return false;
            }

            exit;
        }




        /*check if request is an ajax request
          this is required to update/delete Log record
          via ajax
         */
       // if (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'xmlhttprequest') {
            if(isset($_POST['action']) && $_POST['action'] == "delete"){
                $log = system\library\Changelog::find($_POST['id']);
                if($log->delete()){
                    echo "Record Deleted";
                }else{
                    echo "Record could not be deleted";
                }
                exit;
            }


        /*section reserved for ajax post on update*/
            if(isset($_POST['logid'])){


                if(isset($_POST['emmployee_id']) && empty($_POST['employee_id'])){
                    echo "Please select employee ";

                    exit;
                }

                if(isset($_POST['log_type']) && empty($_POST['log_type'])){
                    echo "Please select change log type";

                    exit;
                }

                if(isset($_POST['description']) && empty($_POST['description'])){
                    echo "Please enter description for change log";

                    exit;
                }

                if(isset($_POST['version_id']) && empty($_POST['version_id'])){
                    echo "Please enter version for change log";

                    exit;
                }

                if(isset($_POST['version_id']) && !empty($_POST['version_id'])){
                    $version                =   system\library\Version::find((int)$_POST['version_id']);
                    $employee               =   system\library\Employee::find((int)$_POST['employee_id']);
                    $module                 =   system\library\Module::find((int)$_POST['module_id']);

                    $log                    =   new system\library\Changelog();
                    $log->id                =   $_POST['logid'];
                    $log->employee_id       =   $_POST['employee_id'];
                    $log->log_type          =   $_POST['log_type'];
                    $log->ticket_number     =   $_POST['ticket_number'];
                    $log->version_id        =   $_POST['version_id'];
                    $log->note              =   $_POST['note'];
                    $log->description       =   $_POST['description'];
                    $log->version_name      =   isset($version->version_name) ? $version->version_name : "";
                    $log->status            =   $_POST['status'];
                    $log->employee          =   isset($employee->name) ? $employee->name : "";
                    $log->module_id         =   $_POST['module_id'];
                    $log->module            =   $module->name;
                    $log->created_at        =    date("Y-m-d H:i:s");
                    $log->updated_at        =   date("Y-m-d H:i:s");

                    try{
                        if($log->update()){

                            echo "Record Updated";

                        }else{
                            echo "Unexpected Error! Record Not be Updated";

                        }
                    }catch (\PDOException $e){
                        echo $e->getMessage();
                    }catch(Exception $e){
                        echo $e->getMessage();
                    }
                }
                exit;
            }

        /*end of ajax post secton*/


        $this->view->title = "Add New Log";
        if(isset($_POST['emmployee_id']) && empty($_POST['employee_id'])){
            $this->view->error_message = "Please select employee ";
            $this->view->render("changelog/add",false);
            exit;
        }

        if(isset($_POST['log_type']) && empty($_POST['log_type'])){
            $this->view->error_message = "Please select change log type";
            $this->view->render("changelog/add",false);
            exit;
        }

        if(isset($_POST['description']) && empty($_POST['description'])){
            $this->view->error_message = "Please enter description for change log";
            $this->view->render("changelog/add",false);
            exit;
        }

        if(isset($_POST['version_id']) && empty($_POST['version_id'])){
            $this->view->error_message = "Please enter version for change log";
            $this->view->render("changelog/add",false);
            exit;
        }

       if(isset($_POST['version_id']) && !empty($_POST['version_id'])){

            $version                =   system\library\Version::find((int)$_POST['version_id']);
            $employee               =   system\library\Employee::find((int)$_POST['employee_id']);
           $module                 =   system\library\Module::find((int)$_POST['module_id']);
            $log                    =   new system\library\Changelog();
            $log->employee_id       =   $_POST['employee_id'];
            $log->log_type          =   $_POST['log_type'];
            $log->ticket_number     =   $_POST['ticket_number'];
            $log->version_id        =   $_POST['version_id'];
            $log->note              =   $_POST['note'];
            $log->description       =   $_POST['description'];
            $log->version_name      =   isset($version->version_name) ? $version->version_name : "";
            $log->status            =   $_POST['status'];
           $log->module_id         =   $_POST['module_id'];
           $log->module         =   $module->name;
            $log->employee          =   isset($employee->name) ? $employee->name : "";
            $log->created_at        =   date("Y-m-d H:i:s");
            $log->updated_at        =   date("Y-m-d H:i:s");

            try{
                if($log->create()){

                    $this->view->success_message = "Record Created";

                }else{
                    $this->view->error_message = "Unexpected Error! Record Not Created";

                }
            }catch (\PDOException $e){
                $this->view->error_message = $e->getMessage();
            }catch(Exception $e){
                $this->view->error_message = $e->getMessage();
            }
       }else{
           $this->view->error_message = "Please fill in the name field";
       }
           $this->view->modules     = system\library\Module::all();
           $this->view->employee    = system\library\Employee::all();
           $this->view->versions    = system\library\Version::all();
           $this->view->render("changelog/add",false);
    }

    public function getLogList(){
        $this->view->title = "Log Listing";
        $this->view->employee = system\library\Employee::all();
        $this->view->versions = system\library\Version::all();
        $this->view->loglists = system\library\Changelog::all();
        $this->view->modules = system\library\Module::all();
        $this->view->render("changelog/index",false);
    }

    public function getAddEmployee(){
        $this->view->title = "Add New Employee";
        $this->view->render("employee/add",false);
    }

    /*Gets the view for version
     * */

    public function getVersionList(){
        $this->view->title = "Version Listing";
        $this->view->versions = system\library\Version::all();
        $this->view->render("version/index",false);
    }


    public function getAddVersion(){
        $this->view->title = "Add New Version";
        $this->view->render("version/add",false);
    }

    public function postAddVersion(){

        if(isset($_POST['action']) && $_POST['action'] == "delete"){
            $log = system\library\Version::find_by_sql("SELECT * FROM changelog WHERE version_id =". (int)$_POST['id'] );
            if(($log) && count($log)>0){
                if($log->delete()){
                    echo "Record Deleted";
                }else{
                    echo "Record could not be deleted";
                }
            }else{
                echo "Record could not be deleted; This version is associated with a post";
            }

            exit;
        }



        if( isset($_POST['action']) && $_POST['action'] == 'update'){

            if($_POST['version_name'] == ""){
                echo "Console Will Quick! Operation cannot be committed";
                exit;
            }

            $version = system\library\Version::find((int)$_POST['id']);
            $version->version_name = $_POST['version_name'];
            $version->version_status = $_POST['version_status'];
            $version->release_date = $_POST['release_date'];
            $version->comment = $_POST['comment'];
            $version->updated_at = date("Y-m-d H:i:s");
            if($version->update()){
                echo "Record Updated";
            }else{
                echo "Unexpected Error! Record could not be updated";
            }

            exit;
        }

        $this->view->title = "Add New Version";
        if(isset($_POST['version_status']) && empty($_POST['version_status'])){
            $this->view->error_message = "Please select a version status ";
            $this->view->render("version/add",false);
            exit;
        }

        if(isset($_POST['release_date']) && empty($_POST['release_date'])){
            $this->view->error_message = "Please fill in the release date field";
            $this->view->render("version/add",false);
            exit;
        }

        if(isset($_POST['version_name']) && !empty($_POST['version_name'])){
            $version = new system\library\Version();
            $version->version_name = $_POST['version_name'];
            $version->version_status = $_POST['version_status'];
            $version->release_date = $_POST['release_date'];
            $version->comment = $_POST['comment'];
            $version->created_at = date("Y-m-d");
            $version->updated_at = date("Y-m-d");
            try{
                if($version->create()){

                    $this->view->success_message = "Record Created";

                }else{
                    $this->view->error_message = "Record Not Created";

                }
            }catch (\PDOException $e){
                $this->view->error_message = $e->getMessage();
            }catch(\Exception $e){
                $this->view->error_message = $e->getMessage();
            }
        }else{
            $this->view->error_message = "Please fill in the name field";
        }

        $this->view->render("version/add",false);

    }


    /*post method to create a new employee
     * */

    /*Todo Get validation object on each model*/

    public function postAddEmployee(){
        $this->view->title = "Add New Employee";
        if(isset($_POST['name']) && !empty($_POST['name'])) {
        $employee = new \system\library\Employee();
        $employee->name     =   $_POST['name'];
        $employee->phone     =   !empty($_POST['phone']) ?$_POST['phone'] :"";
        $employee->email     =   !empty($_POST['email']) ?$_POST['email'] :"";
        $employee->username     =   "";
        $employee->password     =   "";
        $employee->created_at     =   date("Y-m-d H:i:s");
        $employee->updated_at     =   date("Y-m-d H:i:s");

            try {
                if($employee->create()){

                    $this->view->success_message = "Record Created";

                }else{
                    $this->view->error_message = "Record Not Created";

                }
            }catch (\PDOException $e){
                $this->view->error_message = $e->getMessage();
            }catch(\Exception $e){
                $this->view->error_message = $e->getMessage();
            }
        }else{
            $this->view->error_message = "Please fill in the name field";
        }

        $this->view->render("employee/add",false);

    }


    public function postAddModule(){
        $this->view->title = "Add New Module";
        if(isset($_POST['name']) && !empty($_POST['name'])) {
            $module                 = new \system\library\Module();
            $module->name           = $_POST['name'];
            $module->description    = $_POST['description'];
            $module->status         =   0;
            $module->created_at     =   date("Y-m-d H:i:s");
            $module->updated_at     =   date("Y-m-d H:i:s");

            try {
                if($module->create()){
                    $this->view->success_message = "Record Created";
                }else{
                    $this->view->error_message = "Record Not Created";
                }
            }catch (\PDOException $e){
                $this->view->error_message = $e->getMessage();
            }catch(\Exception $e){
                $this->view->error_message = $e->getMessage();
            }
        }else{
            $this->view->error_message = "Please fill in the name field";
        }

        $this->view->render("module/add",false);
    }


    public function postAddStatus(){
        $this->view->title = "Add New Status";
        if(isset($_POST['name']) && !empty($_POST['name'])) {
            $module                 = new \system\library\Logstatus();
            $module->name           = $_POST['name'];
            $module->description    = $_POST['description'];
            $module->status         =   0;
            $module->created_at     =   date("Y-m-d H:i:s");
            $module->updated_at     =   date("Y-m-d H:i:s");

            try {
                if($module->create()){
                    $this->view->success_message = "Record Created";
                }else{
                    $this->view->error_message = "Record Not Created";
                }
            }catch (\PDOException $e){
                $this->view->error_message = $e->getMessage();
            }catch(\Exception $e){
                $this->view->error_message = $e->getMessage();
            }
        }else{
            $this->view->error_message = "Please fill in the name field";
        }

        $this->view->render("logstatus/add",false);
    }


} 