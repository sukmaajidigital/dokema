# Route Refactoring Guide - Flat Routes Pattern

## Perubahan yang Dilakukan

Routes untuk **Log Bimbingan** dan **Penilaian Akhir** telah diubah dari **nested routes** (dengan `magangId` parameter) menjadi **flat routes** untuk memudahkan pengembangan dan maintenance.

### Sebelum (Nested Routes):

```php
GET  /magang/{magangId}/bimbingan          -> Error jika magangId tidak ada
GET  /magang/{magangId}/penilaian/create   -> Butuh magangId di URL
```

### Sesudah (Flat Routes):

```php
GET  /bimbingan                -> Filter berdasarkan role di controller
GET  /penilaian                -> Filter berdasarkan role di controller
```

---

## Filter Data Berdasarkan Role

### LogBimbinganController

```php
public function index()
{
    $user = auth()->user();

    if ($user->role === 'hr') {
        // HR: Lihat semua log bimbingan
        $bimbingan = LogBimbingan::with(['dataMagang.profilPeserta'])->get();
    }
    elseif ($user->role === 'pembimbing') {
        // Pembimbing: Lihat log bimbingan untuk peserta yang dibimbing
        $bimbingan = LogBimbingan::whereHas('dataMagang', function($query) use ($user) {
            $query->where('pembimbing_id', $user->id);
        })->with(['dataMagang.profilPeserta'])->get();
    }
    elseif ($user->role === 'magang') {
        // Magang: Lihat log bimbingan sendiri
        $dataMagang = $user->profilPeserta->dataMagang()->first();
        $bimbingan = $dataMagang ? $dataMagang->logBimbingan : collect();
    }

    return view('magang.bimbingan.index', compact('bimbingan'));
}
```

### PenilaianAkhirController

```php
public function index()
{
    $user = auth()->user();

    if ($user->role === 'hr') {
        // HR: Lihat semua penilaian
        $penilaian = PenilaianAkhir::with(['dataMagang.profilPeserta'])->get();
    }
    elseif ($user->role === 'pembimbing') {
        // Pembimbing: Lihat penilaian peserta yang dibimbing
        $penilaian = PenilaianAkhir::whereHas('dataMagang', function($query) use ($user) {
            $query->where('pembimbing_id', $user->id);
        })->with(['dataMagang.profilPeserta'])->get();
    }
    elseif ($user->role === 'magang') {
        // Magang: Lihat penilaian sendiri (read-only)
        $dataMagang = $user->profilPeserta->dataMagang()->first();
        $penilaian = $dataMagang ? $dataMagang->penilaianAkhir()->get() : collect();
    }

    return view('magang.penilaian.index', compact('penilaian'));
}
```

### LaporanKegiatanController

Sama seperti di atas, tambahkan filter:

```php
public function index()
{
    $user = auth()->user();

    if ($user->role === 'hr') {
        $laporan = LaporanKegiatan::with(['dataMagang.profilPeserta'])->get();
    }
    elseif ($user->role === 'pembimbing') {
        $laporan = LaporanKegiatan::whereHas('dataMagang', function($query) use ($user) {
            $query->where('pembimbing_id', $user->id);
        })->with(['dataMagang.profilPeserta'])->get();
    }
    elseif ($user->role === 'magang') {
        $dataMagang = $user->profilPeserta->dataMagang()->first();
        $laporan = $dataMagang ? $dataMagang->laporanKegiatan : collect();
    }

    return view('magang.laporan.index', compact('laporan'));
}
```

---

## Keuntungan Flat Routes

✅ **Lebih Sederhana**: Tidak perlu pass `magangId` di sidebar/menu  
✅ **Fleksibel**: Filter logic di controller, mudah customize per role  
✅ **Scalable**: Mudah tambah role baru tanpa ubah routes  
✅ **RESTful**: `/bimbingan`, `/penilaian`, `/laporan` lebih clean  
✅ **Error-free**: Tidak ada "Missing parameter" error di navigation

---

## Action Items untuk Developer

1. **Update Controllers** - Implementasi filter berdasarkan role seperti contoh di atas
2. **Update Views** - Pastikan link create/edit tidak menggunakan `magangId` lagi
3. **Test All Roles** - Login sebagai HR, Pembimbing, dan Magang, pastikan hanya lihat data yang seharusnya
4. **Update Create Forms** - Form create bimbingan/penilaian perlu dropdown pilih peserta (untuk pembimbing)

---

## Related Files

-   Routes: `routes/web.php`
-   Controllers:
    -   `app/Http/Controllers/Magang/LogBimbinganController.php`
    -   `app/Http/Controllers/Magang/PenilaianAkhirController.php`
    -   `app/Http/Controllers/Magang/LaporanKegiatanController.php`
-   Sidebar: `resources/views/components/sidebar.blade.php`
