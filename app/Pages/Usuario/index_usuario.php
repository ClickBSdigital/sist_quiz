<?php
// Definir variáveis de conexão com o banco de dados
$host = 'localhost';    // Nome do host (geralmente 'localhost')
$dbname = 'sist_quiz';  // Nome do banco de dados
$user = 'root';         // Usuário do banco de dados (geralmente 'root' em ambientes locais)
$password = '';         // Senha do banco de dados (em ambientes locais, pode ser vazia)

// Criar conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro ao conectar: ' . $e->getMessage();
    exit();
}

require_once '../../Classes/Usuario.php'; // Incluindo a classe Usuario

// Instanciar a classe de usuário
$usuario = new Usuario($pdo);

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';

    // Validar se todos os campos estão preenchidos
    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($confirma_senha)) {
        if ($senha === $confirma_senha) {
            // Registrar o usuário
            if ($usuario->cadastrarUsuario($nome, $email, $senha)) {
                echo "Usuário cadastrado com sucesso!";
            } else {
                echo "Erro ao cadastrar usuário.";
            }
        } else {
            echo "As senhas não coincidem.";
        }
    } else {
        echo "Todos os campos são obrigatórios.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include('../view/navbar.php'); ?>

    <div class="container mt-4">
        <h2>Cadastrar Usuário</h2>
        <form method="POST" action="index_cadastrar.php">
            <div class="mb-3">
                <label class="form-label">Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha:</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirmar Senha:</label>
                <input type="password" name="confirma_senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <div class="mt-4">
            <a href="listar_usuario.php" class="btn btn-success">
                <i class="bi bi-list"></i> Listar Usuários
            </a>
            <a href="editar_usuario.php" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Editar Usuário
            </a>
            <a href="inativar_usuario.php" class="btn btn-danger">
                <i class="bi bi-x-circle"></i> Inativar Usuário
            </a>
            <a href="index_usuario.php" class="btn btn-info">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
