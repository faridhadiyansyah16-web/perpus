<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Anggota Baru</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/members'); ?>" class="btn btn-outline-primary mb-3">
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
    <h5 class="card-title fw-semibold">Form Anggota Baru</h5>
    <form action="<?= base_url('admin/members'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row mt-3">
        <div class="col-12 col-md-6 mb-3">
          <label for="first_name" class="form-label">Nama depan</label>
          <input type="text" class="form-control <?php if ($validation->hasError('first_name')) : ?>is-invalid<?php endif ?>" id="first_name" name="first_name" value="<?= $oldInput['first_name'] ?? ''; ?>" placeholder="Dessy" required>
          <div class="invalid-feedback">
            <?= $validation->getError('first_name'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="last_name" class="form-label">Nama belakang</label>
          <input type="text" class="form-control <?php if ($validation->hasError('last_name')) : ?>is-invalid<?php endif ?>" id="last_name" name="last_name" value="<?= $oldInput['last_name'] ?? ''; ?>" placeholder="Herlia">
          <div class="invalid-feedback">
            <?= $validation->getError('last_name'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
          <label for="kelas" class="form-label">Kelas</label>
          <select class="form-select <?php if ($validation->hasError('kelas')) : ?>is-invalid<?php endif ?>" aria-label="Select kelas" id="kelas" name="kelas" value="<?= $oldInput['kelas'] ?? ''; ?>" required>
            <option>--Pilih Kelas--</option>
            <?php foreach ($kelas as $class) : ?>
              <option value="<?= $class['kelas']; ?>" <?= ($oldInput['kelas'] ?? '') == $class['id'] ? 'selected' : ''; ?>><?= $class['kelas']; ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
            <?= $validation->getError('kelas'); ?>
          </div>
        </div>
      </div>
      <div class="row">
            <div class="col-12 col-md-6 mb-3">
          <label for="nis" class="form-label">Nomor Induk Siswa</label>
          <input type="text" class="form-control <?php if ($validation->hasError('nis')) : ?>is-invalid<?php endif ?>" id="nis" name="nis" value="<?= $oldInput['nis'] ?? ''; ?>" placeholder="Nomor induk siswa..." required>
          <div class="invalid-feedback">
            <?= $validation->getError('nis'); ?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label class="form-label">Jenis kelamin</label>
          <div class="my-2 <?php if ($validation->hasError('gender')) : ?>is-invalid<?php endif ?>">
            <div class="form-check form-check-inline">
              <input type="radio" class="form-check-input" id="male" name="gender" value="1" <?= $oldInput['gender'] ?? '' == '1' ? 'checked' : ''; ?> required>
              <label class="form-check-label" for="male">
                Laki-laki
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input type="radio" class="form-check-input" id="female" name="gender" value="2" <?= $oldInput['gender'] ?? '' == '2' ? 'checked' : ''; ?> required>
              <label class="form-check-label" for="female">
                Perempuan
              </label>
            </div>
          </div>
          <div class="invalid-feedback">
            <?= $validation->getError('gender'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>