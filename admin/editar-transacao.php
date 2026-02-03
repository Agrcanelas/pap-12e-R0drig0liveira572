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

// Messages
$message = '';
$message_type = '';

// Get transaction id from GET
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$current_id_prestador = '';
$current_id_receptor = '';
$current_id_servico = '';
$current_horas_trocadas = '';
$current_estado = '';
$current_nome_prestador = '';
$current_nome_receptor = '';
$current_nome_servico = '';

if ($id > 0) {
  // Handle POST update
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_receptor = isset($_POST['id_receptor']) ? (int) $_POST['id_receptor'] : 0;
    $id_servico = isset($_POST['id_servico']) ? (int) $_POST['id_servico'] : 0;
    $horas_trocadas = isset($_POST['horas_trocadas']) ? (float) $_POST['horas_trocadas'] : 0;
    $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';

    if ($id_receptor <= 0 || $id_servico <= 0 || $horas_trocadas <= 0) {
      $message = 'Por favor preencha todos os campos corretamente.';
      $message_type = 'error';
    } else {
      $sql = "UPDATE transacoes SET id_receptor = ?, id_servico = ?, horas_trocadas = ?, estado = ? WHERE id_transacao = ?";
      $stmt = $conn->prepare($sql);
      if ($stmt) {
        $stmt->bind_param('iidsi', $id_receptor, $id_servico, $horas_trocadas, $estado, $id);
        if ($stmt->execute()) {
          $message = 'Transação actualizada com sucesso.';
          $message_type = 'success';
        } else {
          $message = 'Erro ao actualizar transação: ' . $stmt->error;
          $message_type = 'error';
        }
        $stmt->close();
      } else {
        $message = 'Erro na preparação da consulta: ' . $conn->error;
        $message_type = 'error';
      }
    }
  }

  // Fetch current transaction values
  $sql = "SELECT id_receptor, id_servico, horas_trocadas, estado FROM transacoes WHERE id_transacao = ? LIMIT 1";
  $stmt = $conn->prepare($sql);
  if ($stmt) {
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
      $stmt->bind_result($current_id_receptor, $current_id_servico, $current_horas_trocadas, $current_estado);
      $stmt->fetch();
    }
    $stmt->close();
  }

  // Fetch id_prestador from servicos table using id_servico
  if ($current_id_servico > 0) {
    $sql = "SELECT id_prestador FROM servicos WHERE id_servico = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
      $stmt->bind_param('i', $current_id_servico);
      if ($stmt->execute()) {
        $stmt->bind_result($current_id_prestador);
        $stmt->fetch();
      }
      $stmt->close();
    }
  }

  // Fetch prestador name using id_prestador from servicos
  if ($current_id_prestador > 0) {
    $sql = "SELECT nome FROM utilizadores WHERE id_utilizador = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
      $stmt->bind_param('i', $current_id_prestador);
      if ($stmt->execute()) {
        $stmt->bind_result($current_nome_prestador);
        $stmt->fetch();
      }
      $stmt->close();
    }
  }

  // Fetch receptor name
  if ($current_id_receptor > 0) {
    $sql = "SELECT nome FROM utilizadores WHERE id_utilizador = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
      $stmt->bind_param('i', $current_id_receptor);
      if ($stmt->execute()) {
        $stmt->bind_result($current_nome_receptor);
        $stmt->fetch();
      }
      $stmt->close();
    }
  }

  // Fetch servico name
  if ($current_id_servico > 0) {
    $sql = "SELECT nome FROM servicos WHERE id_servico = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
      $stmt->bind_param('i', $current_id_servico);
      if ($stmt->execute()) {
        $stmt->bind_result($current_nome_servico);
        $stmt->fetch();
      }
      $stmt->close();
    }
  }
} else {
  $message = 'ID de transação inválido.';
  $message_type = 'error';
}

?>
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Editar Transação | Banco do Tempo</title>
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
                <h2 class="mb-0">Editar Transação</h2>
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
                <label class="form-label">Prestador</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($current_nome_prestador ?? 'N/A'); ?>" disabled><br>
                <label class="form-label">Receptor</label>
                <select class="form-control" name="id_receptor" id="id_receptor" required="">
                  <option value="">Selecione um receptor</option>
                  <?php
                  $sql = "SELECT id_utilizador, nome FROM utilizadores ORDER BY nome ASC";
                  $result = $conn->query($sql);
                  if ($result) {
                    while ($row = $result->fetch_assoc()) {
                      $selected = ($row['id_utilizador'] == $current_id_receptor) ? 'selected' : '';
                      echo "<option value='" . htmlspecialchars($row['id_utilizador']) . "' $selected>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                  }
                  ?>
                </select><br>
                <label class="form-label">Serviço</label>
                <select class="form-control" name="id_servico" id="id_servico" required="">
                  <option value="">Selecione um serviço</option>
                  <?php
                  $sql = "SELECT id_servico, nome FROM servicos ORDER BY nome ASC";
                  $result = $conn->query($sql);
                  if ($result) {
                    while ($row = $result->fetch_assoc()) {
                      $selected = ($row['id_servico'] == $current_id_servico) ? 'selected' : '';
                      echo "<option value='" . htmlspecialchars($row['id_servico']) . "' $selected>" . htmlspecialchars($row['nome']) . "</option>";
                    }
                  }
                  ?>
                </select><br>
                <label class="form-label">Horas Trocadas</label>
                <input type="number" step="0.1" class="form-control" name="horas_trocadas" id="horas_trocadas" value="<?php echo htmlspecialchars($current_horas_trocadas); ?>" required=""><br>
                <label class="form-label">Estado</label>
                <select class="form-control" name="estado" id="estado" required="">
                  <option value="">Selecione um estado</option>
                  <option value="a decorrer" <?php echo ($current_estado === 'a decorrer') ? 'selected' : ''; ?>>A Decorrer</option>
                  <option value="concluido" <?php echo ($current_estado === 'concluido') ? 'selected' : ''; ?>>Concluído</option>
                </select><br>
                <button type="submit" class="btn btn-primary mt-3">Gravar</button>
                <a href="transacoes.php" class="btn btn-secondary mt-3 ms-2">Cancelar</a>
              </form>
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