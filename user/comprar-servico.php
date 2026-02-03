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

// Get current user ID and credits
$user_id = (int)$_SESSION['user_id'];
$user_creditos = isset($_SESSION['user_creditos']) ? (int)$_SESSION['user_creditos'] : 0;

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $id_servico = isset($_POST['id_servico']) ? (int)$_POST['id_servico'] : 0;
  $horas = isset($_POST['horas']) ? (int)$_POST['horas'] : 0;

  // Validate input
  if (empty($id_servico) || empty($horas)) {
    header("Location: index.php?error=dados_invalidos");
    exit();
  }

  // Check if user has enough credits
  if ($user_creditos < $horas) {
    header("Location: index.php?error=creditos_insuficientes");
    exit();
  }

  // Get service details
  $sql_servico = "SELECT id_prestador FROM servicos WHERE id_servico = ?";
  $stmt_servico = $conn->prepare($sql_servico);
  $stmt_servico->bind_param("i", $id_servico);
  $stmt_servico->execute();
  $result_servico = $stmt_servico->get_result();

  if ($result_servico->num_rows === 0) {
    $stmt_servico->close();
    header("Location: index.php?error=servico_nao_encontrado");
    exit();
  }

  $row_servico = $result_servico->fetch_assoc();
  $id_prestador = (int)$row_servico['id_prestador'];
  $stmt_servico->close();

  // Check if user is trying to buy their own service
  if ($id_prestador === $user_id) {
    header("Location: index.php?error=nao_pode_comprar_seu_servico");
    exit();
  }

  // Start transaction
  $conn->begin_transaction();

  try {
    // Insert transaction record
    $sql_transacao = "INSERT INTO transacoes (id_receptor, id_servico, horas_trocadas, estado) VALUES (?,?,?,'pendente')";
    $stmt_transacao = $conn->prepare($sql_transacao);
    $stmt_transacao->bind_param("iii", $user_id, $id_servico, $horas);

    if (!$stmt_transacao->execute()) {
      throw new Exception("Erro ao criar transação: " . $stmt_transacao->error);
    }
    $stmt_transacao->close();

    // Deduct credits from buyer
    $sql_update_buyer = "UPDATE utilizadores SET creditos = creditos - ? WHERE id_utilizador = ?";
    $stmt_update_buyer = $conn->prepare($sql_update_buyer);
    $stmt_update_buyer->bind_param("ii", $horas, $user_id);

    if (!$stmt_update_buyer->execute()) {
      throw new Exception("Erro ao atualizar créditos do comprador: " . $stmt_update_buyer->error);
    }
    $stmt_update_buyer->close();

    // Add credits to service provider
    $sql_update_provider = "UPDATE utilizadores SET creditos = creditos + ? WHERE id_utilizador = ?";
    $stmt_update_provider = $conn->prepare($sql_update_provider);
    $stmt_update_provider->bind_param("ii", $horas, $id_prestador);

    if (!$stmt_update_provider->execute()) {
      throw new Exception("Erro ao atualizar créditos do prestador: " . $stmt_update_provider->error);
    }
    $stmt_update_provider->close();

    // Commit transaction
    $conn->commit();

    // Update session with new credit balance
    $_SESSION['user_creditos'] = $user_creditos - $horas;

    // Redirect to success page
    header("Location: index.php?success=1");
    exit();
  } catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    header("Location: index.php?error=erro_transacao");
    exit();
  }
} else {
  // If not POST request, redirect to dashboard
  header("Location: index.php");
  exit();
}
?>
