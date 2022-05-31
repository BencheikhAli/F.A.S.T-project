<?php
//les constant de serveur
switch($_SERVER['HTTP_HOST']){
    case '10.14.252.114':
        define ('HOST', 'localhost');
        define ('PORT', 80);
        define ('DATA', 'fast');
        define ('USER', 'root');
        define ('PASS', '');
        break;
}