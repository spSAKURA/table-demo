<?php
/**
 * Created by PhpStorm.
 * User: zhaoj
 * Date: 2019/4/9
 * Time: 上午 9:19
 */
ini_set('display_errors',1);
$c = [
    'HOST' => '127.0.0.1',
    'USER' => 'root',
    'PWD'  => '123456',
    'DB'   => 'vdo',
    'CHAR' => 'utf8',
];
$mysqli = new mysqli($c['HOST'],$c['USER'],$c['PWD'],$c['DB']);
$mysqli ->set_charset($c['CHAR']);
return $mysqli;