<?php
class Usuario {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function criarUsuario($nome, $email, $senha, $tipo) {
        $hashSenha = password_hash($senha, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo, ativo) VALUES (:nome, :email, :senha, :tipo, 1)"; // Por padrão, o usuário é ativo
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['nome' => $nome, 'email' => $email, 'senha' => $hashSenha, 'tipo' => $tipo]);
    }

    public function buscarUsuarioPorId($id) {
        $sql = "SELECT id, nome, email, tipo, ativo, created_at FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarUsuarioPorEmail($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function validarSenha($email, $senha) {
        $usuario = $this->buscarUsuarioPorEmail($email);
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        }
        return false;
    }

    public function atualizarUsuario($id, $nome, $email, $tipo) {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'nome' => $nome, 'email' => $email, 'tipo' => $tipo]);
    }

    // Modificada para inativar o usuário em vez de excluí-lo
    public function inativarUsuario($id) {
        $sql = "UPDATE usuarios SET ativo = 0 WHERE id = :id"; // Define o status como inativo
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Método para listar todos os usuários
    public function listarUsuarios() {
        $sql = "SELECT id, nome, email, tipo, ativo, created_at FROM usuarios WHERE ativo = 1"; // Filtra usuários ativos
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os usuários ativos como um array associativo
    }
}
?>
