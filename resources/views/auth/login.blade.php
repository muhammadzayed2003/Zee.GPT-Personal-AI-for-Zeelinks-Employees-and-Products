<x-guest-layout>

    <div class="animate-fade-up">

        <div class="mb-8">
            <h2
                class="
                    font-display
                    text-2xl
                    font-semibold
                    mb-1
                "
            >
                Welcome back
            </h2>

            <p class="text-zee-muted text-sm">
                Sign in to continue to Zee.GPT.
            </p>
        </div>

        @if (session('status'))
            <div
                class="
                    mb-5
                    text-sm
                    text-emerald-400
                    bg-emerald-500/10
                    border
                    border-emerald-500/20
                    rounded-lg
                    px-4
                    py-2.5
                "
            >
                {{ session('status') }}
            </div>
        @endif

        <form
            method="POST"
            action="{{ route('login') }}"
            class="space-y-5"
        >
            @csrf

            {{-- EMAIL --}}
            <div>
                <label
                    for="email"
                    class="
                        block
                        text-sm
                        font-medium
                        text-zee-muted
                        mb-1.5
                    "
                >
                    Email address
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="
                        w-full
                        rounded-xl
                        bg-zee-panel-2
                        border
                        border-zee-border
                        px-4
                        py-3
                        text-sm
                        text-zee-text
                        placeholder:text-zee-muted/60
                        outline-none
                        transition
                        focus:border-zee-red
                        focus:shadow-glow-sm
                    "
                >

                @error('email')
                    <p class="mt-1.5 text-xs text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>

                <div
                    class="
                        flex
                        items-center
                        justify-between
                        mb-1.5
                    "
                >
                    <label
                        for="password"
                        class="
                            block
                            text-sm
                            font-medium
                            text-zee-muted
                        "
                    >
                        Password
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="
                                text-xs
                                text-zee-muted
                                hover:text-zee-red-glow
                                transition
                            "
                        >
                            Forgot password?
                        </a>
                    @endif
                </div>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="
                        w-full
                        rounded-xl
                        bg-zee-panel-2
                        border
                        border-zee-border
                        px-4
                        py-3
                        text-sm
                        text-zee-text
                        placeholder:text-zee-muted/60
                        outline-none
                        transition
                        focus:border-zee-red
                        focus:shadow-glow-sm
                    "
                >

                @error('password')
                    <p class="mt-1.5 text-xs text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- REMEMBER --}}
            <label
                class="
                    flex
                    items-center
                    gap-2
                    text-sm
                    text-zee-muted
                    cursor-pointer
                    select-none
                "
            >
                <input
                    type="checkbox"
                    name="remember"
                    class="
                        rounded
                        border-zee-border
                        bg-zee-panel-2
                        text-zee-red
                        focus:ring-zee-red
                        focus:ring-offset-0
                    "
                >

                Remember me
            </label>

            {{-- BUTTON --}}
            <button
                type="submit"
                class="
                    w-full
                    rounded-xl
                    bg-zee-red
                    hover:bg-zee-red-glow
                    text-white
                    text-sm
                    font-medium
                    py-3
                    transition
                    shadow-glow-sm
                    hover:shadow-glow
                "
            >
                Sign in
            </button>

            {{-- REGISTER --}}
            @if (Route::has('register'))
                <p
                    class="
                        text-center
                        text-sm
                        text-zee-muted
                        pt-2
                    "
                >
                    Don't have an account?

                    <a
                        href="{{ route('register') }}"
                        class="
                            text-zee-text
                            hover:text-zee-red-glow
                            font-medium
                            transition
                        "
                    >
                        Create one
                    </a>
                </p>
            @endif

        </form>

    </div>

</x-guest-layout>