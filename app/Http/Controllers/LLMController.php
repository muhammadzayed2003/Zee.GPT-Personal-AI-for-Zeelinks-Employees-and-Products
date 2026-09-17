<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LLMController extends Controller
{
    public function conversations(Request $request)
    {
        return response()->json(
            Conversation::where('user_id', $request->user()->id)
                ->latest()
                ->get()
        );
    }

    public function showConversation(Request $request, $id)
    {
        $conversation = Conversation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $conversation->messages()
                ->orderBy('created_at')
                ->get(),
        ]);
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string'],
            'conversation_id' => ['nullable', 'integer'],
        ]);

        $user = $request->user();

        if (!empty($validated['conversation_id'])) {
            $conversation = Conversation::where('id', $validated['conversation_id'])
                ->where('user_id', $user->id)
                ->firstOrFail();
        } else {
            $conversation = Conversation::create([
                'user_id' => $user->id,
                'title' => Str::limit(trim($validated['message']), 60),
            ]);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'role' => 'user',
            'content' => $validated['message'],
        ]);

        try {
            $tools = $this->discoverMcpTools();

            $messages = [
                [
                    'role' => 'system',
                    'content' => config('zeegpt.system_prompt'),
                ],
            ];

            foreach ($conversation->messages()->orderBy('created_at')->get() as $message) {
                $messages[] = [
                    'role' => $message->role,
                    'content' => $message->content,
                ];
            }

            if (!empty($tools)) {
                $answer = $this->runMcpToolLoop($messages, $tools);
            } else {
                Log::warning('Zee.GPT: No MCP tools discovered.');
                $answer = $this->streamStoredAnswer($messages);
            }

            Message::create([
                'conversation_id' => $conversation->id,
                'role' => 'assistant',
                'content' => $answer,
            ]);

            return response()->json([
                'conversation_id' => $conversation->id,
                'message' => [
                    'role' => 'assistant',
                    'content' => $answer,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Zee.GPT chat error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            try {
                $fallback = $this->ollamaRequest($messages ?? [
                    [
                        'role' => 'system',
                        'content' => config('zeegpt.system_prompt'),
                    ],
                    [
                        'role' => 'user',
                        'content' => $validated['message'],
                    ],
                ]);

                $answer = data_get($fallback, 'message.content')
                    ?? data_get($fallback, 'response')
                    ?? 'Sorry, I could not generate a response right now.';

                Message::create([
                    'conversation_id' => $conversation->id,
                    'role' => 'assistant',
                    'content' => $answer,
                ]);

                return response()->json([
                    'conversation_id' => $conversation->id,
                    'message' => [
                        'role' => 'assistant',
                        'content' => $answer,
                    ],
                ]);
            } catch (\Throwable $fallbackError) {
                Log::error('Zee.GPT fallback error', [
                    'message' => $fallbackError->getMessage(),
                ]);

                return response()->json([
                    'conversation_id' => $conversation->id,
                    'message' => [
                        'role' => 'assistant',
                        'content' => 'Sorry, something went wrong while contacting the AI service.',
                    ],
                ], 500);
            }
        }
    }

    private function discoverMcpTools(): array
    {
        $url = config(
            'zeegpt.mcp_url',
            'http://127.0.0.1:8001/mcp/zeelinks'
        );

        $response = $this->mcpRequest(
            $url,
            [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'tools/list',
                'params' => new \stdClass(),
            ]
        );

        $tools = data_get($response, 'result.tools', []);

        Log::info('Zee.GPT MCP tools discovered', [
            'count' => count($tools),
            'tools' => collect($tools)->pluck('name')->values()->all(),
        ]);

        return collect($tools)
            ->map(function ($tool) {
                $name = $tool['name'] ?? null;

                if (!$name) {
                    return null;
                }

                $schema = $tool['inputSchema'] ?? [
                    'type' => 'object',
                    'properties' => new \stdClass(),
                ];

                $parameters = $this->normalizeOllamaSchema($schema);

                return [
                    'type' => 'function',
                    'function' => [
                        'name' => $name,
                        'description' => (string) ($tool['description'] ?? ''),
                        'parameters' => $parameters,
                    ],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function normalizeOllamaSchema(array $schema): array
    {
        $normalized = [
            'type' => $schema['type'] ?? 'object',
            'properties' => new \stdClass(),
        ];

        foreach (($schema['properties'] ?? []) as $name => $property) {
            if (is_array($property)) {
                $normalized['properties']->{$name} =
                    $this->normalizePropertySchema($property);
            }
        }

        if (!empty($schema['required'])) {
            $normalized['required'] = array_values($schema['required']);
        }

        return $normalized;
    }

    private function normalizePropertySchema(array $schema): array
    {
        $result = [
            'type' => $schema['type'] ?? 'string',
        ];

        if (isset($schema['description'])) {
            $result['description'] = $schema['description'];
        }

        if (isset($schema['enum'])) {
            $result['enum'] = array_values($schema['enum']);
        }

        if (isset($schema['items']) && is_array($schema['items'])) {
            $result['items'] = $schema['items'];
        }

        if (($result['type'] ?? null) === 'object') {
            $result['properties'] = new \stdClass();

            foreach (($schema['properties'] ?? []) as $name => $property) {
                if (is_array($property)) {
                    $result['properties']->{$name} =
                        $this->normalizePropertySchema($property);
                }
            }

            if (!empty($schema['required'])) {
                $result['required'] = array_values($schema['required']);
            }
        }

        return $result;
    }

    private function runMcpToolLoop(array $messages, array $tools): string
    {
        $mcpUrl = config(
            'zeegpt.mcp_url',
            'http://127.0.0.1:8001/mcp/zeelinks'
        );

        for ($iteration = 0; $iteration < 5; $iteration++) {
            Log::info('Zee.GPT Ollama tool loop iteration', [
                'iteration' => $iteration + 1,
            ]);

            $response = $this->ollamaRequest($messages, $tools);

            $assistantMessage = data_get($response, 'message', []);
            $toolCalls = $assistantMessage['tool_calls'] ?? [];

            Log::info('Zee.GPT Ollama response', [
                'has_tool_calls' => !empty($toolCalls),
                'tool_count' => count($toolCalls),
                'content_preview' => Str::limit(
                    (string) ($assistantMessage['content'] ?? ''),
                    200
                ),
            ]);

            if (empty($toolCalls)) {
                return (string) ($assistantMessage['content'] ?? '');
            }

            $toolResults = [];

            foreach ($toolCalls as $toolCall) {
                $function = $toolCall['function'] ?? [];

                if (empty($function['name'])) {
                    continue;
                }

                $name = (string) $function['name'];
                $arguments = $function['arguments'] ?? [];

                if (is_string($arguments)) {
                    $decoded = json_decode($arguments, true);

                    if (is_array($decoded)) {
                        $arguments = $decoded;
                    } else {
                        $arguments = [];
                    }
                }

                if (!is_array($arguments)) {
                    $arguments = [];
                }

                Log::info('Zee.GPT calling MCP tool', [
                    'tool' => $name,
                    'arguments' => $arguments,
                ]);

                $result = $this->callMcpTool(
                    $mcpUrl,
                    $name,
                    $arguments
                );

                Log::info('Zee.GPT MCP tool result', [
                    'tool' => $name,
                    'result_preview' => Str::limit($result, 1000),
                ]);

                $toolResults[] = [
                    'tool' => $name,
                    'result' => $result,
                ];
            }

            if (empty($toolResults)) {
                return (string) ($assistantMessage['content'] ?? '');
            }

            $context = "The following information was retrieved from the authoritative ZeeLinks MCP server.\n\n";

            foreach ($toolResults as $toolResult) {
                $context .= "MCP TOOL: {$toolResult['tool']}\n";
                $context .= "MCP RESULT:\n";
                $context .= $toolResult['result'];
                $context .= "\n\n";
            }

            $context .= "Use the MCP result above as the authoritative source and answer the user's original request directly. Do not mention internal tool calls unless necessary.";

            $messages[] = [
                'role' => 'user',
                'content' => $context,
            ];

            $finalResponse = $this->ollamaRequest($messages);

            $finalContent = data_get(
                $finalResponse,
                'message.content'
            );

            if ($finalContent !== null && $finalContent !== '') {
                return (string) $finalContent;
            }

            $fallbackContent = data_get(
                $finalResponse,
                'response'
            );

            if ($fallbackContent !== null) {
                return (string) $fallbackContent;
            }
        }

        return '';
    }

    private function ollamaRequest(array $messages, array $tools = []): array
    {
        $payload = [
            'model' => config('zeegpt.model', 'qwen3:8b'),
            'messages' => $messages,
            'stream' => false,
            'think' => false,
            'options' => [
                'temperature' => config('zeegpt.temperature', 0.4),
                'num_predict' => config('zeegpt.num_predict', 300),
            ],
        ];

        if (!empty($tools)) {
            $payload['tools'] = array_values($tools);
        }

        Log::info('Zee.GPT sending Ollama request', [
            'message_count' => count($messages),
            'tool_count' => count($tools),
            'tools' => collect($tools)
                ->map(fn ($tool) => data_get($tool, 'function.name'))
                ->filter()
                ->values()
                ->all(),
        ]);

        $response = Http::timeout(180)
            ->connectTimeout(10)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post(
                config(
                    'zeegpt.ollama_url',
                    'http://127.0.0.1:11434/api/chat'
                ),
                $payload
            );

        $response->throw();

        return $response->json();
    }

    private function callMcpTool(
        string $url,
        string $name,
        array|\stdClass $arguments
    ): string {
        $response = $this->mcpRequest(
            $url,
            [
                'jsonrpc' => '2.0',
                'id' => random_int(1000, 9999),
                'method' => 'tools/call',
                'params' => [
                    'name' => $name,
                    'arguments' => $arguments,
                ],
            ]
        );

        if (data_get($response, 'error')) {
            return json_encode(
                data_get($response, 'error'),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        if (data_get($response, 'result.isError') === true) {
            return json_encode(
                data_get($response, 'result', $response),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $content = data_get($response, 'result.content');

        if (is_array($content)) {
            $texts = collect($content)
                ->map(function ($item) {
                    if (isset($item['text'])) {
                        return $item['text'];
                    }

                    if (isset($item['data'])) {
                        return is_string($item['data'])
                            ? $item['data']
                            : json_encode(
                                $item['data'],
                                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            );
                    }

                    return json_encode(
                        $item,
                        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                    );
                })
                ->filter()
                ->values()
                ->all();

            if (!empty($texts)) {
                return implode("\n", $texts);
            }
        }

        $structured = data_get($response, 'result.structuredContent');

        if ($structured !== null) {
            return is_string($structured)
                ? $structured
                : json_encode(
                    $structured,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                );
        }

        return json_encode(
            data_get($response, 'result', $response),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function mcpRequest(
        string $url,
        array $payload
    ): array {
        $response = Http::timeout(120)
            ->connectTimeout(10)
            ->withHeaders([
                'Accept' => 'application/json, text/event-stream',
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);

        $response->throw();

        $body = trim($response->body());

        Log::info('Zee.GPT MCP raw response', [
            'method' => $payload['method'] ?? null,
            'status' => $response->status(),
            'content_type' => $response->header('Content-Type'),
            'body_preview' => Str::limit($body, 1500),
        ]);

        if ($body === '') {
            if ($response->status() === 202) {
                return [];
            }

            throw new \RuntimeException('Invalid MCP response.');
        }

        $decoded = json_decode($body, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        $result = null;
        $dataBuffer = '';

        $lines = preg_split("/\r\n|\r|\n/", $body);

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            if (str_starts_with($line, ':')) {
                continue;
            }

            if (str_starts_with($line, 'data:')) {
                $json = trim(substr($line, 5));

                if ($json === '' || $json === '[DONE]') {
                    continue;
                }

                $dataBuffer .= $json;

                $decodedLine = json_decode($dataBuffer, true);

                if (is_array($decodedLine)) {
                    $result = $decodedLine;
                    $dataBuffer = '';
                }
            }
        }

        if (is_array($result)) {
            return $result;
        }

        Log::error('Zee.GPT invalid MCP response', [
            'method' => $payload['method'] ?? null,
            'status' => $response->status(),
            'content_type' => $response->header('Content-Type'),
            'body' => Str::limit($body, 3000),
        ]);

        throw new \RuntimeException('Invalid MCP response.');
    }

    private function streamOllama(array $messages): \Generator
    {
        $response = Http::withOptions([
            'stream' => true,
        ])
            ->timeout(180)
            ->connectTimeout(10)
            ->post(
                config(
                    'zeegpt.ollama_url',
                    'http://127.0.0.1:11434/api/chat'
                ),
                [
                    'model' => config('zeegpt.model', 'qwen3:8b'),
                    'messages' => $messages,
                    'stream' => true,
                    'think' => false,
                    'options' => [
                        'temperature' => config('zeegpt.temperature', 0.4),
                        'num_predict' => config('zeegpt.num_predict', 300),
                    ],
                ]
            );

        $response->throw();

        $body = $response->toPsrResponse()->getBody();

        $buffer = '';

        while (!$body->eof()) {
            $chunk = $body->read(8192);

            if ($chunk === '') {
                continue;
            }

            $buffer .= $chunk;

            $lines = preg_split("/\r\n|\r|\n/", $buffer);

            $buffer = array_pop($lines) ?? '';

            foreach ($lines as $line) {
                $line = trim($line);

                if ($line === '') {
                    continue;
                }

                $decoded = json_decode($line, true);

                if (!is_array($decoded)) {
                    continue;
                }

                $content = data_get(
                    $decoded,
                    'message.content'
                );

                if ($content !== null && $content !== '') {
                    yield $content;
                }

                if (!empty($decoded['done'])) {
                    return;
                }
            }
        }

        if (trim($buffer) !== '') {
            $decoded = json_decode(trim($buffer), true);

            if (is_array($decoded)) {
                $content = data_get(
                    $decoded,
                    'message.content'
                );

                if ($content !== null && $content !== '') {
                    yield $content;
                }
            }
        }
    }

    private function streamStoredAnswer(array $messages): string
    {
        $response = $this->ollamaRequest($messages);

        return data_get($response, 'message.content')
            ?? data_get($response, 'response')
            ?? '';
    }
}
