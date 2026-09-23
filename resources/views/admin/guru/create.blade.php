@extends('layouts.app')
@section('title','Tambah Guru')
@section('topbar_title','Tambah Guru')
@section('content')<div class="mb-4"><h1 class="page-title">Tambah Guru</h1><p class="page-subtitle">Akun login guru dibuat otomatis dengan role guru.</p></div><div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.guru.store') }}">@include('admin.guru._form')<div class="mt-4"><a href="{{ route('admin.guru.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Simpan</button></div></form></div></div>@endsection
