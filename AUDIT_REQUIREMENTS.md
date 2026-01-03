# DOKEMA - Audit Kesesuaian Requirements

**Grapari Kudus Telkom Akses - Sistem Manajemen Magang**

**Tanggal Audit:** 3 Januari 2026  
**Status Keseluruhan:** ⚠️ **70% Implementasi - Perbaikan Mendesak Diperlukan**

---

## 📋 REQUIREMENTS CHECKLIST

### ✅ REQUIREMENT #1: Calon Peserta Mendaftarkan Diri via Menu Register (Menunggu ACC HRD)

**Status:** ⚠️ **PARTIAL - 60% SELESAI**

#### Apa yang Sudah Ada:

-   ✅ Route register: `/register` (GET & POST)
-   ✅ AuthController::showRegistrationForm() - menampilkan form
-   ✅ AuthController::register() - handle registration
-   ✅ Database User model dengan role 'magang'
-   ✅ Login form yang bagus (login.blade.php)
-   ✅ Password hashing & email verified_at

#### Yang Masih Kurang:

-   ❌ **View register.blade.php TIDAK ADA** - Hanya ada login.blade.php & backup.blade.php di auth folder
-   ❌ **Profil peserta belum auto-dibuat** - User register tapi tidak ada profil detail (nama lengkap, universitas, jurusan, no_hp, dll)
-   ⚠️ **Status awal tidak jelas** - Setelah register, peserta langsung bisa login atau ditandai "menunggu"?
-   ❌ **Tidak ada notifikasi ke HRD** - HRD tidak tahu ada peserta baru
-   ⚠️ **Workflow status tidak konsisten** - Register membuat user tapi DataMagang belum ada

#### Database Relationship Issue:

```
users (dari register)
  → Anda buat user dengan role='magang'

profil_peserta
  → Perlu dibuat saat register untuk capture: nama, email, universitas, jurusan, no_hp, dll

data_magang
  → Perlu dibuat dengan workflow_status = 'draft' / 'submitted' untuk ditinjau HRD
```

#### Action Items:

1. **Buat register.blade.php** - Form register yang comprehensive
    - Name, Email, Password, Confirm Password
    - Nama Lengkap, Universitas, Jurusan, No HP (dipindah ke profil_peserta)
2. **Update AuthController::register()** - Auto-create profil_peserta + data_magang

    ```php
    // Setelah user dibuat, buat juga:
    ProfilPeserta::create([
        'user_id' => $user->id,
        'nama' => $request->nama_lengkap,
        'email' => $user->email,
        'universitas' => $request->universitas,
        'jurusan' => $request->jurusan,
        'no_hp' => $request->no_hp,
    ]);

    DataMagang::create([
        'profil_peserta_id' => $profil->id,
        'workflow_status' => 'submitted',
        'tanggal_mulai' => null,
    ]);
    ```

3. **Pastikan peserta TIDAK BISA LOGIN** sampai HRD approve
    - Use middleware: `if workflow_status != 'approved'` → redirect ke halaman "Menunggu Persetujuan"

---

### ⚠️ REQUIREMENT #2: HRD Dapat ACC Peserta Magang yang Register

**Status:** ⚠️ **80% SELESAI**

#### Apa yang Sudah Ada:

-   ✅ Route workflow approval: `/workflow/approval`
-   ✅ WorkflowMagangController::index() - menampilkan pending applications
-   ✅ WorkflowMagangController::processApplication() - handle approve/reject
-   ✅ Logic auto-assign pembimbing berdasarkan workload
-   ✅ Surat balasan (file upload) handling
-   ✅ Quota checking

#### Yang Masih Kurang:

-   ⚠️ **Middleware role belum ketat** - View workflow approval tidak dibatasi hanya untuk HR
-   ⚠️ **Surat balasan file upload dijadikan mandatory** - Harusnya HRD bisa acc tanpa upload surat (optional)
-   ❌ **Notifikasi email tidak aktif** - TODO comment pada line processApplication
-   ⚠️ **UI/UX tidak jelas** - Tidak ada validasi real-time pembimbing

#### Action Items:

1. **Tambah middleware role:hr pada workflow routes** - Pastikan hanya HR yang akses
2. **Buat surat_balasan optional** - HR bisa approve tanpa file, atau upload nanti
3. **Implementasi email notification** - Kirim email ke peserta saat acc/reject

---

