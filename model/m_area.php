<?php
require_once __DIR__ . '/m_koneksi.php';

class M_Area
{
    public function all($keyword = '')
    {
        global $conn;
        $where = '';
        if (trim($keyword) !== '') {
            $safe = mysqli_real_escape_string($conn, trim($keyword));
            $where = "WHERE a.nama_area LIKE '%$safe%' OR k.nama_kategori LIKE '%$safe%'";
        }
        $sql = "SELECT a.id_area, a.id_kategori, a.nama_area, a.kapasitas, a.terisi, k.nama_kategori
                FROM tb_area_parkir a LEFT JOIN tb_kategori k ON k.id_kategori=a.id_kategori
                $where ORDER BY a.id_area DESC";
        $q = mysqli_query($conn, $sql);
        return $q ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];
    }

    public function find($id)
    {
        global $conn;
        $stmt = mysqli_prepare($conn, 'SELECT * FROM tb_area_parkir WHERE id_area=?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt)->fetch_assoc();
    }

    public function kategori()
    {
        global $conn;
        $q = mysqli_query($conn, 'SELECT id_kategori, nama_kategori FROM tb_kategori ORDER BY nama_kategori');
        return $q ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];
    }

    private function namaSudahAda($nama, $exceptId = 0)
    {
        global $conn;
        $stmt = mysqli_prepare($conn, 'SELECT id_area FROM tb_area_parkir WHERE LOWER(nama_area)=LOWER(?) AND id_area<>?');
        mysqli_stmt_bind_param($stmt, 'si', $nama, $exceptId);
        mysqli_stmt_execute($stmt);
        return (bool) mysqli_stmt_get_result($stmt)->fetch_assoc();
    }

    public function add($nama, $idKategori, $kapasitas)
    {
        global $conn;
        if ($kapasitas < 1) return [false, 'Kapasitas minimal 1.'];
        if ($this->namaSudahAda($nama)) return [false, 'Nama area sudah ada.'];
        $idKategori = $idKategori > 0 ? $idKategori : null;
        $stmt = mysqli_prepare($conn, 'INSERT INTO tb_area_parkir (id_kategori, nama_area, kapasitas, terisi) VALUES (?,?,?,0)');
        mysqli_stmt_bind_param($stmt, 'isi', $idKategori, $nama, $kapasitas);
        return mysqli_stmt_execute($stmt) ? [true, 'Area parkir berhasil ditambahkan.'] : [false, mysqli_error($conn)];
    }

    public function update($id, $nama, $idKategori, $kapasitas)
    {
        global $conn;
        $area = $this->find($id);
        if (!$area) return [false, 'Data area tidak ditemukan.'];
        if ($kapasitas < 1) return [false, 'Kapasitas minimal 1.'];
        if ($kapasitas < (int) $area['terisi']) return [false, 'Kapasitas tidak boleh lebih kecil dari jumlah terisi (' . (int) $area['terisi'] . ').'];
        if ($this->namaSudahAda($nama, $id)) return [false, 'Nama area sudah dipakai.'];
        $idKategori = $idKategori > 0 ? $idKategori : null;
        $stmt = mysqli_prepare($conn, 'UPDATE tb_area_parkir SET id_kategori=?, nama_area=?, kapasitas=? WHERE id_area=?');
        mysqli_stmt_bind_param($stmt, 'isii', $idKategori, $nama, $kapasitas, $id);
        return mysqli_stmt_execute($stmt) ? [true, 'Area parkir berhasil diperbarui.'] : [false, mysqli_error($conn)];
    }

    public function delete($id)
    {
        global $conn;
        $stmt = mysqli_prepare($conn, 'SELECT COUNT(*) c FROM tb_transaksi WHERE id_area=?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        if ((int) mysqli_stmt_get_result($stmt)->fetch_assoc()['c'] > 0) return [false, 'Area tidak dapat dihapus karena sudah dipakai pada data transaksi.'];
        $stmt = mysqli_prepare($conn, 'DELETE FROM tb_area_parkir WHERE id_area=?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return mysqli_stmt_execute($stmt) ? [true, 'Area parkir berhasil dihapus.'] : [false, mysqli_error($conn)];
    }
}
