<?php
require_once '../../DB/Database.php';

// Criar instância da classe Database para a tabela 'eventos'
$db = new Database('eventos'); 
$pdo = $db->getConn(); // Obtém a conexão PDO

// Verifica se a conexão foi estabelecida
if (!$pdo) {
    die("Erro na conexão com o banco de dados.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $professor_id = $_POST['professor_id'] ?? '';

    if (!empty($nome) && !empty($professor_id)) {
        try {
            $sql = "INSERT INTO eventos (nome, professor_id) VALUES (:nome, :professor_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome' => $nome,
                ':professor_id' => $professor_id
            ]);
            $mensagem = "Evento cadastrado com sucesso!";
        } catch (PDOException $e) {
            $mensagem = "Erro ao cadastrar evento: " . $e->getMessage();
        }
    } else {
        $mensagem = "Todos os campos são obrigatórios.";
    }
}

// Buscar professores cadastrados
try {
    $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE tipo = 'professor'");
    $professores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar professores: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include('../view/navbar.php'); ?>

    <div class="container mt-4">
        <h2>Cadastrar Evento</h2>
        
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-info"> <?= $mensagem ?> </div>
        <?php endif; ?>

        <form method="POST" action="cadastrar_eventos.php">
            <div class="mb-3">
                <label class="form-label">Nome do Evento:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Professor Responsável:</label>
                <select name="professor_id" class="form-control" required>
                    <option value="">Selecione um professor</option>
                    <?php foreach ($professores as $professor): ?>
                        <option value="<?= htmlspecialchars($professor['id']) ?>">
                            <?= htmlspecialchars($professor['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Evento</button>
            <a href="../../Pages/Login/painel_professor.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>