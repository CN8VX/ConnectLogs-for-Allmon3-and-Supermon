<?php

// --------------------------------------------------
// Log configuration
// Configuration des logs
// --------------------------------------------------

// Log directory path
// Chemin du répertoire des logs
$log_directory = '/var/log/asterisk/';

// Log file names
// Noms des fichiers de logs
$log_filenames = [
    'connectlog',
    'connectlog.1',
    'connectlog.2',
    'connectlog.3',
    'connectlog.4'
];


// --------------------------------------------------
// ASTDB configuration
// Configuration ASTDB
// --------------------------------------------------

// ASTDB file path
// If you are using ConnectLogs with Allmon3, use:
// "/opt/logfils/astdb.txt"
// If you are using ConnectLogs with Supermon, use:
// "/var/www/html/supermon/astdb.txt"
//
// Chemin du fichier astdb.txt
// Si vous utilisez ConnectLogs avec Allmon3 , utilisez :
// "/opt/logfils/astdb.txt"
// Si vous utilisez ConnectLogs avec Supermon, utilisez :
// "/var/www/html/supermon/astdb.txt"
define("ASTDB_FILE", "/opt/logfils/astdb.txt");


// --------------------------------------------------
// EchoLink configuration
// Configuration EchoLink
// --------------------------------------------------

// EchoLink configuration file path
// Chemin du fichier de configuration EchoLink
define("ECHOLINK_CONF", "/etc/asterisk/echolink.conf");


// --------------------------------------------------
// Page display configuration
// Configuration de l’affichage de la page
// --------------------------------------------------

// Page title
// Titre de la page
$page_title = "Connection logs for AllStar and EchoLink";

// Logo path
// Chemin du logo
define("LOGO_PATH", "img/logo.png");

// Banner title
// Titre de la bannière
$TITLEBAN = "Connection logs for AllStar and EchoLink";

// Sysop name
// Nom du sysop
$SYSOP = "CN8VX";


// --------------------------------------------------
// Login configuration
// Configuration de l’authentification
// --------------------------------------------------

$CONFIG = [

    // Enable or disable login system (true = enabled, false = disabled)
    // Activer ou désactiver le système de connexion (true = activé, false = désactivé)
    'login_required' => true,

    // Display logged-in user and logout button
    // Only effective if login_required is enabled
    //
    // Afficher l’utilisateur connecté et le bouton de déconnexion
    // Actif uniquement si login_required est activé
    'show_user_info' => true,

    // User list ("username" => "password")
    // Liste des utilisateurs ("nom_utilisateur" => "mot_de_passe")
    'users' => [
        "admin" => "admin",
        "user"  => "123456",
        "user1" => "user123"
    ]
];


// --------------------------------------------------
// Auto-adjust configuration
// Ajustement automatique de la configuration
// --------------------------------------------------

// If login is disabled, force hiding user info
// Si la connexion est désactivée, forcer le masquage des infos utilisateur
if (!$CONFIG['login_required']) {
    $CONFIG['show_user_info'] = false;
}

?>
