<?php 

require_once("../common.php");
class User extends Model{

    private $message = "";

    public function __construct(){
        $this->user($this->connection());
    }

    public function user($con){ 
          $input = file_get_contents('php://input');
          $obj   = json_decode($input, true);  

          $Fullname     = $this->input_value($obj['fullname']); 
          $Username     = $this->input_value($obj['username']); 
          $email        = $this->input_value($obj['email']); 
          $phoneNumber  = $this->input_value($obj['phoneNumber']); 
          $password     = $this->input_value($obj['password']); 
     

      
          if(!empty($Fullname) && !empty($Username) && !empty($email) &&!empty($phoneNumber) && !empty($password) ){
            $password = sha1(md5(md5($password)));
 
             //creating user record in the database
             $sql   = "INSERT INTO `users`(`fname`, `username`, `email`, `phone`, `passwd`, `createdAt`) VALUES ('$Fullname', '$Username', '$email', '$phoneNumber', '$password' , NOW() )" ;
             $query = $con->query($sql);
                 //check if the query succeeded
                if($query){
                    $this->message  = "success";
                    $userId = $this->getUserByUserName($Username)['user_id'];  // get the user ID from the users table 
                    $this->createUserWaletRecord( $userId );    // create the user wallet record 
            
                }else{
                    $this->message = "Failed";
                }

          }else{
            $this->message = "All fields are required";
          }



        // converting the output of the page to json

        header('Content-Type: application/json');

        echo json_encode( ['message' => $this->message ]);
    }
    


}

new User();
?>