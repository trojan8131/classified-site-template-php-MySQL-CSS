<?php

class Data
{
    protected $db, $table, $names, $key, $autoincrement;

    public function __construct(&$db, $table, $names, $key = 'id', $autoincrement = true)
    {
        $this->db = $db;
        $this->table = $table;
        $this->names = $names;
        $this->key = $key;
        $this->autoincrement = $autoincrement;
    }

    //INSERT INTO `users` (`userid`, `username`, `useremail`, `userpassword`, `userlevel`) VALUES ('admin', 'admin', 'administrator@aps-edu.pl', '21232f297a57a5a743894a0e4a801fc3', '10');
    //INSERT INTO 'users' ( 'userid','username','useremail','userpassword','userlevel') VALUES ( 'admin', 'admin', 'administrator@aps-edu.pl', '21232f297a57a5a743894a0e4a801fc3', '10' );

    //Naprawione
    protected function query_insert($data)
    {
        $query = "INSERT INTO `" . $this->table . "` (";
        foreach ($this->names as $v) {
            if ($this->autoincrement and ($this->key == $v)) continue;
            $query .= "`$v`,";
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ") VALUES (";
        foreach ($this->names as $v) {
            if ($this->autoincrement and ($this->key == $v)) continue;
            $query .= " '$data[$v]', ";
        }
        $query = substr($query, 0, strlen($query) - 2);
        $query .= " );";
     
        return $query;
    }

    //Testowa funkcja
    public function insert_test($data)
    {
        $query = $this->query_insert($data);
        return $query;
    }

    //Naprawione
    public function insert($data)
    {
        $query = $this->query_insert($data);
        $r = $query;
        $r = $this->db->query($query);

        return $query;
    }
    //Naprawione
    public function getAll($val = false, $key = false, $order = "", $prin = false)
    {
        if (!$key) $key = $this->key;
        if ($val) $query = "SELECT * FROM " . $this->table . " WHERE  $key='$val'" . (($order) ? " ORDER BY $order " : "");
        else $query = "SELECT * FROM " . $this->table . (($order) ? " ORDER BY $order " : "");
        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        $result = array();

        while ($data = $r->fetch_assoc()) {
            $result[$data[$this->key]] = $data;
        }
        if ($prin) {
      
        }
        return $result;
    }
    

    public function getAll_no_order()
    {
        $query = "SELECT * FROM " . $this->table;
        $r = $this->db->query($query);
        $result = array();
        while ($data = $r->fetch_assoc()) {
        }

        return $result;
    }
    //Naprawione
    public function getNames()
    {
        return $this->names;
    }




    protected function query_update($data)
    {
        $key = $data[$this->key];
        $query = "UPDATE " . $this->table . " SET  ";
        foreach ($data as $v => $k) {
            $query .= " $v='$k' , ";
        }

        $query = substr($query, 0, strlen($query) - 2);

        $query .= " WHERE $this->key = '$key'";

        return $query;
    }
    //Naprawione

    public function update($data)
    {
        $query = $this->query_update($data);
        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        return $r;
    }
    public function update_refresh($what, $val, $key, $var_key)
    {
        $query = "UPDATE " . $this->table . " SET  " . $what . "='" . $val . "' WHERE " . $key . "='" . $var_key . "';";

        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        return $query;
    }
    //Naprawione
    public function delete($id, $key = false)
    {

        if (!$key) $key = $this->key;
        $query = "DELETE FROM " . $this->table . " WHERE $key='$id'";
        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        return $r;
    }

    //funkcja łapie lementy prawidłowo
    public function get($val, $key = false)
    {

        if (!$key) $key = $this->key;
        $query = "SELECT * FROM " . $this->table . " WHERE  $key='$val' LIMIT 1";
        $r = $this->db->query($query);
        $result = $r->fetch_assoc();


        return $result;
    }
    public function getLastItem2($key = "date")
    {
        if (!$key) $key = $this->key;
        $query = "SELECT * FROM " . $this->table . " ORDER BY " . $key . " DESC LIMIT 1";
        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }

        $result = $r->fetch_assoc();

        return $result;
    }
    public function getLastItem($key = "date")
    {
        if (!$key) $key = $this->key;
        $query = "SELECT * FROM " . $this->table . " ORDER BY " . $key . " DESC LIMIT 1";
        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }

        $result = $r->fetch_assoc();

