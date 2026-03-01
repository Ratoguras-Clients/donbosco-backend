@if (session('success'))
    <div id="success-message" data-message="{{ session('success') }}" class="hidden"></div>
@endif

<div id="passwordResetForm" class="login-form hidden">
    <form method="POST" action="#" class="space-y-6" id="otpForm_reset">
        @csrf
        <!-- Phone Number -->
        <div id="otpPhoneStep_Password">
            <div>
                <label for="otp_phone_reset" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Phone Number
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                        <span class="iconify" data-icon="line-md:phone-filled" data-width="18"></span>
                    </span>
                    <input id="otp_phone_reset" type="number" name="phone_number" required
                        class="pl-10 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30"
                        placeholder="Enter Your Phone Number">
                </div>
            </div>

            <div class="mt-6">
                <button type="button" id="sendOtpBtn_reset"
                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-nepal-blue hover:bg-nepal-blue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900 transition-colors">
                    Send OTP
                </button>
            </div>
        </div>

        <!-- OTP Verification -->
        <div id="otpVerificationStep_reset" class="hidden">
            <div>
                <label for="otp_code_reset" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Enter OTP Code
                </label>
                <div class="flex justify-between gap-2">
                    <input type="text" maxlength="1"
                        class="otp-input_reset w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                    <input type="text" maxlength="1"
                        class="otp-input_reset w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                    <input type="text" maxlength="1"
                        class="otp-input_reset w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                    <input type="text" maxlength="1"
                        class="otp-input_reset w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                    <input type="text" maxlength="1"
                        class="otp-input_reset w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                    <input type="text" maxlength="1"
                        class="otp-input_reset w-12 h-12 text-center text-xl font-bold rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30">
                </div>
                <input type="hidden" name="otp_code_reset" id="otp_code_hidden_reset">
                <span class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                    Didn't receive the code?</span>
                <span id="resendCountdown_reset" class="text-sm text-gray-500 dark:text-gray-400 mt-1"></span>
                <button type="button" id="resendOtpBtn_reset"
                    class="hidden text-nepal-blue hover:text-nepal-blue/80 dark:text-nepal-blue/90 dark:hover:text-nepal-blue">
                    Resend
                </button>
                </p>
            </div>

            <div class="mt-6">
                <button type="button" id="verifyOtpBtn_reset_reset"
                    class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-nepal-blue hover:bg-nepal-blue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900 transition-colors">
                    Verify & Login
                </button>
            </div>
        </div>

        {{-- Reset Password --}}
        <div id="resetPassword" class="hidden">
            <form id="resetPasswordForm" enctype="multipart/form-data">
                <div class="mt-8 space-y-6">
                    <div>
                        <label for="reset_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Password') }}
                        </label>
                        <input id="reset_password" type="password" name="reset_password" required autofocus
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30"
                            placeholder="{{ __('Password') }}">
                    </div>
                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Confirm Password') }}
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            autofocus
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:border-nepal-blue focus:ring focus:ring-nepal-blue/20 dark:focus:ring-nepal-blue/30"
                            placeholder="{{ __('Confirm Password') }}">
                    </div>
                </div>

                <div class="mt-6">
                    <button type="button" id="resetPasswordBtn"
                        class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-nepal-blue hover:bg-nepal-blue/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-nepal-blue dark:focus:ring-offset-gray-900 transition-colors">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </form>
</div>


