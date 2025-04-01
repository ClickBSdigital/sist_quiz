<?php
class Evento {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Criar um novo evento
    public function criarEvento($nome, $professor_id) {
        $sql = "INSERT INTO eventos (nome, professor_id) VALUES (:nome, :professor_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['nome' => $nome, 'professor_id' => $professor_id]);
    }

    // Buscar evento por ID
    public function buscarEventoPorId($id) {
        $sql = "SELECT id, nome, professor_id, created_at FROM eventos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar todos os eventos de um professor
    public function buscarEventosPorProfessor($professor_id) {
        $sql = "SELECT id, nome, professor_id, created_at FROM eventos WHERE professor_id = :professor_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['professor_id' => $professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualizar dados do evento
    public function atualizarEvento($id, $nome) {
        $sql = "UPDATE eventos SET nome = :nome WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id, 'nome' => $nome]);
    }

    // Excluir evento (esta operação pode ser evitada dependendo dos requisitos)
    public function excluirEvento($id) {
        $sql = "DELETE FROM eventos WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Buscar evento com suas respectivas equipes (opcional)
    public function buscarEventoComEquipes($id) {
        $sql = "SELECT e.id, e.nome AS evento_nome, eq.id AS equipe_id, eq.nome AS equipe_nome
                FROM eventos e
                LEFT JOIN equipes eq ON e.id = eq.evento_id
                WHERE e.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
