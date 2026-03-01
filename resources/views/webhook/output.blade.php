<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Git Pull Result</title>

    <!-- Correct Tailwind CSS (browser build) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Font Awesome 7 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
          integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-x-hidden p-6">

<!-- Animated blurred circles -->
<div class="absolute inset-0 overflow-hidden pointer-events-none">
    <div
        class="absolute -top-40 -right-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse">
    </div>
    <div
        class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse delay-1000">
    </div>
</div>

<div class="relative z-10 max-w-5xl mx-auto text-center">

    <!-- Header -->
    <div class="mb-12 inline-flex items-center gap-3 justify-center">
        <div class="relative">
            <i class="fa-solid fa-circle-check text-green-400 text-4xl"></i>
            <div class="absolute inset-0 w-12 h-12 bg-green-400 rounded-full animate-ping opacity-20 -z-10"
                 style="top: 0; left: 0;"></div>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-white">Pull Successful</h1>
    </div>

    <!-- Branch & Time Badges -->
    <div class="flex items-center justify-center gap-4 mb-12">
            <span
                class="inline-flex items-center gap-2 border border-white/20 bg-white/10 px-4 py-2 text-white rounded-md text-sm font-semibold">
                <i class="fa-solid fa-code-branch"></i>
                {{ $branch }}
            </span>
        <span
            class="inline-flex items-center gap-2 border border-white/20 bg-white/10 px-4 py-2 text-white rounded-md text-sm font-semibold">
                <i class="fa-regular fa-clock"></i>
                {{ \Carbon\Carbon::now()->format('h:i:s A') }}
            </span>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto mb-16">
        <div
            class="bg-white/10 border border-white/20 backdrop-blur-sm rounded-lg py-6 px-4 text-center text-white">
            <div class="text-2xl font-bold text-green-400">{{ $stats['commits'] ?? 1 }}</div>
            <div class="text-sm text-white/70">Commits</div>
        </div>
        <div
            class="bg-white/10 border border-white/20 backdrop-blur-sm rounded-lg py-6 px-4 text-center text-white">
            <div class="text-2xl font-bold text-blue-400">{{ $stats['filesChanged'] ?? 3 }}</div>
            <div class="text-sm text-white/70">Files</div>
        </div>
        <div
            class="bg-white/10 border border-white/20 backdrop-blur-sm rounded-lg py-6 px-4 text-center text-white">
            <div class="text-2xl font-bold text-green-400">+{{ $stats['insertions'] ?? 39 }}</div>
            <div class="text-sm text-white/70">Additions</div>
        </div>
        <div
            class="bg-white/10 border border-white/20 backdrop-blur-sm rounded-lg py-6 px-4 text-center text-white">
            <div class="text-2xl font-bold text-red-400">-{{ $stats['deletions'] ?? 1 }}</div>
            <div class="text-sm text-white/70">Deletions</div>
        </div>
    </div>

    <!-- Terminal output -->
    <div
        class="bg-gray-900/90 border border-gray-700 backdrop-blur-sm rounded-lg shadow-2xl max-w-5xl mx-auto overflow-x-auto font-mono text-sm p-6 text-gray-300">

        <div class="flex items-center justify-between border-b border-gray-700 mb-4 pb-2 text-white font-semibold">
            <div class="flex items-center gap-3">
                <div class="flex gap-2">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                </div>
            </div>
            <div class="">
                <i class="fa-solid fa-terminal text-green-400"></i>
                Git Pull Output
            </div>
            <button id="copyBtn"
                    class="flex items-center gap-1 text-white/70 hover:text-white hover:bg-white/10 px-3 py-1 rounded text-sm select-none">
                <i id="copyIcon" class="fa-regular fa-copy"></i>
                <span id="copyText">Copy</span>
            </button>
        </div>

        @foreach ($output as $index => $line)
            <span class="flex gap-4">
                    <span
                        class="inline-block w-8 text-gray-500 select-none">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <span
                        class="{{ stripos($line, 'error') !== false
                            ? 'text-red-400'
                            : (stripos($line, 'warning') !== false
                                ? 'text-yellow-400'
                                : (stripos($line, 'success') !== false ||
                                stripos($line, 'done') !== false ||
                                stripos($line, 'completed') !== false
                                    ? 'text-green-400'
                                    : (stripos($line, 'from https://') !== false
                                        ? 'text-blue-400'
                                        : (strpos($line, '->') !== false
                                            ? 'text-purple-400'
                                            : (strpos($line, '+') !== false && stripos($line, 'insertion') !== false
                                                ? 'text-green-400'
                                                : (strpos($line, '-') !== false && stripos($line, 'deletion') !== false
                                                    ? 'text-red-400'
                                                    : 'text-gray-300')))))) }}">
                        {{ $line }}
                    </span>
                </span>
        @endforeach
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row justify-center gap-6 mt-10">
        <a href="{{ route('git.pull.download', ['branch' => $branch]) }}"
           class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white rounded px-6 py-3 text-lg font-semibold select-none">
            <i class="fa-solid fa-download"></i> Download Changes
        </a>
        <button id="pullAgainBtn"
                class="flex items-center gap-2 border border-white/20 text-white hover:bg-white/10 rounded px-6 py-3 text-lg font-semibold select-none">
            <i class="fa-solid fa-arrows-rotate"></i> Pull Again
        </button>
    </div>

    <!-- Footer -->
    <div class="mt-16 text-center text-white/50 text-sm select-none">
        Repository synchronized successfully at {{ \Carbon\Carbon::now()->toDayDateTimeString() }}
    </div>
</div>

<script>
    // Copy to clipboard logic
    const copyBtn = document.getElementById("copyBtn");
    const copyText = document.getElementById("copyText");
    const copyIcon = document.getElementById("copyIcon");

    copyBtn.addEventListener("click", () => {
        const textToCopy = `{{ implode("\n", $output) }}`;
        navigator.clipboard.writeText(textToCopy).then(() => {
            copyText.textContent = "Copied!";
            copyIcon.className = "fa-solid fa-check";

            setTimeout(() => {
                copyText.textContent = "Copy";
                copyIcon.className = "fa-regular fa-copy";
            }, 2000);
        });
    });

    // Pull Again button AJAX
    document.getElementById('pullAgainBtn').addEventListener('click', function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Pulling...';

        fetch("{{ route('git.pull.again', ['branch' => $branch]) }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message || 'Pull completed!');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i> Pull Again';
                location.reload();
            })
            .catch(() => {
                alert('Failed to execute pull.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-arrows-rotate"></i> Pull Again';
            });
    });
</script>

</body>

</html>
