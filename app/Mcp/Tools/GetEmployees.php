<?php

namespace App\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Tool;
use Illuminate\Support\Facades\DB;

#[Description('Returns the employee directory of ZeeLinks, including names, designations, departments, and main responsibilities.')]
class GetEmployees extends Tool
{
    public function handle(Request $request): Response
    {
        $employees = DB::table('company_data')
            ->where('key', 'employees')
            ->value('value');

        if (!$employees) {
            return Response::text(
                json_encode([
                    'error' => 'Employee information is not available in the company database.'
                ], JSON_PRETTY_PRINT)
            );
        }

        return Response::text($employees);
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}