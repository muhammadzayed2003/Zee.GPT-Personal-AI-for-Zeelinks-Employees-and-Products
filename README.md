# Zee.GPT — Personal AI for ZeeLinks Employees & Products

**Zee.GPT** is a private, full-stack AI workspace built for **ZeeLinks employees and internal products**.

It provides a ChatGPT-style interface powered by a **locally hosted LLM through Ollama**, with authenticated users, private conversation history, API key management, permission-based access, streaming AI responses, and a custom **Model Context Protocol (MCP)** server for structured company-data retrieval.

The primary goal is to provide a secure and extensible internal AI platform while keeping infrastructure and LLM usage costs as low as possible.

---

## 🎯 Why Zee.GPT?

Zee.GPT was designed as a **cost-efficient internal AI platform**.

Instead of sending every request to paid cloud LLM APIs, the current system runs the language model locally through **Ollama**.

This provides:

* No per-request OpenAI, Claude, or Gemini API cost for local inference
* Full control over the AI backend
* Private internal conversations
* Lower operating costs for employees
* Controlled access to ZeeLinks company knowledge through MCP
* Ability to expose AI capabilities to future ZeeLinks products through API keys
* Flexible model switching without rebuilding the application

The architecture can later be upgraded to cloud or hybrid inference when higher scalability is required.

---

## ✨ Features

### AI Workspace

* ChatGPT-style private AI interface
* Real-time streaming responses
* Conversation context
* Persistent chat history
* New conversation creation
* Conversation selection from the sidebar
* Markdown-style response formatting
* Support for tables, charts, and flowcharts
* Roman Urdu, English, and mixed-language support
* Context-aware follow-up questions

### Authentication

* User registration
* Login and logout
* Password reset
* Email verification support
* Profile management
* Password management
* Approval-based access for new accounts

### User Permissions

Access can be controlled per user.

Available internal permissions include:

* AI Workspace access
* API access
* Administrative permissions

This allows ZeeLinks to decide which employees can access specific parts of the platform.

### Conversation Management

Each authenticated user has their own conversations.

The system stores:

* Conversations
* User messages
* AI responses
* Conversation ownership

Users cannot access another user's private conversation history through the application.

### API Keys

Users with API access can generate and manage API keys.

These keys are intended for future ZeeLinks products that need to communicate with the Zee.GPT backend.

Possible future integrations include:

* Internal web applications
* Mobile applications
* Automation workflows
* AI-powered ZeeLinks products
* Internal business tools

### Local LLM

The current AI backend uses:

**Ollama → Qwen3 8B**

The model can be changed later without redesigning the complete application.

---

## 🔌 Model Context Protocol (MCP)

Zee.GPT includes a custom Laravel MCP server named **ZeeLinksServer**. It gives compatible AI clients controlled access to structured ZeeLinks company data through dedicated tools, a resource, and a reusable prompt.

### MCP Tools

| Tool | Purpose |
| --- | --- |
| `GetEmployees` | Retrieves employee information |
| `GetOwners` | Retrieves ZeeLinks ownership information |
| `GetProducts` | Retrieves information about ZeeLinks products |
| `GetLifeAtZeeLinks` | Retrieves workplace and company-culture information |
| `GetContactLocation` | Retrieves company contact and location details |
| `GetCompanyDescription` | Retrieves the ZeeLinks company description |

### MCP Resource

`CompanyKnowledge` exposes structured organizational context as an MCP resource.

### MCP Prompt

`ZeeLinksAssistant` provides reusable instructions for AI clients interacting with ZeeLinks data.

### MCP Capabilities

* Multi-tool company-data retrieval
* Standardized tool calling
* Structured organizational context
* Reusable AI instructions
* MCP Inspector compatibility for testing tools and sessions
* Modular structure for adding future tools, prompts, and resources

---

## 🏗️ Technology Stack

| Layer | Technology |
| --- | --- |
| Framework | Laravel |
| Backend | PHP |
| Frontend | Blade + Alpine.js |
| Styling | Tailwind CSS |
| Database | SQLite / Laravel-supported database |
| Authentication | Laravel Breeze |
| AI Runtime | Ollama |
| Current LLM | Qwen3 8B |
| AI Integration | Laravel HTTP layer |
| Agent Protocol | Model Context Protocol (MCP) |
| Build Tool | Vite |
| Version Control | Git + GitHub |

---

## 🔄 Architecture

```text
Authenticated User
       │
       ▼
Zee.GPT Web Interface
       │
       ▼
Laravel Application
       ├── Authentication and account approval
       ├── Permissions
       ├── Conversations and messages
       ├── API keys
       ├── LLM Controller ──► Ollama ──► Qwen3 8B
       └── ZeeLinksServer (MCP)
              ├── 6 company-data tools
              ├── CompanyKnowledge resource
              └── ZeeLinksAssistant prompt
```

---

## 💰 Cost Optimization

One of the main design goals of Zee.GPT is reducing recurring AI costs.

### Current Approach

```text
Employee
   ↕
Zee.GPT
   ↕
Local Ollama
   ↕
Qwen3 8B
```

