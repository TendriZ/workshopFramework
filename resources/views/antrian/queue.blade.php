@extends('layouts.auth')

@section('title', 'Nomor Antrian - ' . $antrian->nomor_antrian)

@section('content')
    <div class="container mt-5">
        <div class="card border-0 shadow">
            <div class="card-body text-center py-5">
                <h3 class="card-title mb-3">Nomor Antrian Anda</h3>

                <div class="my-4">
                    <div class="display-1 font-weight-bold text-primary">
                        {{ $antrian->nomor_antrian }}
                    </div>
                </div>

                <div class="mb-4">
                    <h4 class="text-muted">{{ $antrian->nama }}</h4>
                    <span class="badge badge-{{ $antrian->status === 'waiting' ? 'warning' : ($antrian->status === 'called' ? 'success' : 'danger') }} badge-pill">
                        @if($antrian->status === 'waiting')
                            Menunggu
                        @elseif($antrian->status === 'called')
                            Sudah Dipanggil
                        @else
                            Terlewat
                        @endif
                    </span>
                </div>

                @if($antrian->status === 'called')
                    <div class="alert alert-success">
                        <h5>Silakan Masuk!</h5>
                        <p class="mb-0">Nomor Anda telah dipanggil. Silakan menuju ke loket {{ $antrian->loket ?? '1' }}</p>
                        <p class="mb-0 small text-muted">Dipanggil pada: {{ $antrian->called_at?->format('H:i:s') }}</p>
                    </div>
                @elseif($antrian->status === 'waiting')
                    <div class="alert alert-info">
                        <p class="mb-0">Mohon tunggu sampai nomor Anda dipanggil.</p>
                        <p class="mb-0 small">Status akan diperbarui secara otomatis.</p>
                    </div>
                @endif

                <div id="realtimeStatus" class="mt-4" style="display: none;">
                    <div class="text-center">
                        <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                        <span class="ml-2">Menerima update...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3">
            <a href="/guest" class="btn btn-outline-primary">
                <i class="mdi mdi-arrow-left"></i> Kembali ke Form Pendaftaran
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const antrianId = {{ $antrian->id }};
        const eventSource = new EventSource('/antrian/stream');

        eventSource.addEventListener('queue-update', function(event) {
            const data = JSON.parse(event.data);
            console.log('SSE Update:', data);

            const waitingList = data.waiting || [];
            const currentAntrian = waitingList.find(a => a.id === antrianId);

            if (currentAntrian) {
                const statusBadge = document.querySelector('.badge');
                const alertBox = document.querySelector('.alert');

                if (currentAntrian.status === 'called') {
                    window.location.reload();
                }
            }
        });

        eventSource.onerror = function(error) {
            console.error('SSE Error:', error);
        };
    </script>
@endsection