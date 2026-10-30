<?php
require_once __DIR__ . '/_auth.php';
require_admin();

require_once __DIR__ . '/../model/m_tarif.php';
require_once __DIR__ . '/../model/m_log.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$model = new M_Tarif();
$aksi = $_GET['aksi'] ?? 'tampil';

function kembali_tarif($msg = '')
{
    if ($msg !== '') $_SESSION['flash'] = $msg;
    header('Location: c_tarif.php?aksi=tampil');
    exit;
}

if ($aksi === 'tampil') {
    $tarif = $model->all($_GET['q'] ?? '');
    require __DIR__ . '/../views/v_tarif.php';
} elseif ($aksi === 'tambah') {
    require __DIR__ . '/../views/v_tambah_tarif.php';
} elseif ($aksi === 'proses_tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis_kendaraan'] ?? '';
    $nominal = (float) ($_POST['tarif_per_jam'] ?? 0);
    [$ok, $msg] = $model->add($jenis, $nominal);
    if ($ok) M_Log::catat($_SESSION['id_user'] ?? 0, 'Menambahkan tarif ' . $jenis . ' ' . rupiah_log($nominal));
    kembali_tarif($msg);
} elseif ($aksi === 'edit') {
    $data = $model->find((int) ($_GET['id'] ?? 0));
    if (!$data) kembali_tarif('Data tarif tidak ditemukan.');
    require __DIR__ . '/../views/v_edit_tarif.php';
} elseif ($aksi === 'proses_edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis = $_POST['jenis_kendaraan'] ?? '';
    $nominal = (float) ($_POST['tarif_per_jam'] ?? 0);
    [$ok, $msg] = $model->update((int) $_POST['id_tarif'], $jenis, $nominal);
    if ($ok) M_Log::catat($_SESSION['id_user'] ?? 0, 'Mengedit tarif ' . $jenis . ' menjadi ' . rupiah_log($nominal));
    kembali_tarif($msg);
} elseif ($aksi === 'hapus') {
    $id = (int) ($_GET['id'] ?? 0);
    $data = $model->find($id);
    $label = $data ? ($data['jenis_kendaraan'] . ' ' . rupiah_log($data['tarif_per_jam'])) : ('#' . $id);
    [$ok, $msg] = $model->delete($id);
    if ($ok) M_Log::catat($_SESSION['id_user'] ?? 0, 'Menghapus tarif ' . $label);
    kembali_tarif($msg);
} else {
    kembali_tarif();
}

function rupiah_log($v)
{
    return 'Rp' . number_format((float) $v, 0, ',', '.');
}
