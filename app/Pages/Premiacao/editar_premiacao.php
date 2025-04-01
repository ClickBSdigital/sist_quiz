<?php
require_once '../../config/database.php';
require_once '../../classes/Premiacao.php';

$pdo = conectarBanco();
$premiacao = new Premiacao($pdo);

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID da premiação não fornecido.";
    exit;
}

$id = $_GET['id'];
$dadosPremiacao = $premiacao->buscarPremiacaoPorId($id);

if (!$dadosPremiacao) {
    echo "Premiação não encontrada.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fase = $_POST['fase'] ?? '';
    $premio = $_POST['premio'] ?? '';

    if (!empty($fase) && !empty($premio)) {
        if ($premiacao->editarPremiacao($id, $fase, $premio)) {
            echo "Premiação atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar premiação.";
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
    <title>Editar Premiação</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Premiação</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Fase:</label>
                <input type="number" name="fase" class="form-control" value="<?= htmlspecialchars($dadosPremiacao['fase']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Prêmio:</label>
                <input type="text" name="premio" class="form-control" value="<?= htmlspecialchars($dadosPremiacao['premio']) ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="listar_premiacoes.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
