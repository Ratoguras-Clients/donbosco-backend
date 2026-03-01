@if (session('success'))
    <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
@endif


<div id="otpLoginForm" class="login-form hidden">
    <form method="POST" action="#" class="space-y-6" id="otpForm">
        @csrf
        <!-- Phone Number -->
        <div id="otpPhoneStep">
            <div>
                <label for="otp_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Phone Number
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                        <span class="iconify" data-icon="line-md:phone-filled" data-width="18"></span>
                    </span>
                    <input id="otp_phone" type="number" name="phone_number" required
                        class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30"
                        placeholder="Enter Your Phone Number">
                </div>
            </div>

            <div class="mt-6">
                <button type="button" id="sendOtpBtn"
                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-nepal-blue hover:bg-nepal-blue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900 transition-colors">
                    Send OTP
                </button>
            </div>
        </div>

        <!-- OTP Verification -->
        <div id="otpVerificationStep" class="hidden">
            <div>
                <label for="otp_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Enter OTP Code
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
                <span class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Didn't receive the code?</span>
                <span id="resendCountdown" class="text-sm text-gray-500 dark:text-gray-400 mt-1"></span>
                <button type="button" id="resendOtpBtn"
                    class="hidden text-nepal-blue hover:text-nepal-blue/80 dark:text-nepal-blue/90 dark:hover:text-nepal-blue">
                    Resend
                </button>
                </p>
            </div>

            <div class="mt-6">
                <button type="button" id="verifyOtpBtn"
                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-nepal-blue hover:bg-nepal-blue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900 transition-colors">
                    Verify & Login
                </button>
            </div>
        </div>
    </form>
</div>


@push('scripts')
    <script>
        localStorage.removeItem('timerStart');

        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const resendOtpBtn = document.getElementById('resendOtpBtn');
        const verifyOtpBtn = document.getElementById('verifyOtpBtn');
        const otpPhoneStep = document.getElementById('otpPhoneStep');
        const otpVerificationStep = document.getElementById('otpVerificationStep');
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpCodeHidden = document.getElementById('otp_code_hidden');
        const countdownEl = document.getElementById('resendCountdown');

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
                    countdownEl.textContent = `You can resend OTP in ${seconds}s`;
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
            const phone = document.getElementById('otp_phone').value;

            if (phone) {
                sendOtpBtn.disabled = true;

                $.ajax({
                    url: "{{ route('otp.login') }}",
                    method: 'POST',
                    data: {
                        phone: phone,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        var message = data.success ? data.message : (Array.isArray(data.message) ? data
                            .message.join('<br>') : data.message);
                        if (data.success) {
                            otpPhoneStep.classList.add('hidden');
                            otpVerificationStep.classList.remove('hidden');
                            nepalToast.success('Success', 'OTP sent successfully');
                            localStorage.setItem('timerStart', Date.now());
                            startOtpCountdown();

                            if (otpInputs.length > 0) otpInputs[0].focus();
                        } else {
                            sendOtpBtn.disabled = false;
                            nepalToast.error('Error', message);
                        }
                    }
                });
            } else {
                nepalToast.error('Error', 'Please enter your phone number');
            }
        });

        resendOtpBtn?.addEventListener('click', function(e) {
            e.preventDefault();

            const phone = document.getElementById('otp_phone').value;
            if (!phone) {
                nepalToast.error('Error', 'Phone number missing');
                return;
            }

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
                        nepalToast.error('Error', response.message);
                    }
                },
                error: function() {
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
                const digits = pastedData.replace(/\D/g, '').split('').slice(0, otpInputs.length);

                digits.forEach((digit, i) => {
                    if (i < otpInputs.length) otpInputs[i].value = digit;
                });

                const nextIndex = digits.length < otpInputs.length ? digits.length : otpInputs.length - 1;
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
                const phone = document.getElementById('otp_phone').value;
                $.ajax({
                    url: "{{ route('otp.verify') }}",
                    method: 'POST',
                    data: {
                        otp: otp,
                        phone: phone,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            verifyOtpBtn.disabled = true;
                            localStorage.removeItem('timerStart');
                            nepalToast.success('Success', 'OTP verified successfully');
                            window.location.href = response.redirect_url;
                        } else {
                            nepalToast.error('Error', response.message);
                        }
                    }
                });
            } else {
                alert('Please enter the complete OTP');
            }
        });
    </script>
@endpush
