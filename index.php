<?php
include 'include/functions.php';

$local_node = getLocalAllStarNode();
$allstar_logs = array_slice(getAllStarLogs(), 0, 10);
$echolink_active = isEchoLinkActive();
if ($echolink_active) {
    $echolink_logs = array_slice(getEchoLinkLogs(), 0, 10);
    $echolink_info = getEchoLinkInfo();
}
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
  <title>Connection logs for AllStar and EchoLink</title>
</head>

<body>
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
<h1>Logs AllStarLink</h1>

<?php if ($local_node): ?>
<h2>For Node: <span class="colr-vrf"><?= htmlspecialchars($local_node) ?></span></h2>
<?php endif; ?>

&nbsp;
<?php if (!empty($allstar_logs)): ?>
  <table>
    <tr><th>Date</th><th>Action</th><th>Node</th><th>Desc / Call</th><th>Info</th></tr>
    <?php foreach ($allstar_logs as $log): ?>
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
      </tr>
    <?php endforeach; ?>
    <!-- Bouton Voir plus pour AllStar -->
    <tr>
      <td colspan="6" style="text-align:center;">
        <a target="_blank" href="allstar.php">
          <button class="button-01">View more</button>
        </form>
      </td>
    </tr>
  </table>
  &nbsp;
  <?php else: ?>
  <p class="clignote cetr">No AllStar log available.</p>
  <!-- <p class="text-cetr">It is imperative to install <a class="lien" target="_blank" href="https://www.dmr-maroc.com/astuces_tips_asl3.php#interface_graphique_Supermon">Supermon</a> and <a class="lien" target="_blank" href="https://www.dmr-maroc.com/astuces_tips_asl3.php#Activer_le_journal">enable the connection and disconnection log</a> in <code>rpt.conf</code></p> -->
  <?php endif; ?>

  <?php if (!empty($echolink_logs)): ?>
  <br><br>
<h1>Logs EchoLink</h1>
    <h2>EchoLink Call: <span class="colr-vrf"><?php echo htmlspecialchars($echolink_info["call"]); ?></span></h2>
    <?php if (!empty($echolink_info["node"])) : ?>    
    <h2>For Node: <span class="colr-vrf"><?php echo htmlspecialchars($echolink_info["node"]); ?></span></h2>&nbsp;
    <?php endif; ?>  
  <table>
    <tr><th>Date</th><th>Action</th><th>Node</th><th>Callsign</th><th>IP</th></tr>
    <?php foreach ($echolink_logs as $log): ?>
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
    <!-- Bouton Voir plus pour EchoLink -->
    <tr>
        <td colspan="5" style="text-align:center;">
          <a target="_blank" href="echoLink.php">
            <button class="button-01">View more</button>
          </form>
        </td>
      </tr>
    </tr>
  </table>
&nbsp;
<!-- Bouton Node Number Lookup -->
<div class="cetr">
  <a target="_blank" href="https://www.echolink.org/validation/node_lookup.jsp">
    <button class="button-01">Node Number Lookup</button>
  </a>
</div>
<?php endif; ?>
&nbsp;
<?php include 'include/hardware_Info.php'; ?>
</body>

<!-- Début du Footer -->
<?php include 'include/footer.php'; ?> 
<!-- Fin du Footer -->
</html>