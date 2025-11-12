<?php
session_start();

// Verificar si NO está logueado
if (!isset($_SESSION['user_id'])) {
    require_once 'templates/home/homePublic.php';
}else{
    require_once 'templates/home/homeIniciado.php';
}

