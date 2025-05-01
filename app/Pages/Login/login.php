<?php
session_start();
require_once '../../DB/Database.php';

// Criando a conexão com a tabela 'usuarios'
$db = new Database('usuarios'); 
$pdo = $db->getConn();  // Obtendo a conexão PDO corretamente

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
    $stmt->execute(['email' => $email]); // Corrigido para associar corretamente o valor
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        if ($usuario['ativo'] == 1) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            if ($usuario['tipo'] == 'professor') {
                header('Location: ../../Pages/Login/painel_professor.php'); // Caminho corrigido
                exit();
            } else {
                header('Location: ../../Pages/painel_aluno.php'); // Caminho corrigido
                exit();
            }
        } else {
            $erro = "Sua conta está inativa. Contate o administrador.";
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
    <title>Login</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
    <link rel="stylesheet" href="./app/assets/css/nav.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="form-box login">
            <form action="" method="POST">
                <h1>Login</h1>
                <?php if (isset($erro)) echo "<p class='error'>$erro</p>"; ?>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="senha" placeholder="Senha" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" name="login" class="btn">Login</button>
                <p>ou faça login com plataformas sociais</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-github'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <form action="register.php" method="POST">
                <h1>Cadastro</h1>
                <div class="input-box">
                    <input type="text" name="nome" placeholder="Nome" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="senha" placeholder="Senha" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-box">
                <label for="tipo">Tipo:</label>
                    <select name="tipo" id="tipo">
                        <option value="professor">Professor</option>
                        <option value="aluno">Aluno</option>
                    </select>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <button type="submit" class="btn">Cadastre-se</button>
                <p>ou registre-se nas plataformas sociais</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-github'></i></a>
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                </div>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Olá, bem-vindo!</h1>
                <p>Não tem uma conta?</p>
                <button class="btn register-btn">Cadastre-se</button>
            </div>

            <div class="toggle-panel toggle-right">
                <h1>Olá, bem-vindo!</h1>
                <p>Já tem uma conta?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/login.js"></script>
</body>
</html>