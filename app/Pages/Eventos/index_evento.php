<?php
require '../../DB/Database.php';

// Criar instância da classe Database para a tabela 'eventos'
$db = new Database('eventos');
$pdo = $db->getConn(); // Obtém a conexão PDO

// Verifica se a conexão foi estabelecida
if (!$pdo) {
    die("Erro na conexão com o banco de dados.");
}

// Buscar eventos e professores
try {
    $sql = "SELECT eventos.*, usuarios.nome AS professor 
            FROM eventos 
            JOIN usuarios ON eventos.professor_id = usuarios.id";
    $stmt = $pdo->query($sql);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar eventos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include('../../Pages/view/navbar.php'); ?>
    <div class="container mt-4">
        <h2 class="text-center">Gerenciamento de Eventos</h2>
        <div class="text-end mb-3">
            <a href="cadastrar_evento.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Cadastrar Evento</a>
        </div>
        <div class="card">
            <div class="card-header">Lista de Eventos</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome do Evento</th>
                            <th>Professor Responsável</th>
                            <th>Data de Criação</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($eventos) > 0): ?>
                            <?php foreach ($eventos as $evento): ?>
                                <tr>
                                    <td><?= htmlspecialchars($evento['id']) ?></td>
                                    <td><?= htmlspecialchars($evento['nome']) ?></td>
                                    <td><?= htmlspecialchars($evento['professor']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($evento['created_at'])) ?></td>
                                    <td><?= $evento['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                                    <td>
                                        <a href="./editar_eventos.php?id=<?= $evento['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <a href="inativar_evento.php?id=<?= $evento['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja inativar este evento?');">
                                            <i class="bi bi-x-circle"></i> Inativar
                                        </a>
                                        <a href="listar_evento.php?id=<?= $evento['id'] ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-eye"></i> Listar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan='6' class='text-center'>Nenhum evento encontrado</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="../Login/painel_professor.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar ao Painel do Professor</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
