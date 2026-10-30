<?php

function base_url() {
    $projectRoot = str_replace('\\', '/', dirname(__DIR__));
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')) : '';
    if ($docRoot !== '' && stripos($projectRoot, $docRoot) === 0) {
        return rtrim(substr($projectRoot, strlen($docRoot)), '/');
    }
    // Fallback kalau DOCUMENT_ROOT tidak cocok (jarang terjadi, mis. beberapa shared host).
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    return rtrim(dirname(dirname($script)), '/');
}

function e($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
function rupiah($v){return 'Rp'.number_format((float)$v,0,',','.');}
function tanggal_id($dt){return $dt?date('d/m/Y H:i',strtotime($dt)):'-';}
function flash(){if(session_status()!==PHP_SESSION_ACTIVE)session_start();$m=$_SESSION['flash']??'';unset($_SESSION['flash']);return $m;}
