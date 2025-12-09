<?= $this->extend('layouts/print_layout') ?>
<?= $this->section('content') ?>
<?php 
use CodeIgniter\I18n\Time;
?>

<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-4">
      <table class="w-100 justify-center">
         <tr>
            <td>&nbsp;</td>
            <td width="100%">
               <h2 align="center">DAFTAR PEMINJAMAN BUKU <?= strtoupper($month) ?> <?= $year ?></h2>
               <h4 align="center"></h4>
               <h4 align="center">PERPUSTAKAAN SMPN 11 KOTA SUKABUMI</h4>
            </td>
            <td>
               <div style="width:100px"></div>
            </td>
         </tr>
      </table>
    </div>
   </div>
   <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nama peminjam</th>
            <th scope="col">Judul buku</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col">Tgl pinjam</th>
            <th scope="col">Tenggat</th>
            <th scope="col" class="text-center">Status</th>
          </tr>
        </thead>
          <tbody class="table-group-divider">
          <?php 
            $i = 1;
            $now = Time::now(locale: 'id');
          ?>
          <?php if (empty($loans)) : ?>
            <tr>
              <td class="text-center" colspan="8"><b>Tidak ada data</b></td>
            </tr>
          <?php endif; ?>
          <?php
          foreach ($loans as $key => $loan) :
            $loanCreateDate = Time::parse($loan['loan_date'], locale: 'id');
            $loanDueDate = Time::parse($loan['due_date'], locale: 'id');

            $isLate = $now->isAfter($loanDueDate);
            $isDueDate = $now->today()->difference($loanDueDate)->getDays() == 0;
          ?>
            <tr>
              <th scope="row"><?= $i++; ?></th>
              <td>
                  <p>
                    <b><?= "{$loan['first_name']} {$loan['last_name']}"; ?></b>
                  </p>
              </td>
              <td>
                  <p class="text-primary-emphasis text-decoration-underline"><b><?= "{$loan['title']} ({$loan['year']})"; ?></b></p>
                  <p class="text-body"><?= "Author: {$loan['author']}"; ?></p>
              </td>
              <td class="text-center"><?= $loan['quantity']; ?></td>
              <td>
                <b><?= $loanCreateDate->toLocalizedString('dd/MM/y'); ?></b><br>
                <b><?= $loanCreateDate->toLocalizedString('HH:mm:ss'); ?></b>
              </td>
              <td>
                <b><?= $loanDueDate->toLocalizedString('dd/MM/y'); ?></b>
              </td>
              <td class="text-center">
                <?php if ($now->isBefore($loanDueDate)) : ?>
                  <span class="badge bg-success rounded-3 fw-semibold">Normal</span>
                <?php elseif ($now->today()->equals($loanDueDate)) : ?>
                  <span class="badge bg-warning rounded-3 fw-semibold">Jatuh tempo</span>
                <?php else : ?>
                  <span class="badge bg-danger rounded-3 fw-semibold">Terlambat</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
</div>
<?= $this->endSection() ?>