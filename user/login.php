
<?php
include '../config.php';
session_start();

// Messages
$message = '';
$message_type = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Get form data
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? trim($_POST['password']) : '';

  // Validate input
  if (empty($email) || empty($password)) {
    $message = 'Por favor, preencha todos os campos.';
    $message_type = 'error';
  } else {
    // Prepare and execute query to find user
    $sql = "SELECT id_utilizador, nome, perfil, creditos FROM utilizadores WHERE email = ? AND password = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
      $password_hashed = md5($password);
      $stmt->bind_param("ss", $email, $password_hashed);
      
      if ($stmt->execute()) {
        $stmt->bind_result($id_utilizador, $nome, $perfil, $creditos);
        
        if ($stmt->fetch()) {
          // User found, check perfil
          if ($perfil == 0) {
            // Admin user, set session and redirect to admin login page
            $_SESSION['user_id'] = $id_utilizador;
            $_SESSION['user_name'] = $nome;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_perfil'] = $perfil;
            $_SESSION['user_creditos'] = $creditos;
            
            $stmt->close();
            // Redirect to admin area
            header("Location: index.php");
            exit();
          } elseif ($perfil == 1) {
            // Subscriber user, allow login to this page
            $_SESSION['user_id'] = $id_utilizador;
            $_SESSION['user_name'] = $nome;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_perfil'] = $perfil;
            $_SESSION['user_creditos'] = $creditos;
            
            $message = 'Login realizado com sucesso!';
            $message_type = 'success';
            $stmt->close();
            // Redirect to user dashboard or home after 2 seconds
            header("Refresh: 2; url=index.php");
            exit();
          } else {
            $message = 'Acesso negado.';
            $message_type = 'error';
          }
        } else {
          $message = 'Email ou password incorretos.';
          $message_type = 'error';
        }
      } else {
        $message = 'Erro ao validar credenciais: ' . $stmt->error;
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
  <title>Login | Banco do Tempo</title>
  <!-- [Meta] -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- [Favicon] icon -->
  <link rel="icon" href="../assets/images/logotipo.png" type="image/x-icon"> <!-- [Google Font] Family -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" id="main-font-link">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="../assets/fonts/tabler-icons.min.css" >
<!-- [Feather Icons] https://feathericons.com -->
<link rel="stylesheet" href="../assets/fonts/feather.css" >
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="../assets/fonts/fontawesome.css" >
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="../assets/fonts/material.css" >
<!-- [Template CSS Files] -->
<link rel="stylesheet" href="../assets/css/style.css" id="main-style-link" >
<link rel="stylesheet" href="../assets/css/style-preset.css" >

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
  <!-- [ Pre-loader ] start -->
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="auth-header" style="justify-content:center">
          <a href="#"><img src="../assets/images/logotipo.png" alt="Banco do Tempo" style="max-width:200px"></a>
        </div>
        <div class="card my-5">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-end mb-4">
              <h3 class="mb-0"><b>Login</b></h3>
              <a href="registo.php" class="link-primary">Criar nova conta</a>
            </div>
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
              <div class="form-group mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" required>
              </div>
              <div class="form-group mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>
          
          </div>
        </div>
        <div class="auth-footer row">
          <!-- <div class=""> -->
            <div class="col my-1">
              <p class="m-0" align="center">Banco do Tempo, 2026 © Todos os direitos reservados</p>
            </div>
          <!-- </div> -->
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