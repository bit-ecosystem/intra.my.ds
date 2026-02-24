# Admin Guide

Comprehensive reference for all database tables across **Core**, **DMS**, **EAM**, **HRM**, and **LMS** cluster of panels.  
Each section summarizes table purpose, key relationships, and system usage.

🤝🏠📖🚜🧩👥🎓▶✅✒🛟🔗

## 🧭 Usage Summary

| Panel | Core Focus | Key Benefits |
|--------|-------------|---------------|
| ✒ **Core** | Universal structure and workflow logic | Unified identity, process, and property base. Base entries of User, Staff, Job Position, Asset, Organization, Process |
| 🏠 **Staff** | Home for all staff | Task to do, Request via Staff Self Service, Inquire/Ask via Chat |
| 📖 **DMS** | Controlled document lifecycle | Compliance with ISO 9001 / IATF requirements, Classification of documents (containerize), Vector DB for AI. |
| 🚜 **EAM** | Asset tracking and maintenance | Equipment performance and reliability insights. |
| 🧩 **ERP** | Cost Center | Org Units maintains cost structure to valuable resources |
| 👥 **HRM** | Staff, job, and competency management | HRIS + ISA-95 alignment for skill governance. |
| 🎓 **LMS** | Learning and certification | Continuous training and compliance management. Outputs certs |
| ▶ **MES** | Execution of manufacturing processes | Work tracking from raw to finished goods. Outputs OEE, |
| ✅ **QAS** | Tools, Methods and Techniques for Continuous Improvement | List, and running of improvement initiatives. Outputs tasks and reports |
| 🤝 **Lobby** | Lobby for all users | Career, RFQ & Tender (for supplier), Orders & WIP (for customer) |

---

## ✒ Core Panel

| Table | Purpose | Key Relations | Usage |
|--------|----------|----------------|--------|
| **users** | Central registry of all user accounts (staff, vendors, learners, etc.) | hasMany: `attributes`, belongsToMany: `roles`, `permissions` | Authentication, identity, and profile data. |
| **roles** | Defines system roles (admin, manager, vendor, learner) | belongsToMany: `users`, `permissions` | Used by Spatie Permission for access control. |
| **permissions** | Individual permission actions mapped to models | belongsToMany: `roles`, `users` | Controls access per module, resource, or workflow step. |
| **organizations** | Represents company or sub-organization unit | hasMany: `divisions`, `departments`, `teams` | Hierarchical structure for multi-tenant or multi-division setups. |
| **divisions / departments / teams** | Internal organizational grouping | belongsTo: `organization`, hasMany: `users`, `assets`, `processes` | Used for scoping workflows, ownership, and reporting. |
| **processes** | Represents a business process or workflow (e.g. purchase, training, maintenance) | hasMany: `tasks`, `transitions`, `instances` | Core element linking operations, workflows, and SOPs. |
| **tasks** | Defines steps or actions within a process | belongsTo: `process`, hasMany: `task_actions` | Used in workflows for sequential or conditional execution. |
| **task_actions** | Reusable actions or functions (e.g. notify, approve, record metric) | belongsTo: `task` | Encapsulates atomic process logic. |
| **transitions** | Defines valid state changes in a workflow | belongsTo: `process`, hasMany: `states` | Enables state machine control and workflow validation. |
| **states** | Current or possible status within a process (e.g. Draft → Approved) | belongsTo: `process` | Used for workflow visualization and transitions. |
| **requests** | Instance of a process (runtime execution) | belongsTo: `process`, `user`, hasMany: `snapshots`, `metrics` | Captures data per execution (e.g. one training request). |
| **attributes** | Polymorphic table for key-value pairs attached to any model | morphTo: `attributable` | Stores metadata, tags, or dynamic properties. |
| **staff_attributes** | Privileged polymorphic attributes for staff-only properties | morphTo: `attributable` | Holds HR-related or confidential fields. |
| **properties** | Shared definition of measurable or reportable variables | hasMany: `metrics` | Reference for performance, quality, or asset metrics. |
| **metrics** | Stores measurement or KPI records | belongsTo: `property`, `user`, `asset`, `process` | Captures system-wide performance data. |
| **snapshots** | Point-in-time record of key states (e.g. before/after event) | morphTo: `snapshotable` | Enables audits, traceability, and version tracking. |
| **events** | Time-based activities (e.g. meeting, calibration, training session) | morphToMany: `participants` | System calendar integration and activity linkage. |
| **tasks_general** | Shared task list not tied to a process | belongsTo: `user` | Used for personal or administrative reminders. |

---

## 📖 Document Management (DMS) Panel

