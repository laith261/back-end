<?php
class quickQueries
{
    public $con;

    public function __construct($server, $user, $pass, $db)
    {
        global $con;
        $this->$con = mysqli_connect($server, $user, $pass, $db);
        if ($this->$con) {
            mysqli_set_charset($this->$con, "utf8");
            mysqli_options($this->$con, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, true);
            return $this->$con;
        } else {
            echo  mysqli_connect_error();
        }
    }

    // make the quiery
    public function query($stmt)
    {
        global $con;
        $query = mysqli_query($this->$con, $stmt);
        if ($query == true) {
            return $query;
        } else {
            return mysqli_error($this->con);
        }
    }

    // select data from DB
    public function select($table, $where = array(), $data = null)
    {
        $data = $data == null ? "*" : implode(",", $data);
        $where = count($where) == 0 ? "" : " where " . $this->where($where);
        $query = "select $data from `$table` " . $where;
        return $this->query($query);
    }

    // multi quiery
    public function multi_quiry($stmt)
    {
        global $con;
        $query = mysqli_multi_query($this->$con, $stmt);
        return $query == true ? $query : mysqli_error($this->$con);
    }

    // insert data in DB
    public function insert($table, $data = array())
    {
        global $con;
        $keys = "(`" . implode("`,`", array_keys($data)) . "`)";
        $values = "(" . implode(",", $this->statement(array_values($data))) . ")";
        $stmt = "INSERT INTO `$table` $keys values $values";
        $query = $this->query($stmt);
        return $query;
    }

    // update data in DB
    public function update($table, $data = array(), $where = array())
    {
        $update = '';
        $tmp_data = $this->statement($data);
        foreach ($tmp_data as $key => $value) {
            $update .= ",`$key`=$value";
        }
        $update = substr($update, 1);
        $query = "UPDATE `$table` SET $update where " . $this->where($where);
        return $this->query($query);
    }

    // delete data from DB
    public function delete($table, $where = array())
    {
        $stmt = "DELETE FROM `$table` WHERE " . $this->where($where);
        return $this->query($stmt);
    }

    // rows count
    public function nums($query)
    {
        $check = gettype($query) == "string" ? $this->query($query) : $query;
        if ($check == true) {
            return mysqli_num_rows($check);
        } else {
            return $check;
        }
    }

    // make where clusen
    private function where($where = array())
    {
        $where = $this->statement($where);
        $data = "";
        $keys = array_keys($where)[count($where) - 1];
        foreach ($where as $key => $value) {
            if (gettype($value) == 'array') {
                $data .= "$key in (" . implode(",", $value) . ")";
            } else {
                $data .= "$key=" . $value;
            }
            if ($key != $keys) {
                $data .= " and ";
            }
        }
        return $data;
    }


    // fetch data 
    public function fetch($query)
    {
        return mysqli_fetch_assoc($query);
    }

    // fetch data as array
    public function fetchAll($query)
    {
        return mysqli_fetch_all($query, MYSQLI_ASSOC);
    }

    // get last insert id
    public function last()
    {
        global $con;
        return mysqli_insert_id($this->$con);
    }

    // make statement
    private function statement($values = array())
    {
        $values = array_map(function ($ele) {
            $value = $ele;
            if (gettype($value) == "array") {
                return $this->statement($value);
            }
            return gettype($ele) == "string" ? "'$ele'" : $ele;
        }, $values);
        return $values;
    }
}
