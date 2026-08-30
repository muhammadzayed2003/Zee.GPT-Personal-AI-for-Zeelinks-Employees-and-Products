<nav x-data="{ open: false }" class="border-b border-zee-border bg-zee-bg/95 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">


        {{-- LEFT --}}
        <div class="flex items-center gap-8">

            {{-- LOGO --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <x-zee-core :size="30" :animated="false" />

                <span class="font-display font-semibold tracking-tight text-white">
                    Zee.GPT
                </span>
            </a>

            {{-- NAV LINKS --}}
            <div class="hidden sm:flex items-center gap-2">

                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 rounded-lg text-sm transition
                   {{ request()->routeIs('dashboard')
                        ? 'bg-zee-red-dim/50 text-white border border-zee-red/30'
                        : 'text-zee-muted hover:text-white hover:bg-zee-panel-2' }}">
                    Workspace
                </a>

                @if(auth()->user()->ai_workspace_access)
                    <a href="{{ route('api-keys') }}"
                       class="px-4 py-2 rounded-lg text-sm transition
                       {{ request()->routeIs('api-keys*')
                            ? 'bg-zee-red-dim/50 text-white border border-zee-red/30'
                            : 'text-zee-muted hover:text-white hover:bg-zee-panel-2' }}">
                        API Keys
                    </a>
                @endif

                @if(auth()->user()->email === 'zeelinks1slamabad@gmail.com')
                    <a href="{{ route('permissions') }}"
                       class="px-4 py-2 rounded-lg text-sm transition
                       {{ request()->routeIs('permissions*')
                            ? 'bg-zee-red-dim/50 text-white border border-zee-red/30'
                            : 'text-zee-muted hover:text-white hover:bg-zee-panel-2' }}">
                        Permissions
                    </a>
                @endif

            </div>
        </div>

        {{-- RIGHT --}}
        <div class="hidden sm:flex items-center gap-4">

            {{-- STATUS --}}
            <div class="flex items-center gap-2 text-xs text-zee-muted">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Local
            </div>

            {{-- USER --}}
            <div class="relative" x-data="{ dropdown: false }">

                <button
                    @click="dropdown = !dropdown"
                    type="button"
                    class="flex items-center gap-2.5 rounded-xl px-3 py-2
                           hover:bg-zee-panel-2 transition"
                >

                    <div class="w-8 h-8 rounded-full
                                bg-zee-red-dim
                                border border-zee-red/30
                                flex items-center justify-center
                                text-xs font-semibold text-white">

                        {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}

                    </div>

                    <div class="text-left hidden md:block">
                        <p class="text-sm text-white leading-tight">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-zee-muted">
                            Account
                        </p>
                    </div>

                    <svg
                        class="w-4 h-4 text-zee-muted"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />
                    </svg>

                </button>

                {{-- DROPDOWN --}}
                <div
                    x-show="dropdown"
                    @click.outside="dropdown = false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 rounded-xl
                           border border-zee-border
                           bg-zee-panel shadow-xl z-50 overflow-hidden"
                >

                    <div class="px-4 py-3 border-b border-zee-border">
                        <p class="text-sm text-white">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-zee-muted truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <div class="p-2">

                        <a href="{{ route('dashboard') }}"
                           class="block px-3 py-2 rounded-lg text-sm
                                  text-zee-muted hover:text-white
                                  hover:bg-zee-panel-2 transition">
                            Workspace
                        </a>

                        @if(auth()->user()->ai_workspace_access)
                            <a href="{{ route('api-keys') }}"
                               class="block px-3 py-2 rounded-lg text-sm
                                      text-zee-muted hover:text-white
                                      hover:bg-zee-panel-2 transition">
                                API Keys
                            </a>
                        @endif

                        @if(auth()->user()->email === 'zeelinks1slamabad@gmail.com')
                            <a href="{{ route('permissions') }}"
                               class="block px-3 py-2 rounded-lg text-sm
                                      text-zee-muted hover:text-white
                                      hover:bg-zee-panel-2 transition">
                                Permissions
                            </a>
                        @endif

                        <div class="my-2 border-t border-zee-border"></div>

                        {{-- LOGOUT --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="w-full text-left px-3 py-2 rounded-lg
                                       text-sm text-zee-muted
                                       hover:text-red-400
                                       hover:bg-zee-panel-2 transition"
                            >
                                Log out
                            </button>
                        </form>

                    </div>

                </div>

            </div>
        </div>

        {{-- MOBILE --}}
        <div class="flex items-center sm:hidden">

            <button
                @click="open = !open"
                class="p-2 rounded-lg text-zee-muted hover:bg-zee-panel-2"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path
                        x-show="!open"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />

                    <path
                        x-show="open"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>

        </div>

    </div>
</div>

{{-- MOBILE MENU --}}
<div
    x-show="open"
    x-transition
    class="sm:hidden border-t border-zee-border"
>

    <div class="px-4 py-3 space-y-1">

        <a href="{{ route('dashboard') }}"
           class="block px-3 py-2 rounded-lg text-sm
                  text-zee-muted hover:text-white hover:bg-zee-panel-2">
            Workspace
        </a>

        @if(auth()->user()->ai_workspace_access)
            <a href="{{ route('api-keys') }}"
               class="block px-3 py-2 rounded-lg text-sm
                      text-zee-muted hover:text-white hover:bg-zee-panel-2">
                API Keys
            </a>
        @endif

        @if(auth()->user()->email === 'zeelinks1slamabad@gmail.com')
            <a href="{{ route('permissions') }}"
               class="block px-3 py-2 rounded-lg text-sm
                      text-zee-muted hover:text-white hover:bg-zee-panel-2">
                Permissions
            </a>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="w-full text-left px-3 py-2 rounded-lg text-sm
                       text-zee-muted hover:text-red-400
                       hover:bg-zee-panel-2"
            >
                Log out
            </button>
        </form>

    </div>
</div>
