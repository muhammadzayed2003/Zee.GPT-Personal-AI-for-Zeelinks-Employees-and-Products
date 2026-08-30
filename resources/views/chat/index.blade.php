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

        activeChat: null,
        currentTitle: 'New chat',

        chats: [],
        messages: [],

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
                console.error('Could not load conversations:', error);
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
                const response = await fetch(
                    `/conversations/${id}`,
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                if (!response.ok) {
                    throw new Error('Could not load conversation');
                }

                const data = await response.json();

                this.messages = data.messages || [];

                this.$nextTick(() => {
                    this.scrollToBottom();
                });

            } catch (error) {
                console.error('Conversation loading error:', error);
            }
        },

        newChat() {
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
                Math.min(textarea.scrollHeight, 160) + 'px';
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
                        'Content-Type': 'application/json',
                        'Accept': 'application/x-ndjson',

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

                if (!response.ok || !response.body) {
                    throw new Error('AI request failed');
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
                                text.substring(0, 60)
                        });

                        this.currentTitle =
                            conversationTitle ||
                            text.substring(0, 60);

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

                const renderSmoothly = async () => {

                    if (rendering) {
                        return;
                    }

                    rendering = true;

                    while (pendingText.length > 0) {

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
                            pendingText.slice(0, 2);

                        pendingText =
                            pendingText.slice(2);

                        message.content += chunk;

                        this.scrollToBottom();

                        await new Promise(
                            resolve =>
                                setTimeout(resolve, 12)
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

                    buffer += decoder.decode(
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
                            setTimeout(resolve, 20)
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Refresh sidebar timestamps/order
                |--------------------------------------------------------------------------
                */

                await this.loadConversations();

                this.scrollToBottom();

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

        formatMessage(text) {

            if (!text) {
                return '';
            }

            const escaped = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');

            return escaped

                .replace(
                    /\*\*(.*?)\*\*/g,
                    '<strong>$1</strong>'
                )

                .replace(
                    /`([^`]+)`/g,
                    '<code>$1</code>'
                )

                .replace(
                    /\n/g,
                    '<br>'
                );
        }
    };
}
</script>


</body>
</html>
