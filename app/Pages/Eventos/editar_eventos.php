<?php
require_once '../../DB/Database.php';
include('../../Pages/view/navbar.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID do evento não fornecido ou inválido.");
}

$id = $_GET['id'];
$db = new Database('eventos');

// Buscar o evento pelo ID
$evento = $db->select_by_id("id = ?", "*", [$id]);

if (!$evento) {
    die("Evento não encontrado.");
}

// Buscar todos os professores para o select
$dbUsuarios = new Database('usuarios');
$professores = $dbUsuarios->select("tipo = ?", null, null, "id, nome", ['professor']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $professor_id = $_POST['professor_id'] ?? '';
    
    if (!empty($nome) && !empty($professor_id)) {
        $atualizado = $db->update("id = ?", ['nome' => $nome, 'professor_id' => $professor_id], [$id]);
        
        if ($atualizado) {
            echo "<div class='alert alert-success'>Evento atualizado com sucesso!</div>";
        } else {
            echo "<div class='alert alert-danger'>Erro ao atualizar o evento.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Todos os campos são obrigatórios.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="mb-4">Editar Evento</h2>
        <form method="POST" action="editar_eventos.php?id=<?= $id ?>">
            <div class="mb-3">
                <label class="form-label">Nome do Evento:</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($evento['nome']) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Professor Responsável:</label>
                <select name="professor_id" class="form-select" required>
                    <?php foreach ($professores as $professor) { ?>
                        <option value="<?= $professor['id'] ?>" <?= $professor['id'] == $evento['professor_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($professor['nome']) ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Evento</button>
            <a href="index_evento.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>
</body>
</html>
