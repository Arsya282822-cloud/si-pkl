@extends('layouts.app')
@section('title','Edit Perusahaan')
@section('topbar_title','Edit Perusahaan')
@section('content')<div class="mb-4"><h1 class="page-title">Edit Perusahaan</h1></div><div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.perusahaan.update',$perusahaan) }}">@method('PUT') @include('admin.perusahaan._form')<div class="mt-4"><a href="{{ route('admin.perusahaan.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Perbarui</button></div></form></div></div>@endsection
