<?php
class Premiacao {
    private $pdo;

    // Construtor para inicializar a conexão com o banco de dados
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para registrar uma premiação
    public function registrarPremiacao($equipe_id, $fase, $premio) {
        $sql = "INSERT INTO premiacoes (equipe_id, fase, premio, data_premiacao) 
                VALUES (:equipe_id, :fase, :premio, NOW())";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':equipe_id', $equipe_id);
        $stmt->bindParam(':fase', $fase);
        $stmt->bindParam(':premio', $premio);

        return $stmt->execute();
    }

    // Método para buscar todas as premiações de uma equipe
    public function buscarPremiacoesEquipe($equipe_id) {
        $sql = "SELECT fase, premio, data_premiacao FROM premiacoes WHERE equipe_id = :equipe_id ORDER BY fase ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['equipe_id' => $equipe_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para buscar premiação de uma equipe em uma fase específica
    public function buscarPremiacaoPorFase($equipe_id, $fase) {
        $sql = "SELECT premio FROM premiacoes WHERE equipe_id = :equipe_id AND fase = :fase";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['equipe_id' => $equipe_id, 'fase' => $fase]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado['premio'] : null;
    }

    // Método para gerar uma mensagem automática quando uma equipe passa de fase
    public function mensagemParabens($equipe_id, $fase) {
        $premio = $this->buscarPremiacaoPorFase($equipe_id, $fase);
        if ($premio) {
            return "🎉 Parabéns, Equipe $equipe_id! Vocês passaram para a fase $fase e desbloquearam: $premio!";
        }
        return "⏳ Aguardando premiação...";
    }
}
?>
