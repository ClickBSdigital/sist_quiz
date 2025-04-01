<?php
class Participante {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Adicionar um participante a uma equipe
    public function adicionarParticipante($usuario_id, $equipe_id) {
        // Verifica se o usuário já está na equipe
        $sql = "SELECT COUNT(*) FROM participantes WHERE usuario_id = :usuario_id AND equipe_id = :equipe_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id, 'equipe_id' => $equipe_id]);
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            return false; // O usuário já é um participante da equipe
        }

        // Caso o usuário não esteja, adiciona-o à equipe
        $sql = "INSERT INTO participantes (usuario_id, equipe_id) VALUES (:usuario_id, :equipe_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['usuario_id' => $usuario_id, 'equipe_id' => $equipe_id]);
    }

    // Buscar todos os participantes de uma equipe
    public function buscarParticipantesDaEquipe($equipe_id) {
        $sql = "SELECT u.id, u.nome, u.email
                FROM participantes p
                JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.equipe_id = :equipe_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['equipe_id' => $equipe_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar a equipe de um participante
    public function buscarEquipeDoParticipante($usuario_id) {
        $sql = "SELECT e.id, e.nome, e.evento_id
                FROM participantes p
                JOIN equipes e ON p.equipe_id = e.id
                WHERE p.usuario_id = :usuario_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Excluir um participante de uma equipe
    public function excluirParticipante($usuario_id, $equipe_id) {
        $sql = "DELETE FROM participantes WHERE usuario_id = :usuario_id AND equipe_id = :equipe_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['usuario_id' => $usuario_id, 'equipe_id' => $equipe_id]);
    }
}
?>
