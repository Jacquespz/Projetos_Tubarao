<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$movimentacao_id = $_GET['id'];

$stmt = $conn->prepare("SELECT m.id, b.numero AS berco, fo.nome AS filial_origem, fd.nome AS filial_destino, m.placa_caminhao, m.nome_motorista, u.nome AS usuario, m.data_movimentacao 
                         FROM movimentacoes m 
                         JOIN bercos b ON m.berco_id = b.id 
                         JOIN filiais fo ON m.filial_origem_id = fo.id 
                         JOIN filiais fd ON m.filial_destino_id = fd.id 
                         JOIN usuarios u ON m.usuario_id = u.id 
                         WHERE m.id = ?");
$stmt->bind_param("i", $movimentacao_id);
$stmt->execute();
$result = $stmt->get_result();
$movimentacao = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Movimentação</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Recibo de Movimentação</h1>
        <p><strong>ID da Movimentação:</strong> <?php echo $movimentacao['id']; ?></p>
        <p><strong>Berço:</strong> <?php echo $movimentacao['berco']; ?></p>
        <p><strong>Filial de Origem:</strong> <?php echo $movimentacao['filial_origem']; ?></p>
        <p><strong>Filial de Destino:</strong> <?php echo $movimentacao['filial_destino']; ?></p>
        <p><strong>Placa do Caminhão:</strong> <?php echo $movimentacao['placa_caminhao']; ?></p>
        <p><strong>Nome do Motorista:</strong> <?php echo $movimentacao['nome_motorista']; ?></p>
        <p><strong>Usuário:</strong> <?php echo $movimentacao['usuario']; ?></p>
        <p><strong>Data:</strong> <?php echo $movimentacao['data_movimentacao']; ?></p>
        <button onclick="window.print()">Imprimir Recibo</button>
    </div>
</body>
</html>
