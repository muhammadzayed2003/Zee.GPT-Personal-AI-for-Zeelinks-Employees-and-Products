<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Returns information about the owners and leadership of ZeeLinks, including their roles and responsibilities.')]
class GetOwners extends Tool
{
    public function handle(Request $request): Response
    {
        $owners = DB::table('company_data')
            ->where('key', 'owners')
            ->value('value');

        if (!$owners) {
            return Response::text(json_encode([
                'error' => 'Owner information is not available in the company database.'
            ], JSON_PRETTY_PRINT));
        }

        return Response::text($owners);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}