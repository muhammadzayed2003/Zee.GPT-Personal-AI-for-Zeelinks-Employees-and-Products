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

    <title>Zee.GPT</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        /* Markdown tables */
        .zee-prose .zee-markdown-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 0.75rem;
            font-size: 0.875rem;
        }

        .zee-prose .zee-markdown-table th,
        .zee-prose .zee-markdown-table td {
            border: 1px solid rgba(255, 255, 255, 0.10);
            padding: 0.65rem 0.8rem;
            text-align: left;
        }

        .zee-prose .zee-markdown-table th {
            background: rgba(255, 255, 255, 0.06);
            font-weight: 600;
        }

        .zee-prose .zee-markdown-table tr:nth-child(even) td {
            background: rgba(255, 255, 255, 0.025);
        }

        .zee-prose .zee-markdown-table-wrap {
            width: 100%;
            overflow-x: auto;
            margin: 1rem 0;
        }

        /* Markdown code */
        .zee-prose pre {
            overflow-x: auto;
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 0.75rem;
            background: rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .zee-prose pre code {
            background: transparent;
            padding: 0;
            border: 0;
        }

        .zee-prose code {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 0.35rem;
            padding: 0.12rem 0.35rem;
            font-size: 0.85em;
        }

        /* Charts */
        .zee-chart-container {
            width: 100%;
            margin: 1.25rem 0;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.025);
        }

        .zee-chart-wrapper {
            position: relative;
            width: 100%;
            min-height: 280px;
        }

        .zee-chart-wrapper canvas {
            width: 100% !important;
            max-height: 420px;
        }

        .zee-chart-error {
            padding: 0.75rem;
            border-radius: 0.75rem;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.20);
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.8rem;
        }

        /* Mermaid flowcharts */
        .zee-mermaid-container {
            width: 100%;
            overflow-x: auto;
            margin: 1.25rem 0;
            padding: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.10);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.025);
        }

        .zee-mermaid {
            display: flex;
            justify-content: center;
            min-width: max-content;
        }

        .zee-mermaid svg {
            max-width: 100%;
            height: auto;
        }

        .zee-mermaid-error {
            padding: 0.75rem;
            border-radius: 0.75rem;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.20);
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.8rem;
        }
    </style>
</head>

<body
    class="
        bg-zee-bg
        text-zee-text
        font-sans
        antialiased
        h-screen
        overflow-hidden
    "
>
<div
    x-data="chatFrontend()"
    x-init="init()"
    class="flex h-screen"
