<?php
require_once 'proteger-admin.php';
require_once '../crud.php';

$cliente = $_GET["cliente"] ?? "";
$status = $_GET["status"] ?? "";

$sql = "SELECT
            pedido.*,
            usuario.nome AS nome_cliente
        FROM pedido
        INNER JOIN usuario
            ON pedido.id_usuario = usuario.id_usuario
        WHERE 1=1";

$params = [];

/* FILTRO POR CLIENTE */
if (!empty($cliente)) {
    $sql .= " AND usuario.nome LIKE ?";
    $params[] = "%" . $cliente . "%";
}

/* FILTRO POR STATUS */
if (!empty($status)) {
    $sql .= " AND pedido.status = ?";
    $params[] = $status;
}

$sql .= " ORDER BY pedido.id_pedido DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pedidos | Futuroteca</title>

    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/pedidos-adm.css">
</head>

<body>

    <?php include 'partials/sidebar.php'; ?>

    <main class="conteudo">

        <div class="titulo-dashboard">
            <h1>Pedidos</h1>
            <p>Gerencie os pedidos realizados na Futuroteca.</p>
        </div>

        <form method="GET" class="filtros-produtos">

            <input type="text" name="cliente" placeholder="Buscar por cliente..."
                value="<?= htmlspecialchars($cliente) ?>">

            <select name="status">

                <option value="">
                    Todos os status
                </option>

                <option value="PENDENTE" <?= $status === "PENDENTE" ? "selected" : "" ?>>
                    Pendente
                </option>

                <option value="PAGO" <?= $status === "PAGO" ? "selected" : "" ?>>
                    Pago
                </option>

                <option value="ENVIADO" <?= $status === "ENVIADO" ? "selected" : "" ?>>
                    Enviado
                </option>

                <option value="ENTREGUE" <?= $status === "ENTREGUE" ? "selected" : "" ?>>
                    Entregue
                </option>

                <option value="CANCELADO" <?= $status === "CANCELADO" ? "selected" : "" ?>>
                    Cancelado
                </option>

            </select>

            <button type="submit" class="btn-filtrar">
                Filtrar
            </button>

            <a href="pedidos.php" class="btn-limpar">
                Limpar
            </a>

        </form>

        <div class="painel-dashboard">

            <div class="painel-titulo">
                <h3>Pedidos realizados</h3>
            </div>

            <table>

                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Valor</th>
                        <th>Entrega</th>
                        <th>Frete</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($pedidos) == 0): ?>

                        <tr>
                            <td colspan="8">
                                Nenhum pedido realizado.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($pedidos as $pedido): ?>

                            <tr>

                                <td>
                                    #<?= $pedido["id_pedido"] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pedido["nome_cliente"]) ?>
                                </td>

                                <td>
                                    <?= date("d/m/Y H:i", strtotime($pedido["data_pedido"])) ?>
                                </td>

                                <td>
                                    R$ <?= number_format($pedido["valor_total"], 2, ",", ".") ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pedido["tipo_entrega"]) ?>
                                </td>

                                <td>
                                    R$ <?= number_format($pedido["frete"], 2, ",", ".") ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pedido["status"]) ?>
                                </td>
                                <td>

                                    <form action="atualizar-status-pedido.php" method="POST">

                                        <input type="hidden" name="id_pedido" value="<?= $pedido["id_pedido"] ?>">

                                        <select name="status">

                                            <option value="PENDENTE" <?= $pedido["status"] == "PENDENTE" ? "selected" : "" ?>>
                                                Pendente
                                            </option>

                                            <option value="PAGO" <?= $pedido["status"] == "PAGO" ? "selected" : "" ?>>
                                                Pago
                                            </option>

                                            <option value="ENVIADO" <?= $pedido["status"] == "ENVIADO" ? "selected" : "" ?>>
                                                Enviado
                                            </option>

                                            <option value="ENTREGUE" <?= $pedido["status"] == "ENTREGUE" ? "selected" : "" ?>>
                                                Entregue
                                            </option>

                                            <option value="CANCELADO" <?= $pedido["status"] == "CANCELADO" ? "selected" : "" ?>>
                                                Cancelado
                                            </option>

                                        </select>

                                        <button type="submit">
                                            Atualizar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </main>

</body>

</html>