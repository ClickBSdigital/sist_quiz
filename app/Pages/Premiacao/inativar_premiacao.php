<?php
require_once '../config/database.php'; // Ajuste o caminho conforme necessário

if (isset($_GET['id'])) {
    $premiacao_id = $_GET['id'];
    
    try {
        $sql = "UPDATE premiacoes SET ativa = 0 WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $premiacao_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $_SESSION['mensagem'] = "Premiação inativada com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Erro ao inativar premiação.";
        }
    } catch (PDOException $e) {
        $_SESSION['mensagem'] = "Erro no banco de dados: " . $e->getMessage();
    }
} else {
    $_SESSION['mensagem'] = "ID da premiação não especificado.";
}

header("Location: listar_premiacao.php");
exit;
?>
