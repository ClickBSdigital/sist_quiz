<?php

class Database {
    private $conn;
    private string $local = 'localhost';
    private string $db = 'sist_quiz';
    private string $user = 'root';
    private string $password = '';
    private $table;

    public function __construct($table = null) {
        // Verifica se a tabela foi passada
        if (!$table) {
            die("A tabela precisa ser especificada.");
        }
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

    // Método público para acessar a conexão
    public function getConn() {
        return $this->conn;
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
        // Verifica se os valores estão sendo passados corretamente
        if (empty($values)) {
            die("Os valores para inserção não podem ser vazios.");
        }

        $fields = array_keys($values);
        $binds = array_pad([], count($fields), '?');
        $query = "INSERT INTO " . $this->table . " (" . implode(",", $fields) . ") VALUES (" . implode(",", $binds) . ")";
        return $this->execute($query, array_values($values));
    }

    public function select($where = null, $order = null, $limit = null, $fields = '*', $binds = []) {
        // Monta a cláusula WHERE
        $where = $where ? "WHERE " . $where : "";
        // Monta a cláusula ORDER BY
        $order = $order ? "ORDER BY " . $order : "";
        // Monta a cláusula LIMIT
        $limit = $limit ? "LIMIT " . $limit : "";

        $query = "SELECT " . $fields . " FROM " . $this->table . " " . $where . " " . $order . " " . $limit;
        
        // Executa a query com os binds para evitar SQL Injection
        return $this->execute($query, $binds)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select_by_id($where, $fields = '*', $binds = []) {
        // Selecione um único item baseado no id
        $query = "SELECT " . $fields . " FROM " . $this->table . " WHERE " . $where;
        return $this->execute($query, $binds)->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($where, $binds = []) {
        // Deleta com base na condição fornecida
        $query = "DELETE FROM " . $this->table . " WHERE " . $where;
        $stmt = $this->execute($query, $binds);
        return $stmt->rowCount() > 0;
    }

    public function update($where, $array, $binds = []) {
        // Verifica se o array está vazio
        if (empty($array)) {
            die("Os valores para atualização não podem ser vazios.");
        }

        $fields = array_keys($array);
        $values = array_values($array);
        $query = "UPDATE " . $this->table . " SET ";

        // Monta a query com base nos campos e valores
        foreach ($fields as $field) {
            $query .= $field . " = ?, ";
        }
        $query = rtrim($query, ", ");  // Remove a última vírgula
        $query .= " WHERE " . $where;

        // Combina os valores do array com os binds adicionais
        $stmt = $this->execute($query, array_merge($values, $binds));
        return $stmt->rowCount() > 0;
    }
}

?>