>
    {{-- SIDEBAR --}}
    <aside
        class="
            w-72
            shrink-0
            zee-panel
            border-r
            border-zee-border
            flex
            flex-col
        "
    >
        {{-- LOGO --}}
        <div
            class="
                px-5
                py-5
                flex
                items-center
                gap-2.5
                border-b
                border-zee-border
            "
        >
            <x-zee-core
                :size="31"
                :animated="false"
            />

            <span
                class="
                    font-display
                    font-semibold
                    tracking-tight
                "
            >
                Zee.GPT
            </span>
        </div>

        {{-- NEW CHAT --}}
        <div class="px-4 pt-4">
            <button
                @click="newChat()"
                type="button"
                class="
                    w-full
                    flex
                    items-center
                    justify-center
                    gap-2
                    rounded-xl
                    border
                    border-zee-border
                    bg-zee-panel-2
                    hover:border-zee-red/60
                    hover:bg-zee-red-dim/40
                    text-sm
                    font-medium
                    py-2.5
                    transition
                "
            >
                <span class="text-lg leading-none">+</span>
                New chat
            </button>
        </div>

        {{-- APP NAVIGATION --}}
        <div class="px-4 py-4 border-b border-zee-border space-y-2">
            {{-- AI WORKSPACE --}}
            @if(auth()->user()->ai_workspace_access)
                <a
                    href="{{ route('dashboard') }}"
                    class="
                        w-full
                        flex
                        items-center
                        gap-3
                        rounded-xl
                        px-3
                        py-2.5
                        text-sm
                        transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-zee-red-dim/50 text-zee-text border border-zee-red/30'
                            : 'text-zee-muted hover:bg-zee-panel-2 hover:text-zee-text'
                        }}
                    "
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M12 3v18"></path>
                        <path d="M3 12h18"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>

                    <span>AI Workspace</span>
                </a>
            @endif

            {{-- API KEYS --}}
            @if(auth()->user()->api_access)
                <a
                    href="{{ route('api-keys') }}"
                    class="
                        w-full
                        flex
                        items-center
                        gap-3
                        rounded-xl
                        px-3
                        py-2.5
                        text-sm
                        transition
                        {{ request()->routeIs('api-keys')
                            ? 'bg-zee-red-dim/50 text-zee-text border border-zee-red/30'
                            : 'text-zee-muted hover:bg-zee-panel-2 hover:text-zee-text'
                        }}
                    "
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle
                            cx="7.5"
                            cy="15.5"
                            r="5.5"
                        ></circle>

                        <path d="m21 2-9.6 9.6"></path>
                        <path d="m15.5 6.5 2 2"></path>
                        <path d="m18.5 3.5 2 2"></path>
                    </svg>

                    <span>API Keys</span>
                </a>
            @endif

            {{-- PERMISSIONS --}}
            @if(auth()->user()->email === 'zeelinks1slamabad@gmail.com')
                <a
                    href="{{ route('permissions') }}"
                    class="
                        w-full
                        flex
                        items-center
                        gap-3
                        rounded-xl
                        px-3
                        py-2.5
                        text-sm
                        transition
                        {{ request()->routeIs('permissions')
                            ? 'bg-zee-red-dim/50 text-zee-text border border-zee-red/30'
                            : 'text-zee-muted hover:bg-zee-panel-2 hover:text-zee-text'
                        }}
                    "
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>

                    <span>Permissions</span>
                </a>
            @endif
        </div>

        {{-- CHAT HISTORY --}}
        <nav
            class="
                flex-1
                overflow-y-auto
                zee-scroll
                px-3
                py-4
                space-y-1
            "
        >
            <template
                x-for="chat in chats"
                :key="chat.id"
            >
                <button
                    type="button"
                    @click="selectChat(chat.id)"
                    class="
                        w-full
                        text-left
                        truncate
                        rounded-lg
                        px-3
                        py-2
                        text-sm
                        transition
                        text-zee-muted
                        hover:bg-zee-panel-2
                        hover:text-zee-text
                    "
                    :class="
                        activeChat === chat.id
                            ? 'bg-zee-red-dim/50 text-zee-text border border-zee-red/30'
                            : ''
                    "
                    x-text="chat.title"
                ></button>
            </template>

            <template x-if="chats.length === 0">
                <p
                    class="
                        text-xs
                        text-zee-muted
                        px-3
                        py-2
                    "
                >
                    No conversations yet.
                </p>
            </template>
        </nav>

        {{-- USER --}}
        <div
            class="
                border-t
                border-zee-border
                px-4
                py-4
                flex
                items-center
                justify-between
            "
        >
            <div
                class="
                    flex
                    items-center
                    gap-2.5
                    min-w-0
                "
            >
                <div
                    class="
                        w-8
                        h-8
                        rounded-full
                        bg-zee-red-dim
                        border
                        border-zee-red/30
                        flex
                        items-center
                        justify-center
                        text-xs
                        font-semibold
                        shrink-0
                    "
                >
                    {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="text-sm truncate">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-xs text-zee-muted truncate">
                        {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            {{-- LOGOUT --}}
            <form
                method="POST"
                action="{{ route('logout') }}"
            >
                @csrf

                <button
                    type="submit"
                    class="
                        text-zee-muted
                        hover:text-zee-red-glow
                        transition
                    "
                    title="Log out"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line
                            x1="21"
                            y1="12"
                            x2="9"
                            y2="12"
                        ></line>
                    </svg>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CHAT --}}
    <main class="flex-1 flex flex-col relative">

        {{-- HEADER --}}
        <header
            class="
                h-16
                shrink-0
                border-b
                border-zee-border
                flex
                items-center
                justify-between
                px-6
            "
        >
            <div>
                <h1
                    class="
                        font-display
                        font-semibold
                        text-sm
                    "
                    x-text="currentTitle"
                >
                    New chat
                </h1>

                <p
                    class="
                        text-xs
                        text-zee-muted
                    "
                >
                    Private AI workspace
                </p>
            </div>

            <div
                class="
                    flex
                    items-center
                    gap-2
                    text-xs
                    text-zee-muted
                "
            >
                <span
                    class="
                        w-2
                        h-2
                        rounded-full
                        bg-emerald-400
                    "
                ></span>

                Local
            </div>
        </header>

        {{-- MESSAGES --}}
        <div
            x-ref="messagesContainer"
            class="
                flex-1
                overflow-y-auto
                zee-scroll
                px-6
                py-8
            "
        >
            <div
                class="
                    max-w-3xl
                    mx-auto
                "
            >
                {{-- EMPTY STATE --}}
                <template x-if="messages.length === 0 && !loadingConversation">
                    <div
                        class="
                            flex
                            flex-col
                            items-center
                            justify-center
                            text-center
                            pt-24
                            gap-6
                        "
                    >
                        <x-zee-core :size="82" />

                        <div>
                            <h2
                                class="
                                    font-display
                                    text-2xl
                                    font-semibold
                                    mb-2
                                "
                            >
                                How can I help you?
                            </h2>

                            <p
                                class="
                                    text-zee-muted
                                    text-sm
                                    max-w-md
                                "
                            >
                                Start a conversation with Zee.GPT.
                                Your private AI workspace is ready.
                            </p>
                        </div>

                        <div
                            class="
                                grid
                                grid-cols-2
                                gap-3
                                w-full
                                max-w-lg
                                mt-3
                            "
                        >
                            <button
                                @click="usePrompt('Assist me in development')"
                                class="
                                    zee-panel
                                    rounded-xl
                                    p-4
                                    text-left
                                    text-sm
                                    hover:border-zee-red/40
                                    transition
                                "
                            >
                                Development assistance
                            </button>

                            <button
                                @click="usePrompt('Help me write a professional email')"
                                class="
                                    zee-panel
                                    rounded-xl
                                    p-4
                                    text-left
                                    text-sm
                                    hover:border-zee-red/40
                                    transition
                                "
                            >
                                Write an email
                            </button>

                            <button
                                @click="usePrompt('Create a project plan for me')"
                                class="
                                    zee-panel
                                    rounded-xl
                                    p-4
                                    text-left
                                    text-sm
                                    hover:border-zee-red/40
                                    transition
                                "
                            >
                                Project planning
                            </button>

                            <button
                                @click="usePrompt('I need some info about Zeelinks')"
                                class="
                                    zee-panel
                                    rounded-xl
                                    p-4
                                    text-left
                                    text-sm
                                    hover:border-zee-red/40
                                    transition
                                "
                            >
                                Get company's info
                            </button>
                        </div>
                    </div>
                </template>

                {{-- LOADING OLD CHAT --}}
                <template x-if="loadingConversation">
                    <div class="flex justify-center py-10">
                        <span
                            class="
                                text-sm
                                text-zee-muted
                            "
                        >
                            Loading conversation…
                        </span>
                    </div>
                </template>

                {{-- MESSAGES --}}
                <div
                    class="space-y-6"
                    x-show="messages.length > 0"
                >
                    <template
                        x-for="message in messages"
                        :key="message.id"
                    >
                        <div
                            class="flex"
                            :class="
                                message.role === 'user'
                                    ? 'justify-end'
                                    : 'justify-start'
                            "
                        >
                            <div
                                class="
                                    max-w-[85%]
                                    rounded-2xl
                                    px-4
                                    py-3
                                    text-sm
                                    leading-relaxed
                                    zee-prose
                                "
                                :class="
                                    message.role === 'user'
                                        ? 'bg-zee-red-dim/60 border border-zee-red/25'
                                        : 'zee-panel'
                                "
                            >
                                <div
                                    x-html="formatMessage(message.content)"
                                ></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- THINKING --}}
                <template x-if="thinking">
                    <div class="flex justify-start mt-6">
                        <div
                            class="
                                zee-panel
                                rounded-2xl
                                px-4
                                py-3
                                flex
                                items-center
                                gap-3
                            "
                        >
                            <span
                                class="
                                    text-sm
                                    text-zee-muted
                                "
                            >
                                Zee.GPT is thinking…
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- INPUT --}}
        <div
            class="
                border-t
                border-zee-border
                px-6
                py-4
            "
        >
            <div class="max-w-3xl mx-auto">
                <div
                    class="
                        zee-panel
                        rounded-2xl
                        px-3
                        py-2.5
                        flex
                        items-end
                        gap-2
                        focus-within:border-zee-red/50
                        transition
                    "
                >
                    {{-- ATTACH --}}
                    <button
                        type="button"
                        class="
                            text-zee-muted
                            hover:text-zee-red-glow
                            transition
                            p-2
                        "
                        title="Attach file"
                        @click="attachmentMessage()"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"
                            ></path>
                        </svg>
                    </button>

                    {{-- TEXTAREA --}}
                    <textarea
                        x-ref="textarea"
                        x-model="draft"
                        @input="autoResize()"
                        @keydown.enter.prevent="
                            if (!$event.shiftKey) sendMessage()
                        "
                        rows="1"
                        placeholder="Message Zee.GPT…"
                        class="
                            flex-1
                            bg-transparent
                            resize-none
                            outline-none
                            text-sm
                            py-2
                            max-h-40
                            placeholder:text-zee-muted/60
                        "
                    ></textarea>

                    {{-- SEND --}}
                    <button
                        type="button"
                        @click="sendMessage()"
                        :disabled="!draft.trim() || thinking"
                        class="
                            shrink-0
                            rounded-xl
                            bg-zee-red
                            hover:bg-zee-red-glow
                            disabled:opacity-40
                            text-white
                            p-2.5
                            transition
                            shadow-glow-sm
                        "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <line
                                x1="22"
                                y1="2"
                                x2="11"
                                y2="13"
                            ></line>

                            <polygon
                                points="22 2 15 22 11 13 2 9 22 2"
                            ></polygon>
                        </svg>
                    </button>
                </div>

                <p
                    class="
                        text-center
                        text-xs
                        text-zee-muted/70
                        mt-2.5
                    "
                >
                    Built by ZeeLinks ·
                </p>
            </div>
        </div>
    </main>
