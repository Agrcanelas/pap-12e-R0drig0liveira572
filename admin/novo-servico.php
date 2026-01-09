<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
include '../config.php';

$message = '';
$message_type = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
  $descricao = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
  $categoria = isset($_POST['categoria']) ? trim($_POST['categoria']) : ''; 
  $horas = isset($_POST['horas']) ? trim($_POST['horas']) : '';
  $prestador = isset($_POST['prestador']) ? trim($_POST['prestador']) : '';

  // Validate input
  if (empty($nome)) {
    $message = 'Por favor, preencha o campo nome.';
    $message_type = 'error';
  } else {
    // Prepare and execute insert query
    $sql = "INSERT INTO servicos (nome, descricao, categoria, horas, id_prestador) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
      $stmt->bind_param("ssiii", $nome, $descricao, $categoria, $horas, $prestador);
      
      if ($stmt->execute()) {
        $message = 'Serviço adicionado com sucesso!';
        $message_type = 'success';
      } else {
        $message = 'Erro ao adicionar serviço: ' . $stmt->error;
        $message_type = 'error';
      }
      $stmt->close();
    } else {
      $message = 'Erro na preparação da consulta: ' . $conn->error;
      $message_type = 'error';
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Novo Serviço | Banco do Tempo</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- [Favicon] icon -->
  <link rel="icon" href="../assets/images/favicon.svg" type="image/x-icon"> <!-- [Google Font] Family -->
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
            <label>UTILIZADORES</label>
            <i class="ti ti-dashboard"></i>
          </li>
          <li class="pc-item">
            <a href="utilizadores.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-users"></i></span>
              <span class="pc-mtext">Todos os utilizadores</span>
            </a>
          </li>
          <li class="pc-item">
            <a href="novo-utilizador.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-user-plus"></i></span>
              <span class="pc-mtext">Adicionar utilizador</span>
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

          <li class="pc-item">
            <a href="categorias.php" class="pc-link">
              <span class="pc-micon"><i class="ti ti-news"></i></span>
              <span class="pc-mtext">Categorias</span>
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
              <i class="ti ti-face-id"></i> <span>Olá  <?php
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
      <!-- [ breadcrumb ] start -->
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h2 class="mb-0">Adicionar Serviço</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- [ breadcrumb ] end -->

      <!-- [ Main Content ] start -->
      <div class="row">

        <div class="col-md-12 col-xl-12">
          <div class="card tbl-card">
            <div class="card-body">
                <div class="form-group mb-3">
                    <?php
                // Display message if exists
                if (!empty($message)) {
                  $alert_class = $message_type == 'success' ? 'alert-success' : 'alert-danger';
                  echo "<div class='alert {$alert_class} alert-dismissible fade show' role='alert'>";
                  echo htmlspecialchars($message);
                  echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                  echo "</div>";
                }
                ?>
              <form action="" method="POST">
                <label class="form-label">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" required=""><br>
              <label class="form-label">Descrição</label>
              <input type="text" class="form-control" name="descricao" id="descricao" required=""><br>
              <label class="form-label">Categoria</label>
              <select class="form-control" name="categoria" id="categoria" required="">
                <option value="">Selecione uma categoria</option>
                <?php
                // Fetch all categories from database
                $sql_cat = "SELECT id_categoria, nome FROM categorias ORDER BY nome ASC";
                $result_cat = $conn->query($sql_cat);

                if ($result_cat->num_rows > 0) {
                  while ($row_cat = $result_cat->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row_cat['id_categoria']) . "'>" . htmlspecialchars($row_cat['nome']) . "</option>";
                  }
                }
                ?>
              </select><br>
              <label class="form-label">Horas</label>
              <input type="text" class="form-control" name="horas" id="horas" required="">
              <label class="form-label">Prestador</label>
              <select class="form-control" name="prestador" id="prestador" required="">
                <option value="">Selecione um prestador</option>
                <?php
                // Fetch all users from database
                $sql_prest = "SELECT id_utilizador, nome FROM utilizadores ORDER BY nome ASC";
                $result_prest = $conn->query($sql_prest);

                if ($result_prest->num_rows > 0) {
                  while ($row_prest = $result_prest->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row_prest['id_utilizador']) . "'>" . htmlspecialchars($row_prest['nome']) . "</option>";
                  }
                }
                ?>
              </select><br>
              <button type="submit" class="btn btn-primary mt-3">Adicionar serviço</button>
                </form>
            </div>
          </div>
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