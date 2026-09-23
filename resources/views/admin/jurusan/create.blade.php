@extends('layouts.app')
@section('title', 'Tambah Jurusan')
@section('topbar_title', 'Tambah Jurusan')
@section('content')
<div class="mb-4"><h1 class="page-title">Tambah Jurusan</h1></div>
<div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.jurusan.store') }}">@include('admin.jurusan._form')<div class="mt-4"><a href="{{ route('admin.jurusan.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Simpan</button></div></form></div></div>
@endsection
