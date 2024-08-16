<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!--start logo-->
        <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
               <!--end logo-->
        <h1>Bem-vindo, <?php echo $_SESSION['user_nome']; ?>!</h1>
        <a href="logout.php">Logout</a>

        <h2>Gerenciamento</h2>
        <ul>
            <li><a href="cadastrar_filial.php">Cadastrar Filial</a></li>
            <li><a href="cadastrar_berco.php">Cadastrar Berço</a></li>
            <li><a href="movimentacao.php">Registrar Movimentação</a></li>
            <li><a href="historico.php">Histórico de Movimentações</a></li>
            <li><a href="bercos_por_filial.php">Numeração dos Berços por Filial</a></li>
            <li><a href="saldo.php">Saldo de Berços por Filial</a></li>
            <li><a href="relatorio_minutas.php">Relatorio Minutas</a></li>
            <li><a href="relatorio_transito.php">Relatorio Transito</a></li>
        </ul>
    </div>
    <div class="autor">copyright &copy; 2024 - By Jacques Pereira</div>
</body>
</html>
