<?php
// Inicia a sessão
session_start();

// Destroi todas as variáveis da sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona o usuário para a página de login ou página inicial
header('Location: ../../index.php'); // Ou para a página de login: 'Location: login.php'
exit();
?>
