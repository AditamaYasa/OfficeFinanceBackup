<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TigaJaya Finance</title>
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
    <main class="flex items-center justify-center w-full min-h-screen px-4 py-6">
        <section
            class="bg-white/90 backdrop-blur-md p-4 sm:p-6 md:p-8 rounded-xl shadow-lg flex flex-col items-center w-full max-w-xs sm:max-w-sm md:max-w-md animate-fadeIn">
            <div class="mb-1 flex flex-col items-center justify-center">
                <img src="{{ asset('assets/picture/logo.png') }}" alt="logo TigaJaya Finance"
                    class="w-36 md:w-40 lg:w-40 mb-4 sm:mb-6 object-contain">
            </div>

            <div class="flex flex-col items-center w-full">
                <div id="errorBox"
                    class="hidden bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded mb-4 w-full text-left text-xs sm:text-sm">
                    <ul id="errorList" class="list-disc pl-4 sm:pl-5">
                    </ul>
                </div>

                <form id="loginForm" method="POST" action="{{ route('login') }}"
                    class="flex flex-col w-full items-center">
                    @csrf
                    <div id="username" class="w-full mt-2">
                        <label for="usernameInput" class="sr-only">Username</label>
                        <input id="usernameInput" name="username" type="text" placeholder="Username"
                            value="{{ old('username') }}" autofocus
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-[#0B3B9F] rounded-lg mb-3 sm:mb-4 outline-none focus:border-[#0B3B9F] focus:ring-2 focus:ring-[#0B3B9F] focus:ring-opacity-30">
                    </div>

                    <div id="password" class="w-full">
                        <label for="passwordInput" class="sr-only">Password</label>
                        <input id="passwordInput" name="password" type="password" placeholder="Password"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border-2 border-[#0B3B9F] rounded-lg mb-3 sm:mb-4 outline-none focus:border-[#0B3B9F] focus:ring-2 focus:ring-[#0B3B9F] focus:ring-opacity-30">
                    </div>

                    <div class="flex items-center justify-between mt-2 mb-3 sm:mb-4 w-full">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember"
                                class="w-4 h-4 mr-2 appearance-none rounded border border-gray-400 checked:bg-[#0B3B9F] checked:border-[#0B3B9F] relative">
                            <label for="remember" class="text-xs sm:text-sm text-gray-700">Ingat saya</label>
                        </div>
                    </div>

                    <div id="button" class="mt-2 w-full">
                        <button type="submit"
                            class="w-full text-sm sm:text-base md:text-lg bg-[#0B3B9F] text-white py-2 sm:py-3 rounded-lg font-bold transition duration-300 hover:bg-[#F20E0F] hover:text-white active:scale-95">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <script>
            let isFormDisabled = false;

            function displayServerErrors() {
                const errorBox = document.getElementById('errorBox');
                const errorList = document.getElementById('errorList');
                const errors = @json($errors->all());

                if (errors.length > 0) {
                    errorList.innerHTML = '';
                    errors.forEach(error => {
                        const li = document.createElement('li');
                        li.textContent = error;
                        errorList.appendChild(li);
                    });
                    errorBox.classList.remove('hidden');
                }
            }

            function toggleInputs(disabled) {
                const usernameInput = document.getElementById('usernameInput');
                const passwordInput = document.getElementById('passwordInput');

                usernameInput.readOnly = disabled;
                passwordInput.readOnly = disabled;

                if (disabled) {
                    usernameInput.style.pointerEvents = 'none';
                    passwordInput.style.pointerEvents = 'none';
                    usernameInput.style.opacity = '0.6';
                    passwordInput.style.opacity = '0.6';
                } else {
                    usernameInput.style.pointerEvents = 'auto';
                    passwordInput.style.pointerEvents = 'auto';
                    usernameInput.style.opacity = '1';
                    passwordInput.style.opacity = '1';
                }

                isFormDisabled = disabled;
            }

            function showValidationError(message) {
                const errorBox = document.getElementById('errorBox');
                const errorList = document.getElementById('errorList');
                
                errorList.innerHTML = '<li>' + message + '</li>';
                errorBox.classList.remove('hidden');
            }

            function hideValidationError() {
                const errorBox = document.getElementById('errorBox');
                errorBox.classList.add('hidden');
            }

            window.addEventListener('DOMContentLoaded', function() {
                displayServerErrors();

                function startLockoutCountdown() {
                    const errorBox = document.getElementById('errorBox');
                    const errorList = document.getElementById('errorList');
                    if (!errorBox.classList.contains('hidden')) {
                        const errorText = errorBox.textContent;
                        if (!errorText.includes('terkunci') && !errorText.includes('percobaan')) return;

                        let seconds = 60;
                        const updateMessage = () => {
                            const listItems = errorList.querySelectorAll('li');
                            if (listItems.length > 0) {
                                const originalText = listItems[0].textContent;
                                const updatedText = originalText.replace(/\d+ detik/, `${seconds} detik`);
                                listItems[0].textContent = updatedText;
                            }
                            
                            if (seconds > 0) {
                                seconds--;
                                setTimeout(updateMessage, 1000);
                            }
                        };
                        setTimeout(updateMessage, 1000);
                    }
                }

                startLockoutCountdown();
            });

            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const username = document.getElementById('usernameInput').value.trim();
                const password = document.getElementById('passwordInput').value.trim();
                const usernameInput = document.getElementById('usernameInput');
                const passwordInput = document.getElementById('passwordInput');

                let hasError = false;

                usernameInput.classList.remove('border-red-500');
                passwordInput.classList.remove('border-red-500');
                hideValidationError();

                if (username === '' && password === '') {
                    e.preventDefault();
                    showValidationError('Please enter your username and password.');
                    usernameInput.classList.add('border-red-500');
                    passwordInput.classList.add('border-red-500');
                    toggleInputs(true);
                    hasError = true;
                } else {
                    if (username === '') {
                        e.preventDefault();
                        showValidationError('Please enter your username and password.');
                        usernameInput.classList.add('border-red-500');
                        hasError = true;
                    }
                    if (password === '') {
                        e.preventDefault();
                        showValidationError('Please enter your username and password.');
                        passwordInput.classList.add('border-red-500');
                        hasError = true;
                    }

                    if (hasError) {
                        toggleInputs(true);
                    }
                }
            });

            document.getElementById('loginForm').addEventListener('click', function(e) {
                if (isFormDisabled) {
                    toggleInputs(false);
                    hideValidationError();

                    document.getElementById('usernameInput').classList.remove('border-red-500');
                    document.getElementById('passwordInput').classList.remove('border-red-500');

                    const usernameInput = document.getElementById('usernameInput');
                    const passwordInput = document.getElementById('passwordInput');

                    if (usernameInput.value.trim() === '') {
                        setTimeout(() => usernameInput.focus(), 10);
                    } else if (passwordInput.value.trim() === '') {
                        setTimeout(() => passwordInput.focus(), 10);
                    }
                }
            });

            document.getElementById('usernameInput').addEventListener('input', function() {
                if (isFormDisabled) return;

                const username = document.getElementById('usernameInput').value.trim();
                const password = document.getElementById('passwordInput').value.trim();

                if (username !== '' && password !== '') {
                    hideValidationError();
                    document.getElementById('usernameInput').classList.remove('border-red-500');
                    document.getElementById('passwordInput').classList.remove('border-red-500');
                }
            });

            document.getElementById('passwordInput').addEventListener('input', function() {
                if (isFormDisabled) return;

                const username = document.getElementById('usernameInput').value.trim();
                const password = document.getElementById('passwordInput').value.trim();

                if (username !== '' && password !== '') {
                    hideValidationError();
                    document.getElementById('usernameInput').classList.remove('border-red-500');
                    document.getElementById('passwordInput').classList.remove('border-red-500');
                }
            });

            document.getElementById('usernameInput').addEventListener('keydown', function(e) {
                if (isFormDisabled) {
                    e.preventDefault();
                }
            });

            document.getElementById('passwordInput').addEventListener('keydown', function(e) {
                if (isFormDisabled) {
                    e.preventDefault();
                }
            });
        </script>
    </main>
</body>

</html>
