<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Get current user ID from session
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

// Count total serviços for current user
$total_servicos = 0;
$sql_count = "SELECT COUNT(*) AS total FROM servicos WHERE id_prestador = ?";
if ($stmt_count = $conn->prepare($sql_count)) {
  $stmt_count->bind_param("i", $user_id);
  if ($stmt_count->execute()) {
    $stmt_count->bind_result($total_servicos);
    $stmt_count->fetch();
    $total_servicos = (int) $total_servicos;
  }
  $stmt_count->close();
} else {
  // In case of error, keep $total_servicos = 0
}

// Count total transacoes for current user
$total_transacoes = 0;
$sql_count = "SELECT COUNT(*) AS total FROM transacoes WHERE id_receptor = ?";
if ($stmt_count = $conn->prepare($sql_count)) {
  $stmt_count->bind_param("i", $user_id);
  if ($stmt_count->execute()) {
    $stmt_count->bind_result($total_transacoes);
    $stmt_count->fetch();
    $total_transacoes = (int) $total_transacoes;
  }
  $stmt_count->close();
} else {
  // In case of error, keep $total_transacoes = 0
}

?>
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Home | Banco do Tempo</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- [Favicon] icon -->
  <link rel="icon" href="../assets/images/logotipo.png" type="image/x-icon"> <!-- [Google Font] Family -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
    id="main-font-link">
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css">
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="../assets/fonts/feather.css">
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="../assets/fonts/fontawesome.css">
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="../assets/fonts/material.css">
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="../assets/css/style.css" id="main-style-link">
  <style>
    /* Services Catalog Section */
    .services-catalog {
      margin-top: 40px;
    }

    .catalog-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #343a40;
      margin-bottom: 30px;
    }

    .service-card {
      background: white;
      border: 1px solid #e9ecef;
      border-radius: 10px;
      padding: 20px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      height: 100%;
    }

    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .service-card h5 {
      color: #667eea;
      margin-bottom: 10px;
      font-weight: 600;
    }

    .service-card .description {
      color: #6c757d;
      margin-bottom: 15px;
      font-size: 0.9rem;
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
      font-size: 0.9rem;
    }

    .service-card .credits {
      background-color: #667eea;
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 0.85rem;
    }

    .service-card .buy-button {
      margin-top: 15px;
      width: 100%;
      padding: 10px;
      background-color: #667eea;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .service-card .buy-button:hover {
      background-color: #5568d3;
    }

    .service-card .buy-button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }

    .no-services-message {
      text-align: center;
      padding: 40px;
      color: #6c757d;
      font-size: 1.1rem;
    }
  </style>

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->
  <!-- [ Sidebar Menu ] start -->
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="header">
        <a href="index.php" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <img src="../assets/images/logotipo.png" class="img-fluid logo-lg" alt="Banco do Tempo">
        </a>
      </div>
      <div class="navbar-content">
        <ul class="pc-navbar">
          <li class="pc-item">
            <a href="index.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
              <span class="pc-mtext">Dashboard</span>
            </a>
          </li>


          <li class="pc-item pc-caption">
            <label>SERVIÇOS</label>
            <i class="ti ti-news"></i>
          </li>
          <li class="pc-item">
            <a href="servicos.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-list"></i></span>
              <span class="pc-mtext">Todos os serviços</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="novo-servico.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-layout-grid-add"></i></span>
              <span class="pc-mtext">Novo serviço</span>
            </a>
          </li>

        

          <li class="pc-item pc-caption">
            <label>TRANSAÇÕES</label>
            <i class="ti ti-brand-chrome"></i>
          </li>
          <li class="pc-item">
            <a href="transacoes.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-arrows-right-left"></i></span>
              <span class="pc-mtext">Todas as transações</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- [ Sidebar Menu ] end -->
  <!-- [ Header Topbar ] start -->
  <header class="pc-header">
    <div class="header-wrapper">
      <!-- [Mobile Media Block] start -->
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <!-- ======= Menu collapse Icon ===== -->
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
        </ul>
      </div>
      <!-- [Mobile Media Block end] -->
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="dropdown pc-h-item header-user-profile">
            <?php
            $user_creditos = isset($_SESSION['user_creditos']) ? htmlspecialchars($_SESSION['user_creditos']) : '0';
            echo '<i class="ti ti-coin"></i>&nbsp;' . $user_creditos . ' créditos';
            ?>
          </li>
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button"
              aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
              <i class="ti ti-face-id"></i> <span>Olá <?php
            $user_name = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Utilizador';
            // Extract first word from user name
            $name_parts = explode(' ', $user_name);
            echo $name_parts[0];
            ?></span>
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="tab-content" id="mysrpTabContent">
                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel" aria-labelledby="drp-t1"
                  tabindex="0">
                  <a href="editar-perfil.php" class="dropdown-item">
                    <i class="ti ti-edit-circle"></i>
                    <span>Editar Perfil</span>
                  </a>
                  <a href="logout.php" class="dropdown-item">
                    <i class="ti ti-power"></i>
                    <span>Logout</span>
                  </a>
                </div>

              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <!-- [ Header ] end -->

  <div class="pc-container">
    <div class="pc-content">

      <!-- Alert Messages -->
      <?php
      if (isset($_GET['success']) && $_GET['success'] == '1') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        echo '<i class="ti ti-check"></i> Serviço comprado com sucesso! Créditos transferidos.';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
      }
      
      if (isset($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
        $error_messages = [
          'creditos_insuficientes' => 'Créditos insuficientes para comprar este serviço.',
          'servico_nao_encontrado' => 'Serviço não encontrado.',
          'nao_pode_comprar_seu_servico' => 'Não pode comprar o seu próprio serviço.',
          'dados_invalidos' => 'Dados inválidos. Tente novamente.',
          'erro_transacao' => 'Erro ao processar a transação. Tente novamente.'
        ];
        $message = isset($error_messages[$error]) ? $error_messages[$error] : 'Ocorreu um erro.';
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo '<i class="ti ti-alert-triangle"></i> ' . $message;
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
      }
      ?>

      <!-- [ Main Content ] start -->
      <div class="row">
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Serviços</h6>
              <h4 class="mb-3"><?php echo htmlspecialchars($total_servicos); ?></h4>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-3">
          <div class="card">
            <div class="card-body">
              <h6 class="mb-2 f-w-400 text-muted">Transações</h6>
              <h4 class="mb-3"><?php echo htmlspecialchars($total_transacoes); ?></h4>
            </div>
          </div>
        </div>
      </div>

      <!-- Services Catalogue Section -->
      <div class="services-catalog">
        <h2 class="catalog-title">Serviços disponíveis para mim</h2>
        <div class="row">
          <?php
          // Fetch all services EXCEPT the current user's services
          $sql_services = "SELECT 
              s.id_servico,
              s.nome,
              s.descricao,
              s.horas,
              u.nome AS nome_prestador,
              u.creditos
          FROM servicos s
          LEFT JOIN utilizadores u ON s.id_prestador = u.id_utilizador
          WHERE s.id_prestador != ?
          ORDER BY s.data DESC";
          
          $stmt_services = $conn->prepare($sql_services);
          $stmt_services->bind_param("i", $user_id);
          $stmt_services->execute();
          $result_services = $stmt_services->get_result();
          
          if ($result_services && $result_services->num_rows > 0) {
              while ($row = $result_services->fetch_assoc()) {
                  $user_credits = isset($_SESSION['user_creditos']) ? (int)$_SESSION['user_creditos'] : 0;
                  $horas = (int)$row['horas'];
                  $can_buy = $user_credits >= $horas;
                  
                  echo '<div class="col-md-6 col-lg-4 mb-3">';
                  echo '<div class="service-card">';
                  echo '<h5>' . htmlspecialchars($row['nome']) . '</h5>';
                  echo '<p class="description">' . htmlspecialchars($row['descricao']) . '</p>';
                  echo '<div class="provider-info">';
                  echo '<span class="provider-name">👤 ' . htmlspecialchars($row['nome_prestador'] ?? 'Anónimo') . '</span>';
                  echo '<span class="credits"><i class="ti ti-coin"></i> ' . $horas . ' créditos</span>';
                  echo '</div>';
                  echo '<form method="POST" action="comprar-servico.php" style="margin-top: 10px;">';
                  echo '<input type="hidden" name="id_servico" value="' . (int)$row['id_servico'] . '">';
                  echo '<input type="hidden" name="horas" value="' . $horas . '">';
                  echo '<button type="submit" class="buy-button"' . ($can_buy ? '' : ' disabled') . '>';
                  echo $can_buy ? 'Comprar' : 'Créditos insuficientes';
                  echo '</button>';
                  echo '</form>';
                  echo '</div>';
                  echo '</div>';
              }
          } else {
              echo '<div class="col-12">';
              echo '<div class="no-services-message">Nenhum serviço disponível no momento.</div>';
              echo '</div>';
          }
          $stmt_services->close();
          ?>
        </div>
      </div>
      <!-- [ Main Content ] end -->

        <!-- Required Js -->
        <script src="../assets/js/plugins/popper.min.js"></script>
        <script src="../assets/js/plugins/simplebar.min.js"></script>
        <script src="../assets/js/plugins/bootstrap.min.js"></script>
        <script src="../assets/js/pcoded.js"></script>
        <script src="../assets/js/plugins/feather.min.js"></script>

</body>
<!-- [Body] end -->

</html>