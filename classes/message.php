<?php
include_once '../config/database.php';
class Message extends DB
{
//    abc
    public $source;
    public $target;
    public $message;
    public $id;
    public function storeMessage()
    {
        $message["source"] = $this->source;
        $message["target"] = $this->target;
        $message["message"] = $this->message;
        $message["sent"] = date('Y-m-d H:i:s');
        $obj=$this->check_username($message["source"],$message["target"]);
        if($obj){
            http_response_code(201);
            echo json_encode(array(
                "status" => true,
                "message" => "username already exist",
            ));
        }else{
            $result = $this->add("api",$message);
            $id=$this->latest_id();
            if ($result)
            {
                http_response_code(201);
                echo json_encode(array(
                    "status" => true,
                    "message" => "Message stored successfully",
                     "id"=>$id
                ));
            }
            else
            {
                 http_response_code(500);
                 echo json_encode(array(
                    "status" => false,
                    "message" => "Failed to insert message"
                ));

            }
        }
      


    }
 
    public function getMessages()
    {
           $source=$this->source;
           $target=$this->target;
            $result = $this->get_messages($source,$target);
            $object = json_decode(json_encode($result), FALSE);
            if($object)
            {
             
             http_response_code(200);
             echo json_encode(array(
             "status" => true,
             "Data"=> $object
                 ));

            }
            else
            {
                http_response_code(404);
                echo json_encode(array(
                    "status" => false,
                    "message" => "Data Not Found"
                ));
            }
    
  
}  
  
}
?>

