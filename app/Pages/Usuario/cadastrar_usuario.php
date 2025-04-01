<?php
require_once 'Usuario.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? '';

    if (!empty($nome) && !empty($email) && !empty($senha) && !empty($tipo)) {
        $usuario = new Usuario();
        if ($usuario->cadastrar($nome, $email, $senha, $tipo)) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    } else {
        echo "Todos os campos são obrigatórios.";
    }
}
?>

<form method="POST" action="cadastrar_usuario.php">
    <label>Nome:</label>
    <input type="text" name="nome" required>
    <br>
    <label>Email:</label>
    <input type="email" name="email" required>
    <br>
    <label>Senha:</label>
    <input type="password" name="senha" required>
    <br>
    <label>Tipo:</label>
    <select name="tipo" required>
        <option value="professor">Professor</option>
        <option value="aluno">Aluno</option>
    </select>
    <br>
    <button type="submit">Cadastrar</button>
</form>
