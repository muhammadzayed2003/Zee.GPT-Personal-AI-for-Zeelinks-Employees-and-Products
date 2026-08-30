<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="dark"
>
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>{{ $title ?? 'Zee.GPT' }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-zee-bg text-zee-text font-sans antialiased min-h-screen">

    <div class="min-h-screen grid lg:grid-cols-2">

        {{-- LEFT BRAND / HERO --}}
        <div
            class="
                relative
                hidden
                lg:flex
                flex-col
                justify-between
                overflow-hidden
                border-r
                border-zee-border
                px-14
                py-12
            "
        >

            <div
                class="pointer-events-none absolute inset-0"
                style="
                    background:
                    radial-gradient(
                        circle at 50% 10%,
                        rgba(179,18,43,0.18),
                        transparent 60%
                    );
                "
            ></div>

            <div
                class="
                    pointer-events-none
                    absolute
                    -bottom-24
                    -left-24
                    w-72
                    h-72
                    rounded-full
                    blur-3xl
                    opacity-30
                "
                style="background:#B3122B;"
            ></div>

            {{-- LOGO --}}
            <div class="relative z-10 flex items-center gap-3">
                <x-zee-core
                    :size="36"
                    :animated="false"
                />

                <span
                    class="
                        font-display
                        font-semibold
                        text-lg
                        tracking-tight
                    "
                >
                    Zee.GPT
                </span>
            </div>

            {{-- HERO --}}
            <div
                class="
                    relative
                    z-10
                    flex
                    flex-col
                    items-start
                    gap-8
                "
            >

                <x-zee-core :size="190" />

                <div>
                    <h1
                        class="
                            font-display
                            text-4xl
                            leading-tight
                            font-semibold
                            mb-4
                        "
                    >
                        Your private AI,
                        <br>
                        running on your terms.
                    </h1>

                    <p
                        class="
                            text-zee-muted
                            max-w-md
                            leading-relaxed
                        "
                    >
                        Zee.GPT connects you directly to
                        your locally-hosted AI model —
                        private, focused and built for
                        conversations and files.
                    </p>
                </div>
            </div>

            {{-- FOOTER --}}
            <div
                class="
                    relative
                    z-10
                    flex
                    items-center
                    gap-2
                    text-xs
                    text-zee-muted
                "
            >
                <span>
                    Built by
                    <span class="text-zee-text font-medium">
                        ZeeLinks
                    </span>
                </span>

                <span class="w-1 h-1 rounded-full bg-zee-border"></span>

                <span>
                    Powered by
                    <span class="text-zee-text font-medium">
                        Ollama
                    </span>
                </span>
            </div>
        </div>

        {{-- RIGHT FORM --}}
        <div
            class="
                flex
                flex-col
                justify-center
                items-center
                px-6
                py-12
                relative
            "
        >

            {{-- MOBILE LOGO --}}
            <div
                class="
                    lg:hidden
                    flex
                    items-center
                    gap-3
                    mb-10
                "
            >
                <x-zee-core :size="38" />

                <span
                    class="
                        font-display
                        font-semibold
                        text-xl
                    "
                >
                    Zee.GPT
                </span>
            </div>

            <div class="w-full max-w-sm">
                {{ $slot }}
            </div>

            {{-- MOBILE FOOTER --}}
            <div
                class="
                    lg:hidden
                    mt-10
                    flex
                    items-center
                    gap-2
                    text-xs
                    text-zee-muted
                "
            >
                <span>
                    Built by
                    <span class="text-zee-text font-medium">
                        ZeeLinks
                    </span>
                </span>

                <span class="w-1 h-1 rounded-full bg-zee-border"></span>

                <span>
                    Powered by
                    <span class="text-zee-text font-medium">
                        Ollama
                    </span>
                </span>
            </div>

        </div>

    </div>

</body>
</html>