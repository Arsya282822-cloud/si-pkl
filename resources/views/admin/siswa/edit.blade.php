@extends('layouts.app')
@section('title','Edit Siswa')
@section('topbar_title','Edit Siswa')
@section('content')<div class="mb-4"><h1 class="page-title">Edit Siswa</h1></div><div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.siswa.update',$siswa) }}">@method('PUT') @include('admin.siswa._form')<div class="mt-4"><a href="{{ route('admin.siswa.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Perbarui</button></div></form></div></div>@endsection
