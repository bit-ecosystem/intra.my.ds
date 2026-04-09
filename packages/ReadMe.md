# Enterprise OS - Package Architecture Reference

This document summarizes the **modular package architecture** for the Enterprise Intranet system, including all packages, models, and relationships.  

---

## 1. Base Package (Core Engine)

**Purpose:** Shared engine & reusable abstractions.

**Models:**
- `Workflow` → defines workflow templates
- `WorkflowStep` → steps within workflows
- `Task` → atomic tasks linked to workflow steps
- `Event` → system or domain events

**Notes:** No business logic; just engine & core abstractions.

---

## 2. Organization Package (Identity & Structure)

**Purpose:** Org hierarchy, job positions, and staff structure.

**Models:**
- `OrgUnit` → OU, Division, Department, Team hierarchy
- `Team` → smallest organizational unit
- `JobPosition` → position within a team/org unit
- `Person` → the human identity (can be internal or external)

**Relationships:**
- OrgUnit hasMany Teams
- Team hasMany JobPositions
- JobPosition filled by Engagement(s)
- Person may have multiple Engagements

---

## 3. Employment Package (HR / Internal Workforce)

**Purpose:** Full-time and fixed-term employees, lifecycle management.

**Models:**
- `Employee` → internal employee profile
- `Engagement` → links Person → JobPosition + Type (FTE / FTC / TPC / Intern)
- `LeaveRequest` → employee leave requests
- `Payroll` → salary & compensation
- `JobApplication` → recruitment process

**Key:** Engagement type separates **internal** vs **external** workforce.

---

## 4. Artifact / Procurement Package (Assets & Inventory)

**Purpose:** Physical items, tools, equipment, and external contractors.

**Models:**
- `Item` → equipment, consumables, tools, spare parts, storage
- `ItemCategory` → categorize items
- `Stock` → current stock per location
- `Asset` → track unique items (serial numbers)
- `Supplier` → external vendor
- `PurchaseRequest` → internal request for procurement
- `PurchaseOrder` → orders placed with suppliers
- Pivot: `service_offering_items` → links ServiceOffering → required items

**Relationships:**
- ServiceOffering requires Items → stock must be reserved / purchased before use.

---

## 5. Service Package (Business Services / Workflows)

**Purpose:** Service offerings consumed by organization units.

**Models:**
- `ServiceOffering` → the service itself
- `ServiceCategory` → classify services
- `Request` → a user request for a service
- `ServiceAssignment` (optional) → tracks which team/person handles request

**Relationships:**
- ServiceOffering requires Items (from Artifact package)
- ServiceOffering owned by Team
- Requests trigger Workflow (from Base package)

---

## 6. Knowledge Package (DMS + LMS)

**Purpose:** Document management and learning management.

**Models:**
- `Document` → uploaded documents
- `DocumentVersion` → versioning
- `Course` → learning course
- `Module` → course module
- `Quiz` → assessment
- `Certificate` → issued upon passing

**Relationships:**
- Courses link to Modules → Quizzes → Certificate
- Documents can be attached to ServiceOfferings or Workflows

---

## Summary Table

| Package        | Core Models                                                                 | Notes |
|----------------|----------------------------------------------------------------------------|-------|
| Base           | Workflow, WorkflowStep, Task, Event                                         | Core engine, no business logic |
| Organization   | OrgUnit, Team, JobPosition, Person                                         | Hierarchy + identity |
| Employment     | Employee, Engagement, LeaveRequest, Payroll, JobApplication                | Internal workforce & HR |
| Artifact       | Item, ItemCategory, Stock, Asset, Supplier, PurchaseRequest, PurchaseOrder | Inventory, procurement, tools, external contractors |
| Service        | ServiceOffering, ServiceCategory, Request, ServiceAssignment               | Business services, linked to teams & items |
| Knowledge      | Document, DocumentVersion, Course, Module, Quiz, Certificate               | DMS + LMS |

---

## Key Relationships Across Packages

```text
Person ──< Engagement >── JobPosition ── Team ── OrgUnit
    │
    ├── Employee (internal HR)
    └── Contract Worker / TPC / Intern (procurement)

ServiceOffering ──< service_offering_items >── Item
Request ── triggers ── Workflow (Base)
Knowledge (Documents/Courses) ── optional attachment ── ServiceOffering