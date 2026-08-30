<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LLMController extends Controller
{
    public function conversations()
    {
        $conversations = Conversation::where('user_id', auth()->id())
            ->latest('updated_at')
            ->get(['id', 'title', 'created_at', 'updated_at']);

        return response()->json($conversations);
    }

    public function showConversation($id)
    {
        $conversation = Conversation::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at')
            ->get(['id', 'role', 'content', 'created_at']);

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    public function chat(Request $request)
    {
        set_time_limit(120);

        $request->validate([
            'message' => ['required', 'string', 'max:10000'],
            'conversation_id' => ['nullable'],
        ]);

        $message = trim($request->input('message'));
        $conversationId = $request->input('conversation_id');

        // Find existing conversation belonging to current user
        $conversation = null;

        if ($conversationId) {
            $conversation = Conversation::where('id', $conversationId)
                ->where('user_id', auth()->id())
                ->first();
        }

        // Create a new conversation when needed
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_id' => auth()->id(),
                'title' => Str::limit($message, 60),
            ]);
        }

        // Save user message
        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $message,
        ]);

        // Get complete conversation history
        $previousMessages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at')
            ->get();

        $ollamaMessages = [
            [
                'role' => 'system',
                'content' => "You are ZeeGPT, a private AI assistant for ZeeLinks Islamabad, developed by Muhammad Zayed, CEO and Software/AI Engineer.

Mention Muhammad Zayed or ZeeLinks only when specifically asked about your creator, developer, ownership, ZeeGPT, or ZeeLinks. Otherwise never bring them up.

Be natural, friendly, warm, concise and context-aware.

Match the user's language and tone, including English, Roman Urdu and mixed language.

Understand typos, misspellings and informal Roman Urdu naturally.

Infer the intended question from context instead of guessing randomly.

For simple messages, reply briefly.

For complex questions, explain enough to be useful.

Avoid generic customer-service phrases and unnecessary filler.

Do not sound like a customer-support bot.

Remember conversation context and understand follow-up questions.

Do not repeat yourself.

For email or message requests, if the original email or message is already provided, write the reply directly from the user's perspective. Do not ask the user to provide it again.

Be accurate and never invent facts.

If you do not know something, say so briefly.

Never pretend to be human or claim abilities you do not have.

If a message is genuinely unclear, ask a short clarification instead of giving a random answer.

Always answer the user's actual question and do not force unrelated topics."
            ],
        ];

        foreach ($previousMessages as $previousMessage) {
            $ollamaMessages[] = [
                'role' => $previousMessage->role,
                'content' => $previousMessage->content,
            ];
        }

        return response()->stream(
            function () use ($ollamaMessages, $conversation) {

                $assistantResponse = '';

                $ch = curl_init('http://127.0.0.1:11434/api/chat');

                curl_setopt_array($ch, [
                    CURLOPT_POST => true,

                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                    ],

                    CURLOPT_POSTFIELDS => json_encode([
                        'model' => 'qwen3:8b',
                        'messages' => $ollamaMessages,
                        'stream' => true,
                        'think' => false,
                        'options' => [
                            'temperature' => 0.4,
                            'num_predict' => 300,
                        ],
                    ]),

                    CURLOPT_WRITEFUNCTION => function ($curl, $data) use (&$assistantResponse) {

                        echo $data;

                        if (ob_get_level() > 0) {
                            ob_flush();
                        }

                        flush();

                        // Save streamed AI text
                        $lines = explode("\n", $data);

                        foreach ($lines as $line) {
                            $line = trim($line);

                            if ($line === '') {
                                continue;
                            }

                            $decoded = json_decode($line, true);

                            if (
                                isset($decoded['message']['content']) &&
                                is_string($decoded['message']['content'])
                            ) {
                                $assistantResponse .= $decoded['message']['content'];
                            }
                        }

                        return strlen($data);
                    },

                    CURLOPT_CONNECTTIMEOUT => 5,
                    CURLOPT_TIMEOUT => 120,
                ]);

                curl_exec($ch);

                curl_close($ch);

                // Save final AI response
                if (trim($assistantResponse) !== '') {
                    Message::create([
                        'conversation_id' => $conversation->id,
                        'role' => 'assistant',
                        'content' => $assistantResponse,
                    ]);

                    $conversation->touch();
                }
            },

            200,

            [
                'Content-Type' => 'application/x-ndjson',
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'X-Accel-Buffering' => 'no',
                'X-Conversation-ID' => (string) $conversation->id,
                'X-Conversation-Title' => $conversation->title,
                'Access-Control-Expose-Headers' => 'X-Conversation-ID, X-Conversation-Title',
            ]
        );
    }
}