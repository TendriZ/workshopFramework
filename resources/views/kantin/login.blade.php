@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Login Vendor Kantin</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('vendor.login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="idvendor" class="form-label">Pilih Vendor Kantin</label>
                            <select name="idvendor" id="idvendor" class="form-select" required>
                                <option value="">-- Pilih Vendor --</option>
                                @foreach($vendors as $v)
                                    <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simulasikan Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection