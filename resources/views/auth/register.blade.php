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
                Create your account
            </h2>

            <p class="text-zee-muted text-sm">
                Start chatting with your private AI in seconds.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('register') }}"
            class="space-y-5"
        >
            @csrf

            {{-- NAME --}}
            <div>
                <label
                    for="name"
                    class="
                        block
                        text-sm
                        font-medium
                        text-zee-muted
                        mb-1.5
                    "
                >
                    Full name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Your name"
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

                @error('name')
                    <p class="mt-1.5 text-xs text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

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
                <label
                    for="password"
                    class="
                        block
                        text-sm
                        font-medium
                        text-zee-muted
                        mb-1.5
                    "
                >
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
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

            {{-- CONFIRM PASSWORD --}}
            <div>
                <label
                    for="password_confirmation"
                    class="
                        block
                        text-sm
                        font-medium
                        text-zee-muted
                        mb-1.5
                    "
                >
                    Confirm password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
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

                @error('password_confirmation')
                    <p class="mt-1.5 text-xs text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

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
                Create account
            </button>

            {{-- LOGIN --}}
            <p
                class="
                    text-center
                    text-sm
                    text-zee-muted
                    pt-2
                "
            >
                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="
                        text-zee-text
                        hover:text-zee-red-glow
                        font-medium
                        transition
                    "
                >
                    Sign in
                </a>
            </p>

        </form>

    </div>

</x-guest-layout>