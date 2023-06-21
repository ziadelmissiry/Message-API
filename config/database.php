<?php
class DB
{

    //  // Here We are declared variables for Db Connection
    private $hostname;
    private $dbname;
    private $username;
    private $password;

    private $conn;

    public function connect()
    {
        $this->hostname ="localhost"; 
        $this->dbname = "zaae10_api_test";
        $this->username = "zaae10_zaae10";
        $this->password = "Mdawood0332@";
 
      $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
      
        if ($this
            ->conn
            ->connect_errno)
        {
            // if it is true then its means there is an error in the connection
            print_r($this
                ->conn
                ->connect_error);
            exit();
        }
        else
        {
            //No error in Connection and return Conn Variable Here
            return $this->conn;
            // print_r($this->conn);
            
        }
    }

    public function add($table, $values)
    {
        $columns = implode(", ", array_keys($values));
        $escaped_values = array_map(function ($value)
        {
            
            if (is_string($value))
            {
                return "'" . $this->connect()
                    ->real_escape_string($value) . "'";
            }
            if (is_null($value))
            {
                return 'null';
            }
            return $value;
        }
        , array_values($values));
        $values = implode(", ", $escaped_values);
        $user_query = "INSERT INTO " . $table . " ($columns) VALUES ($values)";

        $result = $this->connect()
            ->query($user_query);
        return $result;

    }

    
 
    public function get_messages($source, $target)
    {
        $sql = 'SELECT id, sent, source, target, message FROM api WHERE 1=1';
        $params = array();
    
        if (!empty($source)) {
            $sql .= ' AND source = ?';
            $params[] = $source;
        }
    
        if (!empty($target)) {
            $sql .= ' AND target = ?';
            $params[] = $target;
        }
    
        $stmt = $this->connect()->prepare($sql);
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
    
        if ($stmt->execute()) {
            $data = $stmt->get_result();
            return $data->fetch_all(MYSQLI_ASSOC);
        }
    
        return array();
    }
    
    public function check_username($source, $target) {
        $sql = 'SELECT id FROM api WHERE source = ? AND target = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bind_param("ss", $source, $target);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        if ($num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function latest_id() {
        $sql = 'SELECT id FROM api ORDER BY id DESC LIMIT 1';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        if ($num_rows > 0) {
            $stmt->bind_result($id);
            $stmt->fetch();
            $stmt->close();
            return $id;
        } else {
            $stmt->close();
            return false;
        }
    }
}

?>
