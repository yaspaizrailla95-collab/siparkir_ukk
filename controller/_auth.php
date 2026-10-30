<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function is_logged_in()
{
    return !empty($_SESSION['id_user']);
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: c_login.php');
        exit;
    }
}

function get_role()
{
    return $_SESSION['role'] ?? '';
}

function is_admin()
{
    return get_role() === 'admin';
}

function is_petugas()
{
    return get_role() === 'petugas';
}

/**
 * Ambil URL dashboard sesuai role user yang sedang login.
 * Dipakai supaya setiap role otomatis diarahkan ke tampilannya masing-masing.
 */
function dashboard_url_for_role($role)
{
    switch ($role) {
        case 'admin':
            return 'c_dashboard.php';
        case 'owner':
            return 'c_dashboard_owner.php';
        default:
            return 'c_dashboard_petugas.php';
    }
}

function _tolak_akses($pesanRole)
{
    http_response_code(403);
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Akses Ditolak</title><style>body{font-family:Arial,sans-serif;background:#f5f7fb;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}.box{max-width:500px;background:#fff;border:1px solid #e7edf5;border-radius:16px;padding:30px;box-shadow:0 12px 35px rgba(0,0,0,.06)}h1{margin:0 0 8px;color:#1f3550;font-size:24px}p{color:#697b8f;line-height:1.6}.back{display:inline-block;margin-top:12px;padding:10px 15px;border-radius:9px;background:#3978c7;color:#fff;text-decoration:none}</style></head><body><div class="box"><h1>Akses ditolak</h1><p>Halaman ini hanya dapat diakses oleh akun <strong>' . htmlspecialchars($pesanRole, ENT_QUOTES, 'UTF-8') . '</strong>.</p><a class="back" href="c_login.php">Kembali ke Login</a></div></body></html>';
    exit;
}

/**
 * Wajibkan role tertentu. $roles bisa berupa satu string atau array role.
 */
function require_role($roles)
{
    require_login();

    $roles = (array) $roles;
    if (!in_array(get_role(), $roles, true)) {
        $label = implode(' / ', array_map('ucfirst', $roles));
        _tolak_akses($label);
    }
}

function require_admin()
{
    require_role('admin');
}

function require_petugas()
{
    require_role('petugas');
}

function require_owner()
{
    require_role('owner');
}

/**
 * Halaman operasional (data parkir, riwayat) yang boleh diakses
 * baik oleh Admin maupun Petugas.
 */
function require_staff()
{
    require_role(['admin', 'petugas']);
}

/**
 * Halaman laporan/riwayat: boleh dilihat Admin, Petugas, maupun Owner
 * (Owner hanya melihat, tidak melakukan input transaksi).
 */
function require_report_access()
{
    require_role(['admin', 'petugas', 'owner']);
}
