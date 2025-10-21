<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Ubah Kelas</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= previous_url() ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Edit Kelas</h5>
    <form action="<?= base_url('admin/kelas/' . $kelas['id']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="kelas" class="form-label">Nama Kelas</label>
            <input type="text" class="form-control <?php if ($validation->hasError('kelas')) : ?>is-invalid<?php endif ?>" id="kelas" name="kelas" value="<?= $oldInput['kelas'] ?? $kelas['kelas']; ?>" placeholder="'7A', '7B'" required>
            <div class="invalid-feedback">
              <?= $validation->getError('kelas'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="jumlah_murid" class="form-label">Lantai</label>
            <input type="text" class="form-control <?php if ($validation->hasError('jumlah_murid')) : ?>is-invalid<?php endif ?>" id="jumlah_murid" name="jumlah_murid" value="<?= $oldInput['jumlah_murid'] ?? $kelas['jumlah_murid']; ?>" placeholder="jumlah_murid: 1">
            <div class="invalid-feedback">
              <?= $validation->getError('jumlah_murid'); ?>
            </div>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>