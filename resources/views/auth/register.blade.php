<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar User - TigaJaya Finance</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<style>
    input[type="checkbox"]:checked::after {
        content: "✓";
        color: white;             
        font-size: 10px;         
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<body class="min-h-screen flex items-center justify-center font-poppins bg-cover bg-center bg-no-repeat bg-fixed" 
      style="background-image: linear-gradient(rgba(255,255,255,0.9), rgba(251,252,255,0.85)), url('/assets/picture/background.png');">
    <main class="flex items-center justify-center w-full min-h-screen  px-4 py-6">
        <section class="bg-white/90 backdrop-blur-md p-4 sm:p-6 md:p-8 rounded-xl shadow-lg flex flex-col items-center w-full max-w-xs sm:max-w-sm md:max-w-md animate-fadeIn">
            <div class="mb-1 flex flex-col items-center justify-center">
                <img src="{{ asset('assets/picture/logo.png') }}"  alt="logo TigaJaya Finance" class="w-36 md:w-40 lg:w-40 mb-4 sm:mb-6 object-contain">
            </div>

            <div class="flex flex-col items-center w-full">
                @if ($errors->any())
                    <div id="serverErrors" class="bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded mb-4 w-full text-left text-xs sm:text-sm">
                        <ul class="list-disc pl-4 sm:pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="registerForm" method="POST" action="{{ route('register') }}"  class="flex flex-col w-full items-center">
                    @csrf
                    <div id="username" class="mt-2 w-full">
                        <label for="usernameInput" class="sr-only">Username</label>
                        <input id="usernameInput" name="username" type="text" placeholder="Username"
                            value="{{ old('username') }}" autofocus
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-[#0B3B9F] rounded-lg mb-3 sm:mb-4 outline-none focus:border-[#0B3B9F] focus:ring-2 focus:ring-[#0B3B9F] focus:ring-opacity-30">
                        <div id="usernameError" class="hidden mt-1 mb-3">
                            <p class="text-red-500 text-xs italic text-left">Username is required.</p>
                        </div>

                        @error('username')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="password" class="w-full">
                        <label for="passwordInput" class="sr-only">Password</label>
                        <input id="passwordInput" name="password" type="password" placeholder="Password"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-[#0B3B9F] rounded-lg mb-3 sm:mb-4 outline-none focus:border-[#0B3B9F] focus:ring-2 focus:ring-[#0B3B9F] focus:ring-opacity-30">
                        <div id="passwordError" class="hidden mt-1 mb-3">
                            <p class="text-red-500 text-xs italic text-left">Password is required.</p>
                        </div>

                        @error('password')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="confirmPassword" class="w-full">
                        <label for="confirmPasswordInput" class="sr-only">Confirm Password</label>
                        <input id="confirmPasswordInput" name="password_confirmation" type="password" placeholder="Confirm Password"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-[#0B3B9F] rounded-lg mb-3 sm:mb-4 outline-none focus:border-[#0B3B9F] focus:ring-2 focus:ring-[#0B3B9F] focus:ring-opacity-30">
                        <div id="confirmPasswordError" class="hidden mt-1 mb-3">
                            <p class="text-red-500 text-xs italic text-left">Confirm password is required.</p>
                        </div>
                        <div id="passwordMismatchError" class="hidden mt-1 mb-3">
                            <p class="text-red-500 text-xs italic text-left">Passwords do not match.</p>
                        </div>

                        @error('password_confirmation')
                            <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="emptyFieldsError" class="hidden mt-1 mb-4 w-full text-left">
                        <p class="text-red-500 text-sm font-medium">Semua field harus diisi</p>
                    </div>

                    <div class="flex items-center justify-between mt-2 mb-3 sm:mb-4 w-full">
                        <div class="flex items-center">
                            <input type="checkbox" name="terms" id="terms"
                                class="w-4 h-4 mr-2 appearance-none rounded border border-gray-400 checked:bg-[#0B3B9F] checked:border-[#0B3B9F] relative">
                            <label for="terms" class="text-xs sm:text-sm text-gray-700">Saya setuju dengan syarat & ketentuan</label>
                        </div>
                    </div>
                    <div id="termsError" class="hidden mt-1 mb-4">
                        <p class="text-red-500 text-xs italic text-left">You must agree to the terms and conditions.</p>
                    </div>

                    <div id="button" class="mt-2 w-full">
                        <button type="submit"
                            class="w-full text-sm sm:text-base md:text-lg bg-[#F20E0F] text-white py-2 sm:py-3 rounded-lg font-bold transition duration-300 hover:bg-[#0B3B9F] hover:text-white active:scale-95">
                            Daftar
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <script>
            let isFormDisabled = false;

            function toggleInputs(disabled) {
                const usernameInput = document.getElementById('usernameInput');
                const passwordInput = document.getElementById('passwordInput');
                const confirmPasswordInput = document.getElementById('confirmPasswordInput');
                const termsCheckbox = document.getElementById('terms');

                const inputs = [usernameInput, passwordInput, confirmPasswordInput];

                inputs.forEach(input => {
                    input.readOnly = disabled;
                    if (disabled) {
                        input.style.pointerEvents = 'none';
                        input.style.opacity = '0.6';
                    } else {
                        input.style.pointerEvents = 'auto';
                        input.style.opacity = '1';
                    }
                });

                termsCheckbox.disabled = disabled;
                if (disabled) {
                    termsCheckbox.style.pointerEvents = 'none';
                    termsCheckbox.style.opacity = '0.6';
                } else {
                    termsCheckbox.style.pointerEvents = 'auto';
                    termsCheckbox.style.opacity = '1';
                }

                isFormDisabled = disabled;
            }

            document.getElementById('registerForm').addEventListener('submit', function(e) {
                const username = document.getElementById('usernameInput').value.trim();
                const password = document.getElementById('passwordInput').value.trim();
                const confirmPassword = document.getElementById('confirmPasswordInput').value.trim();
                const termsChecked = document.getElementById('terms').checked;

                const emptyFieldsError = document.getElementById('emptyFieldsError');
                const usernameError = document.getElementById('usernameError');
                const passwordError = document.getElementById('passwordError');
                const confirmPasswordError = document.getElementById('confirmPasswordError');
                const passwordMismatchError = document.getElementById('passwordMismatchError');
                const termsError = document.getElementById('termsError');
                const serverErrors = document.getElementById('serverErrors');

                const usernameInput = document.getElementById('usernameInput');
                const passwordInput = document.getElementById('passwordInput');
                const confirmPasswordInput = document.getElementById('confirmPasswordInput');

                emptyFieldsError.classList.add('hidden');
                usernameError.classList.add('hidden');
                passwordError.classList.add('hidden');
                confirmPasswordError.classList.add('hidden');
                passwordMismatchError.classList.add('hidden');
                termsError.classList.add('hidden');

                if (serverErrors) {
                    serverErrors.classList.add('hidden');
                }

                usernameInput.classList.remove('border-red-500');
                passwordInput.classList.remove('border-red-500');
                confirmPasswordInput.classList.remove('border-red-500');

                let hasError = false;

                if (username === '' && password === '' && confirmPassword === '') {
                    e.preventDefault();
                    emptyFieldsError.classList.remove('hidden');
                    usernameInput.classList.add('border-red-500');
                    passwordInput.classList.add('border-red-500');
                    confirmPasswordInput.classList.add('border-red-500');
                    toggleInputs(true);
                    hasError = true;
                } else {
                    if (username === '') {
                        e.preventDefault();
                        usernameError.classList.remove('hidden');
                        usernameInput.classList.add('border-red-500');
                        hasError = true;
                    }
                    if (password === '') {
                        e.preventDefault();
                        passwordError.classList.remove('hidden');
                        passwordInput.classList.add('border-red-500');
                        hasError = true;
                    }
                    if (confirmPassword === '') {
                        e.preventDefault();
                        confirmPasswordError.classList.remove('hidden');
                        confirmPasswordInput.classList.add('border-red-500');
                        hasError = true;
                    } else if (password !== confirmPassword && password !== '') {
                        e.preventDefault();
                        passwordMismatchError.classList.remove('hidden');
                        passwordInput.classList.add('border-red-500');
                        confirmPasswordInput.classList.add('border-red-500');
                        hasError = true;
                    }

                    if (hasError) {
                        toggleInputs(true);
                    }
                }

                if (!termsChecked && !hasError) {
                    e.preventDefault();
                    termsError.classList.remove('hidden');
                    hasError = true;
                    toggleInputs(true);
                }

                if (hasError) {
                    return false;
                }
            });

            document.getElementById('registerForm').addEventListener('click', function(e) {
                if (isFormDisabled) {
                    toggleInputs(false);

                    document.getElementById('emptyFieldsError').classList.add('hidden');
                    document.getElementById('usernameError').classList.add('hidden');
                    document.getElementById('passwordError').classList.add('hidden');
                    document.getElementById('confirmPasswordError').classList.add('hidden');
                    document.getElementById('passwordMismatchError').classList.add('hidden');
                    document.getElementById('termsError').classList.add('hidden');

                    const serverErrors = document.getElementById('serverErrors');
                    if (serverErrors) {
                        serverErrors.classList.add('hidden');
                    }

                    document.getElementById('usernameInput').classList.remove('border-red-500');
                    document.getElementById('passwordInput').classList.remove('border-red-500');
                    document.getElementById('confirmPasswordInput').classList.remove('border-red-500');

                    const usernameInput = document.getElementById('usernameInput');
                    const passwordInput = document.getElementById('passwordInput');
                    const confirmPasswordInput = document.getElementById('confirmPasswordInput');

                    if (usernameInput.value.trim() === '') {
                        setTimeout(() => usernameInput.focus(), 10);
                    } else if (passwordInput.value.trim() === '') {
                        setTimeout(() => passwordInput.focus(), 10);
                    } else if (confirmPasswordInput.value.trim() === '') {
                        setTimeout(() => confirmPasswordInput.focus(), 10);
                    }
                }
            });

            document.getElementById('usernameInput').addEventListener('input', function() {
                if (isFormDisabled) return;
                const usernameError = document.getElementById('usernameError');
                if (this.value.trim() !== '') {
                    usernameError.classList.add('hidden');
                    this.classList.remove('border-red-500');

                    if (serverErrors) {
                        serverErrors.classList.add('hidden');
                    }

                    const password = document.getElementById('passwordInput').value.trim();
                    const confirmPassword = document.getElementById('confirmPasswordInput').value.trim();
                    if (password !== '' && confirmPassword !== '') {
                        emptyFieldsError.classList.add('hidden');
                        document.getElementById('passwordInput').classList.remove('border-red-500');
                        document.getElementById('confirmPasswordInput').classList.remove('border-red-500');
                    }
                }
            });

            document.getElementById('passwordInput').addEventListener('input', function() {
                if (isFormDisabled) return;

                const passwordError = document.getElementById('passwordError');
                const passwordMismatchError = document.getElementById('passwordMismatchError');
                const confirmPasswordInput = document.getElementById('confirmPasswordInput');

                if (this.value.trim() !== '') {
                    passwordError.classList.add('hidden');
                    this.classList.remove('border-red-500');

                    if (serverErrors) {
                        serverErrors.classList.add('hidden');
                    }

                    const confirmPassword = confirmPasswordInput.value.trim();
                    if (confirmPassword !== '' && this.value !== confirmPassword) {
                        passwordMismatchError.classList.remove('hidden');
                        this.classList.add('border-red-500');
                        confirmPasswordInput.classList.add('border-red-500');
                    } else if (confirmPassword !== '' && this.value === confirmPassword) {
                        passwordMismatchError.classList.add('hidden');
                        this.classList.remove('border-red-500');
                        confirmPasswordInput.classList.remove('border-red-500');
                    }

                    const username = document.getElementById('usernameInput').value.trim();
                    if (username !== '' && confirmPassword !== '') {
                        emptyFieldsError.classList.add('hidden');
                        document.getElementById('usernameInput').classList.remove('border-red-500');
                        confirmPasswordInput.classList.remove('border-red-500');
                    }
                }
            });

            document.getElementById('confirmPasswordInput').addEventListener('input', function() {
                if (isFormDisabled) return;

                const confirmPasswordError = document.getElementById('confirmPasswordError');
                const passwordMismatchError = document.getElementById('passwordMismatchError');
                const passwordInput = document.getElementById('passwordInput');

                if (this.value.trim() !== '') {
                    confirmPasswordError.classList.add('hidden');
                    this.classList.remove('border-red-500');

                    if (serverErrors) {
                        serverErrors.classList.add('hidden');
                    }

                    const password = passwordInput.value.trim();
                    if (password !== '' && this.value !== password) {
                        passwordMismatchError.classList.remove('hidden');
                        this.classList.add('border-red-500');
                        passwordInput.classList.add('border-red-500');
                    } else if (password !== '' && this.value === password) {
                        passwordMismatchError.classList.add('hidden');
                        this.classList.remove('border-red-500');
                        passwordInput.classList.remove('border-red-500');
                    }

                    const username = document.getElementById('usernameInput').value.trim();
                    if (username !== '' && password !== '') {
                        emptyFieldsError.classList.add('hidden');
                        document.getElementById('usernameInput').classList.remove('border-red-500');
                        passwordInput.classList.remove('border-red-500');
                    }
                }
            });

            ['usernameInput', 'passwordInput', 'confirmPasswordInput'].forEach(id => {
                document.getElementById(id).addEventListener('keydown', function(e) {
                    if (isFormDisabled) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    </main>
</body>

</html>