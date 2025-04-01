<?php
class Chat {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Enviar mensagem no chat (pode ser individual ou em grupo)
    public function enviarMensagem($evento_id, $usuario_id, $mensagem) {
        $sql = "INSERT INTO chat (evento_id, usuario_id, mensagem, created_at) 
                VALUES (:evento_id, :usuario_id, :mensagem, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'evento_id' => $evento_id,
            'usuario_id' => $usuario_id,
            'mensagem' => $mensagem
        ]);
    }

    // Buscar mensagens do chat de um evento
    public function buscarMensagensDoEvento($evento_id, $limite = 50) {
        $sql = "SELECT c.id, c.mensagem, c.created_at, u.nome AS usuario
                FROM chat c
                JOIN usuarios u ON c.usuario_id = u.id
                WHERE c.evento_id = :evento_id
                ORDER BY c.created_at DESC
                LIMIT :limite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':evento_id', $evento_id, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Deletar mensagem do chat
    public function deletarMensagem($mensagem_id) {
        $sql = "DELETE FROM chat WHERE id = :mensagem_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['mensagem_id' => $mensagem_id]);
    }
}
?>
