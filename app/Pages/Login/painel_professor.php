<?php
session_start();
require_once '../../DB/Database.php';

// Verifica se o usuário está logado e se é professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header('Location: ../../index.php'); // Redireciona se não for professor
    exit();
}
echo "Bem-vindo, Professor " . htmlspecialchars($_SESSION['usuario_nome']);

// Obtém o ID do professor logado
$professor_id = $_SESSION['usuario_id'];

// Instancia a classe Database, passando a tabela 'eventos'
$db = new Database('eventos');

// Monta a query de seleção
$where = "professor_id = ?";
$binds = [$professor_id];

// Busca eventos criados pelo professor
$result_eventos = $db->select($where, null, null, 'id, nome', $binds);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Professor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
        <?php if (count($result_eventos) > 0): ?>
            <ul>
                <?php foreach ($result_eventos as $evento): ?>
                    <li>
                        <strong><?= htmlspecialchars($evento['nome']) ?></strong>
                        <a href="gerenciar_evento.php?id=<?= htmlspecialchars($evento['id']) ?>">Gerenciar</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não criou nenhum evento.</p>
        <?php endif; ?>
    </main>
</body>
</html>
