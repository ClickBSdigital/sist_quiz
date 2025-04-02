<?php
session_start();
require './app/DB/Database.php';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Quiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="./app/assets/js/indexgaund.js" defer></script>
    <link rel="stylesheet" href="./app/assets/css/nav.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Contact</a></li>
            
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <li><a href="./app/Pages/Login/logout.php">Logout</a></li>
                <?php if ($_SESSION['usuario_tipo'] == 'professor'): ?>
                    <li><a href="./app/Pages/painel_professor.php">Painel Professor</a></li>
                <?php else: ?>
                    <li><a href="./app/Pages/painel_aluno.php">Painel Aluno</a></li>
                <?php endif; ?>
            <?php else: ?>
                <li><a href="./app/Pages/Login/login.php">Login</a></li>
                <li><a href="./app/Pages/Login/login.php">Cadastro</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="wrapper">
        <div class="page-header section-dark" style="background-image: url('http://demos.creative-tim.com/paper-kit-2/assets/img/antoine-barres.jpg')">
          <div class="filter"></div>
    		  <div class="content-center">
    			<div class="container">
    				<div class="title-brand">
    					<h1 class="presentation-title">Sistema de Perguntas</h1>
    				<div class="fog-low">
    					<img src="http://demos.creative-tim.com/paper-kit-2/assets/img/fog-low.png" alt="">
    				</div>
    				<div class="fog-low right">
    					<img src="http://demos.creative-tim.com/paper-kit-2/assets/img/fog-low.png" alt="">
    				</div>
    			</div>
    				<h2 class="presentation-subtitle text-center">O SistQuiz é um sistema de gestão de competições educativas baseado em perguntas e respostas.</h2>
    			</div>
    		</div>
          <div class="moving-clouds" style="background-image: url('http://demos.creative-tim.com/paper-kit-2/assets/img/clouds.png'); ">
          </div>
    	</div>
  </div>
  <?php
include './app/Pages/footer.html';
?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>