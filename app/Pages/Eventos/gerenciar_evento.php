<?php
session_start();
require_once '../../DB/Database.php';

// Verifica se o usuário está logado e se é professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header('Location: ../../index.php');
    exit();
}

// Obtém o ID do evento da URL
$evento_id = $_GET['id'] ?? null;

// Verifica se o evento ID foi passado e é um número válido
if (!$evento_id || !is_numeric($evento_id)) {
    header('Location: painel_professor.php');
    exit();
}

// Conexão com o banco de dados
$db = new Database('eventos');
$evento = $db->select("id = ?", null, null, '*', [$evento_id]);

// Se o evento não existir ou não pertencer ao professor, redireciona
if (!$evento || $evento[0]['professor_id'] != $_SESSION['usuario_id']) {
    header('Location: painel_professor.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../view/navbar.php'); ?>

    <div class="container mt-4">
        <h2>Gerenciar Evento: <?= htmlspecialchars($evento[0]['nome']) ?></h2>
        
        <div class="mt-3">
            <a href="editar_eventos.php?id=<?= $evento_id ?>" class="btn btn-warning">Editar Evento</a>
            <a href="excluir_evento.php?id=<?= $evento_id ?>" class="btn btn-danger">Excluir Evento</a>
            <button class="btn btn-danger" onclick="inativarEvento(<?= $evento_id ?>)">Inativar Evento</button>
            <a href="painel_professor.php" class="btn btn-secondary">Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function inativarEvento(evento_id) {
        if (confirm("Tem certeza que deseja inativar este evento?")) {
            fetch("inativar_evento.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ evento_id: evento_id }),
                credentials: "include"
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === "success") {
                    location.reload();
                }
            })
            .catch(error => console.error("Erro:", error));
        }
    }
    </script>
</body>
</html>
