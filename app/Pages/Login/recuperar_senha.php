<?php
// Incluir a classe de recuperação
require_once 'RecuperacaoSenha.php';
require_once 'db.php'; // Arquivo de conexão com o banco

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verifica se o e-mail existe na tabela de usuários
    $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $recuperacao = new RecuperacaoSenha($pdo);
        $token = $recuperacao->gerarToken($usuario['id']);

        // Enviar o e-mail com o token
        $link = "https://seusite.com/alterar_senha.php?token=$token";
        $assunto = "Recuperação de Senha";
        $mensagem = "Clique no link para redefinir sua senha: $link";
        mail($email, $assunto, $mensagem);

        echo "Um link de recuperação foi enviado para seu e-mail.";
    } else {
        echo "Este e-mail não está registrado.";
    }
}
?>

<form action="recuperar_senha.php" method="POST">
    <label for="email">Digite seu e-mail:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Recuperar Senha</button>
</form>
