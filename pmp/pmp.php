<?php

/**
 *
 * @param sql string
 * @param params array
 */

/*
  settings.php template:
  $db_host = "";
  $db_port = 3306;
  $db_user = "";
  $db_pass = "";
  $db_name = "";
 */


require('settings.php');

$json_command = $_POST['command'];
$command = json_decode($json_command);

// $command = array('sql' => "SELECT * FROM user WHERE 1 = :v1", 'params' => array(':v1' => 1));

$db_name = $command['dbname'];
$sql = $command['sql'];
$params = $command['params'];

$dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name}";
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'');

$pdo = new PDO($dsn, $db_user, $db_pass, $options);

$stmt = $pdo->prepare($sql);

$ret = false;
if ($stmt) {
    $ret = $stmt->execute($params);
    $rows = array();

    for ($i = 0; $i < 3000; $i ++) {
        $row = $stmt->fetch(PDO::FETCH_BOTH);
        if ($row === false) {
            break;
        }
        $rows[] = $row;
    }
}

$result = array('ret'=> $ret,
                'rows' => $rows,
                'last_insert_id' => $pdo->lastInsertId(),
                'affected_rows' => $stmt->rowCount(),
                'pdo_errno' => $pdo->errorCode(),
                'pdo_error' => $pdo->errorInfo(),
                'stmt_errno' => $stmt->errorCode(),
                'stmt_error' => $stmt->errorInfo(),
                'sql' => $sql,
                'params' => $params,
                );

$json_result = json_encode($result);

echo $json_result;


