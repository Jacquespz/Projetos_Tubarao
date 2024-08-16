<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$filial_origem_id = $_GET['filial_origem'];
$filial_destino_id = $_GET['filial_destino'];
$placa_caminhao = $_GET['placa_caminhao'];
$nome_motorista = $_GET['nome_motorista'];
$bercos = $_GET['bercos'];
$usuario = $_GET['usuario'];

$filial_origem_nome = '';
$filial_destino_nome = '';

$filial_origem_result = $conn->query("SELECT nome FROM filiais WHERE id = $filial_origem_id");
if ($filial_origem_row = $filial_origem_result->fetch_assoc()) {
    $filial_origem_nome = $filial_origem_row['nome'];
}

$filial_destino_result = $conn->query("SELECT nome FROM filiais WHERE id = $filial_destino_id");
if ($filial_destino_row = $filial_destino_result->fetch_assoc()) {
    $filial_destino_nome = $filial_destino_row['nome'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minuta de Envio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #000;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #000;
        }
        th {
            background-color: #f2f2f2;
        }
        .assinatura {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">

     <!--start logo-->
     <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
    <li><a href="dashboard.php">Tela Inicial</a></li>
    
        <h1>Minuta de Envio</h1>
        <table>
            <tr>
                <th>Filial Origem</th>
                <td><?php echo $filial_origem_nome; ?></td>
            </tr>
            <tr>
                <th>Filial Destino</th>
                <td><?php echo $filial_destino_nome; ?></td>
            </tr>
            <tr>
                <th>Placa do Caminhão</th>
                <td><?php echo $placa_caminhao; ?></td>
            </tr>
            <tr>
                <th>Nome do Motorista</th>
                <td><?php echo $nome_motorista; ?></td>
            </tr>
            <tr>
                <th>Número dos Berços</th>
                <td><?php echo $bercos; ?></td>
            </tr>
            <tr>
                <th>Nome do Usuário</th>
                <td><?php echo $usuario; ?></td>
            </tr>
        </table>
        <div class="assinatura">
            <p>Assinatura do Recebedor:</p>
            <p>_________________________________________</p>
        </div>
    </div>
</body>
</html>
