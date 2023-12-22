<?php 
require_once("../common.php");
class Airtime extends Model{

    private $message = "";
    private $currentBal = 0;

    public function __construct(){
        $this->airtime_topup();
    }

    public function airtime_topup($con){
          $con   = $this->connection(); 
          $input = file_get_contents('php://input');
          $obj   = json_decode($input, true);  

          $userId     = $this->input_value($obj['user_id']);
          $amount     = $this->input_value($obj['amount']); 

          // 0. Check if the user balance is higher than the requested amount
          // 1. send the airtime to the user phone number
          // 2. Debit the user if the phone recharge waas suuceesful
          // 3. Record the transaction details and send the transaction details to the user email

          // Check if the user balance is higher than the requested amount
           if($this->getUserBalance($userId) >= $amount ){
               // then Debit the user
               $userDebitResponse = $this->debitUser($userId, $amount);

               if(  $userDebitResponse == true){
                 // send the airtime to the user phone number
                 // record the transaction
                $tx_response =  $this->recordTransaction( $obj ); 

                $this->message =  $tx_response;

               }else if(  $userDebitResponse == false){
                   $this->message = 'Failed'; 
               }else{
                  $this->message = 'Insufficient Balance'; 
               } 

           }else {
             $this->message = 'Insufficient Balance'; 
           }

           $this->currentBal = $this->getUserBalance($userId) ;
          
            
           
        // converting the output of the page to json
        header('Content-Type: application/json');
        echo json_encode( ['message' => $this->message, "cuurentBal"=>$this->currentBal ]);
    }
    


}

new Airtime();
?>