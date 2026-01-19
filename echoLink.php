
<?php
include 'include/functions.php';

$allstar_logs = getAllStarLogs();
$echolink_logs = getEchoLinkLogs();
$echolink_info = getEchoLinkInfo();
?>
<?php
session_start();
if ($CONFIG['login_required'] && (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)) {
  header('Location: login.php');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <meta name="DMR-MAROC" content="Amaya, see https://www.dmr-maroc.com/" />
  <!-- Balises meta et informations de la page -->
  <meta name="title" content="Connection logs for AllStar and EchoLink By CN8VX" />
  <meta name="description" content="Connection logs for AllStar and EchoLink." />
  <meta property="og:image" content="img/M.A.R.R.I_trans.png">
  <link rel="shortcut icon" href="img/M.A.R.R.I_trans.png">
  <!-- CSS -->
  <link rel="stylesheet" href="css/style.css">
  <!-- JavaScript -->
  <script src="scripts/main.js"></script>
  <title>Log de Connexions EchoLink</title>
</head>
<body>
<div id="echolink">
  <!-- back to top -->
  <a href="#" class="back_to_top"><i class="fa fa-arrow-up"></i></a>
  <!-- back to top -->
<div class="logtile-container">
    <a><img class="icm" src="<?php echo LOGO_PATH; ?>" alt="Logo"></a>
    <span class="logo-title"><?php echo $TITLEBAN; ?></span>
</div>
<!-- Display user information and logout button -->
<?php if ($CONFIG['login_required'] && $CONFIG['show_user_info'] && isset($_SESSION['username'])): ?>
    <div>
        <h2>You are logged in as : <span class="colr-vrf"><?php echo htmlspecialchars($_SESSION['username']); ?></span></h2>
        <a href="logout.php"><button class="button-01">Logout</button></a>
    </div>
    <?php endif; ?>
  <br>
<h1>Logs EchoLink</h1>
    <h2>EchoLink Call: <span class="colr-vrf"><?php echo htmlspecialchars($echolink_info["call"]); ?></span></h2>
    <?php if (!empty($echolink_info["node"])) : ?>    
    <h2>For Node: <span class="colr-vrf"><?php echo htmlspecialchars($echolink_info["node"]); ?></span></h2>&nbsp;
    <?php endif; ?>  
<!-- Bouton Node Number Lookup -->
<div class="cetr">
  <a target="_blank" href="https://www.echolink.org/validation/node_lookup.jsp">
    <button class="button-01">Node Number Lookup</button>
  </a>
</div><br>
<?php if (!empty($echolink_logs)): ?>
<table>
  <tr><th>Date</th><th>Action</th><th>Node</th><th>Callsign</th><th>IP</th></tr>
  <?php
  foreach ($echolink_logs as $log): ?>
  <?php
        $indicatif = strtoupper($log['indicatif']);
        $clean_indicatif = preg_replace('/-(L|R)$/i', '', $indicatif);
      ?>
    <tr>
      <td><?= $log['datetime'] ?></td>
      <td class="action"><?= $log['action'] ?></td>
      <td><?= $log['node'] ?></td>
      <td>
        <a class="lien" href="https://www.qrz.com/db/<?= htmlspecialchars($clean_indicatif) ?>" target="_blank">
            <?= $indicatif ?>
        </a>
      </td>
      <td><?= $log['ip'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php else: ?>
<p class="clignote cetr">No EchoLink log available.</p>
<p class="text-cetr">The Echolink log table is only displayed when the option is enabled in AllstarLink</p>
<?php endif; ?>
&nbsp;
</div>
</body>
<!-- Début du Footer -->
<?php include 'include/footer.php'; ?> 
<!-- Fin du Footer -->
</html>