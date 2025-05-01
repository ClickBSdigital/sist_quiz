<?php
session_start();
require_once '../../DB/Database.php';

// Verifica se o usuário está logado e se é professor
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'professor') {
    header('Location: ../../index.php'); // Redireciona se não for professor
    exit();
}

$professor_id = $_SESSION['usuario_id'];
$professor_nome = htmlspecialchars($_SESSION['usuario_nome']);

// Instancia a classe Database para a tabela 'eventos'
$db = new Database('eventos');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
    <?php include('../view/navbar.php'); ?>

    <div class="container mt-4">
        <h2 class="mb-3">Bem-vindo, Professor <?= $professor_nome; ?></h2>
        <div class="mb-3 d-flex gap-2 flex-wrap">
            <?php
            $itens = [
                'Eventos' => 'Eventos',
                'Equipes' => 'Equipes',
                'Fases' => 'Fases',
                'Participantes' => 'Participantes',
                'Pontuação' => 'Pontuacao',
                'Premiação' => 'Premiacao',
                'Respostas' => 'Respostas',
                'Usuários' => 'Usuarios'
            ];
            
            foreach ($itens as $nome => $pasta) {
                echo "<div class='dropdown'>
                        <button class='btn btn-primary dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                            <i class='bi bi-gear'></i> Gerenciar $nome
                        </button>
                        <ul class='dropdown-menu'>
                            <li><a class='dropdown-item' href='../../Pages/$pasta/cadastrar.php'><i class='bi bi-plus-circle'></i> Cadastrar</a></li>
                            <li><a class='dropdown-item' href='../../Pages/$pasta/editar.php'><i class='bi bi-pencil'></i> Editar</a></li>
                            <li><a class='dropdown-item' href='../../Pages/$pasta/listar.php'><i class='bi bi-list'></i> Listar</a></li>
                        </ul>
                      </div>";
            }
            ?>
            <a href="logout.php" class="btn btn-danger">
                <i class="bi bi-box-arrow-right"></i> Sair
            </a>
        </div>

        <h3>Seus Eventos</h3>
        <?php if (count($result_eventos) > 0): ?>
            <ul class="list-group">
                <?php foreach ($result_eventos as $evento): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong><?= htmlspecialchars($evento['nome']); ?></strong>
                        <a href="../../Pages/Eventos/gerenciar_evento.php?id=<?= htmlspecialchars($evento['id']); ?>" class="btn btn-info btn-sm">
                            <i class="bi bi-gear"></i> Gerenciar
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-muted">Você ainda não criou nenhum evento.</p>
        <?php endif; ?>

        <div class="mt-4">
            <a href="index_professor.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
