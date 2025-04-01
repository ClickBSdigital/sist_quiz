<?php
require_once '../../DB/Database.php';
require_once '../../Classes/Premiacao.php';

// Criar conexão com o banco
$host = 'localhost'; // Defina o host do banco
$dbname = 'sist_quiz'; // Defina o nome do banco de dados
$user = 'root'; // Defina o nome de usuário do banco de dados
$password = ''; // Defina a senha de acesso ao banco

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

$premiacao = new Premiacao($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturando e filtrando os dados do formulário
    $equipe_id = $_POST['equipe_id'] ?? '';
    $fase = $_POST['fase'] ?? '';
    $premio = $_POST['premio'] ?? '';

    // Verificar se os campos não estão vazios
    if (!empty($equipe_id) && !empty($fase) && !empty($premio)) {
        // Chamar o método de registro de premiação
        if ($premiacao->registrarPremiacao($equipe_id, $fase, $premio)) {
            echo "Premiação cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar premiação.";
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
    <title>Cadastrar Premiação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Cadastrar Premiação</h2>
        <form method="POST" action="cadastro_premiacao.php">
            <div class="mb-3">
                <label class="form-label">ID da Equipe:</label>
                <input type="number" name="equipe_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fase:</label>
                <input type="number" name="fase" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prêmio:</label>
                <input type="text" name="premio" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>
    </div>
</body>
</html>
