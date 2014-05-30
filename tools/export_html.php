<?php 

include_once('../sites/default/settings.php');
include_once('./Smarty-3.1.10/libs/Smarty.class.php');

print_r($databases);

$smarty = new Smarty();
// print_r($smarty);
// $smarty->display('page_n.html');
// $page_n = $smarty->fetch('page_n.html');
// echo $page_n;

$dbhost = $databases['default']['default']['host'];
// $dbhost = "127.0.0.1";
$dbuser = $databases['default']['default']['username'];
$dbpass = $databases['default']['default']['password'];
$dbname = $databases['default']['default']['database'];
$dsn = "mysql:dbname=${dbname};host=${dbhost};port=3306;";

echo $dsn . "\n";
$dbh = new PDO($dsn, $dbuser, $dbpass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

$sql = "SELECT * FROM node_revisions ORDER BY nid DESC  LIMIT 5600";
$sql = "select node.nid, node.title,length(field_data_body.body_value), changed as timestamp, body_value as body  from node,field_data_body where field_data_body.entity_id=node.nid order by node.nid desc";

$gstmt = $dbh->prepare($sql);
$gstmt->execute();
print_r($gstmt);
echo "pdo error info: " . var_export($dbh->errorInfo(), true) . "\n";


$indexes = array();
$cnter = 0;
while ($row = $gstmt->fetch(PDO::FETCH_ASSOC)) {
    // print_r($row);
    $cnter ++;

    $nid = $row['nid'];
    $title = $row['title'];
    $body = $row['body'];
    $body = nl2br($body);

    $sdir = substr("${nid}", 0, 1);

    $indexes[] = array('nid'=>$nid, 'title'=>$title, 'date'=>$row['timestamp']);

    ////////////
    $smarty->assign('nid', $nid);
    $smarty->assign('title', $title);
    $smarty->assign('body', $body);
    $smarty->assign('date', $row['timestamp']);

    $page_n = $smarty->fetch('page_n.html');
    @mkdir("temp/${sdir}");
    file_put_contents("temp/${sdir}/node_${nid}.html", $page_n);
}


$chidxes = array_chunk($indexes, 20);
print_r($chidxes);


$icnter = 0;
foreach ($chidxes as $idx => $rows) {
    $prev = $idx - 1;
    $next = $idx + 1;
    $total_pages = count($chidxes)-1;
    $idx_pname = "temp/page_${idx}.html";
    $title = "QtChina | qtchina.github.io";

    foreach ($rows as $idx2 => $row) {
        $nid = $row['nid'];
        $date = date('Y-m-d H:i', $row['date']);
        $sdir = substr("${nid}", 0, 1);
        
        $rows[$idx2]['url'] = "${sdir}/node_${nid}.html";
    }

    ///////////
    $smarty->assign('title', $title);
    $smarty->assign('curr_page', $idx);
    $smarty->assign('prev_page', $prev);
    $smarty->assign('next_page', $next);
    $smarty->assign('total_pages', $total_pages);
    $smarty->assign('rows', $rows);

    $page_index = $smarty->fetch('page_index.html');
    file_put_contents($idx_pname, $page_index);

}

echo "count: ${cnter} \n";

echo "pdo error info: " . var_export($dbh->errorInfo(), true) . "\n";

var_dump($dbh);
print_r($dbh);

function goana()
{

}

