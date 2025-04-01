<?php
session_start();

class Login {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function autenticar($email, $senha) {
        $sql = "SELECT * FROM usuarios WHERE email = :email AND ativo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            return true;
        }
        return false;
    }

    public function estaLogado() {
        return isset($_SESSION['usuario_id']);
    }

    public function logout() {
        session_destroy();
        unset($_SESSION['usuario_id']);
        unset($_SESSION['usuario_nome']);
        unset($_SESSION['usuario_tipo']);
    }
}
?>
