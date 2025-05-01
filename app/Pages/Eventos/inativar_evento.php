<?php
session_start();
require_once '../../DB/Database.php';

// Habilita logs para debugging (remova em produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho JSON para evitar erros de encoding
header('Content-Type: application/json');

// Testa se os dados foram recebidos corretamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['evento_id'])) {
    $evento_id = intval($_POST['evento_id']);

    // Verifica se o evento_id é válido
    if ($evento_id <= 0) {
        echo json_encode(["status" => "error", "message" => "ID do evento inválido."]);
        exit();
    }

    // Conecta ao banco de dados
    $db = new Database('eventos');

    // Verifica se o evento existe
    $evento = $db->select("id = ?", null, null, '*', [$evento_id]);

    if (!$evento) {
        echo json_encode(["status" => "error", "message" => "Evento não encontrado."]);
        exit();
    }

    // Atualiza o status do evento para inativo (0)
    $atualizado = $db->update(['ativo' => 0], "id = ?", [$evento_id]);

    if ($atualizado) {
        echo json_encode(["status" => "success", "message" => "Evento inativado com sucesso."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao inativar o evento."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Requisição inválida."]);
}
?>
