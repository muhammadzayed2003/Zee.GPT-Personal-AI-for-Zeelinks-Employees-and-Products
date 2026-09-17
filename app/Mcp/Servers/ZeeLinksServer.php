<?php

namespace App\Mcp\Servers;

use App\Mcp\Prompts\ZeeLinksAssistant;
use App\Mcp\Resources\CompanyKnowledge;
use App\Mcp\Tools\GetCompanyDescription;
use App\Mcp\Tools\GetContactLocation;
use App\Mcp\Tools\GetEmployees;
use App\Mcp\Tools\GetLifeAtZeeLinks;
use App\Mcp\Tools\GetOwners;
use App\Mcp\Tools\GetProducts;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Zee Links Server')]
#[Version('1.0.0')]
#[Instructions('Provides ZeeLinks company information through specialized tools, dynamic resources, and prompts.')]
class ZeeLinksServer extends Server
{
    protected array $tools = [
        GetEmployees::class,
        GetOwners::class,
        GetProducts::class,
        GetLifeAtZeeLinks::class,
        GetContactLocation::class,
        GetCompanyDescription::class,
    ];

    protected array $resources = [
        CompanyKnowledge::class,
    ];

    protected array $prompts = [
        ZeeLinksAssistant::class,
    ];
}