### ❌ REQUIREMENT #3: Peserta Magang Baru Login Jika Sudah ACC dari HRD

**Status:** ❌ **0% SELESAI - CRITICAL ISSUE**

#### Masalahnya:

```
Saat ini:
1. User register → langsung bisa login
2. Status user tidak dicheck di middleware login
3. Peserta yang ditolak tetap bisa login

YANG SEHARUSNYA:
1. User register → blocked dari login sampai workflow_status = 'approved'
2. AuthController::login() harus check DataMagang::workflow_status
3. Peserta yang ditolak → permanently blocked
```

#### Action Items:

1. **Update AuthController::login()** - Tambah check workflow_status

    ```php
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        if ($user->role === 'magang') {
            $dataMagang = $user->profilPeserta->dataMagang ?? null;
            if (!$dataMagang || $dataMagang->workflow_status !== 'approved') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda belum disetujui oleh HRD. Silakan tunggu.']);
            }
        }
        return redirect()->intended(route('dashboard'));
    }
    ```

2. **Buat halaman "Menunggu Persetujuan"** - Untuk user yang status belum approved
    - Tampilkan status application
    - Tampilkan feedback jika ditolak

---

### ⚠️ REQUIREMENT #4: HRD Dapat Ploting Pembimbing dari Telkom Akses

**Status:** ⚠️ **70% SELESAI**

#### Apa yang Sudah Ada:

-   ✅ WorkflowMagangController::processApplication() - Ada field pembimbing_id
-   ✅ Auto-assign logic berdasarkan workload terendah
-   ✅ Database field pembimbing_id di data_magang
-   ✅ Relationship User → magangDibimbing

#### Yang Masih Kurang:

-   ⚠️ **Pembimbing list di UI tidak clear** - Tidak ada kolom menunjukkan beban kerja pembimbing
-   ⚠️ **HRD tidak bisa change pembimbing setelah assign** - Tidak ada edit/reassign feature
-   ⚠️ **Tidak ada dashboard untuk pembimbing** - Pembimbing tidak tahu berapa peserta yang ditugaskan

#### Action Items:

1. **Improve UI workflow.approval** - Tampilkan pembimbing dengan workload count
2. **Tambah feature re-assign pembimbing** - Untuk perubahan mid-internship
3. **Buat pembimbing dashboard** - List peserta yang dibimbing

---

### ❌ REQUIREMENT #5: Peserta Hanya Lihat Data Pribadi (Authentication & Role-Based Access)

**Status:** ❌ **40% SELESAI - CRITICAL SECURITY ISSUE**

#### Masalahnya:

```
SAAT INI:
1. LaporanKegiatanController::index() - return ALL laporan, tidak filter by peserta
2. Middleware role:pembimbing tidak ada di routes
3. Peserta bisa akses /penilaian (harusnya hanya pembimbing/hr)
4. Tidak ada authorization check untuk DataMagang ownership

KEAMANAN RUSAK:
- Peserta A bisa lihat laporan peserta B
- Peserta bisa liat penilaian semua orang
- Peserta bisa access log_bimbingan orang lain
```

#### Action Items:

1. **Update LaporanKegiatanController::index()** - Filter hanya laporan milik peserta login

    ```php
    if (Auth::user()->role === 'magang') {
        $dataMagang = Auth::user()->profilPeserta->dataMagang;
        $laporan = $dataMagang->laporanKegiatan()->latest()->paginate(10);
    } else if (Auth::user()->role === 'pembimbing') {
        // Tampilkan laporan dari semua peserta yang dibimbing
        $laporan = LaporanKegiatan::whereIn('data_magang_id',
            Auth::user()->magangDibimbing->pluck('id'))->latest()->paginate(10);
    }
    ```

2. **Tambah middleware role di routes** - Strict access control

    ```php
    Route::middleware(['role:magang'])->group(function () {
        Route::get('/magang/laporan', [LaporanKegiatanController::class, 'index']);
        Route::post('/magang/laporan', [LaporanKegiatanController::class, 'store']);
    });

    Route::middleware(['role:pembimbing'])->group(function () {
        Route::get('/laporan/{id}/approve', [LaporanKegiatanController::class, 'approve']);
    });
    ```

3. **Buat CheckOwnership middleware** - Verify user owns resource

    ```php
    public function handle(Request $request, Closure $next) {
        $laporan = LaporanKegiatan::find($request->route('id'));
        if (Auth::user()->role === 'magang' &&
            $laporan->dataMagang->profilPeserta->user_id !== Auth::id()) {
            abort(403);
        }
        return $next($request);
    }
    ```

