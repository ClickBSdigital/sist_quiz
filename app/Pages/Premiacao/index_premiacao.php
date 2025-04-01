<?php
require '../../DB/Database.php';

// Criando instância do banco
$db = new Database('premiacoes');

// Consulta para buscar premiações com o nome da equipe
$sql = "SELECT premiacoes.*, equipes.nome AS equipe 
        FROM premiacoes 
        JOIN equipes ON premiacoes.equipe_id = equipes.id
        ORDER BY premiacoes.id DESC";

$premiacoes = $db->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Premiações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include('../view/navbar.php'); ?>
    <div class="container mt-4">
        <h2 class="text-center">Gerenciamento de Premiações</h2>
        <div class="text-end mb-3">
            <a href="cadastro_premiacao.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Cadastrar Premiação
            </a>
        </div>
        <div class="card">
            <div class="card-header">Lista de Premiações</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Equipe</th>
                            <th>Fase</th>
                            <th>Prêmio</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($premiacoes)) {
                            foreach ($premiacoes as $premiacao) { ?>
                        <tr>
                            <td><?= htmlspecialchars($premiacao['id']) ?></td>
                            <td><?= htmlspecialchars($premiacao['equipe']) ?></td> <!-- Mostrando o nome da equipe -->
                            <td><?= htmlspecialchars($premiacao['fase']) ?></td>
                            <td><?= htmlspecialchars($premiacao['premio']) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($premiacao['data_premiacao'])) ?></td>
                            <td>
                                <a href="editar_premiacao.php?id=<?= $premiacao['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="inativar_premiacao.php?id=<?= $premiacao['id'] ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Tem certeza que deseja inativar esta premiação?');">
                                    <i class="bi bi-x-circle"></i> Inativar
                                </a>
                                <a href="listar_premiacao.php?id=<?= $premiacao['id'] ?>" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i> Listar
                                </a>
                            </td>
                        </tr>
                        <?php } 
                        } else { ?>
                            <tr><td colspan='6' class='text-center'>Nenhuma premiação encontrada</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
