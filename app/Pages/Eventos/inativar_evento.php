<?php
require_once 'Database.php'; // Certifique-se de ter uma conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['evento_id'])) {
    $evento_id = $_POST['evento_id'];
    
    // Verifica se o evento existe
    $query = "SELECT * FROM eventos WHERE id = ?";
    $stmt = $conexao->prepare($query);
    $stmt->bind_param("i", $evento_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Inativa o evento
        $update_query = "UPDATE eventos SET ativo = 0 WHERE id = ?";
        $stmt = $conexao->prepare($update_query);
        $stmt->bind_param("i", $evento_id);
        if ($stmt->execute()) {
            echo "Evento inativado com sucesso.";
        } else {
            echo "Erro ao inativar o evento.";
        }
    } else {
        echo "Evento não encontrado.";
    }
} else {
    echo "Requisição inválida.";
}
?>
