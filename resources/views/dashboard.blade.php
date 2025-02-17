@extends('template.layout')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    @if(auth()->user()->role_name === 'Admin')
    <!-- Dashboard untuk Admin -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    Total User
                    <h3>{{ $totalUsers }}</h3>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">Jumlah pengguna saat ini</span>
                    <div class="small text-white"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary text-white mb-4">
                <div class="card-body">
                    Total Admin
                    <h3>{{ $totalAdmins }}</h3>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <span class="small text-white">Jumlah admin saat ini</span>
                    <div class="small text-white"><i class="fas fa-user-shield"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('user-table') }}" class="btn btn-info">Lihat Data Pengguna</a>
    </div>
    @else
    <!-- Dashboard untuk User -->
    <div class="alert alert-info">
        <h5>Selamat datang, {{ auth()->user()->username }}!</h5>
        <p class="mb-0">Terima kasih telah menggunakan aplikasi ini.</p>
    </div>
    @endif
</div>

@include('sweetalert::alert')
@endsection