<?php
require '../config/database.php'; // Certifique-se de que esse arquivo contém a conexão com o banco

$sql = "SELECT p.id, e.nome AS equipe, p.fase, p.premio, p.data_premiacao, p.ativo 
        FROM premiacoes p 
        JOIN equipes e ON p.equipe_id = e.id 
        ORDER BY p.data_premiacao DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$premiacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Premiações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Lista de Premiações</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipe</th>
                    <th>Fase</th>
                    <th>Prêmio</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($premiacoes as $premiacao): ?>
                    <tr>
                        <td><?= $premiacao['id'] ?></td>
                        <td><?= htmlspecialchars($premiacao['equipe']) ?></td>
                        <td><?= $premiacao['fase'] ?></td>
                        <td><?= htmlspecialchars($premiacao['premio']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($premiacao['data_premiacao'])) ?></td>
                        <td><?= $premiacao['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                        <td>
                            <a href="editar_premiacao.php?id=<?= $premiacao['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="inativar_premiacao.php?id=<?= $premiacao['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja inativar esta premiação?')">Inativar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="cadastro_premiacao.php" class="btn btn-primary">Cadastrar Nova Premiação</a>
    </div>
</body>
</html>