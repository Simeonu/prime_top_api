<?php 
require_once("../common.php");
class GetBalance extends Model{

    private $message = "";
    private $currentBal = 0;

    public function __construct(){
        $this->airtime_topup();
    }

    public function airtime_topup(){
          $con   = $this->connection(); 
          $input = file_get_contents('php://input');
          $obj   = json_decode($input, true);  

           $userId            = $this->input_value($obj['user_id']); 
           $this->currentBal = $this->getUserBalance($userId) ;
           
        // converting the output of the page to json
        header('Content-Type: application/json');
        echo json_encode( ["cuurentBal"=>$this->currentBal ]);
    }
    


}

new GetBalance();
?>