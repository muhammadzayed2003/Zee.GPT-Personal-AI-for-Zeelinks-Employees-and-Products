<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Support\Facades\DB;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;

#[Description('Returns ZeeLinks contact information, office location, phone number and business hours.')]
class GetContactLocation extends Tool
{
    public function handle(Request $request): Response
    {
        $contactLocation = DB::table('company_data')
            ->where('key', 'contact_location')
            ->value('value');

        if (!$contactLocation) {
            return Response::text(json_encode([
                'error' => 'Contact and location information is not available in the company database.'
            ], JSON_PRETTY_PRINT));
        }

        return Response::text($contactLocation);
    }

    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}