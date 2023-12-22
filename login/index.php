<?php 

require_once("../common.php");
class Login extends Model{
    private $message    = "";
    private $userRecord = [];


    public function __construct(){
        $this->user($this->connection());
    }

    public function user($con){ 
          $input = file_get_contents('php://input');
          $obj   = json_decode($input, true); 

          $email_username  = $this->input_value($obj['username']);
          $password        = $this->input_value($obj['password']);

        if(!empty($email_username) && !empty($password)){
           $password = sha1(md5(md5($password)));


            //    creating record in the database
            $sql   = "SELECT * FROM  `users` WHERE `email` = '$email_username' OR `username` = '$email_username'";
            $query = $con->query($sql);

            //    check if the query succeeded
            if($query){
                $row = $query->fetch_assoc();
                // compare the passwords
                if($row['passwd']  === $password){ 
                    $this->message  = "success"; 
                    $this->userRecord = [ 
                                         "userId"=>$row['user_id'],
                                         "email"=> $row['email'], 
                                         "username"=> $row['username'], 
                                         "fulName"=> $row['fname'] , 
                                         "phoneNumber"=> $row['phone'] 
                                        ];
                }else{
                    $this->message = "failed to login:"; 
                }
            }else{
                $this->message = 'All fields are required';
            }
        } 

        // converting the output of the page to json

        header('Content-Type: application/json');

        echo json_encode( ['message' => $this->message, "userRecord" => $this->userRecord]);
    }
    


}

new Login();
?>