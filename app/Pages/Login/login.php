<?php
session_start();
require_once 'db.php'; // Arquivo de conexão com o banco

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail existe na tabela de usuários
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Verifica se o usuário está ativo
        if ($usuario['ativo'] == 1) {
            // Cria sessão para o usuário
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redireciona o usuário para o painel correto
            if ($usuario['tipo'] == 'professor') {
                header('Location: painel_professor.php');
                exit();
            } else {
                header('Location: painel_aluno.php');
                exit();
            }
        } else {
            $erro = "Sua conta está inativa. Por favor, entre em contato com o administrador.";
        }
    } else {
        $erro = "E-mail ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quiz</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>
            
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            
            <button type="submit">Entrar</button>
        </form>
        <p><a href="recuperar_senha.php">Esqueceu a senha?</a></p>
    </div>
</body>
</html>
