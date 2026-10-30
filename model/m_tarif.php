<?php
require_once __DIR__ . '/m_koneksi.php';

class M_Tarif
{
    const JENIS = ['motor', 'mobil', 'lainnya'];

    public function all($keyword = '')
    {
        global $conn;
        $where = '';
        if (trim($keyword) !== '') {
            $safe = mysqli_real_escape_string($conn, trim($keyword));
            $where = "WHERE t.jenis_kendaraan LIKE '%$safe%'";
        }
        $sql = "SELECT t.id_tarif, t.jenis_kendaraan, t.tarif_per_jam, COUNT(x.id_parkir) AS jumlah_transaksi
                FROM tb_tarif t LEFT JOIN tb_transaksi x ON x.id_tarif=t.id_tarif
                $where GROUP BY t.id_tarif, t.jenis_kendaraan, t.tarif_per_jam
                ORDER BY t.jenis_kendaraan, t.tarif_per_jam";
        $q = mysqli_query($conn, $sql);
        return $q ? mysqli_fetch_all($q, MYSQLI_ASSOC) : [];
    }

    public function find($id)
    {
        global $conn;
        $stmt = mysqli_prepare($conn, 'SELECT * FROM tb_tarif WHERE id_tarif=?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt)->fetch_assoc();
    }

    private function cek($jenis, $tarif)
    {
        if (!in_array($jenis, self::JENIS, true)) return 'Jenis kendaraan tidak valid.';
        if ($tarif <= 0) return 'Tarif per jam harus lebih dari 0.';
        return '';
    }

    private function sudahAda($jenis, $tarif, $exceptId = 0)
    {
        global $conn;
        $stmt = mysqli_prepare($conn, 'SELECT id_tarif FROM tb_tarif WHERE jenis_kendaraan=? AND tarif_per_jam=? AND id_tarif<>?');
        mysqli_stmt_bind_param($stmt, 'sdi', $jenis, $tarif, $exceptId);
        mysqli_stmt_execute($stmt);
        return (bool) mysqli_stmt_get_result($stmt)->fetch_assoc();
    }

    public function add($jenis, $tarif)
    {
        global $conn;
        if ($err = $this->cek($jenis, $tarif)) return [false, $err];
        if ($this->sudahAda($jenis, $tarif)) return [false, 'Tarif dengan jenis dan nominal yang sama sudah ada.'];
        $stmt = mysqli_prepare($conn, 'INSERT INTO tb_tarif (jenis_kendaraan, tarif_per_jam) VALUES (?,?)');
        mysqli_stmt_bind_param($stmt, 'sd', $jenis, $tarif);
        return mysqli_stmt_execute($stmt) ? [true, 'Tarif parkir berhasil ditambahkan.'] : [false, mysqli_error($conn)];
    }

    public function update($id, $jenis, $tarif)
    {
        global $conn;
        if (!$this->find($id)) return [false, 'Data tarif tidak ditemukan.'];
        if ($err = $this->cek($jenis, $tarif)) return [false, $err];
        if ($this->sudahAda($jenis, $tarif, $id)) return [false, 'Tarif dengan jenis dan nominal yang sama sudah ada.'];
        $stmt = mysqli_prepare($conn, 'UPDATE tb_tarif SET jenis_kendaraan=?, tarif_per_jam=? WHERE id_tarif=?');
        mysqli_stmt_bind_param($stmt, 'sdi', $jenis, $tarif, $id);
        return mysqli_stmt_execute($stmt) ? [true, 'Tarif parkir berhasil diperbarui.'] : [false, mysqli_error($conn)];
    }

    public function delete($id)
    {
        global $conn;
        $stmt = mysqli_prepare($conn, 'SELECT COUNT(*) c FROM tb_transaksi WHERE id_tarif=?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        if ((int) mysqli_stmt_get_result($stmt)->fetch_assoc()['c'] > 0) return [false, 'Tarif tidak dapat dihapus karena sudah dipakai pada data transaksi.'];
        $stmt = mysqli_prepare($conn, 'DELETE FROM tb_tarif WHERE id_tarif=?');
        mysqli_stmt_bind_param($stmt, 'i', $id);
        return mysqli_stmt_execute($stmt) ? [true, 'Tarif parkir berhasil dihapus.'] : [false, mysqli_error($conn)];
    }
}
