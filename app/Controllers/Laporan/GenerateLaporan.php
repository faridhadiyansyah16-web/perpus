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

   public function generateLaporanLoan()
   {
      $bulan = $this->request->getVar('tanggalGuru');
      //$begin = new Time($bulan, locale: 'id');
      $dateString = $bulan; // Example date string
      $specificDate = Time::parse($dateString); // Parse the date string

      $monthName = $specificDate->format('F');
      $year = $specificDate->format('Y');
      $type = $this->request->getVar('type');
      //session()->setFlashdata(['msg' => 'tes ambil tanggal '.$monthName.' '.$year]); 
      $loan = $this->loanModel
            ->select('members.*, members.uid as member_uid, books.*, loans.*, loans.qr_code as loan_qr_code, book_stock.quantity as book_stock, racks.name as rack, categories.name as category')
            ->join('members', 'loans.member_id = members.id', 'LEFT')
            ->join('books', 'loans.book_id = books.id', 'LEFT')
            ->join('book_stock', 'books.id = book_stock.book_id', 'LEFT')
            ->join('racks', 'books.rack_id = racks.id', 'LEFT')
            ->join('categories', 'books.category_id = categories.id', 'LEFT')
            ->where("DATE_FORMAT(loan_date, '%Y-%m') =", $bulan)
            ->findAll();

      
        $data = [
            'loans'                 => $loan,
            'month'                => $monthName,
            'year'                 => $year,
        ];
      
         

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-excel');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_peminjaman.xls'
         );

         return view('generate-laporan/laporan-peminjaman-doc', $data);
      }

      return view('generate-laporan/laporan-peminjaman', $data). view('generate-laporan/topdf');
   }
}
