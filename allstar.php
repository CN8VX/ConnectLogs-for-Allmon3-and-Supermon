<?php
include 'include/functions.php';

$local_node = getLocalAllStarNode();
$allstar_logs = getAllStarLogs();
$echolink_logs = getEchoLinkLogs();
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
  <title>Log de Connexions AllSAllStarLink</title>
</head>
<body>
<div id="allstar">
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
<h1>Logs AllStar</h1>

<?php if ($local_node): ?>
<h2>For Node: <span class="colr-vrf"><?= htmlspecialchars($local_node) ?></span></h2>
<?php endif; ?>

&nbsp;
<?php if (!empty($allstar_logs)): ?>
<table>
  <tr><th>Date</th><th>Action</th><th>Node</th><th>Desc / Call</th><th>Info</th><th>IP</th></tr>
  <?php
  foreach ($allstar_logs as $log): ?>
    <tr>
      <td><?= $log['datetime'] ?></td>
      <td class="action"><?= $log['action'] ?></td>
      <td>
        <a class="lien" href="https://stats.allstarlink.org/stats/<?= htmlspecialchars($log['node']) ?>" target="_blank">
          <?= $log['node'] ?>
        </a>
      </td>
      <td>
        <a class="lien" href="https://www.qrz.com/db/<?= htmlspecialchars($log['indicatif']) ?>" target="_blank">
          <?= $log['indicatif'] ?>
        </a>
      </td>
      <td class="info"><?= $log['info'] ?></td>
      <td><?= $log['ip'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>
<?php else: ?>
  <p class="clignote cetr">No AllStar log available.</p>
  <p class="text-cetr">It is imperative to <a class="lien" target="_blank" href="https://www.dmr-maroc.com/astuces_tips_asl3.php#Activer_le_journal">enable the connection and disconnection log</a> in <code>rpt.conf</code></p>
<?php endif; ?>
&nbsp;
</div>
</body>
<!-- Début du Footer -->
<?php include 'include/footer.php'; ?> 
<!-- Fin du Footer -->
</html>