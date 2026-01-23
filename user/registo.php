
<?php
include '../config.php';

// Initialize message
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  if (empty($nome) || empty($email) || empty($password)) {
    $message = 'Por favor preencha todos os campos.';
    $message_type = 'error';
  } else {
    // Hash password with MD5 as requested
    $password_hashed = md5($password);

    // Default values
    $perfil = 0;
    $creditos = 20;

    $sql = "INSERT INTO utilizadores (nome, email, password, creditos, perfil) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
      $stmt->bind_param('sssii', $nome, $email, $password_hashed, $creditos, $perfil);
      if ($stmt->execute()) {
        $message = 'Conta criada com sucesso! Pode agora iniciar sessão.';
        $message_type = 'success';
        // clear POST values
        $_POST = array();
      } else {
        $message = 'Erro ao criar conta: ' . $stmt->error;
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
  <title>Registo | Banco do Tempo</title>
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
              <h3 class="mb-0"><b>Nova conta</b></h3>
              <a href="login.php" class="link-primary">Já tem uma conta?</a>
            </div>
            <?php
            if (!empty($message)) {
                $cls = $message_type === 'success' ? 'alert-success' : 'alert-danger';
                echo "<div class='alert $cls' role='alert'>" . htmlspecialchars($message) . "</div>";
            }
            ?>
            <form action="" method="POST">
             <div class="form-group mb-3">
              <label class="form-label">Nome</label>
              <input type="text" name="nome" class="form-control" value="<?php echo isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : ''; ?>" required>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>
            <div class="form-group mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="d-flex mt-1 justify-content-between">
           
             
            </div>
            <div class="d-grid mt-4">
              <button type="submit" class="btn btn-primary">Registar</button>
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