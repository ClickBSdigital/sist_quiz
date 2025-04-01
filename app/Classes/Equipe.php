<?php
class Equipe {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Criar uma nova equipe
    public function criarEquipe($nome, $evento_id) {
        $sql = "INSERT INTO equipes (nome, evento_id) VALUES (:nome, :evento_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['nome' => $nome, 'evento_id' => $evento_id]);
    }

    // Buscar equipe por ID
    public function buscarEquipePorId($id) {
        $sql = "SELECT id, nome, evento_id, created_at FROM equipes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar todas as equipes de um evento
    public function buscarEquipesPorEvento($evento_id) {
        $sql = "SELECT id, nome, evento_id, created_at FROM equipes WHERE evento_id = :evento_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['evento_id' => $evento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualizar nome da equipe
    public function atualizarEquipe($id, $nome) {
        $sql = "UPDATE equipes SET nome = :nome WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'nome' => $nome]);
    }

    // Excluir equipe
    public function excluirEquipe($id) {
        $sql = "DELETE FROM equipes WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Buscar participantes de uma equipe
    public function buscarParticipantesDaEquipe($id) {
        $sql = "SELECT u.id, u.nome, u.email
                FROM participantes p
                JOIN usuarios u ON p.usuario_id = u.id
                WHERE p.equipe_id = :equipe_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['equipe_id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
