<?php
error_reporting(E_ALL);

// Exécution infinie du script
set_time_limit(0);

//Permet d'afficher au fur et à mesure par vidage des buffers de sorties
ob_implicit_flush();

//Entrée adresse ip server voulue
$address_ip = '192.168.0.21';

//Port utilisé
$port_use = 80;

//Création du socket avec gestion des erreurs
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if ($socket === false) {
    
    echo "Raison de l'échec : " . socket_strerror(socket_last_error());
}

//Affectation nom socket avec gestion des erreurs
$bind = socket_bind($socket, $address_ip, $port_use);

if ($bind === false) {
    
    echo "Raison de l'échec : " . socket_strerror(socket_last_error($socket));
}

//Socket en attente de connection
$listen = socket_listen($socket, 10);

if ($listen === false) {
    
    echo "Raison de l'échec : " . socket_strerror(socket_last_error($socket));
}

do {

    //Accepte socket
    $sockHTTP = socket_accept($socket);

    if ($sockHTTP === false) {
        
        echo "Raisons de l'échec : " . socket_strerror(socket_last_error($socket));
        
        break;
    }

    //Message d'acceuil sur le server web
    $accueil = "Server web de JR-2897. Pour quitter, tapez 'quitter'. Afin d'éteindre le serveur, tapez 'switch off'.\n";

    socket_write($sockHTTP, $accueil, strlen($accueil));

    do {

        //Stockage requette HTTP avec gestion des erreurs
        $buffer = socket_read($sockHTTP, 2048, PHP_NORMAL_READ);

        if ($buffer === false ) {
            
            echo "Raisons de l'échec : " . socket_strerror(socket_last_error($sockHTTP));

            //Permet de quitter les deux boucles do while
            break 2;
        }

        if ($buffer == 'GET index.html HTTP/1.1') {
            
            $date = date('D d M Y h-i-s /GMT');

            $http_answer = "HTTP/1.1 200 OK\n";
            $http_answer = "Date: '$date'\n";
            $http_answer .= "Connection: keep-alive\n";
            $http_answer .= "Content-Type: text/html; charset=UTF-8\n";
            $http_answer .= "Accept-Language: en-us,fr\n";
            
            
            socket_write($sockHTTP, $http_answer, strlen($http_answer));

        }

        if ($buffer == 'GET index.html/admin HTTP/1.1') {
            $http_answer = "HTTP/1.1 403\n";
            socket_write($sockHTTP, $http_answer, strlen($http_answer));
        }

        if ($buffer == 'GET inde.html HTTP/1.1') {
            
            $http_answer = "HTTP/1.1 404\n";
            socket_write($sockHTTP, $http_answer, strlen($http_answer));

        }

        if ($buffer == 'HEAD index.html HTTP/1.1') {
            
            $date = date('D d M Y h-i-s /GMT');

            $http_answer = "HTTP/1.1 200 OK\n";
            $http_answer = "Date: '$date'\n";
            $http_answer .= "Connection: keep-alive\n";
            $http_answer .= "Content-Type: text/html; charset=UTF-8\n";
            $http_answer .= "Accept-Language: en-us,fr\n";            
            
            socket_write($sockHTTP, $http_answer, strlen($http_answer));

        }

        if ($buffer == 'HEAD index.html/admin HTTP/1.1') {
            $http_answer = "HTTP/1.1 403\n";
            socket_write($sockHTTP, $http_answer, strlen($http_answer));
        }

        if ($buffer == 'HEAD inde.html HTTP/1.1') {
            
            $http_answer = "HTTP/1.1 404\n";
            socket_write($sockHTTP, $http_answer, strlen($http_answer));

        }

        if ($buffer == 'quitter') {
            
            break;
        }

        if ($buffer == 'switch off') {
            
            socket_close($sockHTTP);

            //Permet de quitter les deux boucles do while
            break 2;
        }

        socket_write($sockHTTP, $buffer, strlen($buffer));
    } 
    while (true);

    socket_close($sockHTTP);

} 
while (true);

socket_close($sock);
?>