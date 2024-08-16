<?php
include 'db.php';

// Consulta para obter o saldo de berços por filial
$sql = "SELECT f.id, f.nome, COUNT(b.id) AS total_bercos
        FROM filiais f
        LEFT JOIN bercos b ON f.id = b.filial_id
        GROUP BY f.id, f.nome";
$result = $conn->query($sql);

$total_bercos_geral = 0;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saldo de Berços por Filial</title>
     
    <link rel="stylesheet" href="styles.css">
    <style>
      
        .total-geral {
            text-align: right;
        }
        .highlight {
            color: #00d4ff;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
<div class="container">
    
    <!--start logo-->
     <img src="image/logo1.png" width="230" height="90" alt="logo"/>   
    <!--end logo-->

    <li><a href="dashboard.php">Tela Inicial</a></li>

        <h1>Saldo de Berços por Filial</h1>
        <table>
            <thead>
                <tr>
                    <th>Filial</th>
                    <th>Total de Berços</th>
                    <th>Total de Jogos</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['total_bercos']; ?></td>
                        <td><?php echo floor($row['total_bercos'] / 2); ?></td>
                    </tr>
                    <?php $total_bercos_geral += $row['total_bercos']; ?>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td class="total-geral">Total Geral</td>
                    <td class="highlight"><?php echo $total_bercos_geral; ?></td>
                    <td class="highlight"><?php echo floor($total_bercos_geral / 2); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
<?php
$conn->close();
?>