        return $result;
    }
    protected function query_search2($var, $main = false, $category = false)
    {
        if ($main == false and $category == false) {

            $keywords = explode(' ', $var);
            $display_words = "";
            $query = "SELECT * FROM " . $this->table;
            $query .= " WHERE ";
            foreach ($keywords as $word) {
                $query .= "cla_title LIKE '%" . $word . "%' OR ";
                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= " UNION ";
            $query .= "SELECT * FROM " . $this->table;
            $query .= " WHERE ";
            foreach ($keywords as $word) {
                $query .= "cla_summary LIKE '%" . $word . "%' OR ";
                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= " UNION ";
            $query .= "SELECT * FROM " . $this->table;
            $query .= " WHERE ";
            foreach ($keywords as $word) {
                $query .= "cla_text LIKE '%" . $word . "%' OR ";
                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $display_words = substr($display_words, 0, strlen($display_words) - 1);
        } else if ($category == false) {
            $keywords = explode(' ', $var);
            $display_words = "";
            $query = "SELECT * FROM " . $this->table;
            $query .= " WHERE cla_main_category=" . $main . " and (";

            foreach ($keywords as $word) {
                $query .= "cla_title LIKE '%" . $word . "%' OR ";

                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= ") UNION ";
            $query .= "SELECT * FROM " . $this->table;
            $query .= " WHERE cla_main_category=" . $main . " and (";
            foreach ($keywords as $word) {
                $query .= "cla_summary LIKE '%" . $word . "%' OR ";
                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= ") UNION ";
            $query .= "SELECT * FROM " . $this->table;
            $query .= " WHERE cla_main_category=" . $main . " and (";
            foreach ($keywords as $word) {
                $query .= "cla_text LIKE '%" . $word . "%' OR ";
                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= ")";
            $display_words = substr($display_words, 0, strlen($display_words) - 1);
        } else {
            $keywords = explode(' ', $var);
            $display_words = "";
            $query = "SELECT * FROM " . $this->table;
            $query .= " WHERE cla_category=" . $category . " and (";

            foreach ($keywords as $word) {
                $query .= "cla_title LIKE '%" . $word . "%' OR ";

                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= ") UNION ";
            $query .= "SELECT * FROM " . $this->table;
            $query .= " WHERE cla_category=" . $category . " and (";
            foreach ($keywords as $word) {
                $query .= "cla_summary LIKE '%" . $word . "%' OR ";
                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= ") UNION ";
            $query .= "SELECT * FROM " . $this->table;
            $query .= " WHERE cla_category=" . $category . " and (";
            foreach ($keywords as $word) {
                $query .= "cla_text LIKE '%" . $word . "%' OR ";
                $display_words .= $word . ' ';
            }
            $query = substr($query, 0, strlen($query) - 4);
            $query .= ")";
            $display_words = substr($display_words, 0, strlen($display_words) - 1);
        }





        return $query;
    }

    public function search2($body, $main, $category)
    {
        if ($main != "" and $category != "") {
            $query = $this->query_search2($body, $main, $category);

        } else if ($main != "") {
            $query = $this->query_search2($body, $main);

        } else {

            $query = $this->query_search2($body);

        }
        try {


            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        return $r;
    }

    protected function query_search($var, $key)
    {
        $query = "SELECT * FROM " . $this->table;
        $query .= " WHERE " . $key . " LIKE '%" . $var . "%'";

        return $query;
    }

    public function search($var, $key)
    {
        $query = $this->query_search($var, $key);
        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        return $r;
    }

    public function max()
    {
        $r = $this->db->query("select max(cla_id) as max from classifieds;");

        return $r->fetch_assoc();
    }
    public function max2()
    {
        $r = $this->db->query("select max(slot_id) as max from cla_images;");

        return $r->fetch_assoc();
    }


    protected function query_search_events($data, $start_search, $end_search)
    {
        $query = "SELECT * FROM " . $this->table;

        $i = 0;
        foreach ($data as $v => $k) {
            if ($i == 0) $query .= " WHERE ";
            $i++;
            if ($k == NULL) continue;
            $query .= $v . " LIKE '%" . $k . "%' AND ";
        }
        if ($start_search != NULL and $end_search != NULL)
            $query .= " date >= '$start_search' AND date <= '$end_search'";
        $query .= " ORDER BY date desc;";

        return $query;
    }

    public function search_events($data, $start_search, $end_search)
    {
        $query = $this->query_search_events($data, $start_search, $end_search);
        try {
            $r = $this->db->query($query);
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        return $r;
    }



    public function query_search_classified($data, $order)
    {
        $query = "SELECT * FROM " . $this->table;

        $i = 0;
        if($data["cla_text"]=="" and   $data["main_category"]=="" and   $data["cla_category"]=="" and   $data["cla_main_category"]=="" and   $data["region"]=="")
        {
            $query .= " ORDER BY ".$order.";";

            return $query;
        }
        $var=$data;
        foreach ($var as $c => $d) {
            if ($i == 0) $query .= " WHERE ";
            $i++;

            if($c=="cla_text" and $d=!"")
            {
                $keywords = explode(' ', $data["cla_text"]);
                foreach($keywords as $v=>$k)
                {
                $query .= "( cla_title LIKE '%".$k."%' or cla_summary LIKE '%".$k."%' or cla_text LIKE '%".$k."%' ) OR ";
                }
                $query = substr($query, 0, strlen($query) - 3);
                continue;
            }
            if ($d == NULL) continue;
            if($c=="region")
            {
                $query .= " id_city in (select id from city where region_id=".$d.") AND ";
                continue;
            }
            $query .= $c . " ='".$d."' AND ";
        }
        $query .= " ORDER BY ".$order.";";

        return $query;
    }

    public function search_classified($data, $order)
    {
        $query = $this->query_search_classified($data, $order);
        try {
            //$_SESSION["error"] = $query;
            $r = $this->db->query($query);
            //$r=$query;
        } catch (PDOException $e) {
            echo $e->getMessage() . ": " . $e->getCode() . "<br />\nQuery: $query";
            exit;
        }
        return $r;
    }
}
