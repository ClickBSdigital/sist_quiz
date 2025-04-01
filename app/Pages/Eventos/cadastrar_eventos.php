<?php
require_once 'Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $professor_id = $_POST['professor_id'] ?? '';

    if (!empty($nome) && !empty($professor_id)) {
        $sql = "INSERT INTO eventos (nome, professor_id) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("si", $nome, $professor_id);
        
        if ($stmt->execute()) {
            echo "Evento cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar evento.";
        }
        
        $stmt->close();
    } else {
        echo "Todos os campos são obrigatórios.";
    }
}

// Buscar professores cadastrados
$professores = $conexao->query("SELECT id, nome FROM usuarios WHERE tipo = 'professor'");
?>

<form method="POST" action="cadastrar_eventos.php">
    <label>Nome do Evento:</label>
    <input type="text" name="nome" required>
    <br>
    <label>Professor Responsável:</label>
    <select name="professor_id" required>
        <option value="">Selecione um professor</option>
        <?php while ($professor = $professores->fetch_assoc()): ?>
            <option value="<?= $professor['id'] ?>"><?= $professor['nome'] ?></option>
        <?php endwhile; ?>
    </select>
    <br>
    <button type="submit">Cadastrar Evento</button>
</form>
