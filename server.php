<?php
if (!isset($argv[1]) || !isset($argv[2])) {
    echo "Usage: php $argv[0] <host> <port>\n";
    exit;
}

$host   =   $argv[1];
$port   =   $argv[2];

//CREATING TCP SOCKET
$socket     =   socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket)   echo "Failed to create socket: " . socket_strerror(socket_last_error()) . "\n";
//BINDING TO IT
$bind       =   socket_bind($socket, $host, $port);
if (!$bind)     echo "Failed to bind to socket: " . socket_strerror(socket_last_error()) . "\n";
//LISTENING TO SOCKET
$listen     =   socket_listen($socket, 5);
if (!$listen)     echo "Failed to listen to socket: " . socket_strerror(socket_last_error()) . "\n";

echo "HTTP Server running on $host port $port ...\n";

while (TRUE){
    //CLIENT SCOPE
    $clientSocket  =   socket_accept($socket);
    if (!$clientSocket)    {echo "Failed to accept socket connection: " . socket_strerror(socket_last_error()) . "\n"; break;}
        
    $msg    =   "HTTP/1.1 200 OK

Welcome to the web-server!\n";
    socket_write($clientSocket, $msg, strlen($msg));
    while (TRUE) {
    //MESSAGE SCOPE
        $buffer     =       socket_read($clientSocket, 2048, PHP_NORMAL_READ);
        
        if (!$buffer){
            echo "Failed to read socket: " . socket_strerror(socket_last_error()) . "\n";
            break;
        }
        
        $request    =   trim($buffer);      if (!$request)  continue;
        
        $msg        =   "Request $request sent to server\n";
        socket_write($clientSocket, $msg, strlen($msg));
        
        echo "$request\n";
    }
    socket_close($clientSocket);
}

socket_close($socket);
?>
