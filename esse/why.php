<?php
include_once ('code/conexao.php');
include_once ('code/loginC.php');



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
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://cdn.shopify.com/s/files/1/0326/7189/t/65/assets/pf-77820662--OTP-Pto-M-2019-Marlondag64.jpg?v=1581959569');
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
        .pillar-section {
  background: #fff;
  padding: 60px 20px;
  font-family: 'Arial', sans-serif;
  color: #333;
  max-width: 1200px;
  margin: auto;
}

.pillar-container {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.pillar-text h3 {
  font-size: 1.5rem;
  color: #1a1a1a;
  font-weight: bold;
}

.pillar-text p {
  font-size: 1rem;
  line-height: 1.6;
}

.pillar-info {
  display: flex;
  gap: 30px;
  flex-wrap: wrap;
}

.pillar-image img {
  max-width: 300px;
  border-radius: 8px;
}

.pillar-content {
  flex: 1;
}

.pillar-content h2 {
  font-size: 2rem;
  color: #267a6d;
  margin-bottom: 10px;
}

.pillar-content p {
  font-size: 1rem;
  line-height: 1.6;
}

.highlight {
  color: #267a6d;
  font-weight: bold;
}

.donate-box {
  background: #f5f5f5;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.donate-box h3 {
  color: #1a1a1a;
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.donate-buttons {
  margin-top: 15px;
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.donate-buttons button {
  background-color: #e0e0e0;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: bold;
}

.donate-buttons .donate-now {
  background-color: #ff5c5c;
  color: #fff;
}
        footer {
            background: var(--dark);
            color: white;
            padding: 50px 0;
            text-align: center;
        }
    </style>
</head>
<body>
<nav>
        <div class="nav-container">
            <a href="inipage.php" class="logo">Treedom</a>
            <ul class="nav-links">
                
                <li><a href="galeria/galeria.html">Galeria</a></li>
                <li><a href="loja.php">Loja</a></li>
                <li><a href="bosque.php">Bosque</a></li>
                <li class="dropdown">
                <?php 
                if(isset($_SESSION['nome'])){ ?>
                  <a><?php echo $_SESSION['nome'];?></a>
                
              <?php } else{ ?>
          <li><a href="login.php">Login</a></li>
          
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
        <h1>PORQUE ARVORES?</h1>
        <p>Apoie a biodiversidade plantando árvores.</p>
        <div>
            <a href="loja.php" class="btn">COMPRE AGORA</a>
            <a href="loja.php" class="btn btn-outline">GANHE CARTAS COLECIONÁVEIS</a>
        </div>
    </section>
    
    <section class="pillar-section">
        <div class="pillar-container">
          <div class="pillar-text">
            <h3>Por que as árvores são importantes para o meio ambiente?</h3>
            <p>
                As árvores ajudam a limpar o ar que respiramos , filtram a água que bebemos e fornecem habitat para mais de 80% da biodiversidade terrestre do mundo.
            </p>
          </div>
      
          <div class="pillar-info">
            <div class="pillar-image">
              <img src="https://cdn.shopify.com/s/files/1/0326/7189/t/65/assets/air2x-1684511095782.jpg?v=1684511097" alt="Air Image" />
            </div>
            <div class="pillar-content">
              <h2>AR</h2>
              <p>
                As árvores ajudam a <span class="highlight">Limpar o ar</span> que respiramos. Através de suas folhas e cascas, elas absorvem poluentes nocivos e liberam oxigênio limpo para que possamos respirar. Em ambientes urbanos, as árvores absorvem gases poluentes como óxidos de nitrogênio, ozônio e monóxido de carbono, e varrem partículas como poeira e fumaça. Os níveis crescentes de dióxido de carbono causados ​​pelo  desmatamento e pela queima de combustíveis fósseis retêm o calor na atmosfera. Árvores saudáveis ​​e fortes atuam como sumidouros de carbono, compensando o carbono e reduzindo os efeitos das mudanças climáticas .
              </p>
              <p>
                Os níveis crescentes de dióxido de carbono causados ​​pelo desmatamento e pela queima de combustíveis fósseis retêm o calor na atmosfera. Árvores saudáveis ​​e fortes atuam como sumidouros de carbono, compensando o carbono e reduzindo os efeitos das mudanças climáticas .
              </p>
            </div>
          </div>
      
          </div>
        </div>
      </section>
    
      <section class="pillar-section">
        <div class="pillar-container">
          <div class="pillar-text">
            <h3>BIODIVERSIDADE</h3>
            <p>
                Uma única árvore pode abrigar centenas de espécies de insetos, fungos, musgos, mamíferos e plantas. Dependendo do tipo de alimento e abrigo que necessitam, diferentes animais da floresta requerem diferentes tipos de habitat. Sem árvores, os animais da floresta não teriam um lugar para chamar de lar.
            </p>
          </div>
      
          <div class="pillar-info">
            <div class="pillar-image">
              <img src="https://cdn.shopify.com/s/files/1/0326/7189/t/65/assets/biodiversity2x-1684511168968.jpg?v=1684511170" alt="Air Image" />
            </div>
            <div class="pillar-content">
              <h2>FLORESTAS</h2>
              <p>
                Florestas jovens e abertas: essas florestas surgem como resultado de incêndios ou exploração madeireira. Arbustos, gramíneas e árvores jovens atraem animais como ursos-negros, pintassilgos-americanos e pássaros-azuis na América do Norte.
              </p>
              <p>
                Florestas de meia-idade: Em florestas de meia-idade, as árvores mais altas começam a crescer mais que as árvores e a vegetação mais fracas. Uma copa aberta permite o crescimento da vegetação rasteira, preferida por animais como salamandras, alces e pererecas.
              </p>
            </div>
          </div>
        </div>
      </section>


    <section class="container" style="background: var(--light); padding: 60px 20px;">
        <h2 class="section-title">SUSTENTABILIDADE</h2>
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <p style="font-size: 1.2rem; margin-bottom: 30px;">Faça seu próprio impacto na natureza</p>
            <a href="loja.php" class="btn">COMPRE AGORA</a>
        </div>
    </section>
    
    
    <footer>
        <div class="container">
            <h3>GET INVOLVED</h3>
            <div style="display: flex; justify-content: center; margin-top: 30px;">
                <a href="#" style="margin: 0 15px; color: white; text-decoration: none;">INDIVIDUALS</a>
                <a href="#" style="margin: 0 15px; color: white; text-decoration: none;">BUSINESSES</a>
                <a href="#" style="margin: 0 15px; color: white; text-decoration: none;">SCHOOLS</a>
            </div>
            <p style="margin-top: 30px;">© 2023 One Tree Planted. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>