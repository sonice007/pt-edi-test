<?php
//MyReport.php
require APPPATH . "/libraries/koolreport/core/autoload.php";
class MyReport extends \koolreport\KoolReport
{
    use \koolreport\clients\Bootstrap;
    use \koolreport\codeigniter\Friendship;// All you need to do is to claim this friendship
    // use \koolreport\export\Exportable;
    
    public function settings()
    {
        return array(
            "assets" => array(
                "path" => "../../assets",
                "url" => "assets",
            ),
            "dataSources" => array(
                "community_management_system" => array(
                    "connectionString" => "mysql:host=localhost;dbname=community_management_system",
                    "username" => "root",
                    "password" => "",
                    "charset" => "utf8",
                ),
            ),
        );
    }

    public function setup()
    {
        $status = null;
        if(isset($_POST['filter_status'])){
            $status = $_POST['filter_status'];
            if($status != null){
                $where = " where status = ".$status;
            }else{
                $where = "";
            }
        }else{
            $where = "";            
        }

        if(isset($_POST['filter_id'])){
            $id = $_POST['filter_id'];
            if($id != null){
                $where .= " and id = ".$id;
            }
        }
        $this->src('community_management_system')
            ->query("Select * from partner ".$where)
            ->pipe($this->dataStore("partner"));
    }
}