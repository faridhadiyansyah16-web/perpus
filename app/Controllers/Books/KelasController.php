<?php

namespace App\Controllers\Books;

use App\Models\KelasModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\RESTful\ResourceController;

class KelasController extends ResourceController
{
    protected KelasModel $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel;
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $itemPerPage = 20;

        $kelas = $this->kelasModel->paginate($itemPerPage, 'kelas');


        $data = [
            'kelas'             => $kelas,
            'pager'             => $this->kelasModel->pager,
            'currentPage'       => $this->request->getVar('page_kelas') ?? 1,
            'itemPerPage'       => $itemPerPage,
        ];

        return view('kelas/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $kelas = $this->kelasModel->where('id', $id)->first();

        if (empty($kelas)) {
            throw new PageNotFoundException('kelas not found');
        }

        $itemPerPage = 20;

        return view('books/index', $data);
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        return view('kelas/create', [
            'validation' => \Config\Services::validation(),
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        if (!$this->validate([
            'kelas'  => 'required|alpha_numeric_punct|max_length[2]',
            'jumlah_murid' => 'permit_empty|if_exist|alpha_numeric_punct|max_length[16]',
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('kelas/create', $data);
        }

        if (!$this->kelasModel->save([
            'kelas' => $this->request->getVar('kelas'),
            'jumlah_murid' => !empty($this->request->getVar('jumlah_murid')) ? $this->request->getVar('jumlah_murid') : 1,
        ])) {
            $data = [
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('kelas/create', $data);
        }

        session()->setFlashdata(['msg' => 'Insert new kelas successful']);
        return redirect()->to('admin/kelas');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        $kelas = $this->kelasModel->where('id', $id)->first();

        if (empty($kelas)) {
            throw new PageNotFoundException('kelas not found');
        }

        $data = [
            'kelas'          => $kelas,
            'validation'    => \Config\Services::validation(),
        ];

        return view('kelas/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $kelas = $this->kelasModel->where('id', $id)->first();

        if (empty($kelas)) {
            throw new PageNotFoundException('Category not found');
        }

        if (!$this->validate([
            'kelas'  => 'required|alpha_numeric_punct|max_length[2]',
            'jumlah_murid' => 'permit_empty|if_exist|alpha_numeric_punct|max_length[16]',
        ])) {
            $data = [
                'kelas'       => $kelas,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            return view('kelas/edit', $data);
        }

        if (!$this->kelasModel->save([
            'id'   => $id,
            'kelas' => $this->request->getVar('kelas'),
            'jumlah_murid' => !empty($this->request->getVar('jumlah_murid')) ? $this->request->getVar('jumlah_murid') : 1,
        ])) {
            $data = [
                'kelas'   => $kelas,
                'validation' => \Config\Services::validation(),
                'oldInput'   => $this->request->getVar(),
            ];

            session()->setFlashdata(['msg' => 'Insert failed']);
            return view('kelas/create', $data);
        }

        session()->setFlashdata(['msg' => 'Update kelas successful']);
        return redirect()->to('admin/kelas');
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        $kelas = $this->kelasModel->where('id', $id)->first();

        if (empty($kelas)) {
            throw new PageNotFoundException('kelas not found');
        }

        if (!$this->kelasModel->delete($id)) {
            session()->setFlashdata(['msg' => 'Failed to delete kelas', 'error' => true]);
            return redirect()->back();
        }

        session()->setFlashdata(['msg' => 'kelas deleted successfully']);
        return redirect()->to('admin/kelas');
    }
}
