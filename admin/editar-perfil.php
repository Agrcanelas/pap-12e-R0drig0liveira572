<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
include '../config.php';
// Ensure session is started and get logged-in user id
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

// Messages
$message = '';
$message_type = '';

// Get user id from session (logged-in user)
$id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

$current_nome = '';
$current_email = '';
$current_foto = '';
$current_creditos = '';
$current_perfil = '';

if ($id > 0) {
  // Handle POST update
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $foto = ''; // Will be set if file uploaded, otherwise keep current
    $file_error = false;
    
    // Handle uploaded photo (optional)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
      $file = $_FILES['foto'];
      if ($file['error'] !== UPLOAD_ERR_OK) {
        $message = 'Erro no upload do ficheiro.';
        $message_type = 'error';
        $file_error = true;
      } else {
        $allowed = array('jpg','jpeg','png','gif','bmp');
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
          $message = 'Tipo de ficheiro não permitido. Utilize jpg, jpeg, png, gif ou bmp.';
          $message_type = 'error';
          $file_error = true;
        } else {
          $upload_dir = __DIR__ . '/../uploads/';
          if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
          }
          $new_name = md5(date('Y-m-d H:i:s') . microtime(true)) . '.' . $ext;
          $destination = $upload_dir . $new_name;
          if (move_uploaded_file($file['tmp_name'], $destination)) {
            $foto = 'uploads/' . $new_name; // path saved in DB
          } else {
            $message = 'Não foi possível mover o ficheiro enviado.';
            $message_type = 'error';
            $file_error = true;
          }
        }
      }
    }
    
    $creditos = isset($_POST['creditos']) ? (int) $_POST['creditos'] : 0;
    $perfil = isset($_POST['perfil']) ? (int) $_POST['perfil'] : 0;

    if ($nome === '' || $email === '') {
      $message = 'Por favor preencha os campos nome e email.';
      $message_type = 'error';
    } elseif (!empty($file_error)) {
      // file_error already set and $message contains the error
    } else {
      if ($password !== '' && !empty($foto)) {
        // Update with new password and new photo
        $password_hashed = md5($password);
        $sql = "UPDATE utilizadores SET nome = ?, email = ?, password = ?, foto = ?, creditos = ?, perfil = ? WHERE id_utilizador = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
          $stmt->bind_param('ssssiii', $nome, $email, $password_hashed, $foto, $creditos, $perfil, $id);
          if ($stmt->execute()) {
            $message = 'Utilizador actualizado com sucesso.';
            $message_type = 'success';
          } else {
            $message = 'Erro ao actualizar utilizador: ' . $stmt->error;
            $message_type = 'error';
          }
          $stmt->close();
        } else {
          $message = 'Erro na preparação da consulta: ' . $conn->error;
          $message_type = 'error';
        }
      } elseif ($password !== '' && empty($foto)) {
        // Update with new password but keep current photo
        $password_hashed = md5($password);
        $sql = "UPDATE utilizadores SET nome = ?, email = ?, password = ?, creditos = ?, perfil = ? WHERE id_utilizador = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
          $stmt->bind_param('sssiii', $nome, $email, $password_hashed, $creditos, $perfil, $id);
          if ($stmt->execute()) {
            $message = 'Utilizador actualizado com sucesso.';
            $message_type = 'success';
          } else {
            $message = 'Erro ao actualizar utilizador: ' . $stmt->error;
            $message_type = 'error';
          }
          $stmt->close();
        } else {
          $message = 'Erro na preparação da consulta: ' . $conn->error;
          $message_type = 'error';
        }
      } elseif (empty($password) && !empty($foto)) {
        // Update with new photo but keep current password
        $sql = "UPDATE utilizadores SET nome = ?, email = ?, foto = ?, creditos = ?, perfil = ? WHERE id_utilizador = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
          $stmt->bind_param('sssiii', $nome, $email, $foto, $creditos, $perfil, $id);
          if ($stmt->execute()) {
            $message = 'Utilizador actualizado com sucesso.';
            $message_type = 'success';
          } else {
            $message = 'Erro ao actualizar utilizador: ' . $stmt->error;
            $message_type = 'error';
          }
          $stmt->close();
        } else {
          $message = 'Erro na preparação da consulta: ' . $conn->error;
          $message_type = 'error';
        }
      } else {
        // Update without changing password or photo
        $sql = "UPDATE utilizadores SET nome = ?, email = ?, creditos = ?, perfil = ? WHERE id_utilizador = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
          $stmt->bind_param('ssiii', $nome, $email, $creditos, $perfil, $id);
          if ($stmt->execute()) {
            $message = 'Utilizador actualizado com sucesso.';
            $message_type = 'success';
          } else {
            $message = 'Erro ao actualizar utilizador: ' . $stmt->error;
            $message_type = 'error';
          }
          $stmt->close();
        } else {
          $message = 'Erro na preparação da consulta: ' . $conn->error;
          $message_type = 'error';
        }
      }
    }
  }

  // Fetch current user values (fresh from DB after possible update)
  $sql = "SELECT nome, email, foto, creditos, perfil FROM utilizadores WHERE id_utilizador = ? LIMIT 1";
  $stmt = $conn->prepare($sql);
  if ($stmt) {
    $stmt->bind_param('i', $id);
    if ($stmt->execute()) {
      $stmt->bind_result($current_nome, $current_email, $current_foto, $current_creditos, $current_perfil);
      $stmt->fetch();
    }
    $stmt->close();
  }
} else {
  $message = 'ID de utilizador inválido.';
  $message_type = 'error';
}

