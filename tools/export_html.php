<?php 

include_once('../sites/default/settings.php');

print_r($databases);

$dbhost = $databases['default']['default']['host'];
// $dbhost = "127.0.0.1";
$dbuser = $databases['default']['default']['username'];
$dbpass = $databases['default']['default']['password'];
$dbname = $databases['default']['default']['database'];
$dsn = "mysql:dbname=${dbname};host=${dbhost};port=3306;";

echo $dsn . "\n";
$dbh = new PDO($dsn, $dbuser, $dbpass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

$sql = "SELECT * FROM node_revisions ORDER BY nid DESC  LIMIT 5600";

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

    $indexes[] = array('nid'=>$nid, 'title'=>$title, 'date'=>$row['timestamp']);

    $html = 
"
<!DOCTYPE HTML>
<html>
<head>
<meta charset='utf8'>
<title>${title}</title>
</head>
<body>
${body}
</body>
</html>
";
    
    $sdir = substr("${nid}", 0, 1);
    @mkdir("temp/${sdir}");
    file_put_contents("temp/${sdir}/node_${nid}.html", $html);
}



$chidxes = array_chunk($indexes, 20);
print_r($chidxes);


$icnter = 0;
foreach ($chidxes as $idx => $rows) {
    $prev = $idx - 1;
    $next = $idx + 1;
    $navbar = "<p><a href='page_${prev}.html'>上一页</a> <a href='page_${next}.html'>下一页</a> </p> <br />";
    $index_page = "<!DOCTYPE HTML><html><head><meta charset='utf8'><title></title></head><body> ${navbar}";
    foreach ($rows as $idx2 => $row) {
        $nid = $row['nid'];
        $title = $row['title'];
        $date = @date('Y-m-d H:i', $row['date']);
        $sdir = substr("${nid}", 0, 1);
        
        $index_page .= "<p>${date} <a href='${sdir}/node_${nid}.html' target='_blank'>${title}</a></p>\n";
    }

    $index_page .= "${navbar}</body></html>";
    
    $idx_pname = "temp/page_${idx}.html";
    file_put_contents($idx_pname, $index_page);
}

echo "count: ${cnter} \n";

echo "pdo error info: " . var_export($dbh->errorInfo(), true) . "\n";

var_dump($dbh);
print_r($dbh);

