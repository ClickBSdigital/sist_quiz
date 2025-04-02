<?php
session_start();
require_once '../../DB/Database.php'; // Incluindo a classe Database

// Crie uma instância da classe Database para conectar-se ao banco
$db = new Database('usuarios'); // 'usuarios' é a tabela que estamos manipulando

// Acesse a conexão através do método getConn()
$pdo = $db->getConn(); // Aqui você obtém a conexão PDO

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inicializando as variáveis com os dados do formulário
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Prepara a consulta para verificar o e-mail e senha
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    // Verifica se o usuário existe
    if ($stmt->rowCount() > 0) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica a senha
        if (password_verify($senha, $usuario['senha'])) {
            // Cria as variáveis de sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            // Redireciona o usuário com base no tipo
            if ($usuario['tipo'] == 'professor') {
                header('Location: ../../Pages/Login/painel_professor.php');
            } else {
                header('Location: ../../Pages/Login/painel_aluno.php');
            }
            exit();
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "E-mail não encontrado!";
    }
}
?>

