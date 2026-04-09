# Laravel Package Quick Guide

This guide outlines the standard directory structure and commands for building a comprehensive Laravel package.

---

## 📂 Directory Structure & Setup

Run these commands from your package root to initialize the structure. Click a **Context** to see in-depth details.


| Context | Purpose | Path | Command |
| :--- | :--- | :--- | :--- |
| [**Root**](#-root--core-structure) | Core configuration | `src/`, `config/` | `mkdir src config` |
| [**Database**](#database-layer) | Migrations, Factories, Seeders | `src/Database/` | `mkdir -p src/Database/{Migrations,Factories,Seeders}` |
| [**Domain**](#domain-layer-framework-independent) | Models, Data Objects, Enums | `src/Data/` | `mkdir -p src/{Models,Data,Enums,Casts}` |
| [**Business**](#business-logic) | Actions, Services, Rules | `src/Actions/` | `mkdir -p src/{Actions,Services,Policies,Rules,Contracts,Repositories}` |
| [**Support**](#-infrastructure--delivery) | Traits, Helpers | `src/Traits/` | `mkdir -p src/{Traits,Support}` |
| [**HTTP**](#http-layer-non-filament) | Controllers, Requests, API | `src/Http/` | `mkdir -p src/Http/{Controllers,Requests,Middleware,Resources}` |
| [**Console**](#console--background) | Commands, Jobs, Events | `src/Console/` | `mkdir -p src/{Commands,Jobs,Events,Listeners,Mail,Notifications}` |
| [**Filament**](#filament-components) | Panels, Resources, Clusters | `src/Filament/` | `mkdir -p src/Filament/{Resources,Pages,Clusters,Widgets}` |
| [**Livewire**](#-filament--resources) | Components | `src/Livewire/` | `mkdir -p src/Livewire` |
| [**Resources**](#package-assets) | Views, Lang, Assets | `resources/` | `mkdir -p resources/{views,lang,dist}` |
| [**Routing**](#testing--routing) | Web & API Routes | `routes/` | `mkdir routes` |
| [**Testing**](#testing--routing) | Feature & Unit Tests | `tests/` | `mkdir -p tests/{Feature,Unit}` |

---

## 🛠 Usage Tips

### 1. The `-p` Flag
In Oracle Linux, use `mkdir -p` to create nested subfolders. If the parent doesn't exist, it will be created automatically.

### 2. Batch Creation
Create multiple folders at once using brace expansion:
```bash
mkdir -p src/{Models,Data,Enums}
```
# Laravel Package In-Depth Directory Guide

A comprehensive reference for Laravel package architecture and directory purposes.

---

## 🏗️ Root & Core Structure

| Path | What this is for | Description |
| :--- | :--- | :--- |
| `composer.json` | Package metadata | Defines package name, version, and dependencies. |
| `src/` | Package boots | Primary entry point; contains the `ServiceProvider`. |
| `README.md` | Documentation | Human-readable installation and usage guides. |
| `LICENSE` | OSS License | Declares the legal terms governing usage. |
| `phpunit.xml` | Test configuration | Configures the PHPUnit test runner. |

---

## 🗄️ Database & Logic Layers

### Database Layer
*   **Migrations (`src/Database/Migrations`)**: Defines database schema changes.
*   **Model Factories (`src/Database/Factories`)**: Provides blueprints for creating model instances for testing.
*   **Seeders (`src/Database/Seeders`)**: Defines optional initial or demo data for the database.

### Domain Layer (Framework-Independent)
*   **Eloquent Models (`src/Models`)**: Represents core domain entities mapped to the database.
*   **DTOs / Data Objects (`src/Data`)**: Immutable objects that carry and validate structured data.
*   **PHP Enums (`src/Enums`)**: Strongly typed enumerated values for status/types.
*   **Custom Eloquent Casts (`src/Casts`)**: Encapsulates custom serialization logic for model attributes.

### Business Logic
*   **Actions (`src/Actions`)**: Encapsulates a single responsibility business operation.
*   **Services (`src/Services`)**: Coordinates multiple actions or complex repositories.
*   **Authorization Logic (`src/Policies`)**: Defines authorization rules for specific models.
*   **Validation Rules (`src/Rules`)**: Custom Laravel validation rules for data integrity.

---

## 🌐 Infrastructure & Delivery

### HTTP Layer (Non-Filament)
*   **Controllers (`src/Http/Controllers`)**: Handles incoming HTTP requests and returns responses.
*   **Request Validation (`src/Http/Requests`)**: Dedicated objects for validating incoming form data.
*   **Middleware (`src/Http/Middleware`)**: Request filters that execute before or after a controller.
*   **API Resources (`src/Http/Resources`)**: Transforms domain models into clean JSON responses.

### Console & Background
*   **Artisan Commands (`src/Console/Commands`)**: CLI commands provided by the package.
*   **Queue Jobs (`src/Jobs`)**: Encapsulates background tasks that run asynchronously.
*   **Events & Listeners (`src/Events`)**: System for triggering and responding to domain events.
*   **Mailables (`src/Mail`)**: Structured, reusable email message classes.

---

## 🧩 Filament & Resources

### Filament Components
*   **Resource Definitions (`src/Filament/Resources`)**: Declarative configuration that maps database models to Filament.
*   **Resource Pages/Actions**: Encapsulates UI-level logic for CRUD (Create, Read, Update, Delete) operations.
*   **Widgets (`src/Filament/Widgets`)**: Small dashboard components for displaying metrics or data.

### Package Assets
*   **Translations (`resources/lang`)**: Localization files providing translated text for multi-language support.
*   **Blade Views (`resources/views`)**: Reusable Blade templates and UI components.
*   **Raw Assets (`resources/assets`)**: Source CSS, JavaScript, and images before compilation.

---

## 🧪 Testing & Routing
*   **Feature Tests (`tests/Feature`)**: End-to-end tests that exercise HTTP, routes, and overall system behavior.
*   **Unit Tests (`tests/Unit`)**: Isolated tests covering specific classes or utility functions.
*   **Web/API Routes (`routes/`)**: Defines the public endpoints exposed by the package.
