<?php
require_once '../../DB/Database.php';
include('../../Pages/view/navbar.php');

$db = new Database('eventos');
$query = "SELECT eventos.id, eventos.nome, usuarios.nome AS professor, eventos.created_at, eventos.ativo 
          FROM eventos
          JOIN usuarios ON eventos.professor_id = usuarios.id";
$eventos = $db->execute($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h4>Lista de Eventos</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Professor</th>
                    <th>Data de Criação</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($eventos as $evento) { ?>
                    <tr>
                        <td><?= htmlspecialchars($evento['id']) ?></td>
                        <td><?= htmlspecialchars($evento['nome']) ?></td>
                        <td><?= htmlspecialchars($evento['professor']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($evento['created_at'])) ?></td>
                        <td><?= $evento['ativo'] ? 'Ativo' : 'Inativo' ?></td>
                        <td>
                            <a href="editar_eventos.php?id=<?= $evento['id'] ?>" class="btn btn-success btn-sm">Editar</a>
                            <form action="inativar_evento.php" method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?= $evento['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Inativar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="index_evento.php" class="btn btn-primary">Voltar</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
