<?php
// Definir variáveis de conexão com o banco de dados
$host = 'localhost';
$dbname = 'sist_quiz';
$user = 'root';
$password = '';

try {
    // Criar conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro ao conectar: ' . $e->getMessage();
    exit();
}

require_once '../../Classes/Usuario.php';

// Verifique se o ID do usuário foi passado via GET (por exemplo, ?id=1)
$id = isset($_GET['id']) ? $_GET['id'] : 0;

// Instanciar a classe Usuario com a conexão $pdo
$usuario = new Usuario($pdo);

// Agora você pode usar os métodos da classe Usuario, por exemplo, buscar o usuário
$userData = $usuario->buscarUsuarioPorId($id);

if (!$userData) {
    echo "Usuário não encontrado.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../view/navbar.php'); ?>

    <div class="container mt-4">
        <h2>Editar Usuário</h2>

        <!-- Formulário de edição -->
        <form action="salvar_edicao.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $userData['nome']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="professor" <?php echo ($userData['tipo'] == 'professor') ? 'selected' : ''; ?>>Professor</option>
                    <option value="aluno" <?php echo ($userData['tipo'] == 'aluno') ? 'selected' : ''; ?>>Aluno</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha">
                <small class="text-muted">Deixe em branco se não quiser alterar a senha.</small>
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <!-- Botão de voltar -->
        <a href="index_usuario.php" class="btn btn-info">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
