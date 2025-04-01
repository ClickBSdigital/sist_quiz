<?php

class Database {
    private $conn;
    private string $local = 'localhost';
    private string $db = 'sist_quiz';
    private string $user = 'root';
    private string $password = '';
    private $table;

    public function __construct($table = null) {
        $this->table = $table;
        $this->conecta();
    }

    private function conecta() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->local . ";dbname=" . $this->db, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $err) {
            die("Connection Failed: " . $err->getMessage());
        }
    }

    public function execute($query, $binds = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($binds);
            return $stmt;
        } catch (PDOException $err) {
            die("Query Failed: " . $err->getMessage());
        }
    }

    public function insert($values) {
        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');
        $query = "INSERT INTO " . $this->table . " (" . implode(",", $fields) . ") VALUES (" . implode(",", $binds) . ")";
        return $this->execute($query, array_values($values));
    }

    public function select($where = null, $order = null, $limit = null, $fields = '*') {
        $where = $where ? "WHERE " . $where : "";
        $order = $order ? "ORDER BY " . $order : "";
        $limit = $limit ? "LIMIT " . $limit : "";
        $query = "SELECT " . $fields . " FROM " . $this->table . " " . $where . " " . $order . " " . $limit;
        return $this->execute($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_by_id($where, $fields = '*') {
        $query = "SELECT " . $fields . " FROM " . $this->table . " WHERE " . $where;
        return $this->execute($query)->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($where) {
        $query = "DELETE FROM " . $this->table . " WHERE " . $where;
        $stmt = $this->execute($query);
        return $stmt->rowCount() > 0;
    }

    public function update($where, $array) {
        $fields = array_keys($array);
        $values = array_values($array);
        $query = "UPDATE " . $this->table . " SET " . implode(" = ?, ", $fields) . " = ? WHERE " . $where;
        $stmt = $this->execute($query, $values);
        return $stmt->rowCount() > 0;
    }
}
