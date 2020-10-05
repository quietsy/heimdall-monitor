<?php
require '../autoload.php';
error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$datas = array();

$response = file_get_contents(getenv('JELLYFINAPI'));

$sessions = json_decode($response, true);
$key = 0;

foreach ($sessions as $session)
{
    if ($session['UserName'] != null && $session['NowPlayingItem']['Name'] != null)
    {
        $details = '';

        if ($session['PlayState']['PlayMethod'] == 'Transcode')
        {
            $details = $session['TranscodingInfo']['TranscodeReasons'][0] . ' ' . number_format((float)($session['TranscodingInfo']['Bitrate']/1000000), 2, '.', '') . 'Mbps ' . $session['TranscodingInfo']['VideoCodec'];
        }
        
        $datas[$key] = array(
            'duser'         => $session['UserName'],
            'dmedia'         => $session['NowPlayingItem']['Name'],
            'dtranscode'         => $session['PlayState']['PlayMethod'],
            'ddetails'         => $details,
            'dpaused'         => $session['PlayState']['IsPaused'] ? 'Paused' : 'Playing'
        );

        $key++;
    }
}

usort($datas, function ($a, $b) {
    return strcasecmp($a['duser'], $b['duser']);
});

echo json_encode($datas);