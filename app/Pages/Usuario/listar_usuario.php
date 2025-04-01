<?php
// Definir variáveis de conexão com o banco de dados
$host = 'localhost';    // Nome do host (geralmente 'localhost')
$dbname = 'sist_quiz';  // Nome do banco de dados
$user = 'root';         // Usuário do banco de dados (geralmente 'root' em ambientes locais)
$password = '';         // Senha do banco de dados (em ambientes locais, pode ser vazia)

try {
    // Criar conexão com o banco de dados
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erro ao conectar: ' . $e->getMessage();
    exit();
}

require_once '../../Classes/Usuario.php';

// Instanciar a classe Usuario com a conexão $pdo
$usuario = new Usuario($pdo);

// Aqui você pode usar os métodos da classe Usuario para listar os usuários, por exemplo:
// $usuarios = $usuario->listarUsuarios();  // Exemplo de método para listar usuários
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include('../view/navbar.php'); ?>

    <div class="container mt-4">
        <h2>Listar Usuários</h2>
        <!-- Aqui você pode exibir os usuários, exemplo de tabela -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Aqui você chama um método para listar os usuários, se existir na classe Usuario
                // Exemplo de código que chama o método listarUsuarios() da classe Usuario
                // Se a classe tiver esse método, você pode exibir os dados de cada usuário
                foreach ($usuario->listarUsuarios() as $user) {
                    echo "<tr>";
                    echo "<td>" . $user['id'] . "</td>";
                    echo "<td>" . $user['nome'] . "</td>";
                    echo "<td>" . $user['email'] . "</td>";
                    echo "<td>
                            <a href='editar_usuario.php?id=" . $user['id'] . "' class='btn btn-warning'>Editar</a>
                            <a href='inativar_usuario.php?id=" . $user['id'] . "' class='btn btn-danger' onclick='return confirm(\"Tem certeza que deseja inativar este usuário?\")'>Inativar</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Botão de voltar -->
        <a href="index_usuario.php" class="btn btn-info">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
