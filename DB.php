<?php

class  DB
{
    private static $instance;
    private $table;
    private $select;
    private $where;
    private $join;
    private $distinct = false;
    private $conn;
    private $query;
    private $orderBy;
    private $limit;
    private $groupBy;

    public function __construct()
    {
        try {
            // $servername = "localhost";
            // $username = "530255";
            // $password = "tarikaka";
            // $dbname = "530255";

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "phu-lieu";
            $this->conn = new PDO("mysql:host=$servername;port=3306;dbname=$dbname;charset=utf8mb4", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ]);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function table($table)
    {
        $instance = new DB();
        $instance->table = $table;
        return $instance;
    }

    public static function exec($sql)
    {
        $instance = new DB();
        $result = $instance->conn->query($sql);
        return $result->fetchAll();
    }

    //    public static function getInstance()
    //    {
    //        if (self::$instance === null) {
    //            self::$instance = new DB();
    //
    //        }
    //        return self::$instance;
    //
    //    }

    public function sum($column)
    {
        $query = "SELECT SUM($column) as total FROM " . $this->table . " WHERE " . implode(' AND ', $this->where);
        // echo $query;
        return $this->conn->query($query)->fetch()->total;
    }

    public function select(...$selects)
    {
        $this->select = $selects;
        return $this;
    }

    public function where($column, $condition, $value)
    {
        $this->where[] = "$column $condition '$value'";
        return $this;
    }

    public function find($id)
    {
        $this->where[] = "id = $id";
        $this->get();
    }

    public function get()
    {

        $query = "SELECT ";
        if ($this->distinct) {
            $query .= " DISTINCT ";
        }
        if (empty($this->select)) {
            $query .= "*";
        } else {
            $query .= implode(',', $this->select);
        }
        $query .= " FROM " . $this->table;
        if (!empty($this->join)) {
            $query .= implode(' ', $this->join);
        }

        if (isset($this->where)) {
            $query .= " WHERE " . implode(' AND ', $this->where);
        }
        if (isset($this->orderBy)) {
            $query .= " ORDER BY " . implode(' , ', $this->orderBy);
        }
        if (isset($this->groupBy)) {
            $query .= " GROUP BY $this->groupBy";
        }
        if (isset($this->limit)) {
            $query .= " LIMIT $this->limit";
        }

        $this->query = $query;
        // echo $this->query;
        return $this;
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE " . implode(' AND ', $this->where);
        return $this->conn->query($query);
    }

    public function whereNull($column)
    {
        $this->where[] = "$column IS NULL";
        return $this;
    }

    public function groupBy($column)
    {
        $this->groupBy = $column;
        return $this;
    }

    public function whereNotNull($column)
    {
        $this->where[] = "$column IS NOT NULL";
        return $this;
    }

    public function orderBy($column, $order = "ASC")
    {
        $order = strtoupper($order);
        $this->orderBy[] = "$column $order";
        return $this;
    }

    public function limit($number)
    {
        $this->limit = $number;
        return $this;
    }

    public function join($table, $first, $operator, $second)
    {
        $this->join[] = " JOIN $table ON $first $operator $second";
        return $this;
    }

    public function crossJoin($table)
    {
        $this->join[] = " CROSS JOIN $table";
        return $this;
    }

    public function count($column = "*")
    {
        $query = "SELECT COUNT($column) FROM " . $this->table . " WHERE " . implode(' AND ', $this->where);
        return $this->conn->query($query)->fetchColumn();
    }

    public function fetch()
    {
        $this->get();
        $result = $this->conn->query($this->query);
        return $result->fetch();
    }

    public function fetchAll()
    {
        $this->get();
        $result = $this->conn->query($this->query);
        return $result->fetchAll();
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function update($data)
    {

        //        UPDATE `items` SET `sold-out` = '2023-07-16 10:44:41' WHERE `items`.`items_id` = 1;
        $str = [];
        foreach ($data as $key => $column) {
            $str[] = "`$key` = '$column'";
        }
        $query = "UPDATE " . $this->table . " SET " . implode(",", $str) . " WHERE " . implode(' AND ', $this->where);
        $this->conn->exec($query);
        return $this->conn->lastInsertId();
    }

    public function insert($data)
    {
        $columns = implode(", ", array_keys($data));
        $values = "";
        foreach ($data as $key => $value) {
            $values .= "'$value'" . ", ";
        }
        $values = rtrim(trim($values), ',');

        $query = "INSERT INTO " . $this->table . " ( $columns ) " . " VALUES ( $values )";
        //        echo $query;
        $this->conn->exec($query);
        return $this->conn->lastInsertId();
    }
}
