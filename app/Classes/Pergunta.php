<?php
class Pergunta {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Adicionar uma nova pergunta ao evento
    public function adicionarPergunta($evento_id, $enunciado, $resposta_correta, $resposta_errada1, $resposta_errada2, $resposta_errada3, $resposta_errada4) {
        $sql = "INSERT INTO perguntas (evento_id, enunciado, resposta_correta, resposta_errada1, resposta_errada2, resposta_errada3, resposta_errada4) 
                VALUES (:evento_id, :enunciado, :resposta_correta, :resposta_errada1, :resposta_errada2, :resposta_errada3, :resposta_errada4)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'evento_id' => $evento_id,
            'enunciado' => $enunciado,
            'resposta_correta' => $resposta_correta,
            'resposta_errada1' => $resposta_errada1,
            'resposta_errada2' => $resposta_errada2,
            'resposta_errada3' => $resposta_errada3,
            'resposta_errada4' => $resposta_errada4
        ]);
    }

    // Buscar todas as perguntas de um evento
    public function buscarPerguntasDoEvento($evento_id) {
        $sql = "SELECT id, enunciado, resposta_correta, resposta_errada1, resposta_errada2, resposta_errada3, resposta_errada4
                FROM perguntas WHERE evento_id = :evento_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['evento_id' => $evento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar uma pergunta específica
    public function buscarPergunta($id) {
        $sql = "SELECT id, enunciado, resposta_correta, resposta_errada1, resposta_errada2, resposta_errada3, resposta_errada4
                FROM perguntas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualizar uma pergunta existente
    public function atualizarPergunta($id, $enunciado, $resposta_correta, $resposta_errada1, $resposta_errada2, $resposta_errada3, $resposta_errada4) {
        $sql = "UPDATE perguntas SET enunciado = :enunciado, 
                                      resposta_correta = :resposta_correta,
                                      resposta_errada1 = :resposta_errada1,
                                      resposta_errada2 = :resposta_errada2,
                                      resposta_errada3 = :resposta_errada3,
                                      resposta_errada4 = :resposta_errada4
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'enunciado' => $enunciado,
            'resposta_correta' => $resposta_correta,
            'resposta_errada1' => $resposta_errada1,
            'resposta_errada2' => $resposta_errada2,
            'resposta_errada3' => $resposta_errada3,
            'resposta_errada4' => $resposta_errada4
        ]);
    }

    // Excluir uma pergunta
    public function excluirPergunta($id) {
        $sql = "DELETE FROM perguntas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
?>
