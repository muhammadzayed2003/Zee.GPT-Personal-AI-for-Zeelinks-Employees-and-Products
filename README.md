# Zee.GPT — Personal AI for ZeeLinks Employees & Products

**Zee.GPT** is a private AI workspace built for **ZeeLinks employees and internal products**.

It provides a ChatGPT-style interface powered by a **locally hosted LLM through Ollama**, with authenticated users, private conversation history, API key management, permission-based access, and streaming AI responses.

The primary goal is to provide an internal AI platform while keeping infrastructure and LLM usage costs as low as possible.

---

## 🎯 Why Zee.GPT?

Zee.GPT was designed as a **cost-efficient internal AI platform**.

Instead of sending every request to paid cloud LLM APIs, the current system runs the language model locally through **Ollama**.

This provides:

* No per-request OpenAI/Claude/Gemini API cost for local inference
* Full control over the AI backend
* Private internal conversations
* Lower operating cost for employees
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
* Conversation selection from sidebar
* Markdown-style response formatting
* Roman Urdu, English and mixed-language support
* Context-aware follow-up questions

### Authentication

* User registration
* Login/logout
* Password reset
* Email verification support
* Profile management
* Password management

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

## 🏗️ Technology Stack

| Layer           | Technology                 |
| --------------- | -------------------------- |
| Framework       | Laravel                    |
| Backend         | PHP                        |
| Frontend        | Blade + Alpine.js          |
| Styling         | Tailwind CSS               |
| Database        | Laravel-supported database |
| Authentication  | Laravel Breeze             |
| AI Runtime      | Ollama                     |
| Current LLM     | Qwen3 8B                   |
| API             | Laravel HTTP/API layer     |
| Build Tool      | Vite                       |
| Version Control | Git + GitHub               |

---

## 🔄 Architecture

```text
User
  │
  ▼
Zee.GPT Web Interface
  │
  ▼
Laravel Application
  │
  ├── Authentication
  ├── Permissions
  ├── Conversations
  ├── Messages
  └── API Keys
  │
  ▼
LLM Controller
  │
  ▼
Ollama
  │
  ▼
Qwen3 8B
  │
  ▼
Streaming AI Response
  │
  ▼
User
```

---

## 💰 Cost Optimization

One of the main design goals of Zee.GPT is reducing recurring AI costs.

### Current approach

```text
Employee
   ↓
Zee.GPT
   ↓
Local Ollama
   ↓
Qwen3 8B
```

Because inference is performed locally, the system does not require a paid cloud LLM request for every message.

This is particularly useful for:

* Internal employee usage
* Development/testing
* High-frequency AI interactions
* Prototyping new ZeeLinks products
* Internal automation

### Future scaling

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

API keys, database passwords, application secrets and other credentials should never be committed to Git.

---

## ⚙️ Local Development

### Requirements

Install the following before running Zee.GPT:

* PHP
* Composer
* Node.js + npm
* Git
* Ollama
* A supported database

---

### 1. Clone the repository

```bash
git clone https://github.com/muhammadzayed2003/Zee.GPT-Personal-AI-for-Zeelinks-Employees-and-Products.git
cd Zee.GPT-Personal-AI-for-Zeelinks-Employees-and-Products
```

---

### 2. Install PHP dependencies

```bash
composer install
```

---

### 3. Install frontend dependencies

```bash
npm install
```

---

### 4. Create environment file

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

---

### 5. Generate application key

```bash
php artisan key:generate
```

---

### 6. Configure the database

Update the database configuration inside `.env`.

Then run:

```bash
php artisan migrate
```

---

### 7. Install and run Ollama

Install Ollama on the machine that will run the AI backend.

Then download the configured model:

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

---

### 8. Start Laravel

```bash
php artisan serve
```

---

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

Example future architecture:

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

Conceptually:

```text
Admin
 │
 ├── Manage permissions
 ├── Control AI Workspace access
 └── Control API access

Employee
 │
 └── AI Workspace

Authorized Product
 │
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
│
├── Models/
│   ├── User.php
│   ├── Conversation.php
│   ├── Message.php
│   └── ApiKey.php
│
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
└── auth.php

public/
bootstrap/
config/
storage/
tests/
```

---

## 🧠 Current AI Configuration

Current model:

```text
Qwen3 8B
```

AI runtime:

```text
Ollama
```

Streaming:

```text
Enabled
```

Thinking mode:

```text
Disabled
```

The application is structured so the underlying model can be replaced later if a different local or cloud model becomes more suitable.

---

## 🚀 Future Roadmap

Potential future improvements include:

* Hybrid local + cloud LLM routing
* Advanced role-based access control
* Admin analytics dashboard
* Token/usage monitoring
* API usage limits
* Rate limiting
* Model selection
* Multiple AI providers
* RAG knowledge base
* Company knowledge integration
* File/document processing
* Voice AI
* Mobile application integration
* AI agents
* MCP-based tool integration
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

Built with Laravel, Ollama and modern web technologies.
