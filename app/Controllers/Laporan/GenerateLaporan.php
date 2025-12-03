<?php

namespace App\Controllers\Laporan;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;
use DateTime;
use DateInterval;
use DatePeriod;

use App\Models\BookModel;
use App\Models\CategoryModel;
use App\Models\FineModel;
use App\Models\LoanModel;
use App\Models\MemberModel;
use App\Models\RackModel;

class GenerateLaporan extends BaseController
{
   protected BookModel $bookModel;
    protected RackModel $rackModel;
    protected CategoryModel $categoryModel;
    protected MemberModel $memberModel;
    protected LoanModel $loanModel;
    protected FineModel $fineModel;

   public function __construct()
   {
      $this->bookModel = new BookModel;
        $this->rackModel = new RackModel;
        $this->categoryModel = new CategoryModel;
        $this->memberModel = new MemberModel;
        $this->loanModel = new LoanModel;
        $this->fineModel = new FineModel;
   }

   public function index()
   {     
      $books = $this->bookModel
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->findAll();

        $totalBookStocks = array_reduce(
            array_map(function ($book) {
                return $book['quantity'];
            }, $books),
            function ($carry, $item) {
                return ($carry + $item);
            }
        );

        $data = [
            'books'                 => $books,
            'totalBookStock'        => $totalBookStocks,
            'racks'                 => $this->rackModel->findAll(),
            'categories'            => $this->categoryModel->findAll(),
            'members'               => $this->memberModel->findAll(),
            'loans'                 => $this->loanModel->findAll(),
        ];

      return view('generate-laporan/generate-laporan', $data);
   }

   public function generateLaporanBuku()
   {
      $type = $this->request->getVar('type');

      $books = $this->bookModel
            ->select('books.*, book_stock.quantity, categories.name as category, racks.name as rack, racks.floor')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->findAll();

       $data = [
            'books'                 => $books,
            'racks'                 => $this->rackModel->findAll(),
            'categories'            => $this->categoryModel->findAll(),
            'members'               => $this->memberModel->findAll(),
            'loans'                 => $this->loanModel->findAll(),
        ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-excel');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_buku.xls'
         );

         return view('generate-laporan/laporan-buku-doc', $data);
      }

      return view('generate-laporan/laporan-buku', $data).view('generate-laporan/topdf');
   }

   public function generateLaporanGuru()
   {
      $guru = $this->guruModel->getAllGuru();
      $type = $this->request->getVar('type');

      if (empty($guru)) {
         session()->setFlashdata([
            'msg' => 'Data guru kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $bulan = $this->request->getVar('tanggalGuru');

      // hari pertama dalam 1 bulan
      $begin = new Time($bulan, locale: 'id');
      // tanggal terakhir dalam 1 bulan
      $end = (new DateTime($begin->format('Y-m-t')))->modify('+1 day');
      // interval 1 hari
      $interval = DateInterval::createFromDateString('1 day');
      // buat array dari semua hari di bulan
      $period = new DatePeriod($begin, $interval, $end);

      $arrayTanggal = [];
      $dataAbsen = [];

      foreach ($period as $value) {
         // kecualikan hari sabtu dan minggu
         if (!($value->format('D') == 'Sat' || $value->format('D') == 'Sun')) {
            $lewat = Time::parse($value->format('Y-m-d'))->isAfter(Time::today());

            $absenByTanggal = $this->presensiGuruModel
               ->getPresensiByTanggal($value->format('Y-m-d'));

            $absenByTanggal['lewat'] = $lewat;

            array_push($dataAbsen, $absenByTanggal);
            array_push($arrayTanggal, Time::createFromInstance($value, locale: 'id'));
         }
      }

      $laki = 0;

      foreach ($guru as $value) {
         if ($value['jenis_kelamin'] != 'Perempuan') {
            $laki++;
         }
      }

      $data = [
         'tanggal' => $arrayTanggal,
         'bulan' => $begin->toLocalizedString('MMMM'),
         'listAbsen' => $dataAbsen,
         'listGuru' => $guru,
         'jumlahGuru' => [
            'laki' => $laki,
            'perempuan' => count($guru) - $laki
         ],
         'grup' => 'guru',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_absen_guru_' . $begin->toLocalizedString('MMMM-Y') . '.doc'
         );

         return view('admin/generate-laporan/laporan-guru', $data);
      }

      return view('admin/generate-laporan/laporan-guru', $data) . view('admin/generate-laporan/topdf');
   }
}
