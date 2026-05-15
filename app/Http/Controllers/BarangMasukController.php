<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Supplier;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        return view('barang-masuk.index', [
            'barangs'   => Barang::all(),
            'suppliers' => Supplier::all(),
            'canAdd'    => auth()->user()->canAddMenu('barang-masuk'),
        ]);
    }

    // ================= DATATABLE =================
    public function getDataBarangMasuk()
    {
        $data = BarangMasuk::with(['barang', 'supplier'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        if (!auth()->user()->canAddMenu('barang-masuk')) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'], 403);
        }

        $request->validate([
            'tanggal_masuk' => 'required|date',
            'barang_id'     => 'required|exists:barang,id',
            'jumlah_masuk'  => 'required|numeric|min:1',
            'supplier_id'   => 'required|exists:suppliers,id'
        ], [
            'tanggal_masuk.required' => 'Tanggal masuk wajib diisi!',
            'tanggal_masuk.date'     => 'Format tanggal tidak valid!',
            'barang_id.required'     => 'Pilih barang terlebih dahulu!',
            'barang_id.exists'       => 'Barang tidak ditemukan!',
            'jumlah_masuk.required'  => 'Jumlah masuk wajib diisi!',
            'jumlah_masuk.numeric'   => 'Jumlah harus berupa angka!',
            'jumlah_masuk.min'       => 'Jumlah masuk minimal 1!',
            'supplier_id.required'   => 'Pilih supplier terlebih dahulu!',
            'supplier_id.exists'     => 'Supplier tidak ditemukan!',
        ]);

        try {
            $barang = Barang::findOrFail($request->barang_id);

            // Generate kode unik di server — pastikan tidak duplikat
            do {
                $kode = 'TRX-IN-' . now()->timezone('Asia/Jakarta')->format('Y-m-d') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (BarangMasuk::where('kode_transaksi', $kode)->exists());

            $barangMasuk = BarangMasuk::create([
                'tanggal_masuk'  => $request->tanggal_masuk,
                'barang_id'      => $barang->id,
                'jumlah_masuk'   => $request->jumlah_masuk,
                'supplier_id'    => $request->supplier_id,
                'kode_transaksi' => $kode,
                'user_id'        => auth()->id() ?? 1
            ]);

            // update stok
            $barang->increment('stok', $request->jumlah_masuk);

            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'data'    => $barangMasuk->load(['barang', 'supplier'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data. Silakan coba lagi.'
            ], 500);
        }
    }

    // ================= DELETE =================
    public function destroy(BarangMasuk $barangMasuk)
    {
        if (!auth()->user()->canAddMenu('barang-masuk')) {
            return response()->json(['message' => 'Anda tidak memiliki akses untuk melakukan aksi ini.'], 403);
        }

        $barang = $barangMasuk->barang;

        if ($barang) {
            $barang->stok = max(0, $barang->stok - $barangMasuk->jumlah_masuk);
            $barang->save();
        }

        $barangMasuk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Barang Masuk Berhasil Dihapus!'
        ]);
    }

    // ================= DETAIL (UNTUK SATUAN & STOK) =================
    public function getBarangDetail(Request $request)
    {
        $barang = Barang::find($request->barang_id);

        if (!$barang) {
            return response()->json([
                'stok' => 0,
                'satuan' => '-'
            ]);
        }

        return response()->json([
            'stok'   => $barang->stok ?? 0,
            'satuan' => $barang->satuan ?? '-'
        ]);
    }

    // ================= GET SATUAN (DEPRECATED - kept for compatibility) =================
    public function getSatuan()
    {
        return response()->json([]);
    }
}
