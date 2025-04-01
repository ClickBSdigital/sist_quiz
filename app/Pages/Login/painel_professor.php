<?php
session_start();
require_once '../DB/conexao.php';

// Verifica se o usuário está logado e se é professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'professor') {
    header("Location: login.php");
    exit();
}

// Obtém o ID do professor logado
$professor_id = $_SESSION['usuario_id'];

// Busca eventos criados pelo professor
$sql_eventos = "SELECT id, nome FROM eventos WHERE professor_id = ?";
$stmt = $conn->prepare($sql_eventos);
$stmt->bind_param("i", $professor_id);
$stmt->execute();
$result_eventos = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor</title>
    <link rel="stylesheet" href="../assest/css/style.css">
</head>
<body>
    <header>
        <h1>Painel do Professor</h1>
        <nav>
            <a href="criar_evento.php">Criar Evento</a>
            <a href="gerenciar_perguntas.php">Gerenciar Perguntas</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <main>
        <h2>Seus Eventos</h2>
        <?php if ($result_eventos->num_rows > 0): ?>
            <ul>
                <?php while ($evento = $result_eventos->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($evento['nome']) ?></strong>
                        <a href="gerenciar_evento.php?id=<?= $evento['id'] ?>">Gerenciar</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não criou nenhum evento.</p>
        <?php endif; ?>
    </main>
</body>
</html>
