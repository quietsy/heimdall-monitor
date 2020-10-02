<?php
require '../autoload.php';

// Uptime
if (!($totalSeconds = shell_exec('/usr/bin/cut -d. -f1 /proc/uptime')))
{
    $uptime = 'N.A';
}
else
{
    $uptime = Misc::getHumanTime($totalSeconds);
}

$free = 0;

if (shell_exec('cat /proc/meminfo'))
{
    $free    = shell_exec('grep MemFree /proc/meminfo | awk \'{print $2}\'');
    $buffers = shell_exec('grep Buffers /proc/meminfo | awk \'{print $2}\'');
    $cached  = shell_exec('grep Cached /proc/meminfo | awk \'{print $2}\'');

    $free = (int)$free + (int)$buffers + (int)$cached;
}

// Total
if (!($total = shell_exec('grep MemTotal /proc/meminfo | awk \'{print $2}\'')))
{
    $total = 0;
}

// Used
$used = $total - $free;

// Free
if (!($swfree = shell_exec('grep SwapFree /proc/meminfo | awk \'{print $2}\'')))
{
    $swfree = 0;
}

// Total
if (!($swtotal = shell_exec('grep SwapTotal /proc/meminfo | awk \'{print $2}\'')))
{
    $swtotal = 0;
}

// Used
$swused = $swtotal - $swfree;

if (!($load_tmp = shell_exec('cat /proc/loadavg | awk \'{print $1","$2","$3}\'')))
{
    $load = array(0, 0, 0);
}
else
{
    // Number of cores
    $cores = Misc::getCpuCoresNumber();

    $load_exp = explode(',', $load_tmp);

    $load = array_map(
        function ($value, $cores) {
            $v = (int)($value * 100 / $cores);
            if ($v > 100)
                $v = 100;
            return $v;
        }, 
        $load_exp,
        array_fill(0, 3, $cores)
    );
}

$uptime = str_replace(" minutes", "m", $uptime);
$uptime = str_replace(" hours", "h", $uptime);
$uptime = str_replace(" days", "d", $uptime);
$uptime = str_replace(" minute", "m", $uptime);
$uptime = str_replace(" hour", "h", $uptime);
$uptime = str_replace(" day", "d", $uptime);

$datas = array(
    'uptime'        => $uptime,
    'memory'        => Misc::getSize($used * 1024).' / '.Misc::getSize($total * 1024),
    'swap'          => Misc::getSize($swused * 1024).' / '.Misc::getSize($swtotal * 1024),
    'load'       => $load[0].'%/'.$load[1].'%/'.$load[2].'%'
);

echo json_encode($datas);