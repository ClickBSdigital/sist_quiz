<?php
require_once '../../Classes/Usuario.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $usuario = new Usuario();
    
    // Inativa o usuário (em vez de deletá-lo)
    if ($usuario->inativar($id)) {
        echo "Usuário inativado com sucesso!";
    } else {
        echo "Erro ao inativar o usuário.";
    }
} else {
    echo "ID do usuário não informado.";
}
?>