| Table | Purpose | Key Relations | Usage |
|--------|----------|----------------|--------|
| **documents** | Stores metadata for all managed files | belongsTo: `category`, `user`, hasMany: `document_versions`, `document_links` | Core file entity. References file path or GitHub source. |
| **document_versions** | Version tracking for documents | belongsTo: `document`, `user` | Enables version control and revision history. |
| **document_categories** | Classification structure (policy, procedure, form, record) | hasMany: `documents` | Used for retrieval, access rules, and reporting. |
| **document_links** | Polymorphic relation linking docs to other models | morphTo: `linkable` | Associates documents with processes, assets, or jobs. |
| **document_approvals** | Tracks review and approval workflow | belongsTo: `document`, `user`, `role` | Manages controlled document lifecycle (draft → approved → obsolete). |
| **document_revisions** | Audit log of changes to document content or metadata | belongsTo: `document_version` | Provides traceability and revision history. |

---

## 🚜 Enterprise Asset Management (EAM) Panel

| Table | Purpose | Key Relations | Usage |
|--------|----------|----------------|--------|
| **assets** | Master record of physical or digital equipment | belongsTo: `team`, `location`, hasMany: `maintenance`, `metrics` | Central asset register. |
| **asset_categories** | Classification (machinery, IT, tools, facilities) | hasMany: `assets` | Used for reporting and maintenance policy. |
| **asset_locations** | Defines plant, building, or room | hasMany: `assets` | Geographical and organizational tracking. |
| **maintenance_records** | Historical maintenance data | belongsTo: `asset`, `user` | Tracks service, repair, and calibration activities. |
| **work_orders** | Scheduled or requested maintenance jobs | belongsTo: `asset`, `user`, hasMany: `maintenance_tasks` | Core operational unit for maintenance planning. |
| **maintenance_tasks** | Specific actions under a work order | belongsTo: `work_order` | Defines inspection, cleaning, calibration, etc. |
| **asset_calibrations** | Calibration details and certification | belongsTo: `asset`, `user` | Ensures compliance with ISO/TS standards. |
| **asset_warranties** | Manufacturer or service warranty data | belongsTo: `asset` | Tracks coverage and expiry. |
| **asset_documents** | Polymorphic relation to `documents` | morphTo: `documentable` | Stores manuals, certificates, and drawings. |

---

## 👥 Human Resource Management (HRM) Panel

| Table | Purpose | Key Relations | Usage |
|--------|----------|----------------|--------|
| **staff** | Core employee record linked to user | belongsTo: `user`, `team`, `job_post` | Represents employee identity and assignment. |
| **job_posts** | Defines job positions (e.g. Operator, Engineer) | hasMany: `staff`, `personnel_class_properties` | Mirrors ISA-95 Personnel Class. |
| **personnel_class_properties** | Job-level requirements or privileges | belongsTo: `job_post` | Stores structured properties for each position. |
| **qualification_test_specifications** | Defines tests linked to job posts | belongsTo: `job_post` | Stores criteria, passing score, and format. |
| **qualification_test_results** | Staff test attempts and outcomes | belongsTo: `staff`, `qualification_test_specification` | Records competency assessments. |
| **competencies** | Defines skill sets and domains | belongsToMany: `staff` | Used for tracking skills and proficiency levels. |
| **attendances** | Tracks staff presence and activity | belongsTo: `staff`, `event` | Integrates with events and shifts. |
| **payrolls** | Salary and payment data | belongsTo: `staff` | Basic payroll linkage to HR core. |

---

## 🎓 Learning Management (LMS) Panel

| Table | Purpose | Key Relations | Usage |
|--------|----------|----------------|--------|
| **courses** | Core entity representing a learning course | hasMany: `modules`, `enrollments` | Defines learning pathways. |
| **modules** | Subdivision of a course (topics or sections) | belongsTo: `course`, hasMany: `quizzes` | Organizes lessons or material by subject. |
| **quizzes** | Assessment unit within a module | belongsTo: `module`, hasMany: `questions` | Evaluates participant understanding. |
| **questions** | Quiz items with possible answers | belongsTo: `quiz`, hasMany: `answers` | Forms test content. |
| **answers** | Stores answer options and correct flag | belongsTo: `question` | Defines correct/incorrect responses. |
| **enrollments** | Tracks user participation in courses | belongsTo: `user`, `course` | Links learners to courses. |
| **grades** | Final or module-level performance records | belongsTo: `enrollment` | Stores scored results. |
| **certificates** | Issued when user passes a course | belongsTo: `user`, `course`, `grade` | Includes validity period and issue data. |
| **learning_events** | Calendar integration for classes or exams | belongsTo: `course` | Enables scheduling and attendance. |

---

## 🔗 Common Polymorphic Relationships

| Table | Related Models | Description |
|--------|----------------|-------------|
| **attributes** | users, assets, processes, documents, etc. | Adds dynamic metadata and flexibility. |
| **staff_attributes** | staff, hr-only data | Handles sensitive or confidential information. |
| **snapshots** | any auditable entity | Captures state before/after transitions. |
| **document_links / asset_documents** | documents ↔ assets/processes/jobs | Cross-linking for traceability and compliance. |

---

