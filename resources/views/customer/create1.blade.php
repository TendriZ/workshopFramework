@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">Tambah Customer 1 (Blob)</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('customer.store1') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label>Alamat</label>
                    <input type="text" name="alamat" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Provinsi</label>
                    <input type="text" name="provinsi" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Kota</label>
                    <input type="text" name="kota" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control">
                </div>
                <div class="form-group mb-3">
                    <label>Kodepos / Kelurahan</label>
                    <input type="text" name="kodepos_kelurahan" class="form-control">
                </div>
                
                <div class="form-group mb-3">
                    <label>Foto</label><br>
                    <div id="camera-container" style="width: 320px; height: 240px; border: 1px solid #ccc; margin-bottom: 10px; background: #eee;">
                        <video id="videoElement" width="320" height="240" autoplay style="display: none;"></video>
                        <canvas id="canvasElement" width="320" height="240" style="display: none;"></canvas>
                        <img id="photoResult" width="320" height="240" style="display: none;" />
                    </div>
                    <!-- Hidden input to store base64 data -->
                    <input type="hidden" name="foto" id="inputFoto" required>
                    
                    <button type="button" class="btn btn-secondary" onclick="startCamera()">Ambil Foto</button>
                    <button type="button" class="btn btn-info d-none" id="btnSnap" onclick="takeSnapshot()">Snap</button>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Data (Blob)</button>
            </form>
        </div>
    </div>
</div>

<script>
    const video = document.getElementById('videoElement');
    const canvas = document.getElementById('canvasElement');
    const photoResult = document.getElementById('photoResult');
    const inputFoto = document.getElementById('inputFoto');
    const btnSnap = document.getElementById('btnSnap');

    function startCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(stream) {
                    video.srcObject = stream;
                    video.style.display = 'block';
                    photoResult.style.display = 'none';
                    btnSnap.classList.remove('d-none');
                })
                .catch(function(error) {
                    console.error("Camera error:", error);
                    alert("Gagal mengakses kamera!");
                });
        }
    }

    function takeSnapshot() {
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        // Stop video stream
        const stream = video.srcObject;
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());
        
        const dataURL = canvas.toDataURL('image/png');
        photoResult.src = dataURL;
        
        video.style.display = 'none';
        photoResult.style.display = 'block';
        btnSnap.classList.add('d-none');
        
        // set value to hidden input
        inputFoto.value = dataURL;
    }
</script>
@endsection