# Enterprise Intranet

## Modular Package Architecture

This document defines the **official modular package architecture** for the Enterprise Intranet platform.

It is intended to be both a **technical reference** and an **architectural contract**.

The goal of this architecture is to:

- Enforce **clear domain boundaries**
- Enable **long-term scalability** (multi-tenant, multi-panel, SaaS)
- Keep **business logic independent of UI frameworks (Filament)**
- Support **open-source core + proprietary extensions**
- Remain understandable by senior engineers and architects

---

## Core Architectural Principles

1. **Domain-first design** — packages represent *business capabilities*, not technical layers
2. **One-directional dependencies** — no circular coupling between packages
3. **UI is a delivery mechanism** — never the source of truth
4. **Workflow is infrastructure** — not business logic
5. **Identity ≠ Employment** — people, roles, and contracts are distinct concepts
6. **Services orchestrate, domains own** — services coordinate other packages but do not absorb them

---

## Package Overview

```PlainText
packages/
├── Core/
├── Identity/
├── Workforce/
├── Assets/
├── Services/
├── Knowledge/
└── UI/
```

Each top-level package represents a **stable enterprise domain**.

---

## 1. Core Package

### Purpose

Provides the **runtime engine and shared abstractions** used by all other packages.

> Core packages must never depend on business domains.

### Subpackages

```PlainText
Core/
├── Engine/     # Workflow runtime & execution
├── Support/    # Traits, enums, value objects, helpers
└── Security/   # Roles, permissions, authorization policies
```

### Core Engine Models

- `Workflow` — workflow definition and lifecycle
- `WorkflowStep` — discrete steps within a workflow
- `Task` — atomic executable unit
- `Event` — system and domain events

✅ No organization, HR, or service-specific logic  
✅ Designed to be reusable outside this system

### Role & Authorization Model

**Principle:** Positions carry authority. People carry state.

#### Role Categories

**Authority Roles (Position-Based)**
- Assigned to JobPositions
- Examples: service-admin, approver, asset-manager, hr-officer

**Employee-State Roles (Person-Based)**
- Assigned directly to staff
- Examples: new-joiner, learner, onboarding, probation

**Temporary / Contextual Roles**
- Time-bound, workflow-driven
- Examples: acting-manager, incident-commander

---

## 2. Identity Package

### Purpose

Defines **who exists** in the system and **how the organization is structured**.

Identity packages contain *no employment, payroll, or contract logic*.

### Subpackages & Models

```PlainText
Identity/
├── Organization/
│   ├── OrgUnit        # Division / Department / Unit hierarchy
│   ├── Team           # Operational teams
│   └── JobPosition    # Role within an org unit or team
│
└── People/
    └── Person         # Human identity (internal or external)
```

### Key Rules

- `Person` is a **pure identity**
- Job positions describe **structure**, not contracts
- No salary, leave, or engagement data allowed here

---

## 3. Workforce Package

### Purpose

Manages **how people engage with the organization**, including internal staff and external workers.

This package is the **HR and workforce authority**.

### Subpackages

```PlainText
Workforce/
├── Employment/
└── Marketplace/
```

### Employment Models

- `Engagement` ⭐ **Keystone model** linking Person ↔ JobPosition
- `Employee` — internal employee profile
- `LeaveRequest` — leave lifecycle
- `Payroll` — compensation data
- `JobApplication` — recruitment pipeline

### Key Insight

> Everything that works in the system does so via Engagement.

This supports:

- full-time employees
- contractors (TPC)
- interns
- future workforce types without schema redesign

---

## 4. Assets Package

### Purpose

Tracks **physical items, inventory, and procurement lifecycles**.

### Subpackages

```PlainText
Assets/
├── Inventory/
└── Procurement/
```

### Inventory Models

- `Item` — generic item definition
- `ItemCategory` — classification
- `Asset` — uniquely tracked item (serialised)
- `Stock` — quantity per location

### Procurement Models

- `Supplier` — external vendors
- `PurchaseRequest` — internal procurement request
- `PurchaseOrder` — supplier orders

✅ Inventory represents **state**  
✅ Procurement represents **process**

---

## 5. Services Package

### Purpose

Defines **business services** offered by the organization and orchestrates execution across domains.

This is the **coordination layer** of Enterprise OS.

### Subpackages

```PlainText
Services/
├── Catalog/
├── Requests/
└── Fulfillment/
```

### Models

- `ServiceOffering` — the service definition
- `ServiceCategory` — service classification
- `Request` — user request for a service
- `ServiceAssignment` — responsible team or engagement

### Responsibilities

- Bind services to workflows (Core)
- Declare required items (Assets)
- Assign responsible teams (Identity)
- Trigger execution (Workflow Engine)

❌ Services do NOT own HR, inventory, or knowledge data

---

## 6. Knowledge Package

### Purpose

Provides **document management (DMS)** and **learning management (LMS)** capabilities.

### Subpackages

```PlainText
Knowledge/
├── Documents/
└── Learning/
```

### Document Models

- `Document`
- `DocumentVersion`

### Learning Models

- `Course`
- `Module`
- `Quiz`
- `Certificate`

Knowledge entities may be **polymorphically attached** to services, workflows, or assets.

---

## 7. UI Package

### Purpose

Delivers the system through **Filament panels and APIs**.

> UI packages must never contain business logic.

### Structure

```PlainText
UI/
├── Filament/
│   ├── Shared/        # Reusable resources, actions, schemas
│   ├── AdminPanel/    # Admin-facing UI
│   └── TenantPanel/   # Tenant / user-facing UI
└── Api/
```

### UI Rules

- Filament calls **Actions** from domain packages
- No direct DB writes in UI code
- UI depends on domains — never the reverse

---

## Cross-Package Relationships

```PlainText
Person ──< Engagement >── JobPosition ── Team ── OrgUnit
   │
   ├── Employee (internal workforce)
   └── Contractor / TPC / Intern

ServiceOffering ──< service_offering_items >── Item
Request ── triggers ── Workflow (Core Engine)
Knowledge ── attached ── ServiceOffering | Workflow
```

---

## Dependency Rules (Non-Negotiable)

```PlainText
Core        ← nobody
Identity    ← Workforce, Services
Workforce   ← Services
Assets      ← Services
Knowledge   ← Services
Services    ← UI
UI          ← nobody
```

Any violation of these rules introduces architectural debt.

---

## Final Notes

This architecture is designed to evolve into a **true Enterprise Operating System**:

- Supports multi-tenant SaaS
- Enables selective open-sourcing
- Encourages domain ownership
- Prevents framework lock-in

This README is the **source of truth**. Any new package or model must align with it.