</div>

<script>
function chatFrontend() {
    return {
        draft: '',
        thinking: false,
        loadingConversation: false,
        activeChat: null,
        currentTitle: 'New chat',
        chats: [],
        messages: [],
        visualCounter: 0,
        chartInstances: new Map(),

        init() {
            this.loadConversations();
        },

        async loadConversations() {
            try {
                const response = await fetch('/conversations', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    return;
                }

                this.chats = await response.json();
            } catch (error) {
                console.error(
                    'Could not load conversations:',
                    error
                );
            }
        },

        async selectChat(id) {
            if (this.thinking) {
                return;
            }

            this.activeChat = id;

            const chat = this.chats.find(
                item => String(item.id) === String(id)
            );

            if (chat) {
                this.currentTitle = chat.title;
            }

            try {
                this.loadingConversation = true;

                const response = await fetch(
                    `/conversations/${id}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        'Could not load conversation'
                    );
                }

                const data = await response.json();

                this.messages = data.messages || [];

                this.$nextTick(() => {
                    this.scrollToBottom();

                    setTimeout(() => {
                        this.renderVisuals();
                    }, 50);
                });
            } catch (error) {
                console.error(
                    'Conversation loading error:',
                    error
                );
            } finally {
                this.loadingConversation = false;
            }
        },

        newChat() {
            this.destroyCharts();

            this.activeChat = null;
            this.currentTitle = 'New chat';
            this.messages = [];
            this.draft = '';

            this.$nextTick(() => {
                if (this.$refs.textarea) {
                    this.$refs.textarea.focus();
                }
            });
        },

        usePrompt(prompt) {
            this.draft = prompt;

            this.$nextTick(() => {
                if (this.$refs.textarea) {
                    this.$refs.textarea.focus();
                }
            });
        },

        attachmentMessage() {
            alert(
                'File upload will be connected in the backend phase.'
            );
        },

        autoResize() {
            const textarea = this.$refs.textarea;

            if (!textarea) {
                return;
            }

            textarea.style.height = 'auto';

            textarea.style.height =
                Math.min(
                    textarea.scrollHeight,
                    160
                ) + 'px';
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container =
                    this.$refs.messagesContainer;

                if (!container) {
                    return;
                }

                container.scrollTo({
                    top: container.scrollHeight,
                    behavior: 'smooth'
                });
            });
        },

        async sendMessage() {
            const text = this.draft.trim();

            if (!text || this.thinking) {
                return;
            }

            this.messages.push({
                id: Date.now(),
                role: 'user',
                content: text
            });

            this.draft = '';
            this.thinking = true;

            this.$nextTick(() => {
                this.autoResize();
                this.scrollToBottom();
            });

            try {
                const response = await fetch('/chat', {
                    method: 'POST',

                    headers: {
                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/x-ndjson',

                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute('content')
                    },

                    body: JSON.stringify({
                        message: text,
                        conversation_id:
                            this.activeChat
                    })
                });

                if (!response.ok) {
                    const data =
                        await response
                            .json()
                            .catch(() => ({}));

                    if (data.new_chat_required) {
                        alert(
                            data.message ||
                            'This chat has reached its limit. Please start a new chat.'
                        );

                        this.newChat();

                        await this.loadConversations();

                        return;
                    }

                    throw new Error(
                        'AI request failed'
                    );
                }

                if (!response.body) {
                    throw new Error(
                        'AI response stream is unavailable'
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Get conversation ID from backend
                |--------------------------------------------------------------------------
                */

                const conversationId =
                    response.headers.get(
                        'X-Conversation-ID'
                    );

                const conversationTitle =
                    response.headers.get(
                        'X-Conversation-Title'
                    );

                if (conversationId) {
                    this.activeChat =
                        conversationId;

                    const existingChat =
                        this.chats.find(
                            chat =>
                                String(chat.id) ===
                                String(conversationId)
                        );

                    if (!existingChat) {
                        this.chats.unshift({
                            id: conversationId,

                            title:
                                conversationTitle ||
                                text.substring(
                                    0,
                                    60
                                )
                        });

                        this.currentTitle =
                            conversationTitle ||
                            text.substring(
                                0,
                                60
                            );
                    } else {
                        this.currentTitle =
                            existingChat.title;
                    }
                }

                const assistantId =
                    Date.now() + 1;

                this.messages.push({
                    id: assistantId,
                    role: 'assistant',
                    content: ''
                });

                this.thinking = false;

                const reader =
                    response.body.getReader();

                const decoder =
                    new TextDecoder();

                let buffer = '';
                let pendingText = '';
                let rendering = false;

                const renderSmoothly =
                    async () => {
                        if (rendering) {
                            return;
                        }

                        rendering = true;

                        while (
                            pendingText.length > 0
                        ) {
                            const message =
                                this.messages.find(
                                    item =>
                                        item.id ===
                                        assistantId
                                );

                            if (!message) {
                                break;
                            }

                            const chunk =
                                pendingText.slice(
                                    0,
                                    2
                                );

                            pendingText =
                                pendingText.slice(
                                    2
                                );

                            message.content +=
                                chunk;

                            this.scrollToBottom();

                            await new Promise(
                                resolve =>
                                    setTimeout(
                                        resolve,
                                        12
                                    )
                            );
                        }

                        rendering = false;
                    };

                while (true) {
                    const {
                        value,
                        done
                    } = await reader.read();

                    if (done) {
                        break;
                    }

                    buffer +=
                        decoder.decode(
                            value,
                            {
                                stream: true
                            }
                        );

                    const lines =
                        buffer.split('\n');

                    buffer =
                        lines.pop();

                    for (const line of lines) {
                        if (!line.trim()) {
                            continue;
                        }

                        try {
                            const data =
                                JSON.parse(line);

                            if (
                                data.message &&
                                data.message.content
                            ) {
                                pendingText +=
                                    data.message.content;

                                renderSmoothly();
                            }
                        } catch (error) {
                            console.warn(
                                'Stream parsing error:',
                                error
                            );
                        }
                    }
                }

                if (buffer.trim()) {
                    try {
                        const data =
                            JSON.parse(buffer);

                        if (
                            data.message &&
                            data.message.content
                        ) {
                            pendingText +=
                                data.message.content;

                            renderSmoothly();
                        }
                    } catch (error) {
                        console.warn(
                            'Final stream parsing error:',
                            error
                        );
                    }
                }

                while (
                    pendingText.length > 0 ||
                    rendering
                ) {
                    await new Promise(
                        resolve =>
                            setTimeout(
                                resolve,
                                20
                            )
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Render tables, charts and flowcharts
                |--------------------------------------------------------------------------
                */

                await this.loadConversations();

                this.$nextTick(() => {
                    this.renderVisuals();
                    this.scrollToBottom();
                });

            } catch (error) {
                console.error(
                    'Zee.GPT error:',
                    error
                );

                this.messages.push({
                    id: Date.now() + 1,

                    role: 'assistant',

                    content:
                        'Sorry, I could not connect to the AI backend. Please make sure Ollama is running.'
                });

                this.scrollToBottom();

            } finally {
                this.thinking = false;
            }
        },

        /*
        |--------------------------------------------------------------------------
        | Markdown + Visual Blocks
        |--------------------------------------------------------------------------
        */

        formatMessage(text) {
            if (!text) {
                return '';
            }

            let workingText = String(text);

            const visualBlocks = [];

            /*
            |--------------------------------------------------------------------------
            | Extract chart blocks
            |
            | Expected AI format:
            |
            | ```chart
            | {
            |   "chartType": "bar",
            |   ...
            | }
            | ```
            |--------------------------------------------------------------------------
            */

            workingText =
                workingText.replace(
                    /```chart\s*([\s\S]*?)```/gi,
                    (match, code) => {
                        const id =
                            this.createVisualId(
                                'chart'
                            );

                        visualBlocks.push({
                            id,
                            type: 'chart',
                            code: code.trim()
                        });

                        return `\n\n<div class="zee-chart-placeholder" data-visual-id="${id}"></div>\n\n`;
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | Extract Mermaid blocks
            |
            | Expected AI format:
            |
            | ```mermaid
            | flowchart TD
            | A --> B
            | ```
            |--------------------------------------------------------------------------
            */

            workingText =
                workingText.replace(
                    /```mermaid\s*([\s\S]*?)```/gi,
                    (match, code) => {
                        const id =
                            this.createVisualId(
                                'mermaid'
                            );

                        visualBlocks.push({
                            id,
                            type: 'mermaid',
                            code: code.trim()
                        });

                        return `\n\n<div class="zee-mermaid-placeholder" data-visual-id="${id}"></div>\n\n`;
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | Render normal Markdown
            |--------------------------------------------------------------------------
            */

            let html = '';

            try {
                html = window.marked.parse(
                    workingText,
                    {
                        gfm: true,
                        breaks: true
                    }
                );
            } catch (error) {
                console.error(
                    'Markdown rendering error:',
                    error
                );

                html =
                    this.escapeHtml(
                        workingText
                    ).replace(
                        /\n/g,
                        '<br>'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Add custom class to tables
            |--------------------------------------------------------------------------
            */

            html =
                html.replace(
                    /<table>/gi,
                    '<div class="zee-markdown-table-wrap"><table class="zee-markdown-table">'
                );

            html =
                html.replace(
                    /<\/table>/gi,
                    '</table></div>'
                );

            /*
            |--------------------------------------------------------------------------
            | Remove dangerous HTML that AI output could contain
            |--------------------------------------------------------------------------
            */

            html =
                this.sanitizeHtml(
                    html
                );

            /*
            |--------------------------------------------------------------------------
            | Replace visual placeholders
            |--------------------------------------------------------------------------
            */

            for (const block of visualBlocks) {
                const safeId =
                    this.escapeAttribute(
                        block.id
                    );

                let replacement = '';

                if (block.type === 'chart') {
                    replacement = `
                        <div
                            class="zee-chart-container zee-chart-placeholder"
                            data-visual-id="${safeId}"
                        >
                            <div class="zee-chart-wrapper">
                                <canvas
                                    data-chart-id="${safeId}"
                                ></canvas>
                            </div>
                        </div>
                    `;
                }

                if (block.type === 'mermaid') {
                    replacement = `
                        <div
                            class="zee-mermaid-container zee-mermaid-placeholder"
                            data-visual-id="${safeId}"
                        >
                            <div
                                class="zee-mermaid"
                                data-mermaid-id="${safeId}"
                            ></div>
                        </div>
                    `;
                }

                html =
                    html.replace(
                        new RegExp(
                            `<div class="zee-${block.type}-placeholder" data-visual-id="${safeId}"></div>`,
                            'g'
                        ),
                        replacement
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Store visual data in a temporary JS registry
            |--------------------------------------------------------------------------
            */

            for (const block of visualBlocks) {
                this.storeVisualBlock(
                    block
                );
            }

            return html;
        },

        createVisualId(type) {
            this.visualCounter += 1;

            return `zee-${type}-${Date.now()}-${this.visualCounter}`;
        },

        visualBlocks: {},

        storeVisualBlock(block) {
            this.visualBlocks[block.id] = {
                type: block.type,
                code: block.code
            };
        },

        escapeHtml(value) {
            return String(value)
                .replace(
                    /&/g,
                    '&amp;'
                )
                .replace(
                    /</g,
                    '&lt;'
                )
                .replace(
                    />/g,
                    '&gt;'
                )
                .replace(
                    /"/g,
                    '&quot;'
                )
                .replace(
                    /'/g,
                    '&#039;'
                );
        },

        escapeAttribute(value) {
            return this.escapeHtml(
                value
            );
        },

        sanitizeHtml(html) {
            const template =
                document.createElement(
                    'template'
                );

            template.innerHTML = html;

            const dangerousElements =
                template.content.querySelectorAll(
                    'script, iframe, object, embed, form, style, link, meta'
                );

            dangerousElements.forEach(
                element => element.remove()
            );

            const allElements =
                template.content.querySelectorAll(
                    '*'
                );

            allElements.forEach(
                element => {
                    [...element.attributes]
                        .forEach(attribute => {
                            const name =
                                attribute.name.toLowerCase();

                            const value =
                                attribute.value;

                            if (
                                name.startsWith(
                                    'on'
                                )
                            ) {
                                element.removeAttribute(
                                    attribute.name
                                );
                            }

                            if (
                                name === 'href' ||
                                name === 'src' ||
                                name === 'xlink:href'
                            ) {
                                const normalized =
                                    value
                                        .trim()
                                        .toLowerCase();

                                if (
                                    normalized.startsWith(
                                        'javascript:'
                                    ) ||
                                    normalized.startsWith(
                                        'data:'
                                    ) ||
                                    normalized.startsWith(
                                        'vbscript:'
                                    )
                                ) {
                                    element.removeAttribute(
                                        attribute.name
                                    );
                                }
                            }
                        });
                });

            return template.innerHTML;
        },

        /*
        |--------------------------------------------------------------------------
        | Render charts and Mermaid diagrams after Alpine updates the DOM
        |--------------------------------------------------------------------------
        */

        renderVisuals() {
            this.$nextTick(() => {
                this.renderCharts();
                this.renderMermaid();
            });
        },

        renderCharts() {
            const canvases =
                this.$refs.messagesContainer
                    ?.querySelectorAll(
                        'canvas[data-chart-id]'
                    );

            if (!canvases) {
                return;
            }

            canvases.forEach(canvas => {
                const id =
                    canvas.dataset.chartId;

                const block =
                    this.visualBlocks[id];

                if (!block || block.type !== 'chart') {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Don't recreate an already-rendered chart
                |--------------------------------------------------------------------------
                */

                if (
                    this.chartInstances.has(
                        id
                    )
                ) {
                    return;
                }

                let spec;

                try {
                    spec =
                        JSON.parse(
                            block.code
                        );
                } catch (error) {
                    console.error(
                        'Invalid chart JSON:',
                        error
                    );

                    this.showChartError(
                        canvas,
                        'The chart data could not be read.'
                    );

                    return;
                }

                if (
                    !spec ||
                    !spec.chartType ||
                    !Array.isArray(
                        spec.data
                    )
                ) {
                    this.showChartError(
                        canvas,
                        'The chart specification is incomplete.'
                    );

                    return;
                }

                try {
                    const context =
                        canvas.getContext(
                            '2d'
                        );

                    if (!context) {
                        return;
                    }

                    const chart =
                        new window.Chart(
                            context,
                            this.buildChartConfig(
                                spec
                            )
                        );

                    this.chartInstances.set(
                        id,
                        chart
                    );

                } catch (error) {
                    console.error(
                        'Chart rendering error:',
                        error
                    );

                    this.showChartError(
                        canvas,
                        'The chart could not be rendered.'
                    );
                }
            });
        },

        buildChartConfig(spec) {
            const chartType =
                spec.chartType;

            const data =
                Array.isArray(spec.data)
                    ? spec.data
                    : [];

            const series =
                Array.isArray(spec.series)
                    ? spec.series
                    : [];

            const xKey =
                spec.xKey || 'category';

            const labels =
                data.map(
                    row => row[xKey]
                );

            const datasets =
                series.map(
                    currentSeries => ({
                        label:
                            currentSeries.label ||
                            currentSeries.dataKey,

                        data:
                            data.map(
                                row =>
                                    row[
                                        currentSeries.dataKey
                                    ]
                            ),

                        borderWidth: 2,

                        tension:
                            chartType === 'line'
                                ? 0.3
                                : 0,

                        fill:
                            chartType === 'line'
                                ? false
                                : true
                    })
                );

            /*
            |--------------------------------------------------------------------------
            | Pie charts use nameKey/valueKey
            |--------------------------------------------------------------------------
            */

            if (chartType === 'pie') {
                const nameKey =
                    spec.nameKey ||
                    xKey;

                const valueKey =
                    spec.valueKey ||
                    (
                        series[0]
                            ? series[0].dataKey
                            : 'value'
                    );

                return {
                    type: 'pie',

                    data: {
                        labels:
                            data.map(
                                row =>
                                    row[nameKey]
                            ),

                        datasets: [{
                            label:
                                series[0]?.label ||
                                valueKey,

                            data:
                                data.map(
                                    row =>
                                        row[valueKey]
                                ),

                            borderWidth: 1
                        }]
                    },

                    options:
                        this.chartOptions(
                            spec
                        )
                };
            }

            /*
            |--------------------------------------------------------------------------
            | Scatter charts
            |--------------------------------------------------------------------------
            */

            if (chartType === 'scatter') {
                const scatterDatasets =
                    series.map(
                        currentSeries => ({
                            label:
                                currentSeries.label ||
                                currentSeries.dataKey,

                            data:
                                data.map(
                                    row => ({
                                        x:
                                            row[
                                                xKey
                                            ],

                                        y:
                                            row[
                                                currentSeries.dataKey
                                            ]
                                    })
                                ),

                            borderWidth: 2
                        })
                    );

                return {
                    type: 'scatter',

                    data: {
                        datasets:
                            scatterDatasets
                    },

                    options:
                        this.chartOptions(
                            spec
                        )
                };
            }

            /*
            |--------------------------------------------------------------------------
            | Bar and line charts
            |--------------------------------------------------------------------------
            */

            return {
                type: chartType,

                data: {
                    labels,

                    datasets
                },

                options:
                    this.chartOptions(
                        spec
                    )
            };
        },

        chartOptions(spec) {
            const options = {
                responsive: true,

                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display:
                            Array.isArray(
                                spec.series
                            ) &&
                            spec.series.length > 1
                    },

                    title: {
                        display:
                            Boolean(
                                spec.meta?.title
                            ),

                        text:
                            spec.meta?.title ||
                            ''
                    }
                }
            };

            if (
                spec.yAxisMin !== undefined ||
                spec.yAxisMax !== undefined
            ) {
                options.scales = {
                    y: {
                        min:
                            spec.yAxisMin,

                        max:
                            spec.yAxisMax
                    }
                };
            }

            return options;
        },

        showChartError(canvas, message) {
            const container =
                canvas.closest(
                    '.zee-chart-container'
                );

            if (!container) {
                return;
            }

            container.innerHTML = `
                <div class="zee-chart-error">
                    ${this.escapeHtml(message)}
                </div>
            `;
        },

        async renderMermaid() {
            const containers =
                this.$refs.messagesContainer
                    ?.querySelectorAll(
                        '[data-mermaid-id]'
                    );

            if (!containers) {
                return;
            }

            for (
                const container of containers
            ) {
                const id =
                    container.dataset.mermaidId;

                const block =
                    this.visualBlocks[id];

                if (
                    !block ||
                    block.type !== 'mermaid'
                ) {
                    continue;
                }

                if (
                    container.dataset.rendered ===
                    'true'
                ) {
                    continue;
                }

                try {
                    const renderId =
                        `mermaid-${id}`;

                    const result =
                        await window.mermaid.render(
                            renderId,
                            block.code
                        );

                    container.innerHTML =
                        result.svg;

                    container.dataset.rendered =
                        'true';

                } catch (error) {
                    console.error(
                        'Mermaid rendering error:',
                        error
                    );

                    container.innerHTML = `
                        <div class="zee-mermaid-error">
                            Flowchart could not be rendered.
                        </div>
                    `;

                    container.dataset.rendered =
                        'error';
                }
            }
        },

        destroyCharts() {
            this.chartInstances.forEach(
                chart => {
                    try {
                        chart.destroy();
                    } catch (error) {
                        console.warn(
                            'Could not destroy chart:',
                            error
                        );
                    }
                }
            );

            this.chartInstances.clear();

            this.visualBlocks = {};
        }
    };
}
</script>

</body>
</html>