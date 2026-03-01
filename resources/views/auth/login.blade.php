@extends('layouts.guest')

@section('content')
    <div class="min-h-screen flex bg-gray-50">
        <!-- Left side: Branded section -->
        <div class="hidden md:flex md:w-1/2 bg-slate-900 text-white p-12 flex-col justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-lg bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                        RG
                    </div>
                    <span class="font-semibold text-xl">Ratoguras Management</span>
                </div>
            </div>

            <div>
                <h2 class="text-4xl font-bold leading-tight mb-6">Manage Your Operations with Confidence</h2>
                <p class="text-gray-300 text-lg leading-relaxed mb-8">Access your complete business management system.
                    Streamline workflows, track performance, and make data-driven decisions all in one place.</p>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">Real-time Analytics</h3>
                            <p class="text-gray-400 text-sm">Monitor key metrics and performance indicators instantly</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">Enterprise Security</h3>
                            <p class="text-gray-400 text-sm">Your data is protected with industry-leading security protocols
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">Lightning Fast</h3>
                            <p class="text-gray-400 text-sm">Optimized performance for seamless user experience</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center h-10 w-10 rounded-lg bg-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor"
                                        d="M20 17q.86 0 1.45.6t.58 1.4L14 22l-7-2v-9h1.95l7.27 2.69q.78.31.78 1.12q0 .47-.34.82t-.86.37H13l-1.75-.67l-.33.94L13 17zM16 3.23Q17.06 2 18.7 2q1.36 0 2.3 1t1 2.3q0 1.03-1 2.46t-1.97 2.39T16 13q-2.08-1.89-3.06-2.85t-1.97-2.39T10 5.3q0-1.36.97-2.3t2.34-1q1.6 0 2.69 1.23M.984 11H5v11H.984z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white mb-1">Friendly</h3>
                            <p class="text-gray-400 text-sm">Made with love by the Rato Guras Technology team</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-700">
                <p class="text-gray-400 text-sm">Trusted by 10+ businesses worldwide</p>
                <div class="mt-4 flex items-center gap-2">
                    <div class="flex -space-x-2">
                        <img src="https://i.pravatar.cc/32?img=1" alt="user"
                            class="h-8 w-8 rounded-full border-2 border-slate-900" />
                        <img src="https://i.pravatar.cc/32?img=2" alt="user"
                            class="h-8 w-8 rounded-full border-2 border-slate-900" />
                        <img src="https://i.pravatar.cc/32?img=3" alt="user"
                            class="h-8 w-8 rounded-full border-2 border-slate-900" />
                    </div>
                    <span class="text-gray-400 text-sm">And many more...</span>
                </div>
            </div>
        </div>

        <!-- Right side: Login form -->
        <div class="w-full md:w-1/2 flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-sm">
                <div class="md:hidden mb-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="h-10 w-10 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                            M
                        </div>
                        <span class="font-semibold text-xl text-gray-900">RatoGuras Management</span>
                    </div>
                </div>

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-3">Welcome Back</h1>
                    <p class="text-gray-600 text-sm">Sign in to your account to continue</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-2">Email or Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M3 7l9 6 9-6" />
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                </svg>
                            </div>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required
                                autocomplete="username"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors"
                                placeholder="you@example.com" />
                        </div>
                        @error('login')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="11" width="18" height="10" rx="2" />
                                    <path d="M7 11V7a5 5 0 0110 0v4" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password"
                                class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors"
                                placeholder="••••••••" />
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600 cursor-pointer" />
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer">Remember
                            me</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                            <polyline points="10 17 15 12 10 7" />
                            <line x1="15" y1="12" x2="3" y2="12" />
                        </svg>
                        Sign In
                    </button>
                </form>

                <!-- Footer Links -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
                    <a href="#" class="hover:text-gray-700">Privacy Policy</a>
                    <a href="#" class="hover:text-gray-700">Terms of Service</a>
                    <a href="#" class="hover:text-gray-700">Help Center</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggle = this.querySelector('svg');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggle.innerHTML =
                    '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                passwordInput.type = 'password';
                toggle.innerHTML =
                    '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        });
    </script>
@endpush
