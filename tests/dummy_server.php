<?php

$address = 'localhost';
$port = 10000;
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($sock, $address, $port);
socket_listen($sock, 5);

do {
    $msgsock = socket_accept($sock);
    do {
        $buf = socket_read($msgsock, 2048, PHP_NORMAL_READ);
        echo $buf . PHP_EOL;
        if ($buf === 'exit') {
            break;
        }
    } while (true);
    socket_close($msgsock);
} while (true);

socket_close($sock);