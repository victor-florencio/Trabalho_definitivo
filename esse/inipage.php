<?php
include_once("code/loginC.php");
include_once("code/conexao.php"); // <-- Adicione esta linha ANTES de usar $conexao

// -- ETAPA 1: BUSCAR OS DADOS DOS PEDIDOS (LÓGICA DE pedidos.php) --
// Esta lógica agora está na página inicial para alimentar o assistente.
$user_orders_array = [];
if (isset($_SESSION['id'])) {
    $id_usuario = $_SESSION['id'];
    $sql = "SELECT id_pedido, nome, especie, localidade, data_pedido, status, img FROM pedidos WHERE id_user = $id_usuario ORDER BY data_pedido DESC";
    $result = $conexao->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $user_orders_array[] = $row; // Salva todos os pedidos em um array PHP
        }
    }
}
// Se o usuário não estiver logado, o array estará vazio, e o chat saberá disso.

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treedom | Plante Árvores</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/compromise"></script> <style>
        :root {
            --primary: #3a7d44;
            --secondary: #2d5a34;
            --accent: #5cb85c;
            --light: #f8f9fa;
            --dark: #343a40;
            --white: #ffffff;
            --blue: #3498db;
            --orange: #f39c12;
            --grey: #95a5a6;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0; padding: 0; color: var(--dark); line-height: 1.6;
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1469827160215-9d29e96e72f4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; height: 80vh;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            text-align: center; color: white; padding: 0 20px;
        }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        .hero h1 { font-size: 3.5rem; margin-bottom: 1rem; }
        .hero p { font-size: 1.5rem; max-width: 800px; margin-bottom: 2rem; }
        .btn {
            display: inline-block; background: var(--accent); color: white;
            padding: 12px 30px; border-radius: 50px; text-decoration: none;
            font-weight: 600; margin: 10px; transition: all 0.3s ease;
        }
        .btn:hover { background: var(--secondary); transform: translateY(-3px); }
        .btn-outline { background: transparent; border: 2px solid white; }
        nav {
            background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky; top: 0; z-index: 100;
        }
        .nav-container { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .logo { font-size: 1.8rem; font-weight: 700; color: var(--primary); text-decoration: none; }
        .nav-links { display: flex; list-style: none; }
        .nav-links li { margin-left: 30px; }
        .nav-links a { text-decoration: none; color: var(--dark); font-weight: 600; transition: color 0.3s ease; }
        .nav-links a:hover { color: var(--primary); }
        .container { max-width: 1200px; margin: 0 auto; padding: 60px 20px; }
        .section-title { text-align: center; margin-bottom: 50px; color: var(--primary); }
        .regions { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .region-card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease; }
        .region-card:hover { transform: translateY(-10px); }
        .region-img { height: 200px; background-size: cover; background-position: center; }
        .region-content { padding: 20px; }
        .region-content h3 { margin-top: 0; color: var(--primary); }
        footer { background: var(--dark); color: white; padding: 50px 0; text-align: center; }
        .dropdown { position: relative; }
        .submenu {
            position: absolute; top: 100%; left: 0; background: #000; border: 1px solid #444; border-radius: 6px;
            list-style: none; padding: 8px 0; margin: 8px 0 0 0; min-width: 140px; box-shadow: 0 2px 6px rgba(0,0,0,0.5);
            opacity: 0; visibility: hidden; transition: opacity 0.3s ease, transform 0.3s ease; transform: translateY(10px); z-index: 10;
        }
        .submenu li a { display: block; padding: 8px 20px; color: #fff; font-weight: 300; text-decoration: none; transition: background-color 0.5s ease; }
        .submenu li a:hover { background-color: #222; }
        .dropdown:hover .submenu, .dropdown:focus-within .submenu { opacity: 1; visibility: visible; transform: translateY(0); }
       
        /* --- ESTILOS DO ASSISTENTE VIRTUAL E CHAT (COMBINADOS) --- */
        #open-chat-btn {
            position: fixed; bottom: 30px; right: 30px; width: 60px; height: 60px;
            background-color: var(--primary); color: var(--white); border-radius: 50%; border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2); cursor: pointer; display: flex;
            justify-content: center; align-items: center; z-index: 998;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        #open-chat-btn:hover { transform: scale(1.1); background-color: var(--secondary); }
        #open-chat-btn.hidden { transform: scale(0.5); opacity: 0; pointer-events: none; }

        #virtual-assistant-container {
            position: fixed; bottom: 30px; right: 30px; width: 370px; max-width: 90vw;
            height: 70vh; max-height: 600px; background-color: var(--white);
            border-radius: 15px; box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            display: flex; flex-direction: column; z-index: 999;
            opacity: 0; visibility: hidden; transform: translateY(100%) scale(0.8);
            transform-origin: bottom right; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #virtual-assistant-container.active { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }

        .assistant-header {
            display: flex; justify-content: space-between; align-items: center;
            padding: 15px 20px; background-color: var(--primary); color: var(--white);
            border-radius: 15px 15px 0 0; flex-shrink: 0;
        }
        .assistant-header h3 { margin: 0; font-size: 1.2rem; font-family: 'Montserrat', sans-serif; font-weight: 600; }
        #close-assistant-btn {
            background: none; border: none; color: var(--white); font-size: 1.5rem;
            font-weight: bold; cursor: pointer; opacity: 0.8; transition: opacity 0.2s, transform 0.2s;
        }
        #close-assistant-btn:hover { opacity: 1; transform: rotate(90deg); }
        
        #chat-messages {
            flex-grow: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px;
        }
        .message {
            display: flex; align-items: flex-end; gap: 10px; max-width: 85%;
            opacity: 0; transform: translateY(15px); animation: messageAppear 0.4s ease-out forwards;
        }
        @keyframes messageAppear { to { opacity: 1; transform: translateY(0); } }
        .message-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--light); flex-shrink: 0; }
        .message-content { padding: 12px 16px; border-radius: 18px; line-height: 1.4; }
        .user-message { align-self: flex-end; flex-direction: row-reverse; }
        .user-message .message-content { background-color: var(--primary); color: white; border-bottom-right-radius: 4px; }
        .user-message .message-avatar { background: var(--secondary); }
        .bot-message { align-self: flex-start; }
        .bot-message .message-content { background-color: #eef1f4; color: var(--dark); border-bottom-left-radius: 4px; }
        .bot-message .message-avatar { background-image: linear-gradient(45deg, var(--primary), var(--accent)); }
        
        .typing-indicator { display: flex; align-items: center; gap: 5px; padding: 12px 16px; }
        .typing-indicator span { width: 8px; height: 8px; background-color: var(--grey); border-radius: 50%; animation: typing 1s infinite; }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-5px); } }
        
        #chat-input-area { display: flex; padding: 15px; border-top: 1px solid var(--light); background: #f9fbfd; }
        #chat-input {
            flex-grow: 1; border: 1px solid var(--light); border-radius: 20px;
            padding: 10px 18px; font-size: 1rem; outline: none; transition: border-color 0.3s, box-shadow 0.3s;
        }
        #chat-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(92, 184, 92, 0.2); }
        #send-btn {
            background: var(--primary); border: none; color: white; width: 42px; height: 42px;
            border-radius: 50%; margin-left: 10px; cursor: pointer; font-size: 1.2rem;
            display: flex; justify-content: center; align-items: center; transition: background-color 0.3s, transform 0.3s;
        }
        #send-btn:hover { background-color: var(--accent); transform: scale(1.1); }
        
        /* Estilos para o mini-card do pedido dentro do chat */
        .chat-order-card {
            background: var(--white); border: 1px solid var(--light); border-radius: 10px;
            padding: 15px; margin-top: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .chat-order-card p { margin: 4px 0; font-size: 0.9rem; }
        .chat-order-card strong { color: var(--secondary); }
        .status-badge {
            display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem;
            font-weight: 600; color: white; transition: background-color 0.4s ease; text-align: center;
        }
        .status-badge.status-pending { background-color: var(--grey); }
        .status-badge.status-in-progress { background-color: var(--orange); }
        .status-badge.status-shipped { background-color: var(--blue); }
        .status-badge.status-planted { background-color: var(--accent); }

    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="#" class="logo">Treedom</a>
            <ul class="nav-links">
                <li><a href="why.php">Porque árvores?</a></li>
                <li><a href="galeria/galeria.html">Galeria</a></li>
                <li><a href="loja.php">Loja</a></li>
                <li><a href="bosque.php">Bosque</a></li>
                <li><a href="pedidos.php">Meus Pedidos</a></li>
                <li class="dropdown">
                    <?php if(isset($_SESSION['nome'])): ?>
                      <a><?php echo $_SESSION['nome'];?></a>
                      <ul class="submenu">
                          <li><a href="inventario.php">Inventário</a></li>
                          <li><a href="logoutE.php">Sair</a></li>
                      </ul>
                    <?php else: ?>
                      <a href="#">Login/Cadastro <span class="seta">&#9660;</span></a>
                      <ul class="submenu">
                          <li><a href="login.php">Login</a></li>
                          <li><a href="cadastro.php">Cadastro</a></li>
                      </ul>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>
    
    <section class="hero">
        <h1>RESTAURE O NOSSO AMBIENTE</h1>
        <p>Apoie a biodiversidade plantando árvores.</p>
        <div>
            <a href="loja.php" class="btn">Compre Agora</a>
            <a href="bosque.php" class="btn btn-outline">Veja Seu Bosque</a>
        </div>
    </section>
    

    <button id="open-chat-btn" title="Abrir Assistente">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
    </button>

    <div id="virtual-assistant-container">
        <header class="assistant-header">
            <h3>Assistente Virtual</h3>
            <button id="close-assistant-btn" title="Fechar">&times;</button>
        </header>
        <div id="chat-messages">
             </div>
        <div id="chat-input-area">
            <input type="text" id="chat-input" placeholder="Pergunte sobre seu pedido..." />
            <button id="send-btn" title="Enviar">➤</button>
        </div>
    </div>
    
    <script>
        // -- ETAPA 2: DISPONIBILIZAR OS DADOS PHP PARA O JAVASCRIPT --
        const userOrdersData = <?php echo json_encode($user_orders_array); ?>;

        document.addEventListener('DOMContentLoaded', () => {
          const openBtn = document.getElementById('open-chat-btn');
          const assistantContainer = document.getElementById('virtual-assistant-container');
          const closeBtn = document.getElementById('close-assistant-btn');
          const chatMessages = document.getElementById('chat-messages');
          const input = document.getElementById('chat-input');
          const sendBtn = document.getElementById('send-btn');

          // --- LÓGICA DE ABRIR/FECHAR O ASSISTENTE ---
          if (openBtn && assistantContainer && closeBtn) {
            openBtn.addEventListener('click', () => {
              assistantContainer.classList.add('active');
              openBtn.classList.add('hidden');
              if(chatMessages.children.length === 0) { // Adiciona msg inicial apenas na primeira vez
                appendMessage('Olá! 👋 Sou seu assistente de plantio. Quer saber sobre algum pedido? É só perguntar pelo número. Ex: "status do pedido 123".', 'bot');
              }
            });
            closeBtn.addEventListener('click', () => {
              assistantContainer.classList.remove('active');
              openBtn.classList.remove('hidden');
            });
          }

         
          function appendMessage(html, sender, messageId = null) {
              const messageDiv = document.createElement('div');
              messageDiv.className = `message ${sender}-message`;
              if (messageId) {
                  messageDiv.id = messageId;
              }
              const avatar = `<div class="message-avatar"></div>`;
              const content = `<div class="message-content">${html}</div>`;
              messageDiv.innerHTML = sender === 'user' ? `${content}${avatar}` : `${avatar}${content}`;
              chatMessages.appendChild(messageDiv);
              chatMessages.scrollTop = chatMessages.scrollHeight;
          }

          function showTypingIndicator() {
              const typingDiv = document.createElement('div');
              typingDiv.className = 'message bot-message';
              typingDiv.id = 'typing-indicator';
              typingDiv.innerHTML = `<div class="message-avatar"></div><div class="message-content typing-indicator"><span></span><span></span><span></span></div>`;
              chatMessages.appendChild(typingDiv);
              chatMessages.scrollTop = chatMessages.scrollHeight;
          }
          function removeTypingIndicator() {
              const indicator = document.getElementById('typing-indicator');
              if (indicator) indicator.remove();
          }
          
          function getStatusClass(statusText) {
              if (!statusText) return 'status-pending';
              const s = statusText.toLowerCase();
              if (s.includes('plantada')) return 'status-planted';
              if (s.includes('chegou') || s.includes('plantio')) return 'status-shipped';
              if (s.includes('andamento')) return 'status-in-progress';
              return 'status-pending';
          }

          function findOrderData(id) {
              return userOrdersData.find(order => order.id_pedido == id);
          }
          
          function createOrderCardHTML(orderData) {
              // Converte a data para o formato brasileiro
              const date = new Date(orderData.data_pedido);
              const formattedDate = date.toLocaleDateString('pt-BR');

              return `
                  <div class="chat-order-card" id="chat-order-${orderData.id_pedido}">
                      <p><strong>Pedido #${orderData.id_pedido}</strong>: ${orderData.nome}</p>
                      <p><strong>Espécie</strong>: ${orderData.especie}</p>
                      <p><strong>Data</strong>: ${formattedDate}</p>
                      <div id="status-badge-${orderData.id_pedido}" class="status-badge ${getStatusClass(orderData.status)}">
                          ${orderData.status}
                      </div>
                  </div>
              `;
          }
          
          

          function updateChatCardStatus(pedidoId, newStatus) {
              const badge = document.getElementById(`status-badge-${pedidoId}`);
              if (!badge) return;
              badge.textContent = newStatus;
              badge.className = 'status-badge ' + getStatusClass(newStatus);
          }

          const handleSend = () => {
              const msg = input.value.trim();
              if (!msg) return;
              appendMessage(`<p>${msg}</p>`, 'user');
              input.value = '';
              setTimeout(() => {
                  showTypingIndicator();
                  processMessage(msg.toLowerCase());
              }, 500);
          };
          
          

          function processMessage(msg) {
              removeTypingIndicator();
              if (msg.includes('oi') || msg.includes('olá')) {
                  appendMessage('<p>Oi! Como posso ajudar com seus pedidos hoje?</p>', 'bot'); return;
              }
              if (msg.includes('obrigado') || msg.includes('valeu')) {
                  appendMessage('<p>De nada! Se precisar de mais alguma coisa, estarei por aqui. 🌱</p>', 'bot'); return;
              }

              const doc = nlp(msg);
              const numeros = doc.numbers().out('array');
              if (numeros.length === 0) {
                  appendMessage('<p>Desculpe, não consegui identificar um número de pedido na sua mensagem.</p>', 'bot'); return;
              }

              const pedidoId = numeros[0];
              const orderData = findOrderData(pedidoId);

              if (orderData) {
                  const cardHTML = createOrderCardHTML(orderData);
                  appendMessage(`<p>Encontrei o pedido #${pedidoId}! Aqui estão os detalhes:</p>${cardHTML}`, 'bot');
                  appendMessage(`<p>Vou iniciar uma simulação de atualização para você...</p>`, 'bot');
                  
                  // Simulation sequence
                  setTimeout(() => {
                      showTypingIndicator();
                      setTimeout(() => {
                          removeTypingIndicator();
                          const novoStatus1 = 'Em andamento';
                          updateChatCardStatus(pedidoId, novoStatus1);
                          appendMessage(`<p>O pedido #${pedidoId} foi atualizado para: "${novoStatus1}".</p>`, 'bot');
                      }, 1500);
                  }, 2000);

                  setTimeout(() => {
                      showTypingIndicator();
                      setTimeout(() => {
                          removeTypingIndicator();
                          const novoStatus2 = 'Sua planta chegou ao local de plantio';
                          updateChatCardStatus(pedidoId, novoStatus2);
                          appendMessage(`<p>Boas notícias! Pedido #${pedidoId}: "${novoStatus2}".</p>`, 'bot');
                      }, 1500);
                  }, 6000);

                  setTimeout(() => {
                      showTypingIndicator();
                      setTimeout(() => {
                          removeTypingIndicator();
                          const novoStatus3 = 'Árvore plantada';
                          updateChatCardStatus(pedidoId, novoStatus3);
                          appendMessage(`<p>Missão cumprida! 🌳 O pedido #${pedidoId} agora tem o status: "${novoStatus3}".</p>`, 'bot');
                      }, 1500);
                  }, 10000);

              } else {
                  appendMessage(`<p>Hum... não encontrei nenhum pedido com o número ${pedidoId} na sua conta.</p>`, 'bot');
              }
          }

          sendBtn.addEventListener('click', handleSend);
          input.addEventListener('keypress', e => {
              if (e.key === 'Enter') handleSend();
          });
        });
    </script>
</body>
</html>