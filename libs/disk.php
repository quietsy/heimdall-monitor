<?php
require '../autoload.php';

$datas = array();

if (!(exec('/bin/df -T -B1 | awk -v c=`/bin/df -T -B1 | grep -bo "Type" | awk -F: \'{print $2}\'` \'{print substr($0,c);}\' | tail -n +2 | sort -r -k 5 -i | awk \'{print $1","$2","$3","$4","$5","$6","$7}\'', $df)))
{
    $datas[] = array(
        'total'         => 'N.A',
        'used'          => 'N.A',
        'free'          => 'N.A',
        'filesystem'    => 'N.A',
    );
}
else
{
    $mounted_points = array();
    $key = 0;

    foreach ($df as $mounted)
    {
        list($filesystem, $type, $total, $used, $free, $percent, $mount) = explode(',', $mounted);

        if (strpos($type, 'tmpfs') !== false)
            continue;

        if (strpos($filesystem, 'overlay') !== false)
            continue;

        if (!in_array($mount, $mounted_points))
        {
            $mounted_points[] = trim($mount);

            $datas[$key] = array(
                'total'         => Misc::getSize($total ),
                'used'          => Misc::getSize($used ),
                'free'          => Misc::getSize($free ),
            );

            $datas[$key]['filesystem'] = $filesystem;
        }

        $key++;
    }

}

$output = array_slice($datas, 0, 3);

echo json_encode($output);