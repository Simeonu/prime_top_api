<?php 

require_once("../common.php");
class FundWallet extends Model{

    private $message    = "";
    private $currentBal = 0;

    public function __construct(){
        $this->user($this->connection());
    }

    public function user($con){ 
          $input = file_get_contents('php://input');
          $obj   = json_decode($input, true); 

          $userId     = $this->input_value($obj['user_id']);
          $amount     = $this->input_value($obj['amount']); 
     

        //creating record in the database
        $sql   = "UPDATE  `wallet`  SET `balance` = `balance` + '$amount', `updatedAt`=NOW() WHERE `user_id` = '$userId'" ;
        $query = $con->query($sql);

        //check if the query succeeded
        if($query){
            $this->message  = "success";  
            $this->currentBal = $this->getUserBalance($userId);
        }else{
            $this->message = "Failed".$con->error;
        }

        // converting the output of the page to json

        header('Content-Type: application/json');

        echo json_encode( ['message' => $this->message, "cuurentBal"=>$this->currentBal ]);
    }
    


}

new FundWallet();
?>