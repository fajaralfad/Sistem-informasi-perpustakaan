@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">Riwayat Peminjaman</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Peminjaman</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Peminjaman
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPeminjaman }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-books fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Peminjaman Aktif
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $peminjamanAktif }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Denda
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalDenda, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter & Pencarian</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('member.riwayat') }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Cari Judul Buku</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ $search }}" 
                               placeholder="Masukkan judul buku...">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="terlambat" {{ $status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </div>
                @if($search || $status)
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('member.riwayat') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> Reset Filter
                            </a>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Borrowing History Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Riwayat Peminjaman</h6>
        </div>
        <div class="card-body">
            @if($peminjamans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Buku</th>
                                <th>Pengarang</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                                <th>Denda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjamans as $index => $peminjaman)
                                <tr>
                                    <td>{{ $peminjamans->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $peminjaman->buku->judul }}</strong>
                                        <br>
                                        <small class="text-muted">ISBN: {{ $peminjaman->buku->isbn ?? '-' }}</small>
                                    </td>
                                    <td>{{ $peminjaman->buku->pengarang->nama ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                                        @if($peminjaman->status == 'dipinjam' && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($peminjaman->tanggal_kembali)))
                                            <br>
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                Terlambat {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_kembali)) }} hari
                                            </small>
                                        @elseif($peminjaman->status == 'dipinjam')
                                            @php
                                                $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_kembali), false);
                                            @endphp
                                            @if($daysLeft <= 2 && $daysLeft >= 0)
                                                <br>
                                                <small class="text-warning">
                                                    <i class="fas fa-clock"></i> 
                                                    {{ $daysLeft == 0 ? 'Hari ini' : $daysLeft . ' hari lagi' }}
                                                </small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if($peminjaman->status == 'dipinjam')
                                            <span class="badge badge-primary">Sedang Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge badge-success">Dikembalikan</span>
                                        @elseif($peminjaman->status == 'terlambat')
                                            <span class="badge badge-danger">Terlambat</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($peminjaman->denda && $peminjaman->denda->count() > 0)
                                            @php
                                                $totalDendaItem = $peminjaman->denda->sum('jumlah');
                                                $dendaBelumBayar = $peminjaman->denda->where('tanggal_bayar', null)->sum('jumlah');
                                            @endphp
                                            <span class="text-danger">
                                                Rp {{ number_format($totalDendaItem, 0, ',', '.') }}
                                            </span>
                                            @if($dendaBelumBayar > 0)
                                                <br>
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-circle"></i> Belum Lunas
                                                </small>
                                            @else
                                                <br>
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle"></i> Lunas
                                                </small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('member.riwayat.detail', $peminjaman->id) }}" 
                                               class="btn btn-info btn-sm" 
                                               title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($peminjaman->status == 'dipinjam')
                                                <button type="button" 
                                                        class="btn btn-warning btn-sm" 
                                                        onclick="perpanjangPeminjaman({{ $peminjaman->id }})"
                                                        title="Perpanjang">
                                                    <i class="fas fa-clock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Menampilkan {{ $peminjamans->firstItem() }} sampai {{ $peminjamans->lastItem() }} 
                            dari {{ $peminjamans->total() }} hasil
                        </small>
                    </div>
                    <div>
                        {{ $peminjamans->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-600">Tidak ada riwayat peminjaman</h5>
                    <p class="text-gray-500">
                        @if($search || $status)
                            Tidak ditemukan riwayat peminjaman dengan kriteria pencarian yang Anda masukkan.
                        @else
                            Anda belum memiliki riwayat peminjaman buku.
                        @endif
                    </p>
                    @if($search || $status)
                        <a href="{{ route('member.riwayat') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Semua Riwayat
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function perpanjangPeminjaman(peminjamanId) {
    Swal.fire({
        title: 'Perpanjang Peminjaman?',
        text: 'Apakah Anda yakin ingin memperpanjang peminjaman ini selama 7 hari?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Perpanjang!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            });

            // Send AJAX request
            fetch(`/member/peminjaman/${peminjamanId}/perpanjang`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.success + '. Tanggal kembali baru: ' + data.new_due_date,
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.error || 'Terjadi kesalahan saat memperpanjang peminjaman',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem',
                    icon: 'error'
                });
            });
        }
    });
}

// Auto-submit form when status changes
document.getElementById('status').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.table th {
    background-color: #f8f9fc;
    font-weight: 600;
    border-color: #e3e6f0;
}

.table td {
    vertical-align: middle;
    border-color: #e3e6f0;
}

.badge {
    font-size: 0.75em;
    padding: 0.375em 0.75em;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush