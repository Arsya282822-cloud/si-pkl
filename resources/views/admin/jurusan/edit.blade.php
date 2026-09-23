@extends('layouts.app')
@section('title', 'Edit Jurusan')
@section('topbar_title', 'Edit Jurusan')
@section('content')
<div class="mb-4"><h1 class="page-title">Edit Jurusan</h1></div>
<div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.jurusan.update', $jurusan) }}">@method('PUT') @include('admin.jurusan._form')<div class="mt-4"><a href="{{ route('admin.jurusan.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Perbarui</button></div></form></div></div>
@endsection
