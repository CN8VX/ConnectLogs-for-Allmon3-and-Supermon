<?php

// --------------------------------------------------
// Configuration include
// Inclusion de la configuration
// --------------------------------------------------

include_once 'config.php';


// --------------------------------------------------
// Get local AllStar node from rpt.conf
// Récupérer le node AllStar local depuis rpt.conf
// --------------------------------------------------

function getLocalAllStarNode($rpt_conf = '/etc/asterisk/rpt.conf') {

    // Check if rpt.conf is readable
    // Vérifier si rpt.conf est lisible
    if (!is_readable($rpt_conf)) {
        return null;
    }

    // Read file line by line and find the first node section
    // Lire le fichier ligne par ligne et trouver le premier node
    foreach (file($rpt_conf, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (preg_match('/^\[(\d+)\]/', trim($line), $m)) {
            return $m[1]; // First node found / Premier node trouvé
        }
    }

    return null;
}


// --------------------------------------------------
// Get ASTDB node information
// Récupérer les informations du node depuis ASTDB
// --------------------------------------------------

function getAstdbNodeInfo($node) {

    // ASTDB file path defined in config.php
    // Chemin du fichier ASTDB défini dans config.php
    $file = ASTDB_FILE;

    // If ASTDB file does not exist, return empty data
    // Si le fichier ASTDB n’existe pas, retourner des valeurs vides
    if (!file_exists($file)) {
        return [
            'indicatif' => '',
            'info'      => ''
        ];
    }

    // Read ASTDB file
    // Lire le fichier ASTDB
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Parse each line
    // Analyser chaque ligne
    foreach ($lines as $line) {

        // Split fields separated by "|"
        // Séparer les champs avec "|"
        $parts = array_map('trim', explode('|', $line));

        // Validate line format
        // Vérifier le format de la ligne
        if (count($parts) < 4) {
            continue;
        }

        // Match node number
        // Comparer le numéro du node
        if ($parts[0] === (string)$node) {
            return [
                'indicatif' => $parts[1],
                'info'      => trim($parts[2] . ' ' . $parts[3])
            ];
        }
    }

    // Default empty return
    // Retour vide par défaut
    return [
        'indicatif' => '',
        'info'      => ''
    ];
}


// --------------------------------------------------
// Get AllStar connection logs
// Récupérer les logs de connexion AllStar
// --------------------------------------------------

function getAllStarLogs() {

    // Use global log configuration
    // Utiliser la configuration globale des logs
    global $log_directory, $log_filenames;

    $logs = [];

    // Loop through log files
    // Parcourir les fichiers de logs
    foreach ($log_filenames as $filename) {

        $file = $log_directory . $filename;

        // Skip if log file does not exist
        // Ignorer si le fichier de log n’existe pas
        if (!file_exists($file)) {
            continue;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            // Process only AllStar logs
            // Traiter uniquement les logs AllStar
            if (strpos($line, ' AllStar ') === false) {
                continue;
            }

            // Parse AllStar log format
            // Analyser le format du log AllStar
            if (preg_match(
                '/^(.+?) == (\d+) (Connected|Disconnected) AllStar (\d+) (<=IN==|=OUT=>|=v=)/',
                $line,
                $m
            )) {

                $datetime_raw = $m[1];
                $local_node   = $m[2];
                $action       = $m[3];
                $remote_node  = $m[4];

                // Format date and time
                // Formater la date et l’heure
                $datetime = date('d-m-Y H:i:s', strtotime($datetime_raw));

                // Lookup ASTDB information
                // Récupérer les infos ASTDB
                $nodeInfo = getAstdbNodeInfo($remote_node);

                $logs[] = [
                    'datetime'  => $datetime,
                    'action'    => $action,
                    'node'      => $remote_node,
                    'indicatif' => $nodeInfo['indicatif'],
                    'info'      => $nodeInfo['info'],
                    'ip'        => null
                ];
            }
        }
    }

    // Sort logs by descending date
    // Trier les logs par date décroissante
    usort($logs, function ($a, $b) {
        return strtotime($b['datetime']) - strtotime($a['datetime']);
    });

    return $logs;
}


// --------------------------------------------------
// Check if EchoLink module is active
// Vérifier si le module EchoLink est actif
// --------------------------------------------------

function isEchoLinkActive() {

    $modulesConfPath = "/etc/asterisk/modules.conf";

    // Check if modules.conf exists
    // Vérifier si modules.conf existe
    if (!file_exists($modulesConfPath)) {
        return false;
    }

    $lines = file($modulesConfPath);

    foreach ($lines as $line) {

        $line = trim($line);

        // Ignore commented lines
        // Ignorer les lignes commentées
        if (str_starts_with($line, ';')) {
            continue;
        }

        // Detect EchoLink module load state
        // Détecter l’état de chargement du module EchoLink
        if (str_contains($line, 'chan_echolink.so')) {

            if (str_starts_with($line, 'noload')) {
                return false;
            }

            if (str_starts_with($line, 'load')) {
                return true;
            }
        }
    }

    // Default: EchoLink not active
    // Par défaut : EchoLink non actif
    return false;
}


// --------------------------------------------------
// Get EchoLink connection logs
// Récupérer les logs de connexion EchoLink
// --------------------------------------------------

function getEchoLinkLogs() {

    global $log_directory, $log_filenames;

    $logs = [];
    $active_sessions = [];

    foreach ($log_filenames as $filename) {

        $file_path = $log_directory . $filename;

        if (!file_exists($file_path)) {
            continue;
        }

        $file_content = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($file_content as $line) {

            // Parse EchoLink log format
            // Analyser le format du log EchoLink
            if (preg_match(
                '/^(.+?) == (\d+) (Connected|Disconnected) EchoLink (\d+) (<=IN==|=OUT=>|=v=) (.*?) \[EchoLink (\d+)\] \((.*?)\)/',
                $line,
                $matches
            )) {

                $datetime_raw = $matches[1];
                // $matches[2] = local node (non utilisé)
                $action       = $matches[3];
                $node_id      = $matches[7];
                $symbol       = $matches[5];
                $callsign     = trim($matches[6]);
                $ip           = trim($matches[8]);

                // Format date and time
                // Formater la date et l'heure
                $datetime = date('d-m-Y H:i:s', strtotime($datetime_raw));

                // Normalize connection state
                // Normaliser l'état de connexion
                if (in_array($symbol, ['=OUT=>', '<=IN=='])) {
                    $action = 'Connected';
                } elseif ($symbol === '=v=') {
                    $action = 'Disconnected';
                }

                // Track active EchoLink sessions
                // Suivre les sessions EchoLink actives
                if ($action === 'Connected') {
                    $active_sessions[$node_id] = $callsign;
                } elseif ($action === 'Disconnected' && isset($active_sessions[$node_id])) {
                    $callsign = $active_sessions[$node_id];
                }

                $logs[] = [
                    'datetime'  => $datetime,
                    'action'    => $action,
                    'node'      => $node_id,
                    'indicatif' => $callsign,
                    'info'      => '',
                    'ip'        => $ip ?: 'No IP'
                ];
            }
        }
    }

    // Sort logs by descending date
    // Trier les logs par date décroissante
    usort($logs, function ($a, $b) {
        return strtotime($b['datetime']) - strtotime($a['datetime']);
    });

    return $logs;
}


// --------------------------------------------------
// Get EchoLink node information
// Récupérer les informations du node EchoLink
// --------------------------------------------------

function getEchoLinkInfo() {

    // EchoLink configuration file path
    // Chemin du fichier de configuration EchoLink
    $file = ECHOLINK_CONF;

    $call = "";
    $node = "";

    if (file_exists($file)) {

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $in_el0_section = false;

        foreach ($lines as $line) {

            $line = trim($line);

            // Remove inline comments
            // Supprimer les commentaires en fin de ligne
            $line = preg_replace('/\s*;.*$/', '', $line);

            // Detect [el0] section
            // Détecter la section [el0]
            if (preg_match('/^\[el0\]/i', $line)) {
                $in_el0_section = true;
                continue;
            }

            // End of [el0] section
            // Fin de la section [el0]
            if ($in_el0_section && preg_match('/^\[.+\]/', $line)) {
                break;
            }

            if ($in_el0_section) {

                // Read callsign
                // Lire l’indicatif
                if (preg_match('/^call\s*=\s*(.+)$/i', $line, $matches)) {
                    $call = trim($matches[1]);
                }

                // Read node number
                // Lire le numéro de node
                if (preg_match('/^node\s*=\s*(.+)$/i', $line, $matches)) {
                    $node = trim($matches[1]);
                }
            }
        }
    }

    // Ignore default node "000000"
    // Ignorer le node par défaut "000000"
    return [
        "call" => $call,
        "node" => ($node !== "000000" ? $node : "")
    ];
}


