<?php
session_start();
include 'db.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_filial = $_POST['nome_filial'];

    // Prepara e executa a inserção no banco de dados
    $stmt = $conn->prepare("INSERT INTO filiais (nome) VALUES (?)");
    $stmt->bind_param("s", $nome_filial);

    if ($stmt->execute()) {
        echo "Filial cadastrada com sucesso!";
    } else {
        echo "Erro ao cadastrar filial: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Filial</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!--start logo-->
    <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
        <h1>Cadastrar Filial</h1>
        <form action="cadastrar_filial.php" method="POST">
            <label for="nome_filial">Nome da Filial:</label>
            <input type="text" id="nome_filial" name="nome_filial" required>
            <button type="submit">Cadastrar</button>
            <li><a href="dashboard.php">Tela Inicial</a></li>
        </form>
    </div>
</body>
</html>
