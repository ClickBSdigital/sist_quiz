<?php
class Log {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Registrar ação realizada por um usuário
    public function registrarAcao($usuario_id, $acao) {
        $sql = "INSERT INTO logs (usuario_id, acao, timestamp) 
                VALUES (:usuario_id, :acao, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'usuario_id' => $usuario_id,
            'acao' => $acao
        ]);
    }

    // Buscar logs de ações feitas por um usuário
    public function buscarLogsPorUsuario($usuario_id, $limite = 50) {
        $sql = "SELECT l.id, l.acao, l.timestamp, u.nome AS usuario
                FROM logs l
                JOIN usuarios u ON l.usuario_id = u.id
                WHERE l.usuario_id = :usuario_id
                ORDER BY l.timestamp DESC
                LIMIT :limite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar todos os logs no sistema
    public function buscarTodosLogs($limite = 50) {
        $sql = "SELECT l.id, l.acao, l.timestamp, u.nome AS usuario
                FROM logs l
                JOIN usuarios u ON l.usuario_id = u.id
                ORDER BY l.timestamp DESC
                LIMIT :limite";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Deletar log
    public function deletarLog($log_id) {
        $sql = "DELETE FROM logs WHERE id = :log_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['log_id' => $log_id]);
    }
}
?>
