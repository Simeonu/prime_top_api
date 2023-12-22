<?php 

class Model{

     public function connection(){
        return new Mysqli("localhost", "root", "", "primetop");
      //   return new Mysqli("localhost", "primeadd_primeadds", "Simeon&13890", "primeadd_primeadds");
     }

     public function input_value($data){
      //   $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
     }


     // this function returns the user details using the username
     public function getUserByUserName($userName){
         $con = $this->connection();
         $q   = $con->query("SELECT * FROM `users` WHERE `username` = '$userName'");
         return $q->fetch_assoc();
     }

     public function getUserById($userId){
         $con = $this->connection();
         $q   = $con->query("SELECT * FROM `users` WHERE `user_id` = '$userId'");
         return $q->fetch_assoc();
     }



     // Get the balance of the user from the users wallet
     public function getUserBalance($userId){
          $con = $this->connection();
          $currBal = $con->query("SELECT * FROM `wallet` WHERE `user_id` = '$userId'")->fetch_assoc()['balance'];
          return floatval( $currBal );
     }



     public function debitUser($userId, $amount){
              //creating record in the database
              $userCurrentBal = $this->getUserBalance($userId);  // get user currentbalance
              // check if the user balance is up to the amount to tbe debited
              if( $amount <= $userCurrentBal){
                $con   = $this->connection();
                $sql   = "UPDATE  `wallet`  SET `balance` = `balance` - '$amount', `updatedAt`=NOW() WHERE `user_id` = '$userId'" ;
                $query = $con->query($sql); 
                if($query){
                    return true;
                }else{
                   return false;
                }
              }else{
                return 'Insufficient Balance'; 
              }
             
     }



     // This function tries to create a wallet record for the user
     public function createUserWaletRecord( $userId ){     
         $con   = $this->connection(); 
         //creating record in the database
         $sql   = "INSERT INTO `wallet`(`user_id`, `balance`, `updatedAt`) VALUES ('$userId', '0' , NOW() )" ;
         $query = $con->query($sql); 
        //check if the query succeeded
         return $query;
    }



// this function records each transaction to the database for a given user
public function recordTransaction( $obj ){  
      $con       = $this->connection();
      $user_id   = $this->input_value($obj['user_id']);
      $tx_type   = $this->input_value($obj['tx_type']);
      $amount    = $this->input_value($obj['amount']); 
      $tx_ref    = $this->input_value($obj['tx_ref']); 
      $tx_status = $this->input_value($obj['status']);   
 
      if(!empty($user_id) && !empty($tx_type) && !empty($amount) &&!empty($tx_ref) && !empty($tx_status) ){
         // creating record in the database
         $sql   = "INSERT INTO `transactions`(`user_id`, `tx_type`, `amount`, `tx_ref`, `tx_status`, `createdAt`) VALUES ('$user_id','$tx_type','$amount','$tx_ref','$tx_status' , NOW() )" ;
         $query = $con->query($sql);
          //   check if the query succeeded
          if($query){
              return "success"; 
          }else{
            return "Failed";
          }
      }else{
        return "Required";
      }

}




}


// work around base class 
class Common extends Model{
   
}

$common = new Common();

?>