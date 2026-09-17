<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Returns ZeeLinks products and services, including AI solutions, software development, automation, web development, and related technology services.')]
class GetProducts extends Tool
{
    public function handle(Request $request): Response
    {
        $products = DB::table('company_data')
            ->where('key', 'products')
            ->value('value');

        if (!$products) {
            return Response::text(json_encode([
                'error' => 'Product information is not available in the company database.'
            ], JSON_PRETTY_PRINT));
        }

        return Response::text($products);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}