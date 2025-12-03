<?= $this->extend('layouts/print_layout_doc') ?>
<?= $this->section('content') ?>
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-4">
      <table class="w-100 justify-center">
         <tr>
            <td>&nbsp;</td>
            <td width="100%">
               <h2 align="center">DAFTAR BUKU</h2>
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
   <div>
      <table>
          <tr>
            <th align="center">No</th>
            <th align="center">Judul Buku</th>
            <th align="center">Pengarang</th>
            <th align="center">Penerbit</th>
            <th align="center" width="100px">Tahun Terbit</th>
            <th align="center">Jumlah Buku</th>
         </tr>
         <tr>
            <?php $i = 1; ?>
            <?php foreach ($books as $book) : ?>
               <td align="center" width="50px"><?= $i++; ?></td>
               <td width="300px"><?= $book['title']; ?></td>
               <td><?= $book['author']; ?></td>
               <td><?= $book['publisher']; ?></td>
               <td align="center"><?= $book['year']; ?></td>
               <td align="center"><?= $book['quantity']; ?></td>
         </tr>
            <?php endforeach; ?>
      </table>
   </div>
</div>
<?= $this->endSection() ?>