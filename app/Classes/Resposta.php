<?php
class Resposta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Registrar a resposta de um participante
    public function registrarResposta($participante_id, $pergunta_id, $resposta_escolhida, $correta) {
        $sql = "INSERT INTO respostas (participante_id, pergunta_id, resposta_escolhida, correta) 
                VALUES (:participante_id, :pergunta_id, :resposta_escolhida, :correta)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'participante_id' => $participante_id,
            'pergunta_id' => $pergunta_id,
            'resposta_escolhida' => $resposta_escolhida,
            'correta' => $correta
        ]);
    }

    // Buscar todas as respostas de um participante em um evento
    public function buscarRespostasDoParticipante($participante_id, $evento_id) {
        $sql = "SELECT r.id, r.pergunta_id, p.enunciado, r.resposta_escolhida, r.correta 
                FROM respostas r
                JOIN perguntas p ON r.pergunta_id = p.id
                WHERE r.participante_id = :participante_id 
                AND p.evento_id = :evento_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['participante_id' => $participante_id, 'evento_id' => $evento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Verificar se a resposta de um participante está correta
    public function verificarResposta($pergunta_id, $resposta_escolhida) {
        $sql = "SELECT resposta_correta FROM perguntas WHERE id = :pergunta_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pergunta_id' => $pergunta_id]);
        $pergunta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pergunta) {
            return $pergunta['resposta_correta'] === $resposta_escolhida;
        }
        return false;
    }

    // Contar o número de respostas corretas de um participante em um evento
    public function contarRespostasCorretas($participante_id, $evento_id) {
        $sql = "SELECT COUNT(*) AS total_corretas 
                FROM respostas r
                JOIN perguntas p ON r.pergunta_id = p.id
                WHERE r.participante_id = :participante_id 
                AND p.evento_id = :evento_id 
                AND r.correta = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['participante_id' => $participante_id, 'evento_id' => $evento_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total_corretas'];
    }
}
?>
