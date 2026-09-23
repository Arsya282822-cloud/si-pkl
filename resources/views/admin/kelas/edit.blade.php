@extends('layouts.app')
@section('title','Edit Kelas')
@section('topbar_title','Edit Kelas')
@section('content')<div class="mb-4"><h1 class="page-title">Edit Kelas</h1></div><div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.kelas.update',$kelas) }}">@method('PUT') @include('admin.kelas._form')<div class="mt-4"><a href="{{ route('admin.kelas.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Perbarui</button></div></form></div></div>@endsection
