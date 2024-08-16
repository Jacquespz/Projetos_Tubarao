<?php
include 'db.php';

$sql = "SELECT 
            m.numero_minuta, 
            b.numero AS numero_berco, 
            f_origem.nome AS filial_origem, 
            f_destino.nome AS filial_destino,
            mov.placa_caminhao, 
            mov.nome_motorista, 
            mov.data_movimentacao AS data_envio
        FROM minutas m
        JOIN movimentacoes mov ON m.movimentacao_id = mov.id
        JOIN bercos b ON mov.berco_id = b.id
        LEFT JOIN filiais f_origem ON mov.filial_origem_id = f_origem.id
        LEFT JOIN filiais f_destino ON mov.filial_destino_id = f_destino.id
        WHERE mov.tipo = 'envio' 
        AND b.filial_id IS NULL
        ORDER BY mov.data_movimentacao DESC";

$result = $conn->query($sql);

$transito = [];
while ($row = $result->fetch_assoc()) {
    $transito[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Berços em Trânsito</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <!--start logo-->
    <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
    <li><a href="dashboard.php">Tela Inicial</a></li>
        <h1>Relatório de Berços em Trânsito</h1>
        <table>
            <thead>
                <tr>
                    <th>Número da Minuta</th>
                    <th>Número do Berço</th>
                    <th>Filial de Origem</th>
                    <th>Filial de Destino</th>
                    <th>Placa do Caminhão</th>
                    <th>Nome do Motorista</th>
                    <th>Data de Envio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transito as $item): ?>
                    <tr>
                        <td><?php echo $item['numero_minuta']; ?></td>
                        <td><?php echo $item['numero_berco']; ?></td>
                        <td><?php echo $item['filial_origem']; ?></td>
                        <td><?php echo $item['filial_destino']; ?></td>
                        <td><?php echo $item['placa_caminhao']; ?></td>
                        <td><?php echo $item['nome_motorista']; ?></td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($item['data_envio'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
