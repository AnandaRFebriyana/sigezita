<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\Posyandu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $posyanduList = Posyandu::where('is_active', true)->get();
        } else {
            $posyanduList = $user->posyandu
                ? collect([$user->posyandu])
                : collect();
        }
        return view('laporan.index', compact('posyanduList'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'posyandu_id'  => 'nullable|exists:posyandu,id',
            'tanggal_dari' => 'required|date',
            'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari',
            'format'       => 'required|in:html,pdf',
        ]);

        $query = Pengukuran::with(['balita.posyandu', 'petugas'])
            ->whereBetween('tanggal_ukur', [$request->tanggal_dari, $request->tanggal_sampai])
            ->orderBy('tanggal_ukur');

        if ($request->filled('posyandu_id')) {
            $query->whereHas('balita', fn($q) => $q->where('posyandu_id', $request->posyandu_id));
        }

        $user = Auth::user();
        if ($user->isPetugas()) {
            $query->whereHas('balita', function ($q) use ($user) {
                $q->where('posyandu_id', $user->posyandu_id);
            });
        }

        $pengukuran = $query->get();
        $posyandu = $request->filled('posyandu_id')
            ? Posyandu::find($request->posyandu_id)
            : null;

        // Ringkasan statistik
        $stats = [
            'total'    => $pengukuran->count(),
            'normal'   => $pengukuran->where('status_stunting', 'Normal')->count(),
            'berisiko' => $pengukuran->where('status_stunting', 'Berisiko Gangguan Pertumbuhan')->count(),
            'stunting' => $pengukuran->where('status_stunting', 'Stunting')->count(),
        ];

        $params = [
            'tanggal_dari'   => $request->tanggal_dari,
            'tanggal_sampai' => $request->tanggal_sampai,
        ];

        if ($request->input('format') === 'pdf') {
            return view('laporan.pdf', compact('pengukuran', 'posyandu', 'stats', 'params'));
        }

        return view('laporan.hasil', compact('pengukuran', 'posyandu', 'stats', 'params'));
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'posyandu_id'    => 'nullable|exists:posyandu,id',
            'tanggal_dari'   => 'required|date',
            'tanggal_sampai' => 'required|date',
        ]);

        $query = Pengukuran::with(['balita.posyandu', 'petugas'])
            ->whereBetween('tanggal_ukur', [$request->tanggal_dari, $request->tanggal_sampai])
            ->orderBy('tanggal_ukur');

        if ($request->filled('posyandu_id')) {
            $query->whereHas('balita', fn($q) => $q->where('posyandu_id', $request->posyandu_id));
        }

        $pengukuran = $query->get();

        // Generate CSV manual (tanpa dependency tambahan)
        $filename = 'laporan-gizi-' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($pengukuran) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

            // Header
            fputcsv($file, [
                'No', 'Tanggal Ukur', 'Kode Balita', 'Nama Balita',
                'Jenis Kelamin', 'Umur (Bulan)', 'BB (kg)', 'TB (cm)',
                'Kategori BB/U', 'Kategori TB/U', 'Kategori BB/TB',
                'Z-Score BB/U', 'Z-Score TB/U', 'Z-Score BB/TB',
                'Status Stunting', 'Posyandu', 'Petugas',
            ]);

            foreach ($pengukuran as $i => $p) {
                fputcsv($file, [
                    $i + 1,
                    $p->tanggal_ukur->format('d/m/Y'),
                    $p->balita->kode_balita,
                    $p->balita->nama_balita,
                    $p->balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                    $p->umur_bulan,
                    $p->berat_badan,
                    $p->tinggi_badan,
                    $p->kategori_bbu,
                    $p->kategori_tbu,
                    $p->kategori_bbtb,
                    $p->zscore_bbu,
                    $p->zscore_tbu,
                    $p->zscore_bbtb,
                    $p->status_stunting,
                    $p->balita->posyandu->nama ?? '-',
                    $p->petugas->name ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}