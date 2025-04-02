<?php
session_start();
require_once '../../DB/Database.php';

// Verifica se o usuário está logado e se é aluno
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'aluno') {
    header('Location: ../../index.php'); // Redireciona se não for aluno
    exit();
}
echo "Bem-vindo, Aluno " . htmlspecialchars($_SESSION['usuario_nome']);

// Obtém o ID do aluno logado
$aluno_id = $_SESSION['usuario_id'];

// Busca eventos disponíveis
$sql_eventos = "SELECT e.id, e.nome 
                FROM eventos e
                JOIN equipes eq ON e.id = eq.evento_id
                JOIN participantes p ON eq.id = p.equipe_id
                WHERE p.usuario_id = ?";
$stmt = $conn->prepare($sql_eventos);
$stmt->bind_param("i", $aluno_id);
$stmt->execute();
$result_eventos = $stmt->get_result();

// Busca a pontuação do aluno
$sql_pontuacao = "SELECT SUM(pontos) as total_pontos FROM pontuacoes WHERE participante_id = ?";
$stmt_pontuacao = $conn->prepare($sql_pontuacao);
$stmt_pontuacao->bind_param("i", $aluno_id);
$stmt_pontuacao->execute();
$result_pontuacao = $stmt_pontuacao->get_result();
$pontuacao = $result_pontuacao->fetch_assoc()['total_pontos'] ?? 0;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Aluno</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Painel do Aluno</h1>
        <nav>
            <a href="eventos_disponiveis.php">Eventos</a>
            <a href="ver_ranking.php">Ranking</a>
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
                        <a href="participar_evento.php?id=<?= htmlspecialchars($evento['id']) ?>">Participar</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não participa de nenhum evento.</p>
        <?php endif; ?>

        <h2>Sua Pontuação</h2>
        <p>Total de pontos: <strong><?= $pontuacao ?></strong></p>
    </main>
</body>
</html>
