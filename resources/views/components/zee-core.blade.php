@props([
    'size' => 40,
    'animated' => true,
])

<span
    class="zee-core"
    style="width: {{ $size }}px; height: {{ $size }}px;"
>
    <svg
        viewBox="0 0 100 100"
        width="{{ $size }}"
        height="{{ $size }}"
        class="relative z-10 {{ $animated ? 'animate-pulse-glow' : '' }}"
        xmlns="http://www.w3.org/2000/svg"
    >
        <defs>
            <linearGradient
                id="zeeCoreEdge"
                x1="0"
                y1="0"
                x2="1"
                y2="1"
            >
                <stop offset="0%" stop-color="#3A3340" />
                <stop offset="100%" stop-color="#141218" />
            </linearGradient>

            <radialGradient
                id="zeeCoreVisor"
                cx="50%"
                cy="50%"
                r="60%"
            >
                <stop offset="0%" stop-color="#FF3B57" />
                <stop offset="100%" stop-color="#7A1622" />
            </radialGradient>
        </defs>

        <polygon
            points="50,4 91,27 91,73 50,96 9,73 9,27"
            fill="url(#zeeCoreEdge)"
            stroke="#3A3340"
            stroke-width="1.5"
        />

        <polygon
            points="50,16 80,32.5 80,67.5 50,84 20,67.5 20,32.5"
            fill="#0E0D11"
            stroke="#7A1622"
            stroke-opacity="0.35"
            stroke-width="1"
        />

        <rect
            x="28"
            y="46"
            width="44"
            height="8"
            rx="4"
            fill="url(#zeeCoreVisor)"
        />

        <circle
            cx="50"
            cy="50"
            r="2.2"
            fill="#FF8FA0"
        />

        <line
            x1="50"
            y1="16"
            x2="50"
            y2="4"
            stroke="#7A1622"
            stroke-width="1.5"
        />

        <line
            x1="20"
            y1="32.5"
            x2="9"
            y2="27"
            stroke="#7A1622"
            stroke-width="1.5"
        />

        <line
            x1="80"
            y1="32.5"
            x2="91"
            y2="27"
            stroke="#7A1622"
            stroke-width="1.5"
        />
    </svg>
</span>