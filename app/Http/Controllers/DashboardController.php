<?php

namespace App\Http\Controllers;

use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } else {
            return $this->petugasDashboard();
        }
    }

    private function adminDashboard()
    {
        $totalBalita = Balita::where('is_active', true)->count();
        $totalPengukuran = Pengukuran::count();
        $totalBerisiko = Pengukuran::where('status_stunting', 'Berisiko Gangguan Pertumbuhan')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')->from('pengukuran')->groupBy('balita_id');
            })->count();
        $totalStunting = Pengukuran::where('status_stunting', 'Stunting')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')->from('pengukuran')->groupBy('balita_id');
            })->count();

        // Distribusi status gizi (data terbaru per balita)
        $distribusiStatus = $this->getDistribusiStatus();

        // Tren pengukuran bulanan (12 bulan terakhir)
        $trenBulanan = $this->getTrenBulanan();

        // Distribusi indikator
        $distribusiIndikator = $this->getDistribusiIndikator();

        // Posyandu stats
        $totalPosyandu = Posyandu::where('is_active', true)->count();
        $totalPetugas = User::where('role', 'petugas')->where('is_active', true)->count();

        return view('admin.dashboard', compact(
            'totalBalita', 'totalPengukuran', 'totalBerisiko', 'totalStunting',
            'distribusiStatus', 'trenBulanan', 'distribusiIndikator',
            'totalPosyandu', 'totalPetugas'
        ));
    }

    private function petugasDashboard()
    {
        $user = Auth::user();
        $posyanduIds = $user->posyandu_id ? [$user->posyandu_id] : [];

        $totalBalita = Balita::whereIn('posyandu_id', $posyanduIds)->where('is_active', true)->count();
        $totalPengukuran = Pengukuran::whereHas('balita', function ($q) use ($posyanduIds) {
            $q->whereIn('posyandu_id', $posyanduIds);
        })->count();

        // Pengukuran hari ini
        $pengukuranHariIni = Pengukuran::where('user_id', $user->id)
            ->whereDate('tanggal_ukur', today())
            ->count();

        // Status distribusi untuk posyandu ini
        $distribusiStatus = $this->getDistribusiStatus($posyanduIds);
        $trenBulanan = $this->getTrenBulanan($posyanduIds);
        $distribusiIndikator = $this->getDistribusiIndikator($posyanduIds);

        $totalBerisiko = Pengukuran::whereHas('balita', function ($q) use ($posyanduIds) {
            $q->whereIn('posyandu_id', $posyanduIds);
        })->where('status_stunting', 'Berisiko Gangguan Pertumbuhan')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')->from('pengukuran')->groupBy('balita_id');
            })->count();

        $totalStunting = Pengukuran::whereHas('balita', function ($q) use ($posyanduIds) {
            $q->whereIn('posyandu_id', $posyanduIds);
        })->where('status_stunting', 'Stunting')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')->from('pengukuran')->groupBy('balita_id');
            })->count();

        // Balita terakhir diukur
        $recentPengukuran = Pengukuran::with(['balita.posyandu'])
            ->whereHas('balita', function ($q) use ($posyanduIds) {
                $q->whereIn('posyandu_id', $posyanduIds);
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'totalBalita', 'totalPengukuran', 'pengukuranHariIni',
            'totalBerisiko', 'totalStunting',
            'distribusiStatus', 'trenBulanan', 'distribusiIndikator',
            'recentPengukuran'
        ));
    }

    private function getDistribusiStatus(array $posyanduIds = []): array
    {
        $query = Pengukuran::whereIn('id', function ($q) {
            $q->selectRaw('MAX(id)')->from('pengukuran')->groupBy('balita_id');
        });

        if (!empty($posyanduIds)) {
            $query->whereHas('balita', fn($q) => $q->whereIn('posyandu_id', $posyanduIds));
        }

        return [
            'Normal' => (clone $query)->where('status_stunting', 'Normal')->count(),
            'Berisiko' => (clone $query)->where('status_stunting', 'Berisiko Gangguan Pertumbuhan')->count(),
            'Stunting' => (clone $query)->where('status_stunting', 'Stunting')->count(),
        ];
    }

    private function getTrenBulanan(array $posyanduIds = []): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $query = Pengukuran::whereYear('tanggal_ukur', $date->year)
                ->whereMonth('tanggal_ukur', $date->month);

            if (!empty($posyanduIds)) {
                $query->whereHas('balita', fn($q) => $q->whereIn('posyandu_id', $posyanduIds));
            }

            $data[] = [
                'bulan' => $date->format('M Y'),
                'total' => $query->count(),
            ];
        }
        return $data;
    }

    private function getDistribusiIndikator(array $posyanduIds = []): array
    {
        $query = Pengukuran::whereIn('id', function ($q) {
            $q->selectRaw('MAX(tanggal_ukur)')->from('pengukuran')->groupBy('balita_id');
        });

        if (!empty($posyanduIds)) {
            $query->whereHas('balita', fn($q) => $q->whereIn('posyandu_id', $posyanduIds));
        }

        return [
            'bbu' => [
                'Gizi Buruk'  => (clone $query)->where('kategori_bbu', 'Gizi Buruk')->count(),
                'Gizi Kurang' => (clone $query)->where('kategori_bbu', 'Gizi Kurang')->count(),
                'Gizi Baik'   => (clone $query)->where('kategori_bbu', 'Gizi Baik')->count(),
                'Gizi Lebih'  => (clone $query)->where('kategori_bbu', 'Gizi Lebih')->count(),
                'Obesitas'    => (clone $query)->where('kategori_bbu', 'Obesitas')->count(),
            ],
            'tbu' => [
                'Sangat Pendek' => (clone $query)->where('kategori_tbu', 'Sangat Pendek')->count(),
                'Pendek'        => (clone $query)->where('kategori_tbu', 'Pendek')->count(),
                'Normal'        => (clone $query)->where('kategori_tbu', 'Normal')->count(),
                'Tinggi'        => (clone $query)->where('kategori_tbu', 'Tinggi')->count(),
            ],
            'bbtb' => [
                'Sangat Kurus' => (clone $query)->where('kategori_bbtb', 'Sangat Kurus')->count(),
                'Kurus'        => (clone $query)->where('kategori_bbtb', 'Kurus')->count(),
                'Normal'       => (clone $query)->where('kategori_bbtb', 'Normal')->count(),
                'Gemuk'        => (clone $query)->where('kategori_bbtb', 'Gemuk')->count(),
                'Obesitas'     => (clone $query)->where('kategori_bbtb', 'Obesitas')->count(),
            ],
        ];
    }
}