<?php 

require_once("../../common.php");
class Transactions extends Model{

    private $message = "";
    private $record  = [];

    public function __construct(){
        $this->tx_record( $this->connection() );
    }

    public function tx_record($con){ 
          $input = file_get_contents('php://input');
          $obj   = json_decode($input, true);  

          $user_id = $this->input_value($obj['user_id']);  
          // Get tx records fr this user
            $sql   = "SELECT * FROM `transactions` WHERE `user_id` ='$user_id'";
            $query = $con->query($sql);
        
            //    check if the query succeeded
            if($query){
                $this->message  = "success";
                $this->record   = $query->fetch_assoc();
        
            }else{
                $this->message = "Failed".$con->error;
            }

        // converting the output of the page to json

        header('Content-Type: application/json');

        echo json_encode( ['message' => $this->message, "record"=>$this->record ]);
    }
    


}

new Transactions();
?>