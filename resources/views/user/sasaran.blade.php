@extends('layouts.user')

@section('title','Daftar Sasaran')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/sppg.css') }}">
@endsection

@section('content')

<div class="user-page-container">

<div class="text-end mb-3">

    <a href="javascript:history.back()" class="back-link">
    Kembali
</a>

</div>

    <div class="dashboard-header">

    <div class="header-left">
        <h2 class="mb-0">{{ $sppg->nama_sppg }}</h2>
    </div>

</div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <table class="table table-bordered table-striped">

        <thead class="table-light">
            <tr>
                <th width="60">No</th>
                <th>Nama Sasaran</th>
                <th width="180">Kapasitas</th>
            </tr>
        </thead>

        <tbody>

        @forelse($sasaran as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_sasaran }}</td>
                <td>{{ $item->kapasitas }} Porsi/Hari</td>
            </tr>

        @empty

            <tr>

                <td colspan="4" class="text-center">
                    Belum ada data sasaran
                </td>

            </tr>

        @endforelse

        </tbody>

        <tfoot>

            <tr>

                <th colspan="2" class="text-end">
                    Total Kapasitas
                </th>

                <th colspan="2">
                    {{ $sasaran->sum('kapasitas') }} Porsi/Hari
                </th>

            </tr>

        </tfoot>

    </table>

</div>
</div>
@endsection