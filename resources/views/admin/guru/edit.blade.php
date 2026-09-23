@extends('layouts.app')
@section('title','Edit Guru')
@section('topbar_title','Edit Guru')
@section('content')<div class="mb-4"><h1 class="page-title">Edit Guru</h1></div><div class="card dashboard-card"><div class="card-body p-4"><form method="POST" action="{{ route('admin.guru.update',$guru) }}">@method('PUT') @include('admin.guru._form')<div class="mt-4"><a href="{{ route('admin.guru.index') }}" class="btn btn-light">Kembali</a><button class="btn btn-primary">Perbarui</button></div></form></div></div>@endsection
