<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;


#[Description('Returns the official company description and key information about ZeeLinks Islamabad, including its business focus, technology expertise and mission.')]
class GetCompanyDescription extends Tool
{
    public function handle(Request $request): Response
    {
        $company = DB::table('company_data')
            ->where('key', 'company_description')
            ->value('value');

        if (!$company) {
            return Response::text(json_encode([
                'error' => 'Company description is not available in the company database.'
            ], JSON_PRETTY_PRINT));
        }

        return Response::text($company);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}