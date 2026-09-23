@extends('layouts.app')
@section('title','Tambah Siswa')
@section('topbar_title','Tambah Siswa')
@section('content')<div class="mb-4"><h1 class="page-title">Tambah Siswa</h1><p class="page-subtitle">Akun login siswa dibuat otomatis.</p></div><div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.siswa.store') }}">@include('admin.siswa._form')<div class="mt-4"><a href="{{ route('admin.siswa.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Simpan</button></div></form></div></div>@endsection
