<?php
class RecuperacaoSenha {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Gera um token para recuperação de senha
    public function gerarToken($usuario_id) {
        $token = bin2hex(random_bytes(16)); // Gera um token aleatório
        $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expira em 1 hora

        // Inserir o token na tabela de recuperação
        $stmt = $this->pdo->prepare("INSERT INTO recuperacao_senha (usuario_id, token, expiracao) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $token, $expiracao]);

        return $token;
    }

    // Verifica se o token é válido
    public function verificarToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM recuperacao_senha WHERE token = ? AND usado = 0 AND expiracao > NOW()");
        $stmt->execute([$token]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            return $dados['usuario_id']; // Retorna o ID do usuário se o token for válido
        }

        return false; // Token inválido ou expirado
    }

    // Marca o token como usado (quando a senha for alterada)
    public function usarToken($token) {
        $stmt = $this->pdo->prepare("UPDATE recuperacao_senha SET usado = 1 WHERE token = ?");
        $stmt->execute([$token]);
    }
}
?>
