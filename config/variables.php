<?php

define("MI_HOST", 'http://localhost/2024/extramural/');

if(!$_SESSION){
  //header("location:".MI_HOST);
}

// Configuración de reporte de errores (excluye E_DEPRECATED y E_NOTICE)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', '0');

// Polyfill / Helper para codificación segura a UTF-8 compatible con PHP 8.2+
if (!function_exists('safe_utf8_encode')) {
    function safe_utf8_encode($str) {
        if ($str === null || $str === '') {
            return '';
        }
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($str, 'UTF-8', 'ISO-8859-1');
        }
        return @utf8_encode($str);
    }
}
?>