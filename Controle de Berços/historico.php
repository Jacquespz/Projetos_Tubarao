<?php
include 'db.php';

$filialFilter = isset($_GET['filial']) ? $_GET['filial'] : '';
$bercoFilter = isset($_GET['berco']) ? $_GET['berco'] : '';

$sql = "SELECT 
            m.numero_minuta, 
            b.numero AS numero_berco, 
            f_origem.nome AS filial_origem, 
            f_destino.nome AS filial_destino,
            mov.placa_caminhao, 
            mov.nome_motorista, 
            u.nome AS usuario_envio, 
            u2.nome AS usuario_recebimento, 
            mov.data_movimentacao AS data_envio, 
            mov2.data_movimentacao AS data_recebimento
        FROM minutas m
        JOIN movimentacoes mov ON m.movimentacao_id = mov.id
        LEFT JOIN movimentacoes mov2 ON mov2.berco_id = mov.berco_id AND mov2.tipo = 'recebimento' AND mov2.filial_destino_id = mov.filial_destino_id
        JOIN bercos b ON mov.berco_id = b.id
        LEFT JOIN filiais f_origem ON mov.filial_origem_id = f_origem.id
        LEFT JOIN filiais f_destino ON mov.filial_destino_id = f_destino.id
        LEFT JOIN usuarios u ON mov.usuario_id = u.id
        LEFT JOIN usuarios u2 ON mov2.usuario_id = u2.id
        WHERE mov.tipo = 'envio'";

if ($filialFilter) {
    $sql .= " AND (mov.filial_origem_id = '$filialFilter' OR mov.filial_destino_id = '$filialFilter')";
}

if ($bercoFilter) {
    $sql .= " AND b.numero = '$bercoFilter'";
}

$sql .= " ORDER BY mov.id DESC"; // Ordena os resultados em ordem decrescente pelo ID da movimentação

$result = $conn->query($sql);

$historico = [];
while ($row = $result->fetch_assoc()) {
    $historico[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Movimentações</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>



    <div class="container">
               <!--start logo-->
               <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
               <!--end logo-->
               <li><a href="dashboard.php">Tela Inicial</a></li>
               <h1>Histórico de Movimentações</h1>
         <table>
            <thead>
                <tr>
                    <th>Número da Minuta</th>
                    <th>Número do Berço</th>
                    <th>Filial de Origem</th>
                    <th>Filial de Destino</th>
                    <th>Placa do Caminhão</th>
                    <th>Nome do Motorista</th>
                    <th>Usuário Envio</th>
                    <th>Data Envio</th>
                    <th>Usuário Recebimento</th>
                    <th>Data Recebimento</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $mov): ?>
                    <tr>
                        <td><?php echo $mov['numero_minuta']; ?></td>
                        <td><?php echo $mov['numero_berco']; ?></td>
                        <td><?php echo $mov['filial_origem']; ?></td>
                        <td><?php echo $mov['filial_destino']; ?></td>
                        <td><?php echo $mov['placa_caminhao']; ?></td>
                        <td><?php echo $mov['nome_motorista']; ?></td>
                        <td><?php echo $mov['usuario_envio']; ?></td>
                        <td><?php echo $mov['data_envio']; ?></td>
                        <td><?php echo $mov['usuario_recebimento']; ?></td>
                        <td><?php echo $mov['data_recebimento']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
