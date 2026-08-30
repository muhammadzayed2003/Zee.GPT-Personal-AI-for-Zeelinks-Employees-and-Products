<x-app-layout>

    <div class="min-h-screen bg-zee-bg text-zee-text">

        <!-- Header -->
        <div class="border-b border-zee-border">
            <div class="max-w-6xl mx-auto px-6 py-6">

                <div class="flex items-center justify-between">

                    <div>
                        <h1 class="font-display text-2xl font-semibold">
                            Permissions
                        </h1>

                        <p class="text-sm text-zee-muted mt-1">
                            Manage user access to Zee.GPT services.
                        </p>
                    </div>

                    <div class="flex items-center gap-2 text-xs text-zee-muted">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Admin Panel
                    </div>

                </div>

            </div>
        </div>

        <!-- Content -->
        <div class="max-w-6xl mx-auto px-6 py-8">

            @if(session('success'))

                <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-5 py-4">

                    <div class="flex items-center gap-3">

                        <span class="text-emerald-400 text-lg">
                            ✓
                        </span>

                        <p class="text-sm text-emerald-300">
                            {{ session('success') }}
                        </p>

                    </div>

                </div>

            @endif

            @if(session('error'))

                <div class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-5 py-4">

                    <p class="text-sm text-red-300">
                        {{ session('error') }}
                    </p>

                </div>

            @endif

            <!-- Users -->
            <div class="zee-panel rounded-2xl border border-zee-border overflow-hidden">

                <div class="px-6 py-5 border-b border-zee-border">

                    <h2 class="font-display font-semibold">
                        Users
                    </h2>

                    <p class="text-xs text-zee-muted mt-1">
                        Control AI workspace and API access.
                    </p>

                </div>

                <div class="divide-y divide-zee-border">

                    @forelse($users as $user)

                        <div class="px-6 py-5">

                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                                <!-- User -->
                                <div class="flex items-center gap-4 min-w-0">

                                    <div class="w-11 h-11 rounded-full bg-zee-red-dim border border-zee-red/30 flex items-center justify-center text-sm font-semibold shrink-0">

                                        {{ Str::upper(Str::substr($user->name, 0, 1)) }}

                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-medium truncate">
                                            {{ $user->name }}
                                        </p>

                                        <p class="text-sm text-zee-muted truncate">
                                            {{ $user->email }}
                                        </p>

                                    </div>

                                </div>

                                <!-- Permissions -->
                                <form
                                    method="POST"
                                    action="{{ route('permissions.update', $user) }}"
                                    class="flex flex-wrap items-center gap-3"
                                >

                                    @csrf
                                    @method('PUT')

                                    <!-- AI Workspace -->
                                    <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-zee-border bg-zee-panel-2 cursor-pointer hover:border-zee-red/40 transition">

                                        <input
                                            type="checkbox"
                                            name="ai_workspace_access"
                                            value="1"
                                            {{ $user->ai_workspace_access ? 'checked' : '' }}
                                            class="rounded border-zee-border bg-zee-bg text-zee-red focus:ring-zee-red"
                                        >

                                        <span class="text-sm">
                                            AI Workspace
                                        </span>

                                    </label>

                                    <!-- API Access -->
                                    <label class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-zee-border bg-zee-panel-2 cursor-pointer hover:border-zee-red/40 transition">

                                        <input
                                            type="checkbox"
                                            name="api_access"
                                            value="1"
                                            {{ $user->api_access ? 'checked' : '' }}
                                            class="rounded border-zee-border bg-zee-bg text-zee-red focus:ring-zee-red"
                                        >

                                        <span class="text-sm">
                                            API Access
                                        </span>

                                    </label>

                                    <button
                                        type="submit"
                                        class="px-5 py-2.5 rounded-xl bg-zee-red hover:bg-zee-red-glow text-white text-sm font-medium transition shadow-glow-sm"
                                    >
                                        Save
                                    </button>

                                </form>

                            </div>

                        </div>

                    @empty

                        <div class="px-6 py-12 text-center">

                            <p class="text-zee-muted text-sm">
                                No users found.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
