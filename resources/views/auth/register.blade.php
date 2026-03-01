@extends('layouts.guest')

@section('content')
    @if (session('success'))
        <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
    @endif

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side - Banner/Image -->
        <div
            class="hidden md:flex md:w-1/2 bg-nepal-blue dark:bg-gray-800 text-white p-8 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Elements -->
            <div
                class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full filter blur-3xl -translate-y-1/2 translate-x-1/4">
            </div>
            <div
                class="absolute bottom-0 left-0 w-64 h-64 bg-nepal-red/20 rounded-full filter blur-3xl translate-y-1/3 -translate-x-1/4">
            </div>

            <div class="relative z-10">
                <!-- Logo and Title -->

                <!-- Welcome Message -->
                <div class="mb-8">
                    <div class="inline-block px-3 py-1 bg-white/10 rounded-full text-sm font-medium mb-2">
                        {{ __('Official Portal') }}
                    </div>
                    <h2 class="text-4xl font-bold mb-4 leading-tight">
                        {{ __('Join the Digital Nepal Initiative') }}</h2>
                    <p class="text-lg opacity-80 mb-4">
                        {{ __('Register to access the appointment management system and start your digital journey.') }}</p>
                    <div class="h-1 w-20 bg-nepal-red rounded mb-6"></div>
                </div>

                <!-- Features List -->
                <div class="space-y-5 mb-10">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-white/10 p-2 rounded-lg mr-3">
                            <span class="iconify text-white" data-icon="tabler:user-plus" data-width="24"></span>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('Easy Registration') }}</h3>
                            <p class="text-white/80 text-sm">
                                {{ __('Simple and secure registration process with OTP verification') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-white/10 p-2 rounded-lg mr-3">
                            <span class="iconify text-white" data-icon="tabler:calendar-check" data-width="24"></span>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('Streamlined Appointments') }}</h3>
                            <p class="text-white/80 text-sm">
                                {{ __('Manage appointments efficiently with our intuitive interface') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-white/10 p-2 rounded-lg mr-3">
                            <span class="iconify text-white" data-icon="tabler:users" data-width="24"></span>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('Interdepartmental Coordination') }}</h3>
                            <p class="text-white/80 text-sm">
                                {{ __('Seamlessly coordinate with departments and staff across ministries') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 bg-white/10 p-2 rounded-lg mr-3">
                            <span class="iconify text-white" data-icon="tabler:shield-check" data-width="24"></span>
                        </div>
                        <div>
                            <h3 class="font-medium text-white">{{ __('Secure & Reliable') }}</h3>
                            <p class="text-white/80 text-sm">
                                {{ __('Enterprise-grade security for all government data and communications') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial/Quote -->
                <div class="bg-white/10 p-5 rounded-lg border border-white/20 mb-8">
                    <p class="italic text-white/90 mb-3">
                        "{{ __('The digital transformation initiative has empowered citizens to access government services more efficiently, reducing bureaucracy and improving transparency.') }}"
                    </p>
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-3">
                            <span class="iconify" data-icon="tabler:user" data-width="20"></span>
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ __('Ministry of Digital Initiatives') }}</p>
                            <p class="text-sm text-white/70">{{ __('Government of Nepal') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="bg-white/10 p-3 rounded-lg text-center">
                        <p class="text-2xl font-bold">{{ __('500K+') }}</p>
                        <p class="text-xs text-white/70">{{ __('Citizens Served') }}</p>
                    </div>
                    <div class="bg-white/10 p-3 rounded-lg text-center">
                        <p class="text-2xl font-bold">{{ __('75+') }}</p>
                        <p class="text-xs text-white/70">{{ __('Departments') }}</p>
                    </div>
                    <div class="bg-white/10 p-3 rounded-lg text-center">
                        <p class="text-2xl font-bold">{{ __('24/7') }}</p>
                        <p class="text-xs text-white/70">{{ __('Support') }}</p>
                    </div>
                </div>
            </div>

            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-5 mix-blend-overlay">
                <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                    <defs>
                        <pattern id="pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M0 20 L40 20 M20 0 L20 40" stroke="currentColor" stroke-width="1" fill="none" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#pattern)" />
                </svg>
            </div>

            <!-- Nepal Flag Colors Accent -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-nepal-red via-nepal-blue to-nepal-red">
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div
            class="w-full md:w-1/2 flex flex-col items-center justify-center p-6 md:p-12 bg-white dark:bg-gray-900 transition-colors duration-200">
            <div class="w-full max-w-md">
                <!-- Mobile Logo and Theme Toggle -->
                <div class="flex items-center justify-between mb-8">
                    <div class="md:hidden flex items-center">
                        <img src="{{ url('images/logo.png') }}" alt="Nepal Government Logo" class="h-16 mr-3"
                            onerror="this.src='https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Screenshot%202025-05-01%20at%2022.03.51-ye6FxaFELHtGfwaE12MDUZABqQKrhf.png'; this.onerror=null;">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Government of Nepal</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Appointment Management System</p>
                        </div>
                    </div>
                    <!-- Theme Toggle Button -->
                </div>

                <!-- Registration Form Header -->
                <div class="text-center mb-6" id="registrationFormHeader">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create Your Account</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Register to access government services</p>
                </div>
                <div class="text-center mb-6 hidden" id="otpVerificationHeader">
                    <div
                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 mb-4">
                        <span class="iconify text-green-600 dark:text-green-400" data-icon="tabler:message-2"
                            data-width="24"></span>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Verify Your Phone</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Enter the OTP sent to your phone</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div
                        class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-md text-blue-700 dark:text-blue-300">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="" class="space-y-6" id="registerForm">
                    @csrf
                    <div id="otpPhoneStep">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Full Name') }}
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                                        <span class="iconify" data-icon="tabler:user" data-width="18"></span>
                                    </span>
                                    <input id="name" type="text" name="name" required
                                        class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30"
                                        placeholder="{{ __('Enter your full name') }}" value="{{ old('name') }}">
                                </div>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ __('Phone Number') }}
                                </label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-500 dark:text-gray-400">
                                        <span class="iconify" data-icon="tabler:phone" data-width="18"></span>
                                    </span>
                                    <input id="number" type="number" name="number" required
                                        class="w-full pl-10 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30"
                                        placeholder="{{ __('Enter your phone number') }}" value="{{ old('number') }}">
                                </div>
                                @error('number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-nepal-blue focus:ring-nepal-blue dark:bg-gray-800">
                                <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    {{ __('I agree to the') }} <a href="#"
                                        class="text-nepal-blue hover:underline dark:text-nepal-blue/90">{{ __('Terms of Service') }}</a>
                                    {{ __('and') }} <a href="#"
                                        class="text-nepal-blue hover:underline dark:text-nepal-blue/90">{{ __('Privacy Policy') }}</a>
                                </label>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="button" id="sendOtpBtn"
                                class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-nepal-blue hover:bg-nepal-blue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900 transition-colors">
                                <span class="iconify mr-2" data-icon="tabler:send" data-width="18"></span>
                                {{ __('Send Verification Code') }}
                            </button>
                        </div>
                    </div>

                    <div id="otpVerificationStep" class="hidden space-y-6">
                        <div>
                            <label for="otp_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                {{ __('Enter OTP Code') }}
                            </label>
                            <div class="flex justify-between gap-2">
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                                <input type="text" maxlength="1"
                                    class="otp-input w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                            </div>
                            <input type="hidden" name="otp_code" id="otp_code_hidden">
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ __("Didn't receive the code?") }}</span>
                                <div class="flex items-center">
                                    <span id="resendCountdown"
                                        class="text-sm text-gray-500 dark:text-gray-400 mr-2"></span>
                                    <button type="button" id="resendOtpBtn"
                                        class="hidden text-nepal-blue hover:text-nepal-blue/80 dark:text-nepal-blue/90 dark:hover:text-nepal-blue text-sm font-medium">
                                        {{ __('Resend') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="button" id="verifyOtpBtn"
                                class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-nepal-blue hover:bg-nepal-blue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900 transition-colors">
                                <span class="iconify mr-2" data-icon="tabler:check" data-width="18"></span>
                                {{ __('Verify & Complete Registration') }}
                            </button>
                        </div>

                        <button type="button" id="backToPhoneStep"
                            class="w-full flex justify-center items-center py-2 px-4 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900">
                            <span class="iconify mr-2" data-icon="tabler:arrow-left" data-width="18"></span>
                            {{ __('Back') }}
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span
                            class="px-2 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">{{ __('or') }}</span>
                    </div>
                </div>

                <!-- Alternative Options -->
                <div class="space-y-4">
                    <!-- Google Sign Up Button -->
                    <button type="button"
                        class="w-full flex items-center justify-center gap-2 py-2.5 px-4 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900">
                        <span class="iconify" data-icon="flat-color:google" data-width="18"></span>
                        {{ __('Sign up with Google') }}
                    </button>

                    <!-- Login Link -->
                    <p class="text-center text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Already have an account?') }}
                        <a href="{{ route('login') }}"
                            class="font-medium text-nepal-blue hover:text-nepal-blue/80 dark:text-nepal-blue/90 dark:hover:text-nepal-blue">
                            {{ __('Login now') }}
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Theme toggler functionality
            const themeToggle = document.getElementById('themeToggle');
            const darkIcon = document.querySelector('.dark-icon');
            const lightIcon = document.querySelector('.light-icon');

            // Set initial state
            if (document.documentElement.classList.contains('dark')) {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }

            // Toggle theme
            themeToggle.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    darkIcon.classList.add('hidden');
                    lightIcon.classList.remove('hidden');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    darkIcon.classList.remove('hidden');
                    lightIcon.classList.add('hidden');
                }
            });

            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const resendOtpBtn = document.getElementById('resendOtpBtn');
            const verifyOtpBtn = document.getElementById('verifyOtpBtn');
            const backToPhoneStep = document.getElementById('backToPhoneStep');
            const otpPhoneStep = document.getElementById('otpPhoneStep');
            const otpVerificationStep = document.getElementById('otpVerificationStep');
            const registrationFormHeader = document.getElementById('registrationFormHeader');
            const otpVerificationHeader = document.getElementById('otpVerificationHeader');
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpCodeHidden = document.getElementById('otp_code_hidden');
            const countdownEl = document.getElementById('resendCountdown');

            localStorage.removeItem('timerStart');

            function showOtpVerificationStep() {
                otpPhoneStep.classList.add('hidden');
                otpVerificationStep.classList.remove('hidden');
                registrationFormHeader.classList.add('hidden');
                otpVerificationHeader.classList.remove('hidden');
                if (otpInputs.length > 0) otpInputs[0].focus();
            }

            function showPhoneStep() {
                otpPhoneStep.classList.remove('hidden');
                otpVerificationStep.classList.add('hidden');
                registrationFormHeader.classList.remove('hidden');
                otpVerificationHeader.classList.add('hidden');
            }

            function startOtpCountdown() {
                const start = parseInt(localStorage.getItem('timerStart'));
                if (!start || isNaN(start)) return;

                resendOtpBtn.classList.add('hidden'); // Hide resend button

                const interval = setInterval(() => {
                    const now = Date.now();
                    const elapsed = now - start;
                    const remaining = 2 * 60 * 1000 - elapsed; // 2 minutes

                    if (remaining > 0) {
                        const seconds = Math.ceil(remaining / 1000);
                        countdownEl.textContent = `${seconds}s`;
                    } else {
                        countdownEl.textContent = '';
                        resendOtpBtn.classList.remove('hidden'); // Show resend button
                        clearInterval(interval);
                    }
                }, 1000);
            }

            if (localStorage.getItem('timerStart')) {
                startOtpCountdown();
            }

            sendOtpBtn?.addEventListener('click', function(e) {
                e.preventDefault();
                const name = document.getElementById('name').value;
                const phone = document.getElementById('number').value;

                if (phone) {
                    sendOtpBtn.disabled = true;
                    sendOtpBtn.innerHTML =
                        '<span class="iconify animate-spin mr-2" data-icon="tabler:loader-2" data-width="18"></span> Sending...';

                    $.ajax({
                        url: "{{ route('register') }}",
                        method: 'POST',
                        data: {
                            name: name,
                            number: phone,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            var message = data.success ? data.message : (Array.isArray(data
                                    .message) ? data
                                .message.join('<br>') : data.message);
                            if (data.success) {
                                showOtpVerificationStep();
                                nepalToast.success('Success', 'OTP sent successfully');
                                localStorage.setItem('timerStart', Date.now());
                                startOtpCountdown();
                            } else {
                                sendOtpBtn.disabled = false;
                                sendOtpBtn.innerHTML =
                                    '<span class="iconify mr-2" data-icon="tabler:send" data-width="18"></span> Send Verification Code';
                                nepalToast.error('Error', message);
                            }
                        },
                        error: function() {
                            sendOtpBtn.disabled = false;
                            sendOtpBtn.innerHTML =
                                '<span class="iconify mr-2" data-icon="tabler:send" data-width="18"></span> Send Verification Code';
                            nepalToast.error('Error',
                            'Something went wrong. Please try again.');
                        }
                    });
                } else {
                    nepalToast.error('Error', 'Please enter your phone number');
                }
            });

            backToPhoneStep?.addEventListener('click', function(e) {
                e.preventDefault();
                showPhoneStep();
            });

            resendOtpBtn?.addEventListener('click', function(e) {
                e.preventDefault();

                const phone = document.getElementById('number').value;
                if (!phone) {
                    nepalToast.error('Error', 'Phone number missing');
                    return;
                }

                resendOtpBtn.disabled = true;
                resendOtpBtn.textContent = 'Sending...';

                $.ajax({
                    url: "{{ route('otp.login') }}",
                    method: 'POST',
                    data: {
                        phone: phone,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            localStorage.setItem('timerStart', Date.now());
                            startOtpCountdown();
                            nepalToast.success('Success', 'OTP resent successfully');
                        } else {
                            resendOtpBtn.disabled = false;
                            resendOtpBtn.textContent = 'Resend';
                            nepalToast.error('Error', response.message);
                        }
                    },
                    error: function() {
                        resendOtpBtn.disabled = false;
                        resendOtpBtn.textContent = 'Resend';
                        nepalToast.error('Error', 'Failed to resend OTP. Please try again.');
                    }
                });
            });

            // OTP input logic
            otpInputs.forEach((input, index) => {
                input.addEventListener('keyup', function(e) {
                    if (/^[0-9]$/.test(e.key)) {
                        if (index < otpInputs.length - 1) otpInputs[index + 1].focus();
                        updateOtpHiddenField();
                    }

                    if (e.key === 'Backspace') {
                        if (index > 0) otpInputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    const digits = pastedData.replace(/\D/g, '').split('').slice(0, otpInputs
                        .length);

                    digits.forEach((digit, i) => {
                        if (i < otpInputs.length) otpInputs[i].value = digit;
                    });

                    const nextIndex = digits.length < otpInputs.length ? digits.length : otpInputs
                        .length - 1;
                    otpInputs[nextIndex].focus();
                    updateOtpHiddenField();
                });
            });

            function updateOtpHiddenField() {
                let otp = '';
                otpInputs.forEach(input => {
                    otp += input.value;
                });
                otpCodeHidden.value = otp;
                verifyOtpBtn.disabled = otp.length !== otpInputs.length;
            }

            verifyOtpBtn.disabled = true;

            verifyOtpBtn?.addEventListener('click', function(e) {
                e.preventDefault();
                const otp = otpCodeHidden.value;

                if (otp.length === otpInputs.length) {
                    const phone = document.getElementById('number').value;

                    verifyOtpBtn.disabled = true;
                    verifyOtpBtn.innerHTML =
                        '<span class="iconify animate-spin mr-2" data-icon="tabler:loader-2" data-width="18"></span> Verifying...';

                    $.ajax({
                        url: "{{ route('register.citizen.verify') }}",
                        method: 'POST',
                        data: {
                            otp: otp,
                            phone: phone,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                localStorage.removeItem('timerStart');
                                nepalToast.success('Success', 'OTP verified successfully');

                                // Success animation
                                verifyOtpBtn.innerHTML =
                                    '<span class="iconify mr-2" data-icon="tabler:check" data-width="18"></span> Verified!';
                                verifyOtpBtn.classList.remove('bg-nepal-blue');
                                verifyOtpBtn.classList.add('bg-green-600');

                                setTimeout(() => {
                                    window.location.href = response.redirect_url;
                                }, 1000);
                            } else {
                                verifyOtpBtn.disabled = false;
                                verifyOtpBtn.innerHTML =
                                    '<span class="iconify mr-2" data-icon="tabler:check" data-width="18"></span> Verify & Complete Registration';
                                nepalToast.error('Error', response.message);
                            }
                        },
                        error: function() {
                            verifyOtpBtn.disabled = false;
                            verifyOtpBtn.innerHTML =
                                '<span class="iconify mr-2" data-icon="tabler:check" data-width="18"></span> Verify & Complete Registration';
                            nepalToast.error('Error', 'Verification failed. Please try again.');
                        }
                    });
                } else {
                    nepalToast.error('Error', 'Please enter the complete OTP');
                }
            });

            // Form validation
            const nameInput = document.getElementById('name');
            const phoneInput = document.getElementById('number');
            const rememberCheckbox = document.getElementById('remember_me');

            function validateForm() {
                let isValid = true;

                // Validate name
                if (!nameInput.value.trim()) {
                    isValid = false;
                    nameInput.classList.add('border-red-500', 'dark:border-red-500');
                } else {
                    nameInput.classList.remove('border-red-500', 'dark:border-red-500');
                }

                // Validate phone
                if (!phoneInput.value.trim() || phoneInput.value.length < 10) {
                    isValid = false;
                    phoneInput.classList.add('border-red-500', 'dark:border-red-500');
                } else {
                    phoneInput.classList.remove('border-red-500', 'dark:border-red-500');
                }

                // Validate terms agreement
                if (!rememberCheckbox.checked) {
                    isValid = false;
                    rememberCheckbox.classList.add('ring-2', 'ring-red-500');
                } else {
                    rememberCheckbox.classList.remove('ring-2', 'ring-red-500');
                }

                return isValid;
            }

            // Add validation before sending OTP
            const originalSendOtpClick = sendOtpBtn.onclick;
            sendOtpBtn.onclick = function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    nepalToast.error('Error', 'Please fill all required fields and agree to the terms');
                    return false;
                }

                // Call the original handler
                return originalSendOtpClick.call(this, e);
            };

            // Input validation on blur
            nameInput.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('border-red-500', 'dark:border-red-500');
                } else {
                    this.classList.remove('border-red-500', 'dark:border-red-500');
                }
            });

            phoneInput.addEventListener('blur', function() {
                if (!this.value.trim() || this.value.length < 10) {
                    this.classList.add('border-red-500', 'dark:border-red-500');
                } else {
                    this.classList.remove('border-red-500', 'dark:border-red-500');
                }
            });

            rememberCheckbox.addEventListener('change', function() {
                if (!this.checked) {
                    this.classList.add('ring-2', 'ring-red-500');
                } else {
                    this.classList.remove('ring-2', 'ring-red-500');
                }
            });
        });
    </script>
@endpush
