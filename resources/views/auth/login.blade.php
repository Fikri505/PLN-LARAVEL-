<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — PLN UID JATENG DIY</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-[#083D77] via-[#0A5EB0] to-[#0EA5E9] font-sans flex items-center justify-center p-4 relative overflow-hidden">

    {{-- Animated background shapes --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-pln-yellow/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-sky-300/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-white/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        {{-- Login Card --}}
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 md:p-10 animate-fade-in-up border border-white/50">
            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-pln-blue to-pln-blue-dark shadow-xl shadow-pln-blue/30 mb-5 p-3">
                    <img src="{{ asset('images/Logo_pln.png') }}" alt="PLN" class="w-full h-full object-contain drop-shadow-lg">
                </div>
                <h1 class="text-2xl font-black text-text-primary tracking-tight">PLN UID JATENG DIY</h1>
                <p class="text-sm text-text-secondary mt-1 font-medium">Dashboard IT Support System</p>
            </div>

            {{-- Error Message --}}
            @if($errors->has('login'))
                <div class="alert alert-error mb-6 animate-fade-in-up">
                    <span>⚠️</span>
                    <span>{{ $errors->first('login') }}</span>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="form-label">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                           class="form-input !py-3.5 !rounded-2xl !border-2 focus:!border-pln-blue"
                           placeholder="Masukkan username Anda" autofocus required>
                </div>

                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-input !py-3.5 !rounded-2xl !border-2 focus:!border-pln-blue"
                           placeholder="Masukkan password Anda" required>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-pln-blue to-pln-blue-dark text-white font-bold rounded-2xl text-base uppercase tracking-wide shadow-xl shadow-pln-blue/30 hover:shadow-2xl hover:shadow-pln-blue/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200">
                    🔐 Login
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-white/50 text-xs mt-6 font-medium">
            &copy; {{ date('Y') }} PLN UID Jateng & DIY · IT Support Division
        </p>
    </div>
</body>
</html>
