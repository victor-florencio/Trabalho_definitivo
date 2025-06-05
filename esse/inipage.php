<?php
include_once("code/loginC.php");



if (!isset($_SESSION['nome'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Tree Planted | Reforestation Nonprofit</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3a7d44;
            --secondary: #2d5a34;
            --accent: #5cb85c;
            --light: #f8f9fa;
            --dark: #343a40;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1469827160215-9d29e96e72f4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }
        
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.5rem;
            max-width: 800px;
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-block;
            background: var(--accent);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            margin: 10px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid white;
        }
        
        nav {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
        }
        
        .nav-links li {
            margin-left: 30px;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            color: var(--primary);
        }
        
        .regions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .region-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .region-card:hover {
            transform: translateY(-10px);
        }
        
        .region-img {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        
        .region-content {
            padding: 20px;
        }
        
        .region-content h3 {
            margin-top: 0;
            color: var(--primary);
        }
        
        footer {
            background: var(--dark);
            color: white;
            padding: 50px 0;
            text-align: center;
        }
         /* Dropdown container */
.dropdown {
  position: relative;
}

/* Link principal do dropdown */
.dropbtn {
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  color: #fff;
  font-weight: 300;
  font-size: 1em;
  text-decoration: none;
  padding: 8px 0;
  position: relative;
  transition: color 0.3s ease;
}

/* Seta ao lado do texto */
.seta {
  font-size: 0.6em;
  line-height: 1;
  transition: transform 0.3s ease;
  display: inline-block;
}

/* Submenu escondido por padrão */
.submenu {
  position: absolute;
  top: 100%;
  left: 0;
  background: #000;
  border: 1px solid #444;
  border-radius: 6px;
  list-style: none;
  padding: 8px 0;
  margin: 8px 0 0 0;
  min-width: 140px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.5);
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease;
  z-index: 10;
}

/* Itens do submenu */
.submenu li a {
  display: block;
  padding: 8px 20px;
  color: #fff;
  font-weight: 300;
  font-size: 0.95em;
  text-decoration: none;
  white-space: nowrap;
  transition: background-color 0.5s ease;
}

.submenu li a:hover {
  background-color: #222;
}

/* Mostrar submenu ao passar o mouse */
.dropdown:hover .submenu,
.dropdown:focus-within .submenu {
  opacity: 1;
  visibility: visible;
}

/* Seta rotaciona ao abrir */
.dropdown:hover .seta,
.dropdown:focus-within .seta {
  transform: rotate(180deg);
}

/* Mantém a linha embaixo do texto no hover do link principal */
.dropbtn::after {
  content: '';
  position: absolute;
  width: 0%;
  height: 2px;
  bottom: 0;
  left: 0;
  background-color: white;
  transition: width 0.3s ease;
}

.dropbtn:hover::after,
.dropbtn:focus::after {
  width: 100%;
}
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

                        <li class="dropdown">
                <?php 
                if(isset($_SESSION['nome'])){ ?>
                  <a><?php echo $_SESSION['nome'];?></a>
                <ul class="submenu">
              <li><a href="inventario.php">inventário<span ></span></a></li>
            <li><a href="logoutE.php">Sair<span ></span></a></li>
              <?php } else{ ?>
                <a href="#">Login/Cadastro <span class="seta">&#9660;</span></a>
        <ul class="submenu">
          <li><a href="login.php">Login</a></li>
          <li><a href="cadastro.php">Cadastro</a></li>
        </ul>
        </li> <?php } ?>


                <!-- <?php if(isset($_SESSION['nome'])){
                  
                  echo $_SESSION['nome'];
                  
                  }else{ ?>
                <li><a href="login.php">Login</a></li>
               <?php } ?>
            </ul> -->
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
    
    <section class="container">
        <h2 class="section-title">Nosso Trabalho</h2>
        <div class="regions">
            <div class="region-card">
                <div class="region-img" style="background-image: url('https://images.unsplash.com/photo-1483728642387-6c3bdd6c93e5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');"></div>
                <div class="region-content">
                    <h3>America do Norte</h3>
                    <p>Restauramos Florestas no Estados Unidos, Canada e Mexico. Todas as infomações abaixo.</p>
                    <a href="Why.php" class="btn">Saiba mais</a>
                </div>
            </div>
            
            <div class="region-card">
                <div class="region-img" style="background-image: url('https://images.unsplash.com/photo-1465056836041-7f43ac27dcb5?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');"></div>
                <div class="region-content">
                    <h3>America Latina e Caribe</h3>
                    <p>Criamos Suporte para Novos locais.</p>
                </div>
            </div>
            
            <div class="region-card">
                <div class="region-img" style="background-image: url('https://images.unsplash.com/photo-1442570468985-f63ed5de9086?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');"></div>
                <div class="region-content">
                    <h3>Africa</h3>
                    <p>Combatemos a desertificação e desenvolvemos o ambiente.</p>
                </div>
            </div>
        </div>
    </section>
    
    <section class="container" style="background: var(--light); padding: 60px 20px;">
        <h2 class="section-title">Sustentabilidade e Desenvolvimento</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 1.2rem; margin-bottom: 30px;">Faça seu proprio impacto no mundo.</p>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <h3>Esteja Por dentro</h3>
            <div style="display: flex; justify-content: center; margin-top: 30px;">
                <a href="./galeria/galeria.html" style="margin: 0 15px; color: white; text-decoration: none;">Sobre</a>
                <a href="why.html" style="margin: 0 15px; color: white; text-decoration: none;">Porque árvores</a>
                
            </div>
            <p style="margin-top: 30px;">© 2024 Treedom. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>