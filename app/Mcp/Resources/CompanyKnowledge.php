<?php

namespace App\Mcp\Resources;

use Illuminate\Support\Facades\Cache;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Resource;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Uri;

#[Uri('zeelinks://company/knowledge')]
#[Description('Dynamic company knowledge and general information about ZeeLinks.')]
class CompanyKnowledge extends Resource
{
    public function handle(Request $request): Response
    {
        $knowledge = [
            'company' => 'ZeeLinks',
            'location' => 'Islamabad, Pakistan',
            'type' => 'Technology and Software Development Company',
            'focus' => [
                'Artificial Intelligence',
                'AI Automation',
                'AI Agents',
                'Software Development',
                'Web Development',
                'API Integrations',
                'Business Process Automation',
            ],
            'contact' => [
                'phone' => '+92 3275 39 13 35',
                'address' => 'Islamabad Tehsil, Jinnah Garden, Phase 1 Block D, 205 Street, 36',
                'business_hours' => '24 hours',
            ],
            'description' => 'ZeeLinks is a technology company focused on software development, artificial intelligence, automation and digital solutions for businesses.',
        ];

        return Response::text(
            json_encode($knowledge, JSON_PRETTY_PRINT)
        );
    }
}