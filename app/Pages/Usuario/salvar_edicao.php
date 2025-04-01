<?php
// Definir variáveis de conexão com o banco de dados
$host = 'localhost';
$dbname = 'sist_quiz';
$user = 'root';
$password = '';

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

// Obter os dados do formulário
$id = $_POST['id'];
$nome = $_POST['nome'];
$email = $_POST['email'];
$tipo = $_POST['tipo'];
$senha = isset($_POST['senha']) ? $_POST['senha'] : '';

// Atualizar o usuário no banco de dados
if (!empty($senha)) {
    // Se a senha for fornecida, atualiza a senha
    $usuario->atualizarUsuario($id, $nome, $email, $tipo, $senha);
} else {
    // Se a senha não for fornecida, não atualiza a senha
    $usuario->atualizarUsuario($id, $nome, $email, $tipo);
}

// Redirecionar de volta para a página de listagem de usuários
header('Location: listar_usuario.php');
exit();
?>
