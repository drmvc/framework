<?php
/**
 * Simple tests file
 */

namespace Modules\QveriBilder\Core;
require_once "QveriBilder.php";

$qb = new QveriBilder();

$database = 'dm';
$table = 'users';
$email = 'who@am.me';

echo "CREATE DATABASE $database;\n";
echo $qb->create()->database($database)->get() . "\n";

//echo "CREATE TABLE $table;\n";
echo $qb->create()->table($table)
        ->columns(array(array('id', 'INT', 'NOT NULL', 'AUTOINCREMENT', true), array('email', 'TEXT', 'NOT NULL')))
        ->get() . "\n";

echo "SELECT id,username FROM $table WHERE email = '$email';\n";
echo $qb->select(array('id', 'username'))->from($table)->where(array('email' => ':email'))->get();

echo "SELECT * FROM $table LEFT JOIN orders ON (orders.id_user = users.id) WHERE email = '$email' AND id = '1';\n";
echo $qb->select()->from($table)->left_join('orders', 'orders.id_user', 'users.id')->where(array('email' => $email, 'id' => 1))->get();
