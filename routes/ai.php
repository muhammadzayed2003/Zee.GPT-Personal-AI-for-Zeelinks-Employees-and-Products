<?php

use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\ZeeLinksServer;

Mcp::web('/mcp/zeelinks', ZeeLinksServer::class);