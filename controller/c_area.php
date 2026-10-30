<?php
require_once __DIR__ . '/_auth.php';
require_admin();

require_once __DIR__ . '/../model/m_area.php';
require_once __DIR__ . '/../model/m_log.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$model = new M_Area();
$aksi = $_GET['aksi'] ?? 'tampil';

function kembali_area($msg = '')
{
    if ($msg !== '') $_SESSION['flash'] = $msg;
    header('Location: c_area.php?aksi=tampil');
    exit;
}

if ($aksi === 'tampil') {
    $area = $model->all($_GET['q'] ?? '');
    require __DIR__ . '/../views/v_area.php';
} elseif ($aksi === 'tambah') {
    $kategori = $model->kategori();
    require __DIR__ . '/../views/v_tambah_area.php';
} elseif ($aksi === 'proses_tambah' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_area'] ?? '');
    if ($nama === '') kembali_area('Nama area wajib diisi.');
    [$ok, $msg] = $model->add($nama, (int) ($_POST['id_kategori'] ?? 0), (int) ($_POST['kapasitas'] ?? 0));
    if ($ok) M_Log::catat($_SESSION['id_user'] ?? 0, 'Menambahkan area parkir ' . $nama);
    kembali_area($msg);
} elseif ($aksi === 'edit') {
    $data = $model->find((int) ($_GET['id'] ?? 0));
    if (!$data) kembali_area('Data area tidak ditemukan.');
    $kategori = $model->kategori();
    require __DIR__ . '/../views/v_edit_area.php';
} elseif ($aksi === 'proses_edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_area'] ?? '');
    if ($nama === '') kembali_area('Nama area wajib diisi.');
    [$ok, $msg] = $model->update((int) $_POST['id_area'], $nama, (int) ($_POST['id_kategori'] ?? 0), (int) ($_POST['kapasitas'] ?? 0));
    if ($ok) M_Log::catat($_SESSION['id_user'] ?? 0, 'Mengedit area parkir ' . $nama);
    kembali_area($msg);
} elseif ($aksi === 'hapus') {
    $id = (int) ($_GET['id'] ?? 0);
    $data = $model->find($id);
    $nama = $data['nama_area'] ?? ('#' . $id);
    [$ok, $msg] = $model->delete($id);
    if ($ok) M_Log::catat($_SESSION['id_user'] ?? 0, 'Menghapus area parkir ' . $nama);
    kembali_area($msg);
} else {
    kembali_area();
}