Because inference is performed locally, the system does not require a paid cloud LLM request for every message.

This is particularly useful for:

* Internal employee usage
* Development and testing
* High-frequency AI interactions
* Prototyping new ZeeLinks products
* Internal automation

### Future Scaling

When the number of users grows, the architecture can evolve into a hybrid setup:

```text
                    ┌── Local Ollama
                    │
User → Zee.GPT API ─┤
                    │
                    └── Cloud LLM
```

This allows expensive cloud models to be used only when required.

---

## 🔐 Security

The repository is intended to contain the application's source code, **not private credentials**.

Sensitive environment configuration should remain inside `.env`.

The repository intentionally tracks:

```text
.env.example
```

but does **not** track:

```text
.env
```

API keys, database passwords, application secrets, and other credentials should never be committed to Git.

---

## ⚙️ Local Development

### Requirements

Install the following before running Zee.GPT:

* PHP
* Composer
* Node.js and npm
* Git
* Ollama
* A supported database

### 1. Clone the Repository

```bash
git clone https://github.com/muhammadzayed2003/Zee.GPT-Personal-AI-for-Zeelinks-Employees-and-Products.git
cd Zee.GPT-Personal-AI-for-Zeelinks-Employees-and-Products
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
```

### 4. Create the Environment File

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate the Application Key

```bash
php artisan key:generate
```

### 6. Configure the Database

Update the database configuration inside `.env`, then run:

```bash
php artisan migrate
```

### 7. Install and Run Ollama

Install Ollama on the machine that will run the AI backend, then download the configured model:

```bash
ollama pull qwen3:8b
```

Start Ollama:

```bash
ollama serve
```

The application currently communicates with Ollama through:

```text
http://127.0.0.1:11434
```

### 8. Start Laravel

```bash
php artisan serve
```

### 9. Start Vite

In another terminal:

```bash
npm run dev
```

The application can then be accessed through the Laravel development URL.

---

## 🔑 API Access

Zee.GPT includes an API key management layer for users who have API access enabled.

The long-term purpose is to allow other ZeeLinks applications to consume centralized AI capabilities.

```text
ZeeLinks Product
       │
       │ API Key
       ▼
Zee.GPT API
       │
       ▼
AI Orchestration
       │
       ▼
LLM
```

This means individual ZeeLinks products do not necessarily need to maintain their own separate AI infrastructure.

---

## 👥 Access Model

Zee.GPT is designed for controlled internal usage.

```text
Admin
 ├── Review new accounts
 ├── Manage permissions
 ├── Control AI Workspace access
 └── Control API access

Employee
 └── AI Workspace

Authorized Product
 └── Zee.GPT API
```

Additional roles and granular permissions can be added as the platform evolves.

---

## 📁 Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Mcp/
│   ├── Prompts/
│   │   └── ZeeLinksAssistant.php
│   ├── Resources/
│   │   └── CompanyKnowledge.php
│   ├── Servers/
│   │   └── ZeeLinksServer.php
│   └── Tools/
│       ├── GetCompanyDescription.php
│       ├── GetContactLocation.php
│       ├── GetEmployees.php
│       ├── GetLifeAtZeeLinks.php
│       ├── GetOwners.php
│       └── GetProducts.php
└── Models/
    ├── User.php
    ├── Conversation.php
    ├── Message.php
    ├── ApiKey.php
    └── CompanyData.php

config/
└── zeegpt.php

database/
├── migrations/
├── factories/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
├── web.php
├── api.php
├── ai.php
└── auth.php
```

---

## 🧠 Current AI Configuration

| Setting | Current Configuration |
| --- | --- |
| Model | Qwen3 8B |
| Runtime | Ollama |
| Streaming | Enabled |
| Thinking mode | Disabled |

The application is structured so the underlying model can be replaced later if a different local or cloud model becomes more suitable.

---

## 🚀 Future Roadmap

Potential future improvements include:

* Hybrid local and cloud LLM routing
* Advanced role-based access control
* Admin analytics dashboard
* Token and usage monitoring
* API usage limits and rate limiting
* Model selection
* Multiple AI providers
* RAG knowledge base
* File and document processing
* Voice AI
* Mobile application integration
* Advanced AI agents
* Additional MCP tools and external MCP clients
* Product-specific AI endpoints
* Centralized AI billing and usage management

---

## 🛡️ Repository Policy

This repository contains the source code for Zee.GPT.

The code may be publicly visible for documentation, portfolio, development, or organizational transparency purposes, while actual deployment credentials, private infrastructure configuration, API secrets, databases, and production environment variables remain outside the repository.

**Public visibility does not mean public authorization to use ZeeLinks infrastructure or services.**

---

## 📄 License

This project is proprietary software developed for ZeeLinks.

Unless explicitly authorized, copying, redistribution, commercial use, deployment, or reuse of the project as a competing service is not permitted.

---

## 👨‍💻 Project

**Zee.GPT**

Private AI infrastructure for **ZeeLinks employees and products**.

Built with Laravel, Ollama, MCP, and modern web technologies.
