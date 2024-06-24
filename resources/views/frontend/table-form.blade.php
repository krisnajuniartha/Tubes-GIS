@extends('layout')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4" style="padding-top: 5rem">Data Ruas Jalan</h2>

    <div class="box-ruas">
        <div class="row mb-3">
            <div class="col">
                <div class="card text-center">
                    <div class="card-header">
                        <i class="fas fa-road"></i> Total ruas jalan
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ count($ruasJalanDetails) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center text-white bg-danger">
                    <div class="card-header">
                        <i class="fas fa-exclamation-circle"></i> Jalan kondisi rusak
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['kondisi_id'] == 3)) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center text-white bg-warning">
                    <div class="card-header">
                        <i class="fas fa-exclamation-circle"></i> Jalan kondisi sedang
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['kondisi_id'] == 2)) }}</h5>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center text-white bg-success">
                    <div class="card-header">
                        <i class="fas fa-check-circle"></i> Jalan kondisi baik
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ count(array_filter($ruasJalanDetails, fn($ruas) => $ruas['kondisi_id'] == 1)) }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Pencarian -->
    <div class="search-bar" style="padding-top: 3rem">
        <form action="{{ route('ruasjalan.search') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan Kode Ruas atau Nama Ruas" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
    </div>
    

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kode Ruas</th>
                    <th>Panjang (km)</th>
                    <th>Lebar (m)</th>
                    <th>Eksisting</th>
                    <th>Kondisi</th>
                    <th>Jenis Jalan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ruasJalanDetails as $index => $ruas)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $ruas['nama_ruas'] }}</td>
                    <td>{{ $ruas['kode_ruas'] }}</td>
                    <td>{{ $ruas['panjang'] }}</td>
                    <td>{{ $ruas['lebar'] }}</td>
                    <td>
                        @if($ruas['eksisting_id'] == 1)
                            Tanah
                        @elseif($ruas['eksisting_id'] == 2)
                            Tanah/Beton
                        @elseif($ruas['eksisting_id'] == 3)
                            Perkerasan
                        @elseif($ruas['eksisting_id'] == 4)
                            Koral
                        @elseif($ruas['eksisting_id'] == 5)
                            Lapen
                        @elseif($ruas['eksisting_id'] == 6)
                            Paving
                        @elseif($ruas['eksisting_id'] == 7)
                            Hotmix
                        @elseif($ruas['eksisting_id'] == 8)
                            Beton
                        @elseif($ruas['eksisting_id'] == 9)
                            Beton/Lapen
                        @endif
                    </td>
                    <td>
                        @if($ruas['kondisi_id'] == 1)
                            Baik
                        @elseif($ruas['kondisi_id'] == 2)
                            Sedang
                        @elseif($ruas['kondisi_id'] == 3)
                            Rusak
                        @endif
                    </td>
                    <td>
                        @if($ruas['jenisjalan_id'] == 1)
                            Desa
                        @elseif($ruas['jenisjalan_id'] == 2)
                            Kabupaten
                        @elseif($ruas['jenisjalan_id'] == 3)
                            Provinsi
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('ruasjalan.delete', $ruas['id']) }}" method="POST" class="delete-form" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm delete-button">Hapus</button>
                        </form>
                        <a href="{{ route('getRuasJalanForEdit', ['id' => $ruas['id']]) }}" class="btn btn-primary btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{-- {{ $ruasJalanDetails->links() }} --}}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var form = this.closest('.delete-form');

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var apiToken = "{{ Session::get('token') }}";
            console.log('API Token:', apiToken);
        });
    </script>
</div>
@endsection
    