@extends('layouts.app')
@section('title','Tambah Kelas')
@section('topbar_title','Tambah Kelas')
@section('content')<div class="mb-4"><h1 class="page-title">Tambah Kelas</h1></div><div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.kelas.store') }}">@include('admin.kelas._form')<div class="mt-4"><a href="{{ route('admin.kelas.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Simpan</button></div></form></div></div>@endsection
