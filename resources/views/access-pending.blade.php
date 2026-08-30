<x-app-layout>

    <div class="min-h-screen bg-zee-bg text-zee-text flex items-center justify-center px-6">

        <div class="w-full max-w-lg">

            <!-- Logo -->
            <div class="flex justify-center mb-8">

                <x-zee-core
                    :size="82"
                    :animated="false"
                />

            </div>

            <!-- Card -->
            <div class="zee-panel border border-zee-border rounded-3xl p-8 sm:p-10 text-center shadow-2xl">

                <!-- Status -->
                <div class="flex justify-center mb-6">

                    <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center">

                        <svg
                            width="30"
                            height="30"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            class="text-amber-400"
                        >
                            <circle cx="12" cy="12" r="9"></circle>
                            <polyline points="12 7 12 12 15 14"></polyline>
                        </svg>

                    </div>

                </div>

                <h1 class="font-display text-2xl sm:text-3xl font-semibold">
                    Access Pending
                </h1>

                <p class="text-zee-muted text-sm leading-relaxed mt-4 max-w-md mx-auto">
                    Your Zee.GPT account has been created successfully,
                    but your AI Workspace access is still waiting for approval.
                </p>

                <!-- User -->
                <div class="mt-7 rounded-2xl border border-zee-border bg-zee-panel-2 px-5 py-4 text-left">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-full bg-zee-red-dim border border-zee-red/30 flex items-center justify-center text-sm font-semibold">

                            {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}

                        </div>

                        <div class="min-w-0">

                            <p class="text-sm font-medium truncate">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-zee-muted truncate">
                                {{ auth()->user()->email }}
                            </p>

                        </div>

                    </div>

                </div>

                <!-- Info -->
                <div class="mt-5 rounded-2xl border border-zee-border bg-zee-bg/50 px-5 py-4 text-left">

                    <div class="flex gap-3">

                        <span class="text-zee-red-glow mt-0.5">
                            ✦
                        </span>

                        <div>

                            <p class="text-sm font-medium">
                                What happens next?
                            </p>

                            <p class="text-xs text-zee-muted leading-relaxed mt-1">
                                An administrator will review your account and enable
                                the appropriate Zee.GPT services. Once approved,
                                you'll be able to access your workspace.
                            </p>

                        </div>

                    </div>

                </div>

                <!-- Logout -->
                <form
                    method="POST"
                    action="{{ route('logout') }}"
                    class="mt-7"
                >

                    @csrf

                    <button
                        type="submit"
                        class="w-full rounded-xl border border-zee-border bg-zee-panel-2 hover:border-zee-red/40 hover:bg-zee-red-dim/30 py-3 text-sm font-medium transition"
                    >
                        Log Out
                    </button>

                </form>

            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-zee-muted/60 mt-6">
                Built by ZeeLinks · Powered by Ollama
            </p>

        </div>

    </div>

</x-app-layout>