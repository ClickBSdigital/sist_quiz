<?php
require_once 'RecuperacaoSenha.php';
require_once 'db.php'; // Arquivo de conexão com o banco

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $recuperacao = new RecuperacaoSenha($pdo);
    $usuario_id = $recuperacao->verificarToken($token);

    if ($usuario_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nova_senha = $_POST['senha'];

            // Criptografar a senha
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            // Atualizar a senha no banco de dados
            $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $stmt->execute([$senha_hash, $usuario_id]);

            // Marcar o token como usado
            $recuperacao->usarToken($token);

            echo "Senha alterada com sucesso!";
        }
    } else {
        echo "Token inválido ou expirado.";
    }
}
?>

<form action="alterar_senha.php?token=<?php echo $_GET['token']; ?>" method="POST">
    <label for="senha">Nova Senha:</label>
    <input type="password" name="senha" id="senha" required>
    <button type="submit">Alterar Senha</button>
</form>
