<?php
error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
require '../autoload.php';
$a = shell_exec('curl -i --header \'Referer: '.getenv('QBITTORRENTURL').'\' --data \''.getenv('QBITTORRENTAUTH').'\' --cookie-jar .data/qbcookie '.getenv('QBITTORRENTURL').'/api/v2/auth/login');
$response = shell_exec('curl --cookie .data/qbcookie '.getenv('QBITTORRENTURL').'/api/v2/torrents/info');
$torrents = json_decode($response);

$datas = array();
$key = 0;

foreach ($torrents as $torrent)
{
    if ((strpos($torrent->state, 'downloading') !== false) || (strpos($torrent->state, 'queuedDL') !== false)) {
        $datas[$key] = array(
            'dname'         => substr($torrent->name,0,40),
            'dstatus'         => $torrent->state,
            'dsize'         => Misc::getSize($torrent->total_size),
            'ddown'         => Misc::getSize($torrent->dlspeed),
            'dseeders'         => $torrent->num_seeds.'('.$torrent->num_complete.')',
            'ddownloaded'         => round($torrent->progress*100,2)."%"
        );

        $key++;
    }
}

echo json_encode($datas);

?>