<?php
require '../autoload.php';

$datas = array();

$a = exec('cat data/stats | tail -n +2 | sort --field-separator=\',\' -k 2 -h -r', $containers);

$key = 0;

foreach ($containers as $container)
{
	if ($key > 2) break;
    list($dname, $dcpu, $dmem, $dnet) = explode(',', $container);

    $datas[$key] = array(
        'dname'         => $dname,
        'dcpu'         => $dcpu,
        'dmem'         => explode(' / ', $dmem)[0],
        'dnet'         => $dnet,
    );

    $key++;
}


echo json_encode($datas);