?>
<!DOCTYPE html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Editar Perfil | Banco do Tempo</title>
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
                <h2 class="mb-0">Editar Perfil</h2>
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
                  <form action="" method="POST" enctype="multipart/form-data">
                    <label class="form-label">Nome</label>
              <input type="text" class="form-control" name="nome" id="nome" value="<?php echo htmlspecialchars($current_nome); ?>" required=""><br>
              <label class="form-label">Email</label>
              <input type="text" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($current_email); ?>" required=""><br>
              <label class="form-label">Password (deixe em branco para manter a atual)</label>
              <div class="input-group mb-3">
                <input type="password" class="form-control" name="password" id="password" value="" >
                <button type="button" class="btn btn-outline-secondary" id="togglePassword" aria-label="Mostrar/ocultar password">
                  <i class="fas fa-eye" id="togglePasswordIcon"></i>
                </button>
              </div>
              <label class="form-label">Foto</label>
              <?php
              if (!empty($current_foto)) {
                echo "<div class='mb-2'><img src='../" . htmlspecialchars($current_foto) . "' alt='Foto de perfil' style='max-width: 200px; max-height: 200px;'></div>";
              }
              ?>
              <input type="file" class="form-control" name="foto" id="foto" accept=".jpg,.jpeg,.png,.gif,.bmp"><br>
              <small class="text-muted">Formatos permitidos: jpg, jpeg, png, gif, bmp</small><br>
              <label class="form-label">Créditos</label>
              <input type="number" class="form-control" name="creditos" id="creditos" value="<?php echo htmlspecialchars($current_creditos); ?>"><br>
              <label class="form-label">Perfil</label>
              <select class="form-control" name="perfil" id="perfil" required="">
                <option value="0" <?php echo ($current_perfil === '0' || $current_perfil === 0) ? 'selected' : ''; ?>>Subscritor</option>
                <option value="1" <?php echo ($current_perfil === '1' || $current_perfil === 1) ? 'selected' : ''; ?>>Admin</option>
              </select>
                  <button type="submit" class="btn btn-primary mt-3">Gravar</button>
                  <a href="index.php" class="btn btn-secondary mt-3 ms-2">Cancelar</a>
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

      <script>
        // Toggle password visibility
        (function(){
          const btn = document.getElementById('togglePassword');
          const input = document.getElementById('password');
          const icon = document.getElementById('togglePasswordIcon');
          if (btn && input && icon) {
            btn.addEventListener('click', function(){
              if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
              } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
              }
            });
          }
        })();
      </script>

</body>
<!-- [Body] end -->

</html>