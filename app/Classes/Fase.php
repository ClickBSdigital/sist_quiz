<?php
class Fase {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Criar uma nova fase no evento
    public function criarFase($evento_id, $nome_fase, $descricao_fase, $premio_fase) {
        $sql = "INSERT INTO fases (evento_id, nome_fase, descricao_fase, premio_fase, created_at) 
                VALUES (:evento_id, :nome_fase, :descricao_fase, :premio_fase, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'evento_id' => $evento_id,
            'nome_fase' => $nome_fase,
            'descricao_fase' => $descricao_fase,
            'premio_fase' => $premio_fase
        ]);
    }

    // Buscar todas as fases de um evento
    public function buscarFasesDoEvento($evento_id) {
        $sql = "SELECT id, nome_fase, descricao_fase, premio_fase FROM fases WHERE evento_id = :evento_id ORDER BY id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['evento_id' => $evento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar uma fase específica
    public function buscarFasePorId($fase_id) {
        $sql = "SELECT * FROM fases WHERE id = :fase_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['fase_id' => $fase_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar informações da fase
    public function atualizarFase($fase_id, $nome_fase, $descricao_fase, $premio_fase) {
        $sql = "UPDATE fases SET nome_fase = :nome_fase, descricao_fase = :descricao_fase, premio_fase = :premio_fase WHERE id = :fase_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'fase_id' => $fase_id,
            'nome_fase' => $nome_fase,
            'descricao_fase' => $descricao_fase,
            'premio_fase' => $premio_fase
        ]);
    }

    // Deletar uma fase
    public function deletarFase($fase_id) {
        $sql = "DELETE FROM fases WHERE id = :fase_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['fase_id' => $fase_id]);
    }
}
?>
