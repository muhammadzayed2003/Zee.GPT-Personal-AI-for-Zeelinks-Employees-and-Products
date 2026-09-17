<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Returns information about life and work culture at ZeeLinks, including workplace environment, teamwork, learning, and employee activities.')]
class GetLifeAtZeeLinks extends Tool
{
    public function handle(Request $request): Response
    {
        $lifeAtZeeLinks = DB::table('company_data')
            ->where('key', 'life_at_zeelinks')
            ->value('value');

        if (!$lifeAtZeeLinks) {
            return Response::text(json_encode([
                'error' => 'Life at ZeeLinks information is not available in the company database.'
            ], JSON_PRETTY_PRINT));
        }

        return Response::text($lifeAtZeeLinks);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}