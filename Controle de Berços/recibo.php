<?php
include 'db.php';

$id = $_GET['id'];

$sql = "SELECT 
            m.numero_minuta, 
            f_origem.nome AS filial_origem, 
            f_destino.nome AS filial_destino,
            m.placa_caminhao, 
            m.nome_motorista, 
            GROUP_CONCAT(b.numero ORDER BY b.numero ASC SEPARATOR ', ') AS numero_bercos,
            u.nome AS usuario_nome,
            m.data_criacao,
            m.observacoes
        FROM minutas m
        JOIN movimentacoes mov ON m.movimentacao_id = mov.id
        JOIN bercos b ON mov.berco_id = b.id
        LEFT JOIN filiais f_origem ON mov.filial_origem_id = f_origem.id
        LEFT JOIN filiais f_destino ON mov.filial_destino_id = f_destino.id
        LEFT JOIN usuarios u ON mov.usuario_id = u.id
        WHERE m.numero_minuta = ?
        GROUP BY m.numero_minuta, f_origem.nome, f_destino.nome, m.placa_caminhao, m.nome_motorista, u.nome, m.data_criacao, m.observacoes";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$minuta = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Envio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .minuta-info {
            margin-bottom: 20px;
        }
        .minuta-info p {
            margin: 10px 0;
            color: #555;
            line-height: 1.6;
        }
        .minuta-info p strong {
            color: #000;
        }
        .minuta-info p .highlight {
            font-size: 1.2em;
            font-weight: bold;
            color: #000;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        .signature {
            text-align: center;
            width: 45%;
        }
        .signature p {
            margin: 40px 0 10px;
            border-top: 1px solid #000;
            width: 100%;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        .print-button button {
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .print-button button:hover {
            background-color: #218838;
        }
        @media print {
            .print-button {
                display: none;
            }
            .container {
                box-shadow: none;
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recibo de Envio</h1>
        <div class="minuta-info">
            <p><strong>Número da Minuta:</strong> <span class="highlight"><?php echo $minuta['numero_minuta']; ?></span></p>
            <p><strong>Filial Origem:</strong> <?php echo $minuta['filial_origem']; ?></p>
            <p><strong>Filial Destino:</strong> <?php echo $minuta['filial_destino']; ?></p>
            <p><strong>Placa do Caminhão:</strong> <?php echo $minuta['placa_caminhao']; ?></p>
            <p><strong>Nome do Motorista:</strong> <?php echo $minuta['nome_motorista']; ?></p>
            <p><strong>Número dos Berços:</strong> <span class="highlight"><?php echo $minuta['numero_bercos']; ?></span></p>
            <p><strong>Nome do Usuário:</strong> <?php echo $minuta['usuario_nome']; ?></p>
            <p><strong>Data da Criação:</strong> <?php echo date('d/m/Y H:i:s', strtotime($minuta['data_criacao'])); ?></p>
            <p><strong>Observações:</strong> <?php echo nl2br($minuta['observacoes']); ?></p>
        </div>
        <div class="signatures">
            <div class="signature">
                <p>Assinatura do Motorista</p>
            </div>
            <div class="signature">
                <p>Assinatura do Recebedor</p>
            </div>
        </div>
        <div class="print-button">
            <button onclick="window.print()">Imprimir</button>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
