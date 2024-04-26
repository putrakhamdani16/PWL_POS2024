<?php

namespace App\Http\Controllers;

use App\Http\Requests\BarangRequest;
use App\Models\kategoriModel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\barangModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class barangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object)[
            'title' => 'Daftar Barang yang tersedia'
        ];

        $activeMenu = 'barang';
        $kategori = kategoriModel::all();

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page,
            'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $barangs = barangModel::select('barang_id', 'barang_kode', 'barang_nama',
            'harga_beli', 'harga_jual', 'kategori_id')->with('kategori');

//        FIltering data
        if ($request->kategori_id) {
            $barangs->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barangs)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '"class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '"> ' . csrf_field() . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function create(): view
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object)[
            'title' => "Tambah Barang Baru"
        ];

        $kategori = kategoriModel::all();
        $activeMenu = 'barang';

        return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page,
            'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'kategori_id' => 'required|integer'
        ]);

        barangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    public function show(string $id)
    {
        $barang = barangModel::with('kategori')->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object)[
            'title' => "Detail Barang"
        ];

        $activeMenu = 'barang';

        return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang,
            'activeMenu' => $activeMenu]);
    }

    public function edit(string $id)
    {
        $barang = barangModel::find($id);
        $kategori = kategoriModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object)[
            'title' => "Edit Barang"
        ];

        $activeMenu = 'barang';

        return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang,
            'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'kategori_id' => 'required|integer'
        ]);

        barangModel::find($id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = barangModel::find($id);
        if (!$check) {
            return redirect('/barang')->with('error', 'Data barang tidak ditermukan');
        }

        try {
            barangModel::destroy($id);

            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (QueryException $e) {

            return redirect('/barang')->with('error', 'Data barang gagal dihapus, karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}