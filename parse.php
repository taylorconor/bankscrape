<?php

if (!isset($argv[2]) || !isset($argv[1])) {
    die("Usage: php ".$argv[0]." [registration number] [PAC number]\n");
}

exec("/usr/local/bin/phantomjs scrape.js ".$argv[1]." ".$argv[2], $output, $s);
$res = implode("\n", $output);

include('simple_html_dom.php');
include('Database.php');

$db = Database::getConnection();

$html = str_get_html($res);

$account_list =$html->find(".myaccounts-list");
foreach ($account_list[0]->find(".myaccounts-item") as $account) {
    $name = trim(strip_tags($account->find(".fa-ml")[0]));
    $name = str_replace("-", "", str_replace(" ", "", $name));
    $balance = trim(str_replace(",", "", str_replace("Balance:", "", strip_tags($account->find(".balance")[0]))));

    $table = "CREATE TABLE IF NOT EXISTS `$name` (
                `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `balance` double NOT NULL,
                    PRIMARY KEY (`ts`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $res = $db->query($table);
    if ($res !== TRUE) {
        die($db->error."\n");
    }

    $sql = "INSERT INTO $name (balance) VALUES(".floatval($balance).");";
    $res = $db->query($sql);
    if ($res !== TRUE) {
        die($db->error."\n");
    }
}
