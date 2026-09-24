<x-app-layout>
    <div class="container-fluid px-4 py-4">
        <!-- Header Halaman -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fw-semibold">
                        <i class="ph ph-users-three me-1"></i> Sistem & Akun
                    </span>
                    <span class="text-muted fs-7">• Hak Akses Pengguna</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1">Manajemen Pengguna</h1>
                <p class="text-muted mb-0 fs-6">Kelola akun pengguna, hak akses peran (Role), aktivasi akun, dan reset kata sandi.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                    <i data-lucide="user-plus" style="width: 18px; height: 18px;"></i>
                    <span>Tambah Pengguna Baru</span>
                </button>
            </div>
        </div>

        <!-- Alert Notifikasi Flash Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0 border-start border-4 border-success mb-4" role="alert">
                <i data-lucide="check-circle-2" class="text-success" style="width: 20px; height: 20px;"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 shadow-sm border-0 border-start border-4 border-danger mb-4" role="alert">
                <i data-lucide="alert-circle" class="text-danger" style="width: 20px; height: 20px;"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 border-start border-4 border-danger mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i data-lucide="alert-triangle" class="text-danger" style="width: 20px; height: 20px;"></i>
                    <strong class="text-danger">Terdapat kesalahan pengisian data:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- 1. STATISTIK PENGGUNA -->
        <div class="row g-3 mb-4">
            <!-- Total Pengguna -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-primary-subtle text-primary">
                        <i data-lucide="users" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-medium">Total Akun</div>
                        <div class="h4 fw-bold mb-0 text-slate-800">{{ $stats['total'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Administrator -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-indigo-subtle text-indigo" style="background-color: #ede9fe; color: #7c3aed;">
                        <i data-lucide="shield-check" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-medium">Administrator</div>
                        <div class="h4 fw-bold mb-0 text-slate-800">{{ $stats['admin'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Guru Pembimbing -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-success-subtle text-success">
                        <i data-lucide="graduation-cap" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-medium">Guru Pembimbing</div>
                        <div class="h4 fw-bold mb-0 text-slate-800">{{ $stats['guru'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Peserta Didik (Siswa) -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-info-subtle text-info">
                        <i data-lucide="user-check" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-medium">Siswa PKL</div>
                        <div class="h4 fw-bold mb-0 text-slate-800">{{ $stats['siswa'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Instruktur DUDI -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-warning-subtle text-warning">
                        <i data-lucide="briefcase" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-medium">Instruktur DUDI</div>
                        <div class="h4 fw-bold mb-0 text-slate-800">{{ $stats['instruktur'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Akun Nonaktif -->
            <div class="col-12 col-sm-6 col-xl-2">
                <div class="pro-card p-3 h-100 d-flex align-items-center gap-3">
                    <div class="p-2.5 rounded-3 bg-danger-subtle text-danger">
                        <i data-lucide="user-x" style="width: 24px; height: 24px;"></i>
                    </div>
                    <div>
                        <div class="fs-7 text-muted fw-medium">Nonaktif</div>
                        <div class="h4 fw-bold mb-0 text-slate-800">{{ $stats['nonaktif'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. FILTER & PENCARIAN -->
        <div class="pro-card p-3 mb-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i data-lucide="search" style="width: 16px; height: 16px;"></i>
                        </span>
                        <input type="text" name="q" value="{{ $q }}" class="form-control border-start-0 ps-0" placeholder="Cari nama atau email pengguna...">
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <select name="role_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Role / Peran --</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->id }}" {{ (string)$roleId === (string)$r->id ? 'selected' : '' }}>
                                Role: {{ ucfirst($r->nama_role) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Status: Aktif</option>
                        <option value="nonaktif" {{ $status === 'nonaktif' ? 'selected' : '' }}>Status: Nonaktif</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1">
                        <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                        <span>Filter</span>
                    </button>
                    @if($q || $roleId || $status)
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light border text-muted" title="Reset Filter">
                            <i data-lucide="rotate-ccw" style="width: 16px; height: 16px;"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 3. TABEL DATA PENGGUNA -->
        <div class="pro-card overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted fs-7 text-uppercase fw-semibold border-bottom">
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th style="min-width: 220px;">Pengguna</th>
                            <th style="min-width: 140px;">Role / Hak Akses</th>
                            <th style="min-width: 180px;">Terkait Data Profil</th>
                            <th style="min-width: 110px;">Status</th>
                            <th style="min-width: 140px;">Terdaftar Pada</th>
                            <th class="text-end pe-4" style="width: 160px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-slate-800">
                        @forelse($users as $index => $u)
                            @php
                                $roleName = strtolower($u->role?->nama_role ?? 'guest');
                                $initials = strtoupper(substr($u->name, 0, 2));
                                
                                // Color badges by role
                                $roleColors = [
                                    'admin'      => ['bg' => '#ede9fe', 'text' => '#7c3aed', 'icon' => 'shield-check'],
                                    'guru'       => ['bg' => '#dcfce7', 'text' => '#15803d', 'icon' => 'graduation-cap'],
                                    'siswa'      => ['bg' => '#e0f2fe', 'text' => '#0369a1', 'icon' => 'user-check'],
                                    'instruktur' => ['bg' => '#fef3c7', 'text' => '#b45309', 'icon' => 'briefcase'],
                                ];
                                $rc = $roleColors[$roleName] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'icon' => 'user'];
                            @endphp
                            <tr>
                                <td class="ps-4 text-muted fw-medium fs-7">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold text-white fs-7 shadow-xs flex-shrink-0"
                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-slate-900 d-flex align-items-center gap-1.5">
                                                <span>{{ $u->name }}</span>
                                                @if(Auth::id() === $u->id)
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 10px;">Anda</span>
                                                @endif
                                            </div>
                                            <div class="text-muted fs-7 font-monospace">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill fw-semibold fs-7"
                                          style="background-color: {{ $rc['bg'] }}; color: {{ $rc['text'] }};">
                                        <i data-lucide="{{ $rc['icon'] }}" style="width: 14px; height: 14px;"></i>
                                        <span>{{ ucfirst($roleName) }}</span>
                                    </span>
                                </td>
                                <td>
                                    @if($u->siswa)
                                        <div class="fs-7">
                                            <span class="fw-semibold text-slate-800"><i class="ph ph-student me-1 text-primary"></i>Siswa:</span>
                                            {{ $u->siswa->nama }}
                                            <div class="text-muted" style="font-size: 11px;">NIS: {{ $u->siswa->nis }} | {{ $u->siswa->kelas?->nama_kelas }}</div>
                                        </div>
                                    @elseif($u->guru)
                                        <div class="fs-7">
                                            <span class="fw-semibold text-slate-800"><i class="ph ph-chalkboard-teacher me-1 text-success"></i>Guru:</span>
                                            {{ $u->guru->nama }}
                                            <div class="text-muted" style="font-size: 11px;">NIP: {{ $u->guru->nip ?: '-' }}</div>
                                        </div>
                                    @elseif($u->pembimbingIndustri)
                                        <div class="fs-7">
                                            <span class="fw-semibold text-slate-800"><i class="ph ph-briefcase me-1 text-warning"></i>Instruktur:</span>
                                            {{ $u->pembimbingIndustri->nama }}
                                            <div class="text-muted" style="font-size: 11px;">{{ $u->pembimbingIndustri->perusahaan?->nama_perusahaan }}</div>
                                        </div>
                                    @else
                                        <span class="text-muted fs-7 italic">Akun Sistem / Admin Langsung</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.users.toggle-status', $u) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm px-2.5 py-1 rounded-pill fw-semibold border-0 d-inline-flex align-items-center gap-1 {{ $u->status === 'aktif' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}"
                                                title="Klik untuk mengubah status akun"
                                                {{ Auth::id() === $u->id ? 'disabled' : '' }}>
                                            <span class="rounded-circle d-inline-block" style="width: 6px; height: 6px; background-color: currentColor;"></span>
                                            <span>{{ ucfirst($u->status) }}</span>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="fs-7 text-slate-700">{{ $u->created_at ? $u->created_at->translatedFormat('d M Y') : '-' }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $u->created_at ? $u->created_at->format('H:i') . ' WIB' : '' }}</div>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <!-- Tombol Edit -->
                                        <button type="button" 
                                                class="btn btn-sm btn-light border text-primary" 
                                                title="Edit Akun"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditUser"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                data-email="{{ $u->email }}"
                                                data-role="{{ $u->role_id }}"
                                                data-status="{{ $u->status }}"
                                                onclick="openEditModal(this)">
                                            <i data-lucide="edit-3" style="width: 15px; height: 15px;"></i>
                                        </button>

                                        <!-- Tombol Reset Password -->
                                        <button type="button" 
                                                class="btn btn-sm btn-light border text-warning" 
                                                title="Reset Password"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalResetPassword"
                                                data-id="{{ $u->id }}"
                                                data-name="{{ $u->name }}"
                                                onclick="openResetModal(this)">
                                            <i data-lucide="key" style="width: 15px; height: 15px;"></i>
                                        </button>

                                        <!-- Tombol Hapus -->
                                        @if(Auth::id() !== $u->id)
                                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna {{ $u->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Akun">
                                                    <i data-lucide="trash-2" style="width: 15px; height: 15px;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <div class="p-3 bg-light rounded-circle text-muted mb-3">
                                            <i data-lucide="user-x" style="width: 36px; height: 36px;"></i>
                                        </div>
                                        <h6 class="fw-bold text-slate-800 mb-1">Tidak ada data pengguna yang sesuai</h6>
                                        <p class="text-muted fs-7 mb-3">Coba ubah kata kunci pencarian atau reset filter di atas.</p>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                                            Reset Pencarian
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($users->hasPages())
                <div class="p-3 border-top bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted fs-7">
                        Menampilkan <strong>{{ $users->firstItem() }}</strong> - <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> total pengguna
                    </div>
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 1: TAMBAH PENGGUNA BARU                                 -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-labelledby="modalTambahUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-primary text-white py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="user-plus" style="width: 20px; height: 20px;"></i>
                        <h5 class="modal-title fw-bold fs-6" id="modalTambahUserLabel">Tambah Pengguna Baru</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-7 text-slate-800">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i data-lucide="user" style="width: 16px; height: 16px;"></i></span>
                                <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso, S.Kom" required>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-7 text-slate-800">Alamat Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i data-lucide="mail" style="width: 16px; height: 16px;"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="user@smklabor.sch.id" required>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-7 text-slate-800">Kata Sandi (Password) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i data-lucide="lock" style="width: 16px; height: 16px;"></i></span>
                                <input type="password" name="password" id="addPasswordInput" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassVisibility('addPasswordInput', this)">
                                    <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Role & Status -->
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold fs-7 text-slate-800">Role Hak Akses <span class="text-danger">*</span></label>
                                <select name="role_id" class="form-select" required>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}">{{ ucfirst($r->nama_role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold fs-7 text-slate-800">Status Akun <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-light border text-muted" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                            <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                            <span>Simpan Pengguna</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 2: EDIT PENGGUNA                                         -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-slate-900 text-white py-3 px-4" style="background-color: #0f172a;">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="edit-3" style="width: 20px; height: 20px;"></i>
                        <h5 class="modal-title fw-bold fs-6 text-white" id="modalEditUserLabel">Edit Data Pengguna</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditUser" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <!-- Nama Lengkap -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-7 text-slate-800">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-7 text-slate-800">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>

                        <!-- Ganti Password (Opsional) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-7 text-slate-800">
                                Password Baru <span class="text-muted fw-normal">(Kosongkan jika tidak ingin diubah)</span>
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ganti" minlength="6">
                        </div>

                        <!-- Role & Status -->
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold fs-7 text-slate-800">Role Hak Akses <span class="text-danger">*</span></label>
                                <select name="role_id" id="editRole" class="form-select" required>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}">{{ ucfirst($r->nama_role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold fs-7 text-slate-800">Status Akun <span class="text-danger">*</span></label>
                                <select name="status" id="editStatus" class="form-select" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-light border text-muted" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1.5">
                            <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODAL 3: RESET PASSWORD CEPAT                                  -->
    <!-- ============================================================== -->
    <div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header bg-warning text-dark py-3 px-4">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="key" style="width: 20px; height: 20px;"></i>
                        <h5 class="modal-title fw-bold fs-6 text-dark" id="modalResetPasswordLabel">Reset Kata Sandi Akun</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formResetPassword" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <p class="text-muted fs-7 mb-3">
                            Mereset kata sandi untuk pengguna: <strong id="resetUserName" class="text-slate-900"></strong>
                        </p>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-7 text-slate-800">Password Baru <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i data-lucide="lock" style="width: 16px; height: 16px;"></i></span>
                                <input type="password" name="new_password" id="newPasswordInput" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassVisibility('newPasswordInput', this)">
                                    <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="setQuickPassword('smklabor2026')">
                                Gunakan: <span class="font-monospace fw-bold">smklabor2026</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setQuickPassword('password123')">
                                Gunakan: <span class="font-monospace fw-bold">password123</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3 border-top">
                        <button type="button" class="btn btn-light border text-muted" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning text-dark fw-bold d-inline-flex align-items-center gap-1.5">
                            <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Handler Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });

        function openEditModal(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');
            const email = btn.getAttribute('data-email');
            const role = btn.getAttribute('data-role');
            const status = btn.getAttribute('data-status');

            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editStatus').value = status;

            document.getElementById('formEditUser').action = `{{ url('admin/users') }}/${id}`;
        }

        function openResetModal(btn) {
            const id = btn.getAttribute('data-id');
            const name = btn.getAttribute('data-name');

            document.getElementById('resetUserName').innerText = name;
            document.getElementById('newPasswordInput').value = '';
            document.getElementById('formResetPassword').action = `{{ url('admin/users') }}/${id}/reset-password`;
        }

        function setQuickPassword(val) {
            document.getElementById('newPasswordInput').value = val;
        }

        function togglePassVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<i data-lucide="eye-off" style="width: 16px; height: 16px;"></i>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<i data-lucide="eye" style="width: 16px; height: 16px;"></i>';
            }
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }
    </script>
</x-app-layout>
