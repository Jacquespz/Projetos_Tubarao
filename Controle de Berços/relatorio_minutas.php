<?php
include 'db.php';

$sql = "SELECT 
            m.numero_minuta, 
            f_origem.nome AS filial_origem, 
            f_destino.nome AS filial_destino,
            mov.placa_caminhao, 
            mov.nome_motorista, 
            GROUP_CONCAT(DISTINCT b.numero ORDER BY b.numero ASC SEPARATOR ', ') AS numero_bercos,
            mov.data_movimentacao AS data_envio,
            mov2.data_movimentacao AS data_recebimento,
            IF(mov2.id IS NULL, 'Em Trânsito', 'Recebido') AS status
        FROM minutas m
        JOIN movimentacoes mov ON m.movimentacao_id = mov.id
        LEFT JOIN movimentacoes mov2 ON mov2.berco_id = mov.berco_id AND mov2.tipo = 'recebimento' AND mov2.filial_destino_id = mov.filial_destino_id
        JOIN bercos b ON mov.berco_id = b.id
        LEFT JOIN filiais f_origem ON mov.filial_origem_id = f_origem.id
        LEFT JOIN filiais f_destino ON mov.filial_destino_id = f_destino.id
        GROUP BY m.numero_minuta, f_origem.nome, f_destino.nome, mov.placa_caminhao, mov.nome_motorista, mov.data_movimentacao, mov2.data_movimentacao
        ORDER BY m.numero_minuta DESC";

$result = $conn->query($sql);

$minutas = [];
while ($row = $result->fetch_assoc()) {
    $minutas[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Minutas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <!--start logo-->
 <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->
    <li><a href="dashboard.php">Tela Inicial</a></li>
    
        <h1>Relatório de Minutas</h1>
        <table>
            <thead>
                <tr>
                    <th>Número da Minuta</th>
                    <th>Filial Origem</th>
                    <th>Filial Destino</th>
                    <th>Placa do Caminhão</th>
                    <th>Nome do Motorista</th>
                    <th>Número dos Berços</th>
                    <th>Data de Envio</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($minutas as $minuta): ?>
                    <tr>
                        <td><?php echo $minuta['numero_minuta']; ?></td>
                        <td><?php echo $minuta['filial_origem']; ?></td>
                        <td><?php echo $minuta['filial_destino']; ?></td>
                        <td><?php echo $minuta['placa_caminhao']; ?></td>
                        <td><?php echo $minuta['nome_motorista']; ?></td>
                        <td><?php echo $minuta['numero_bercos']; ?></td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($minuta['data_envio'])); ?></td>
                        <td><?php echo $minuta['status']; ?></td>
                        <td><a href="minuta_envio.php?numero_minuta=<?php echo $minuta['numero_minuta']; ?>" target="_blank">Reimprimir</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
