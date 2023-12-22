<?php 

require_once("../../common.php");
class CreateTx extends Model{

    private $message    = ""; 

    public function __construct(){
        $this->createTxRecord($this->connection());
    }

    public function createTxRecord($con){ 
          $input = file_get_contents('php://input');
          $obj   = json_decode($input, true); 
          $this->message = $this->recordTransaction( $obj ); //record transaction

        // converting the output of the page to json
        header('Content-Type: application/json');

        echo json_encode( ['message' => $this->message]);
    }
    


}

new CreateTx();
?>