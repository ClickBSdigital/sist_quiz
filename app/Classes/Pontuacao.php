<?php
class Pontuacao {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Registrar ou atualizar a pontuação de um participante
    public function registrarPontuacaoParticipante($participante_id, $evento_id, $pontos) {
        $sql = "INSERT INTO pontuacoes (participante_id, evento_id, pontos) 
                VALUES (:participante_id, :evento_id, :pontos) 
                ON DUPLICATE KEY UPDATE pontos = pontos + :pontos";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'participante_id' => $participante_id,
            'evento_id' => $evento_id,
            'pontos' => $pontos
        ]);
    }

    // Buscar a pontuação total de um participante em um evento
    public function buscarPontuacaoParticipante($participante_id, $evento_id) {
        $sql = "SELECT pontos FROM pontuacoes WHERE participante_id = :participante_id AND evento_id = :evento_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['participante_id' => $participante_id, 'evento_id' => $evento_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['pontos'] : 0;
    }

    // Buscar a pontuação total de uma equipe em um evento
    public function buscarPontuacaoEquipe($equipe_id, $evento_id) {
        $sql = "SELECT SUM(p.pontos) AS total_pontos 
                FROM pontuacoes p
                JOIN participantes pa ON p.participante_id = pa.id
                WHERE pa.equipe_id = :equipe_id AND p.evento_id = :evento_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['equipe_id' => $equipe_id, 'evento_id' => $evento_id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['total_pontos'] : 0;
    }

    // Buscar ranking de participantes no evento
    public function buscarRankingParticipantes($evento_id) {
        $sql = "SELECT u.nome, p.pontos 
                FROM pontuacoes p
                JOIN participantes pa ON p.participante_id = pa.id
                JOIN usuarios u ON pa.usuario_id = u.id
                WHERE p.evento_id = :evento_id
                ORDER BY p.pontos DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['evento_id' => $evento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar ranking de equipes no evento
    public function buscarRankingEquipes($evento_id) {
        $sql = "SELECT e.nome, SUM(p.pontos) AS total_pontos 
                FROM pontuacoes p
                JOIN participantes pa ON p.participante_id = pa.id
                JOIN equipes e ON pa.equipe_id = e.id
                WHERE p.evento_id = :evento_id
                GROUP BY e.id
                ORDER BY total_pontos DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['evento_id' => $evento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