4. **Update semua controllers** dengan authorization checks:
    - DataMagangController
    - LaporanKegiatanController
    - LogBimbinganController
    - PenilaianAkhirController

---

### ✅ REQUIREMENT #6: Peserta Dapat Laporan Harian Rutin

**Status:** ✅ **85% SELESAI**

#### Apa yang Sudah Ada:

-   ✅ Route create/store laporan: `/magang/laporan`
-   ✅ LaporanKegiatanController dengan form validation
-   ✅ Model LaporanKegiatan dengan relationships
-   ✅ Database field: tanggal_laporan, deskripsi, path_lampiran
-   ✅ File attachment handling (pdf, jpg, png)
-   ✅ Auto-generate LogBimbingan (Issue #7 implemented)

#### Yang Masih Kurang:

-   ⚠️ **Update/edit harus check ownership** - Peserta bisa edit laporan orang lain
-   ⚠️ **Tidak ada date validation** - Bisa input tanggal masa depan
-   ⚠️ **UI belum ada** - Tidak ada view untuk create & list laporan
-   ❌ **Tidak bisa hard-delete** - Seharusnya soft-delete untuk audit trail

#### Action Items:

1. **Tambah ownership check pada update/delete**
2. **Validasi tanggal tidak boleh masa depan**
3. **Implementasi soft-delete pada model** - Add deleted_at field
4. **Create Blade views** untuk laporan

---

### ✅ REQUIREMENT #7: Pembimbing Dapat Review Laporan Harian

**Status:** ✅ **90% SELESAI - Hampir Complete**

#### Apa yang Sudah Ada:

-   ✅ LaporanKegiatanController::approve() - Approve laporan
-   ✅ LaporanKegiatanController::reject() - Reject laporan
-   ✅ Database fields: status_verifikasi, verified_by, verified_at, catatan_verifikasi
-   ✅ Route endpoints `/laporan/{id}/approve` dan `/laporan/{id}/reject`
-   ✅ Authorization check - Hanya pembimbing assigned yang bisa review

#### Yang Masih Kurang:

-   ⚠️ **UI untuk review belum lengkap** - Tidak ada tombol approve/reject di view
-   ⚠️ **Catatan rejection tidak diminta** - Peserta tidak tahu kenapa ditolak
-   ⚠️ **Tidak ada notifikasi ke peserta** - Peserta tidak tahu laporan sudah di-review

#### Action Items:

1. **Update route untuk require catatan pada reject**

    ```php
    Route::post('/laporan/{id}/reject', [...])->name('laporan.reject');
    // Validate: catatan_verifikasi required
    ```

2. **Buat UI untuk pembimbing review** - Dashboard review laporan
3. **Implementasi email notification** - Notify peserta saat laporan diverifikasi

---

### ⚠️ REQUIREMENT #8: Pembimbing Dapat Penilaian Menggunakan Form Penilaian

**Status:** ⚠️ **75% SELESAI**

#### Apa yang Sudah Ada:

-   ✅ PenilaianAkhirController::create() & store()
-   ✅ Database model PenilaianAkhir dengan fields:
    -   nilai_kehadiran, nilai_kedisiplinan, nilai_keterampilan, nilai_sikap
    -   umpan_balik, surat_nilai (file)
-   ✅ Authorization - Hanya pembimbing assigned yang bisa nilai
-   ✅ Validation ada (numeric 0-100)
-   ✅ One-to-one relationship (penilaian per magang)

#### Yang Masih Kurang:

-   ⚠️ **Update penilaian belum ada** - PenilaianAkhirController::update() ada di code tapi tidak lengkap
-   ⚠️ **Tidak ada UI create/edit** - Tidak ada view untuk form penilaian
-   ⚠️ **Surat nilai PDF tidak di-generate** - Hanya file upload, tidak otomatis generate
-   ⚠️ **List penilaian tidak ada filter** - Pembimbing lihat semua penilaian, bukan hanya milik mereka

#### Action Items:

1. **Lengkapi PenilaianAkhirController::update()** - Edit penilaian
2. **Buat UI untuk form penilaian** - Create & edit views
3. **Implementasi PDF generation** - Generate surat nilai otomatis menggunakan dompdf
4. **Filter penilaian per pembimbing** - Dashboard penilaian

---

## 🔐 SECURITY & ACCESS CONTROL ISSUES

### 🔴 CRITICAL ISSUES:

#### 1. **No Role-Based Route Protection**

```
❌ SAAT INI: Semua route protected dengan auth() saja
✅ SEHARUSNYA: Tiap route dibatasi per role
```

-   Workshop route tidak punya middleware role:hr
-   Pembimbing routes tidak punya middleware role:pembimbing
-   Peserta bisa akses resource orang lain

#### 2. **No Ownership Verification**

```
❌ Peserta A bisa edit laporan peserta B (route: /magang/laporan/{id}/edit)
✅ Seharusnya: Only owner + admin dapat edit
```

#### 3. **Data Filtering Missing**

```
❌ LaporanKegiatanController::index() return ALL laporan
✅ Seharusnya: Filtered by logged-in user + role
```

---

## 📊 IMPLEMENTATION PRIORITY & EFFORT

| #   | Requirement       | Status | Priority    | Effort        | Time |
| --- | ----------------- | ------ | ----------- | ------------- | ---- |
| 1   | Register Form     | ❌ 60% | 🔴 CRITICAL | HIGH          | 2h   |
| 2   | HRD Approval      | ⚠️ 80% | 🔴 CRITICAL | MEDIUM        | 1.5h |
| 3   | Login Gate        | ❌ 0%  | 🔴 CRITICAL | MEDIUM        | 1h   |
| 4   | Pembimbing Assign | ⚠️ 70% | 🟠 HIGH     | MEDIUM        | 1.5h |
| 5   | Access Control    | ❌ 40% | 🔴 CRITICAL | **VERY HIGH** | 4h   |
| 6   | Laporan Harian    | ✅ 85% | 🟠 HIGH     | LOW           | 1h   |
| 7   | Laporan Review    | ✅ 90% | 🟠 HIGH     | LOW           | 0.5h |
| 8   | Penilaian Form    | ⚠️ 75% | 🟠 HIGH     | MEDIUM        | 1.5h |

**Total Estimated Time:** ~13 hours
**Critical Blockers:** Issues #1, #3, #5

---

## 🛠️ DATABASE SCHEMA REVIEW

### ✅ Existing Tables:

-   `users` - OK, punya role field
-   `profil_peserta` - OK, ada user_id relationship
-   `data_magang` - OK, punya workflow_status enum
-   `laporan_kegiatan` - OK, ada status_verifikasi + verified_by
-   `penilaian_akhir` - OK, semua fields ada
-   `log_bimbingan` - OK
-   `workflow_transitions` - OK (untuk audit)

### ⚠️ Missing Fields:

-   `profil_peserta.deleted_at` - Untuk soft-delete (optional)
-   `data_magang.alasan_penolakan` - Untuk track rejection reason (ada tapi di laporan)
-   `laporan_kegiatan.deleted_at` - Untuk soft-delete audit trail (optional)

---

## 📋 NEXT STEPS

### Phase 1: CRITICAL (Must do FIRST)

1. Implement ownership verification middleware
2. Add role-based route protection
3. Fix login gate untuk workflow_status check
4. Create register.blade.php form
5. Update register flow untuk create profil_peserta + data_magang

### Phase 2: HIGH (Do after Phase 1)

6. Create laporan create/list views
7. Complete penilaian form UI
8. Improve workflow approval UI
9. Add email notifications

### Phase 3: MAINTENANCE (Polish)

10. Add soft-delete migrations
11. Create pembimbing dashboard
12. Implement PDF generation untuk surat nilai
13. Add audit logging untuk semua actions

---

## 📝 VALIDATION CHECKLIST

Setelah implementasi, test:

-   [ ] User baru tidak bisa login sebelum HRD approve
-   [ ] Peserta hanya lihat laporan milik sendiri
-   [ ] Pembimbing hanya lihat peserta yang dibimbing
-   [ ] HRD bisa approve/reject/reassign pembimbing
-   [ ] Pembimbing bisa review & penilaian
-   [ ] Email notifications terkirim dengan benar
-   [ ] Soft-delete & audit trails working
-   [ ] No SQL injection / XSS vulnerabilities

---

**Audit Completed:** 3 Januari 2026
**Auditor:** AI Assistant
**Recommendation:** Prioritize Phase 1 untuk security compliance
