<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP - Koleksi Buku</title>
    <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/style.css') }}">
    <style>
        .otp-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        .otp-input:focus {
            border-color: #667eea;
            outline: none;
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5">
                            <div class="brand-logo text-center">
                                <h3>Verifikasi OTP</h3>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <p class="text-center">Masukkan kode OTP 6 digit yang telah dikirim ke email Anda</p>

                            <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
                                @csrf

                                {{-- OTP Inputs --}}
                                <div class="otp-container">
                                    <input type="text" class="otp-input" maxlength="1" id="otp1" autofocus>
                                    <input type="text" class="otp-input" maxlength="1" id="otp2">
                                    <input type="text" class="otp-input" maxlength="1" id="otp3">
                                    <input type="text" class="otp-input" maxlength="1" id="otp4">
                                    <input type="text" class="otp-input" maxlength="1" id="otp5">
                                    <input type="text" class="otp-input" maxlength="1" id="otp6">
                                </div>

                                {{-- Hidden input untuk submit --}}
                                <input type="hidden" name="otp" id="otpFull">

                                @error('otp')
                                    <p class="text-danger text-center">{{ $message }}</p>
                                @enderror

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium">
                                        Verifikasi
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-3">
                                <form action="{{ route('otp.resend') }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link">
                                        Kirim ulang kode OTP
                                    </button>
                                </form>
                            </div>

                            <div class="text-center mt-2">
                                <a href="{{ route('login') }}" class="text-primary">Kembali ke Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        
        inputs.forEach((input, index) => {
            // Auto focus next input
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                
                // Auto submit when all filled
                updateHiddenOTP();
                if (index === inputs.length - 1 && e.target.value) {
                    document.getElementById('otpForm').submit();
                }
            });

            // Backspace to previous
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // Only numbers
            input.addEventListener('keypress', (e) => {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault();
                }
            });

            // Paste handling
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = e.clipboardData.getData('text');
                const digits = paste.match(/\d/g);
                
                if (digits) {
                    digits.slice(0, 6).forEach((digit, i) => {
                        if (inputs[i]) {
                            inputs[i].value = digit;
                        }
                    });
                    updateHiddenOTP();
                }
            });
        });

        function updateHiddenOTP() {
            const otp = Array.from(inputs).map(input => input.value).join('');
            document.getElementById('otpFull').value = otp;
        }
    </script>
</body>
</html>