<?php
session_start();
require_once '../../DB/Database.php'; // Conexão com o banco

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inicializando as variáveis com os dados do formulário
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);
    $tipo = $_POST['tipo']; // aluno ou professor

    // Crie uma instância da classe Database para conectar-se ao banco
    $db = new Database('usuarios'); // 'usuarios' é a tabela que estamos manipulando

    // Acesse a conexão através do método getConn()
    $conn = $db->getConn();

    // Verifique se o e-mail já está cadastrado
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    // Verifica se o e-mail já está registrado
    if ($stmt->rowCount() > 0) {
        echo "Erro: E-mail já cadastrado!";
        exit();
    }

    // Insere o novo usuário no banco de dados
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo, ativo) VALUES (?, ?, ?, ?, 1)");

    if ($stmt->execute([$nome, $email, $senha, $tipo])) {
        $_SESSION['usuario_id'] = $conn->lastInsertId();
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_email'] = $email;
        $_SESSION['usuario_tipo'] = $tipo;

        // Redireciona com base no tipo de usuário
        if ($tipo == 'professor') {
            header('Location: ../../Pages/Login/painel_professor.php');
        } else {
            header('Location: ../../Pages/Login/painel_aluno.php');
        }
        exit();
    } else {
        echo "Erro ao cadastrar usuário!";
    }
}
?>

