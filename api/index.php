<?php
include_once '../classes/message.php';
    $message = new Message();
    $request = $_SERVER["REQUEST_URI"]; 
    $parts = explode('/', $request);
    $word = end($parts);
    $parts = explode('?', $word);
    $action = $parts[0];
switch ($action)
{
    case "store-message":
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check for required parameters
            if (!isset($_POST['source'], $_POST['target'], $_POST['message'])) {
                http_response_code(400); // Bad request
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Missing parameter(s)']);
                exit;
            }

            // Validate parameters
            $sources = trim($_POST['source']);
            $targets = trim($_POST['target']);
            $messages = trim($_POST['message']);
            if (empty($sources) || empty($targets) || empty($messages)) {
                http_response_code(400); // Bad request
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid parameter(s)']);
                exit;
            }
            if (strlen($sources) < 4 || strlen($sources) > 32 || !ctype_alnum($sources)) {
                http_response_code(400); // Bad request
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid source parameter']);
                exit;
            }
            if (!preg_match('/^[a-zA-Z0-9]{4,32}$/', $sources)) {
                http_response_code(400); // Bad request
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid source parameter']);
                exit; 
            }
            if (!preg_match('/^[a-zA-Z0-9]{4,32}$/', $targets)) {
                http_response_code(400); // Bad request
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid source parameter']);
                exit; 
            }
            if (strlen($targets) < 4 || strlen($targets) > 32 || !ctype_alnum($targets)) {
                http_response_code(400); // Bad request
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Invalid target parameter']);
                exit;
            }
        
            $message->source=$sources;
            $message->target=$targets;
            $message->message=$messages;
            $message->storeMessage();
         
        }else {
            http_response_code(405); // Method not allowed
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }
    break;

case "get-messages":
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sources = isset($_GET['source']) ? trim($_GET['source']) : null;
        $targets = isset($_GET['target']) ? trim($_GET['target']) : null;
        if (empty($sources) && empty($targets)) {
            http_response_code(400); // Bad request
            header('Content-Type: application/json');
            echo json_encode(['error' => 'At least one of source or target is required']);
            exit;
        }
        if (!empty($sources) && (strlen($sources) < 4 || strlen($sources) > 32 || !ctype_alnum($sources))) {
            http_response_code(400); // Bad request
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid source parameter']);
            exit;
        }
        if (!empty($targets) && (strlen($targets) < 4 || strlen($targets) > 32 || !ctype_alnum($targets))) {
            http_response_code(400); // Bad request
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid target parameter']);
            exit;
        }
        $message->source=$sources;
        $message->target=$targets;
        $message->getMessages();
     
    }else {
        http_response_code(405); // Method not allowed
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
break;


    default:
        die("Permission denied E-2");
    break;
}

?>
