@extends('layout')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Data Ruas Jalan</h2>

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

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>id</th>
                        <th>Nama</th>
                        <th>Kode Ruas</th>
                        <th>Panjang (m)</th>
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
                            <td>{{ $ruas['id'] }}</td>
                            <td>{{ $ruas['nama_ruas'] }}</td>
                            <td>{{ $ruas['kode_ruas'] }}</td>
                            <td>{{ $ruas['panjang'] }}</td>
                            <td>{{ $ruas['lebar'] }}</td>
                            <td>{{ $ruas['eksisting_id'] }}</td>
                            <td>
                                @if($ruas['kondisi_id'] == 1)
                                    Baik
                                @elseif($ruas['kondisi_id'] == 2)
                                    Sedang
                                @elseif($ruas['kondisi_id'] == 3)
                                    Rusak
                                @endif
                            </td>
                            <td>{{ $ruas['jenisjalan_id'] }}</td>
                            <td>
                                <form action="{{ route('ruasjalan.delete', $ruas['id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
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
                var apiToken = "{{ Session::get('token') }}";
                console.log('API Token:', apiToken);
            });
        </script>
        
    </div>
@endsection
