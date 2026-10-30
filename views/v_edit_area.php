<?php require_once __DIR__.'/_helpers.php'; $current_page_override='v_area.php'; $basePath=base_url();  ?>
<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit Area Parkir - SiParkir</title><link rel="stylesheet" href="<?=e($basePath.'/asset/dashboard_admin.css')?>"></head><body><div class="layout"><?php include __DIR__.'/_sidebar.php'; ?><main class="main"><header class="header"><div><p class="small-title">PARKING MANAGEMENT</p><h1>Edit Area Parkir</h1><p class="subtitle">Perbarui data area parkir.</p></div></header>
<section class="page-panel form-panel">
  <form method="post" action="<?=e($basePath.'/controller/c_area.php?aksi=proses_edit')?>">
    <input type="hidden" name="id_area" value="<?=$data['id_area']?>">
    <div class="form-group"><label>Nama Area</label><input class="form-input" name="nama_area" maxlength="50" required value="<?=e($data['nama_area'])?>"></div>
    <div class="form-group"><label>Kategori</label>
      <select class="form-select" name="id_kategori">
        <option value="0">- Tanpa kategori -</option>
        <?php foreach($kategori as $k):?><option value="<?=$k['id_kategori']?>" <?=((int)$data['id_kategori']===(int)$k['id_kategori'])?'selected':''?>><?=e($k['nama_kategori'])?></option><?php endforeach;?>
      </select></div>
    <div class="form-group"><label>Kapasitas (terisi saat ini: <?=$data['terisi']?>)</label><input class="form-input" type="number" name="kapasitas" min="<?=max(1,(int)$data['terisi'])?>" required value="<?=$data['kapasitas']?>"></div>
    <div class="form-actions"><button class="btn-primary">Simpan Perubahan</button><a class="btn-secondary" href="<?=e($basePath.'/controller/c_area.php?aksi=tampil')?>">Kembali</a></div>
  </form>
</section></main></div></body></html>
