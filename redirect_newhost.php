<?php

// print_r($_SERVER);
$curr_host = $_SERVER['HTTP_HOST'];
// echo $curr_host . "\n";

$poss_hosts = array('nullget.sourceforge.net',
                    'qtcona.tk', 'www.qtcona.tk');
$dest_hosts = array('qtchina.tk', 'www.qtchina.tk', 'cvs.qtchina.tk');
if (in_array($curr_host, $dest_hosts)) {
} else {
    $dest_url = "http://qtchina.tk{$_SERVER['REQUEST_URI']}";
    header("Location: {$dest_url}");

    echo("New Location: <a href='{$dest_url}'>{$dest_url}</a>\n");
    exit;
}


