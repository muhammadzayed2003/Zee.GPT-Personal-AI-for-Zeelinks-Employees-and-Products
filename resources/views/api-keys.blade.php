<x-app-layout>

    <div class="min-h-screen bg-zee-bg text-zee-text">

        <!-- Header -->
        <div class="border-b border-zee-border">

            <div class="max-w-5xl mx-auto px-6 py-6">

                <div class="flex items-center justify-between">

                    <div>

                        <h1 class="font-display text-2xl font-semibold">
                            API Keys
                        </h1>

                        <p class="text-sm text-zee-muted mt-1">
                            Create and manage keys for connecting your applications to Zee.GPT.
                        </p>

                    </div>

                    <div class="flex items-center gap-2 text-xs text-zee-muted">

                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>

                        API Access

                    </div>

                </div>

            </div>

        </div>

        <div class="max-w-5xl mx-auto px-6 py-8">

            <!-- New API Key -->
            @if(session('new_api_key'))

                <div class="mb-6 rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-6">

                    <div class="flex items-start gap-4">

                        <div class="w-10 h-10 rounded-xl bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                            ✓
                        </div>

                        <div class="flex-1 min-w-0">

                            <h2 class="font-semibold text-emerald-300">
                                API Key Generated
                            </h2>

                            <p class="text-sm text-emerald-300/70 mt-1">
                                Copy this key now. For security, the full key will not be shown again.
                            </p>

                            <div class="mt-4 flex flex-col sm:flex-row gap-2">

                                <input
                                    id="newApiKey"
                                    type="text"
                                    readonly
                                    value="{{ session('new_api_key') }}"
                                    class="flex-1 rounded-xl border border-emerald-500/20 bg-zee-bg px-4 py-3 text-sm text-zee-text font-mono outline-none"
                                >

                                <button
                                    onclick="copyApiKey()"
                                    type="button"
                                    class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium transition"
                                >
                                    Copy Key
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            @endif

            <!-- Success -->
            @if(session('success'))

                <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4">

                    <p class="text-sm text-emerald-300">
                        {{ session('success') }}
                    </p>

                </div>

            @endif

            <!-- Generate -->
            <div class="zee-panel rounded-2xl border border-zee-border p-6 mb-6">

                <div class="flex items-center gap-3 mb-5">

                    <div class="w-10 h-10 rounded-xl bg-zee-red-dim border border-zee-red/30 flex items-center justify-center text-zee-red-glow">
                        +
                    </div>

                    <div>

                        <h2 class="font-display font-semibold">
                            Generate New API Key
                        </h2>

                        <p class="text-xs text-zee-muted mt-1">
                            Use a separate key for each application.
                        </p>

                    </div>

                </div>

                <form
                    method="POST"
                    action="{{ route('api-keys.store') }}"
                    class="flex flex-col sm:flex-row gap-3"
                >

                    @csrf

                    <input
                        type="text"
                        name="name"
                        placeholder="e.g. My Website"
                        required
                        maxlength="100"
                        class="flex-1 rounded-xl border border-zee-border bg-zee-panel-2 px-4 py-3 text-sm text-zee-text placeholder:text-zee-muted/50 outline-none focus:border-zee-red/50 transition"
                    >

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-zee-red hover:bg-zee-red-glow text-white text-sm font-medium transition shadow-glow-sm"
                    >
                        Generate Key
                    </button>

                </form>

                @error('name')

                    <p class="mt-2 text-sm text-red-400">
                        {{ $message }}
                    </p>

                @enderror

            </div>

            <!-- Existing Keys -->
            <div class="zee-panel rounded-2xl border border-zee-border overflow-hidden">

                <div class="px-6 py-5 border-b border-zee-border">

                    <h2 class="font-display font-semibold">
                        Your API Keys
                    </h2>

                    <p class="text-xs text-zee-muted mt-1">
                        Keys are stored securely and cannot be viewed again after creation.
                    </p>

                </div>

                @forelse($apiKeys as $apiKey)

                    <div class="px-6 py-5 border-b border-zee-border last:border-b-0">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                            <div class="flex items-center gap-4 min-w-0">

                                <div class="w-10 h-10 rounded-xl bg-zee-panel-2 border border-zee-border flex items-center justify-center text-zee-muted shrink-0">

                                    <svg
                                        width="18"
                                        height="18"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <circle cx="7.5" cy="15.5" r="5.5"></circle>
                                        <path d="m21 2-9.6 9.6"></path>
                                        <path d="m15.5 6.5 2 2"></path>
                                        <path d="m18 4 2 2"></path>
                                    </svg>

                                </div>

                                <div class="min-w-0">

                                    <p class="font-medium truncate">
                                        {{ $apiKey->name }}
                                    </p>

                                    <p class="text-sm text-zee-muted font-mono mt-1">
                                        {{ $apiKey->key_prefix }}••••••••
                                    </p>

                                    <p class="text-xs text-zee-muted/70 mt-1">
                                        Created {{ $apiKey->created_at->diffForHumans() }}
                                    </p>

                                </div>

                            </div>

                            <form
                                method="POST"
                                action="{{ route('api-keys.destroy', $apiKey) }}"
                                onsubmit="return confirm('Are you sure you want to revoke this API key?');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="px-4 py-2.5 rounded-xl border border-red-500/30 bg-red-500/10 text-red-400 hover:bg-red-500/20 text-sm transition"
                                >
                                    Revoke
                                </button>

                            </form>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <div class="w-12 h-12 mx-auto rounded-2xl bg-zee-panel-2 border border-zee-border flex items-center justify-center text-zee-muted mb-4">

                            <svg
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <circle cx="7.5" cy="15.5" r="5.5"></circle>
                                <path d="m21 2-9.6 9.6"></path>
                            </svg>

                        </div>

                        <p class="text-sm text-zee-muted">
                            You don't have any API keys yet.
                        </p>

                        <p class="text-xs text-zee-muted/60 mt-1">
                            Generate your first key above.
                        </p>

                    </div>

                @endforelse

            </div>

            <!-- Security Note -->
            <div class="mt-6 flex items-start gap-3 px-1">

                <span class="text-zee-muted mt-0.5">
                    🔒
                </span>

                <p class="text-xs text-zee-muted leading-relaxed">
                    Keep your API keys private. Never expose them in frontend JavaScript,
                    public repositories, or client-side applications.
                </p>

            </div>

        </div>

    </div>

    <script>
        function copyApiKey() {

            const input = document.getElementById('newApiKey');

            if (!input) {
                return;
            }

            navigator.clipboard.writeText(input.value)
                .then(() => {
                    alert('API key copied to clipboard.');
                })
                .catch(() => {
                    input.select();
                    document.execCommand('copy');
                    alert('API key copied to clipboard.');
                });
        }
    </script>

</x-app-layout>