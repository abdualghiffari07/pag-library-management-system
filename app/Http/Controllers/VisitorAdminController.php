<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\VisitorCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VisitorAdminController extends Controller
{
    // Daftar pengunjung
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sort = $request->query('sort', 'latest');

        if (!in_array($sort, ['latest', 'oldest', 'all'], true)) {
            $sort = 'latest';
        }

        $query = Visitor::query()
            ->select('visitors.*')
            ->selectSub(
                DB::table('loans')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('loans.visitor_id', 'visitors.visitor_id'),
                'loans_count'
            )
            ->withCount('checkins')
            ->when($search !== '', function ($query) use ($search) {
                $keyword = '%' . $search . '%';

                $query->where(function ($query) use ($keyword) {
                    $query->where('visitor_name', 'like', $keyword)
                        ->orWhere('employee_number', 'like', $keyword)
                        ->orWhere('phone_number', 'like', $keyword)
                        ->orWhere('visitor_category', 'like', $keyword);
                });
            });

        // Urutan data
        switch ($sort) {
            case 'oldest':
                $query->orderBy('visitors.created_at')
                    ->orderBy('visitors.visitor_id');
                break;

            case 'all':
                $query->orderBy('visitors.visitor_name')
                    ->orderBy('visitors.visitor_id');
                break;

            default:
                $query->orderByDesc('visitors.created_at')
                    ->orderByDesc('visitors.visitor_id');
                break;
        }

        return view('pages.tables.Visitors.visitors', [
            'title' => 'Daftar Pengunjung',
            'visitors' => $query->paginate(20)->withQueryString(),
            'search' => $search,
            'sort' => $sort,
        ]);
    }

    // Detail pengunjung dan riwayat selfie
    public function detail(Visitor $visitor)
    {
        $checkins = $visitor->checkins()
            ->orderByDesc('checked_in_at')
            ->orderByDesc('checkin_id')
            ->paginate(6);

        $lastCheckin = $visitor->checkins()
            ->orderByDesc('checked_in_at')
            ->orderByDesc('checkin_id')
            ->first();

        return response()->json([
            'visitor' => [
                'id' => $visitor->visitor_id,
                'name' => $visitor->visitor_name,
                'category' => $visitor->visitor_category,
                'identity' => $visitor->employee_number,
                'phone' => $visitor->phone_number ?: '-',
                'registered_at' => $visitor->created_at
                    ? $visitor->created_at->format('d M Y, H:i')
                    : '-',
                'profile_url' => $visitor->profile_photo
                    ? route('visitors.profile-photo', $visitor->visitor_id)
                    : null,
                'total_visits' => $checkins->total(),
                'last_visit' => $lastCheckin?->checked_in_at
                    ? $lastCheckin->checked_in_at->format('d M Y, H:i')
                    : '-',
            ],
            'history' => $checkins->getCollection()
                ->map(function ($checkin) {
                    return [
                        'id' => $checkin->checkin_id,
                        'date' => $checkin->checked_in_at
                            ? $checkin->checked_in_at->format('d M Y, H:i')
                            : '-',
                        'photo_url' => route(
                            'visitors.selfie',
                            $checkin->checkin_id
                        ),
                    ];
                })
                ->values(),
            'page' => $checkins->currentPage(),
            'last_page' => $checkins->lastPage(),
        ]);
    }

    // Foto profil privat
    public function profilePhoto(Visitor $visitor)
    {
        return $this->servePhoto(
            $visitor->profile_photo,
            'visitors/profiles'
        );
    }

    // Foto selfie privat
    public function selfie(VisitorCheckin $checkin)
    {
        return $this->servePhoto(
            $checkin->selfie_path,
            'visitors/checkins'
        );
    }

    // Hapus pengunjung
    public function destroy(Visitor $visitor)
    {
        try {
            $result = DB::transaction(function () use ($visitor) {
                $visitor = Visitor::whereKey($visitor->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $hasLoans = DB::table('loans')
                    ->where('visitor_id', $visitor->visitor_id)
                    ->exists();

                $hasCheckins = $visitor->checkins()->exists();

                // Riwayat harus tetap tersimpan
                if ($hasLoans || $hasCheckins) {
                    return [
                        'deleted' => false,
                        'path' => null,
                    ];
                }

                $path = $visitor->profile_photo;

                $visitor->delete();

                return [
                    'deleted' => true,
                    'path' => $path,
                ];
            });

            if (!$result['deleted']) {
                return back()->with(
                    'error',
                    'Pengunjung memiliki riwayat kunjungan atau peminjaman sehingga tidak dapat dihapus.'
                );
            }

            // Hapus foto setelah transaksi database berhasil
            if ($result['path']) {
                try {
                    Storage::disk('local')->delete($result['path']);
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            return back()->with(
                'success',
                'Data pengunjung berhasil dihapus.'
            );

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Data pengunjung gagal dihapus. Periksa apakah masih ada relasi yang tersimpan.'
            );
        }
    }

    // Baca foto dari penyimpanan privat
    private function servePhoto(?string $path, string $directory)
    {
        $pattern = '~\A'
            . preg_quote($directory, '~')
            . '/[A-Za-z0-9_-]+\.(?:jpe?g|png|webp)\z~ix';

        if (!$path || !preg_match($pattern, $path)) {
            abort(404);
        }

        $disk = Storage::disk('local');

        if (!$disk->exists($path)) {
            abort(404);
        }

        $mime = $disk->mimeType($path);

        if (!in_array($mime, [
            'image/jpeg',
            'image/png',
            'image/webp',
        ], true)) {
            abort(404);
        }

        return response()->file($disk->path($path), [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, no-store, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}