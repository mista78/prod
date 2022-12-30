<?php


$params = [
    'database'  => denv('DEVLIE_ADMIN_DB'),
    'host'  => denv('DEVLIE_HOST'),
    'username'  => denv('DEVLIE_USER'),
    'password'  => denv('DEVLIE_PASS'),
    'prefix'    => denv('PREFIX') ?? "wp_" // default prefix is 'wp_', you can change to your own prefix
];
Corcel\Database::connect($params);

function connect()
{
    global $db, $data, $conf;
    $data = $data;
    try {
        foreach ($data as $key => $database) {
            if (isset($conf[$key]['db'])) {
                $db[$key] = $conf[$key]['db'];
                return true;
            }
            extract($data[$key]);
            $conn = new PDO("mysql:host=$host", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS $base";
            $conn->exec($sql);
            $pdo = new PDO(
                'mysql:host=' . $host . ';dbname=' . $base . ';',
                $user,
                $pass,
                [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']
            );
            $conf[$key]['db'] = $pdo;
            $db[$key] = $pdo;
        }
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