@push('scripts')
    <script>
        localStorage.removeItem('timerStart');

        const sendOtpBtn_reset = document.getElementById('sendOtpBtn_reset');
        const resendOtpBtn_reset = document.getElementById('resendOtpBtn_reset');
        const verifyOtpBtn_reset = document.getElementById('verifyOtpBtn_reset_reset');
        const otpPhoneStep_Password = document.getElementById('otpPhoneStep_Password');
        const otpVerificationStep_reset = document.getElementById('otpVerificationStep_reset');
        const otpInputs_reset = document.querySelectorAll('.otp-input_reset');
        const otpCodeHidden_reset = document.getElementById('otp_code_hidden_reset');
        const countdownEl_reset = document.getElementById('resendCountdown_reset');

        function startOtpCountdown_reset() {
            const start = parseInt(localStorage.getItem('timerStart'));
            if (!start || isNaN(start)) return;

            resendOtpBtn_reset.classList.add('hidden'); // Hide resend button

            const interval = setInterval(() => {
                const now = Date.now();
                const elapsed = now - start;
                const remaining = 2 * 60 * 1000 - elapsed; // 2 minutes

                if (remaining > 0) {
                    const seconds = Math.ceil(remaining / 1000);
                    countdownEl_reset.textContent = `You can resend OTP in ${seconds}s`;
                } else {
                    countdownEl_reset.textContent = '';
                    resendOtpBtn_reset.classList.remove('hidden'); // Show resend button
                    clearInterval(interval);
                }
            }, 1000);
        }

        if (localStorage.getItem('timerStart')) {
            startOtpCountdown_reset();
        }

        sendOtpBtn_reset?.addEventListener('click', function(e) {
            e.preventDefault();
            const reset_phone = document.getElementById('otp_phone_reset').value;

            if (reset_phone) {
                sendOtpBtn_reset.disabled = true;

                $.ajax({
                    url: "{{ route('forgot.password.otp') }}",
                    method: 'POST',
                    data: {
                        phone: reset_phone,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        var message = data.success ? data.message : (Array.isArray(data.message) ? data
                            .message.join('<br>') : data.message);
                        if (data.success) {
                            otpPhoneStep_Password.classList.add('hidden');
                            otpVerificationStep_reset.classList.remove('hidden');
                            nepalToast.success('Success', 'OTP sent successfully');
                            localStorage.setItem('timerStart', Date.now());
                            startOtpCountdown_reset();

                            if (otpInputs_reset.length > 0) otpInputs_reset[0].focus();
                        } else {
                            sendOtpBtn_reset.disabled = false;
                            nepalToast.error('Error', message);
                        }
                    }
                });
            } else {
                nepalToast.error('Error', 'Please enter your phone number');
            }
        });

        resendOtpBtn_reset?.addEventListener('click', function(e) {
            e.preventDefault();
            const reset_phone = document.getElementById('otp_phone_reset').value;
            if (!reset_phone) {
                nepalToast.error('Error', 'Phone number missing');
                return;
            }
            $.ajax({
                url: "{{ route('forgot.password.otp') }}",
                method: 'POST',
                data: {
                    phone: reset_phone,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        localStorage.setItem('timerStart', Date.now());
                        startOtpCountdown_reset();
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
        otpInputs_reset.forEach((input, index) => {
            input.addEventListener('keyup', function(e) {
                if (/^[0-9]$/.test(e.key)) {
                    if (index < otpInputs_reset.length - 1) otpInputs_reset[index + 1].focus();
                    updateOtpHiddenField_reset();
                }

                if (e.key === 'Backspace') {
                    if (index > 0) otpInputs_reset[index - 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text');
                const digits = pastedData.replace(/\D/g, '').split('').slice(0, otpInputs_reset.length);

                digits.forEach((digit, i) => {
                    if (i < otpInputs_reset.length) otpInputs_reset[i].value = digit;
                });

                const nextIndex = digits.length < otpInputs_reset.length ? digits.length : otpInputs_reset
                    .length - 1;
                otpInputs_reset[nextIndex].focus();
                updateOtpHiddenField_reset();
            });
        });

        function updateOtpHiddenField_reset() {
            let otp = '';
            otpInputs_reset.forEach(input => {
                otp += input.value;
            });
            otpCodeHidden_reset.value = otp;
            verifyOtpBtn_reset.disabled = otp.length !== otpInputs_reset.length;
        }

        verifyOtpBtn_reset.disabled = true;

        verifyOtpBtn_reset?.addEventListener('click', function(e) {
            e.preventDefault();
            const otp = otpCodeHidden_reset.value;

            if (otp.length === otpInputs_reset.length) {
                const otp_phone_reset = document.getElementById('otp_phone_reset').value;
                $.ajax({
                    url: "{{ route('reset.password.otp.verify') }}",
                    method: 'POST',
                    data: {
                        otp: otp,
                        phone: otp_phone_reset,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            verifyOtpBtn_reset.disabled = true;
                            localStorage.removeItem('timerStart');
                            nepalToast.success('Success', 'OTP verified successfully');
                            $('#otpVerificationStep_reset').addClass('hidden');
                            $('#resetPassword').removeClass('hidden');
                            sendOtpBtn_reset.disabled = false;
                        } else {
                            nepalToast.error('Error', response.message);
                        }
                    }
                });
            } else {
                alert('Please enter the complete OTP');
            }
        });

        $(document).ready(function() {
            const successMessage = $('#success-message').data('message');
            if (successMessage) {
                nepalToast.success('Success', successMessage);
            }
        });
    </script>
@endpush
