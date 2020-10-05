<?php
require 'autoload.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/frontend.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
    <script src="js/esm.js" type="text/javascript"></script>
    <script>
    $(function(){
        esm.getAll();
        setInterval(function(){ esm.getAll(); }, 5000);
    });
    </script>
    <style type="text/css"> body { background:none transparent; } </style>
</head>
<body>
<div id="main-container">
    <div class="box column-left" id="esm-system">
        <div class="box-header">
            <h1>System</h1>
        </div>
        <div class="box-content">
            <table class="firstBold">
                <tbody>
                    <tr>
                        <td>Uptime</td>
                        <td id="system-uptime"></td>
                    </tr>
                    <tr>
                        <td>Memory</td>
                        <td id="system-memory"></td>
                    </tr>
                    <tr>
                        <td>Swap</td>
                        <td id="system-swap"></td>
                    </tr>
                    <tr>
                        <td>CPU 1/5/15</td>
                        <td id="system-load"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="box column-left" id="esm-disk">
        <div class="box-header">
            <h1>Disk</h1>
        </div>

        <div class="box-content">
            <table>
                <thead>
                    <tr>
                        <th>Filesystem</th>
                        <th>Free</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php if(getenv("DOCKERSTATS") !== false){ ?>
    <div class="box column-right" id="esm-docker">
        <div class="box-header">
            <h1>Docker</h1>
        </div>

        <div class="box-content">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>CPU</th>
                        <th>Memory</th>
                        <th>Network</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
    <?php if(getenv("JELLYFINAPI") !== false){ ?>
    <div class="box column-right" id="esm-streams">
        <div class="box-header">
            <h1>Streams</h1>
        </div>

        <div class="box-content">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Media</th>
                        <th>Transcode</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
    <?php if(getenv("QBITTORRENTURL") !== false){ ?>
    <div class="box column-right" id="esm-downloads">
        <div class="box-header">
            <h1>Downloads</h1>
        </div>

        <div class="box-content">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Speed</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php } ?>
</div>
</body>
</html>
