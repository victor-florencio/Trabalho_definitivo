<?php
include_once("code/conexao.php");
include_once("code/loginC.php"); 

session_start();
$id_usuario = $_SESSION['id']; 

$sql = "SELECT * FROM pedidos WHERE id_user = $id_usuario";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Meus Pedidos</title>
    <style>
        #chat { border: 1px solid #ccc; height: 150px; overflow-y: auto; padding: 10px; margin-top: 20px; }
        .user { color: blue; margin-bottom: 5px; }
        .bot { color: green; margin-bottom: 5px; }
        table, th, td { border: 1px solid black; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 5px; }
        #input, #btn { margin-top: 10px; font-size: 16px; padding: 5px; }
        #input { width: 300px; }
    </style>
    <script src="https://unpkg.com/compromise"></script>
</head>
<body>

<h1>Meus Pedidos</h1>

<table id="pedidoTable" border="1">
    <thead>
        <tr>
            <th>ID Pedido</th>
            <th>Nome</th>
            <th>Espécie</th>
            <th>Localidade</th>
            <th>Data do Pedido</th>
            <th>Status</th>
            <th>Imagem</th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr data-pedido-id="<?= htmlspecialchars($row['id_pedido']) ?>">
            <td><?= htmlspecialchars($row['id_pedido']) ?></td>
            <td><?= htmlspecialchars($row['nome']) ?></td>
            <td><?= htmlspecialchars($row['especie']) ?></td>
            <td><?= htmlspecialchars($row['localidade']) ?></td>
            <td><?= htmlspecialchars($row['data_pedido']) ?></td>
            <td class="status"><?= htmlspecialchars($row['status']) ?></td>
            <td>
                <?php if ($row['img']) { ?>
                    <img src="uploads/<?= htmlspecialchars($row['img']) ?>" width="100" />
                <?php } else { ?>
                    Nenhuma imagem
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<div id="chat"></div>

<input type="text" id="input" placeholder="Pergunte sobre seu pedido..." />
<button id="btn">Enviar</button>

<script>
    const chat = document.getElementById('chat');
    const input = document.getElementById('input');
    const btn = document.getElementById('btn');
    const pedidoTable = document.getElementById('pedidoTable').querySelector('tbody');

    function appendMessage(text, sender) {
        const div = document.createElement('div');
        div.textContent = (sender === 'user' ? 'Você: ' : 'Bot: ') + text;
        div.className = sender;
        chat.appendChild(div);
        chat.scrollTop = chat.scrollHeight;
    }

    function buscarStatusPedido(id) {
        for(let row of pedidoTable.rows) {
            if(row.dataset.pedidoId === id) {
                return row.querySelector('.status').textContent;
            }
        }
        return null;
    }

    function atualizarStatusPedido(id, novoStatus) {
        for(let row of pedidoTable.rows) {
            if(row.dataset.pedidoId === id) {
                row.querySelector('.status').textContent = novoStatus;
            }
        }
    }

    btn.onclick = () => {
        const msg = input.value.trim().toLowerCase();
        if (!msg) return;

        appendMessage(input.value.trim(), 'user');
        input.value = '';

        // Respostas simples para interatividade
        if (msg.includes('oi') || msg.includes('olá') || msg.includes('ola') || msg.includes('tudo bem')) {
            appendMessage('Oi! Como posso ajudar com seus pedidos?', 'bot');
            return;
        }

        if (msg.includes('obrigado') || msg.includes('valeu')) {
            appendMessage('De nada! Qualquer coisa só chamar.', 'bot');
            return;
        }

        // Procurar número do pedido
        const doc = nlp(msg);
        const numeros = doc.numbers().out('array');
        if (numeros.length === 0) {
            appendMessage('Pô, não achei nenhum número de pedido na sua mensagem. Pode falar o número do pedido?', 'bot');
            return;
        }

        const pedidoId = numeros[0];
        const status = buscarStatusPedido(pedidoId);

        if (status) {
            appendMessage(`Status atual do pedido ${pedidoId}: "${status}". Vou atualizar ele em etapas...`, 'bot');

            atualizarStatusPedido(pedidoId, 'Em andamento');
            appendMessage(`Pedido ${pedidoId} está "Em andamento".`, 'bot');

            setTimeout(() => {
                atualizarStatusPedido(pedidoId, 'Sua planta chegou ao local de plantio');
                appendMessage(`Pedido ${pedidoId}: "Sua planta chegou ao local de plantio".`, 'bot');
            }, 5000);

            setTimeout(() => {
                atualizarStatusPedido(pedidoId, 'Árvore plantada');
                appendMessage(`Pedido ${pedidoId}: "Árvore plantada".`, 'bot');
            }, 10000);

        } else {
            appendMessage(`Não achei o pedido ${pedidoId} aqui. Confere o número e tenta de novo, blz?`, 'bot');
        }
    };

    input.addEventListener('keypress', e => {
        if(e.key === 'Enter') btn.click();
    });
</script>

</body>
</html>
