<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco do Tempo - Troque Serviços</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-attachment: fixed;
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('assets/images/fundo.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
            background-attachment: fixed;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-section .subtitle {
            font-size: 1.5rem;
            margin-bottom: 30px;
            opacity: 0.9;
        }

        .btn-custom {
            background-color: white;
            color: #667eea;
            padding: 12px 40px;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.05);
        }

        /* About Section */
        .about-section {
            padding: 80px 0;
            background-color: #fafafb;
        }

        .about-content {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .about-image {
            flex: 1;
        }

        .about-image img {
            width: 100%;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .about-text {
            flex: 1;
        }

        .about-text h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #343a40;
        }

        .about-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #6c757d;
            margin-bottom: 20px;
        }

        /* Services Catalog Section */
        .services-section {
            padding: 80px 0;
            background-color: white;
        }

        .services-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 60px;
            color: #343a40;
        }

        .service-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .service-card h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .service-card .description {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .service-card .provider-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
        }

        .service-card .provider-name {
            color: #343a40;
            font-weight: 600;
        }

        .service-card .credits {
            background-color: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Footer Section */
        .footer-section {
            background-color: #343a40;
            color: white;
            padding: 40px 0;
            text-align: center;
        }

        .footer-section p {
            margin: 0;
            font-size: 0.95rem;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }

            .hero-section .subtitle {
                font-size: 1rem;
            }

            .about-content {
                flex-direction: column;
            }

            .about-text h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body class="landing-page">
    <!-- SECTION 1: HERO SECTION WITH BACKGROUND IMAGE -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Banco do Tempo</h1>
            <p class="subtitle">Troque serviços e cultive comunidade</p>
            <a href="user/login.php" class="btn-custom">Começar Agora</a>
        </div>
    </section>

    <!-- SECTION 2: ABOUT SECTION WITH 2 COLUMNS -->
    <section class="about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-image">
                    <img src="assets/images/logotipo.png" alt="Banco do Tempo">
                </div>
                <div class="about-text">
                    <h2>O que é  o Banco do Tempo?</h2>
                    <p>
                         O Banco do Tempo é uma plataforma inovadora que permite aos utilizadores trocar serviços com base em créditos de horas. 
                        Em vez de dinheiro, você troca o seu tempo e habilidades com outros membros da comunidade.
                    </p>
                    <p>
                        Seja jardinagem, cozinha, bricolage ou qualquer outro serviço, você pode oferecer suas competências e receber 
                        ajuda de outros membros em troca. É uma forma de fortalecer laços comunitários enquanto obtém os serviços de que precisa.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: SERVICES CATALOG FROM DATABASE -->
    <section class="services-section">
        <div class="container">
            <h2>Serviços Disponíveis</h2>
            <div class="row">
                <?php
                // Fetch services with provider information
                $sql = "SELECT 
                    s.id_servico,
                    s.nome,
                    s.descricao,
                    s.horas,
                    u.nome AS nome_prestador,
                    u.creditos
                FROM servicos s
                LEFT JOIN utilizadores u ON s.id_prestador = u.id_utilizador
                ORDER BY s.data DESC";
                
                $result = $conn->query($sql);
                
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-6 col-lg-4">';
                        echo '<div class="service-card">';
                        echo '<h4>' . htmlspecialchars($row['nome']) . '</h4>';
                        echo '<p class="description">' . htmlspecialchars($row['descricao']) . '</p>';
                        echo '<div class="provider-info">';
                        echo '<span class="provider-name">👤 ' . htmlspecialchars($row['nome_prestador'] ?? 'Anónimo') . '</span>';
                        echo '<span class="credits"><i class="ti ti-coin"></i> ' . htmlspecialchars($row['horas']) . ' créditos</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12 text-center">';
                    echo '<p>Nenhum serviço disponível no momento.</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- SECTION 4: FOOTER WITH COPYRIGHT -->
    <section class="footer-section">
        <div class="container">
            <p>&copy; 2026 Banco do Tempo. Todos os direitos reservados. | Desenvolvido com <i class="ti ti-heart"></i> por Rodrigo Oliveira</p>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>