<?php
require_once 'Database.php';

if (!isset($_GET['id'])) {
    die("ID do evento não fornecido.");
}

$id = $_GET['id'];
$db = new Database();
$conexao = $db->getConnection();

// Buscar o evento pelo ID
$query = "SELECT * FROM eventos WHERE id = ?";
$stmt = $conexao->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$evento = $result->fetch_assoc();

if (!$evento) {
    die("Evento não encontrado.");
}

// Buscar todos os professores para o select
$queryProfessores = "SELECT id, nome FROM usuarios WHERE tipo = 'professor'";
$resultProfessores = $conexao->query($queryProfessores);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'] ?? '';
    $professor_id = $_POST['professor_id'] ?? '';
    
    if (!empty($nome) && !empty($professor_id)) {
        $queryUpdate = "UPDATE eventos SET nome = ?, professor_id = ? WHERE id = ?";
        $stmt = $conexao->prepare($queryUpdate);
        $stmt->bind_param("sii", $nome, $professor_id, $id);
        
        if ($stmt->execute()) {
            echo "Evento atualizado com sucesso!";
        } else {
            echo "Erro ao atualizar o evento.";
        }
    } else {
        echo "Todos os campos são obrigatórios.";
    }
}
?>

<form method="POST" action="editar_eventos.php?id=<?= $id ?>">
    <label>Nome do Evento:</label>
    <input type="text" name="nome" value="<?= htmlspecialchars($evento['nome']) ?>" required>
    <br>
    <label>Professor Responsável:</label>
    <select name="professor_id" required>
        <?php while ($professor = $resultProfessores->fetch_assoc()) { ?>
            <option value="<?= $professor['id'] ?>" <?= $professor['id'] == $evento['professor_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($professor['nome']) ?>
            </option>
        <?php } ?>
    </select>
    <br>
    <button type="submit">Atualizar Evento</button>
</form>
