<?php
require '../../DB/Database.php';
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
    <?php include('navbar.php'); ?>
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
                        <?php
                        $sql = "SELECT eventos.*, usuarios.nome AS professor FROM eventos JOIN usuarios ON eventos.professor_id = usuarios.id";
                        $eventos = mysqli_query($conexao, $sql);
                        
                        if (mysqli_num_rows($eventos) > 0) {
                            while ($evento = mysqli_fetch_assoc($eventos)) {
                        ?>
                        <tr>
                            <td><?= $evento['id'] ?></td>
                            <td><?= $evento['nome'] ?></td>
                            <td><?= $evento['professor'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($evento['created_at'])) ?></td>
                            <td><?= $evento['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                            <td>
                                <a href="editar_evento.php?id=<?= $evento['id'] ?>" class="btn btn-warning btn-sm">
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
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>Nenhum evento encontrado</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
