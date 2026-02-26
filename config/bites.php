<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Filament Panels Configuration
    |--------------------------------------------------------------------------
    | Define your panels for the dashboard.
    | Each panel needs:
    | - id: used for route and permission
    | - label: display name
    | - description: short text about the panel
    | - color: Tailwind bg/text color
    */

    'panels' => [
        'staff' => [
            'label' => 'Staff Panel',
            'role_can_access' => 'ut_staff',
            'home' => 'pages.dashboard',
            'description' => 'Staff members can perform day to day internal processes, view reports, and access tools relevant to their roles.',
            'color' => '#0F4B8F',
        ],
        'hrm' => [
            'label' => 'HR Panel',
            'role_can_access' => ['ou_people_planner', 'hr_executive'],
            'home' => 'home',
            'description' => 'About HR management tasks including employee records, attendance, leave management, and performance evaluations.',
            'color' => '#0F4B8F',
        ],
        'erp' => [
            'label' => 'ERP Panel',
            'role_can_access' => 'ou_people_planner',
            'home' => 'home',
            'description' => 'About Organization Setup from defining departments, job position, roles, and permissions to managing workflows and processes.',
            'color' => '#0F4B8F',
        ],
        'eam' => [
            'label' => 'EAM Panel',
            'role_can_access' => 'ut_staff',
            'home' => 'pages.dashboard',
            'description' => 'About Enterprise Asset Management including asset tracking, maintenance scheduling, and lifecycle management.',
            'color' => '#0F4B8F',
        ],
        'dms' => [
            'label' => 'DMS Panel',
            'role_can_access' => null,
            'home' => 'home',
            'description' => 'Document Management System for storing, organizing, and retrieving company documents and files.',
            'color' => '#0F4B8F',
        ],
        'lms' => [
            'label' => 'LMS Panel',
            'role_can_access' => 'ut_staff',
            'home' => 'home',
            'description' => 'Learning Management System for managing training programs, courses, and employee development activities.',
            'color' => '#0F4B8F',
        ],
        'qas' => [
            'label' => 'Quality Panel',
            'role_can_access' => null,
            'home' => 'pages.dashboard',
            'description' => 'Quality Assurance System for monitoring, managing, and improving quality control processes within the organization.',
            'color' => '#0F4B8F',
        ],
        'core' => [
            'label' => 'Core Panel',
            'role_can_access' => null,
            'home' => 'home',
            'description' => 'Administrative functions including user management, system settings, and overall platform configuration.',
            'color' => '#0F4B8F',
        ],
        'idp' => [
            'label' => 'Identity Provider Panel',
            'role_can_access' => null,
            'home' => 'home',
            'description' => 'IAM using Passport',
            'color' => '#0F4B8F',
        ],
    ],
    'emergency' => [
        'ert' => '03-xxxx xxxx',
        'fire' => '994',
        'ambulance' => '999',
        'security' => 'Ext 555',
    ],
    'staff_panel' => [
        'route' => 'filament.staff.resources.menus.index',
        'namespace' => 'filament.staff.*', // for routeIs()
    ],
    // Base URL for local Ollama instance (example: http://127.0.0.1:11434)
    'ollama_url' => env('DMS_OLLAMA_URL', 'http://127.0.0.1:11434'),
    // Path appended for embeddings; adjust if your Ollama setup differs
    'ollama_embed_path' => env('DMS_OLLAMA_EMBED_PATH', '/embed'),
    // Path appended for generation / chat
    'ollama_chat_path' => env('DMS_OLLAMA_CHAT_PATH', '/chat'),
    // Model name to request for embeddings / generation
    'ollama_model' => env('DMS_OLLAMA_MODEL', 'llama2'),
    // Whether to dispatch vectorization jobs synchronously (for tests/dev)
    'vectorize_sync' => env('DMS_VECTORIZE_SYNC', false),
    // Model key used when storing vectors (should match ollama_model)
    'vector_model' => env('DMS_VECTOR_MODEL', env('DMS_OLLAMA_MODEL', 'llama2')),
    'workflow_actions' => [
        'create_request' => [
            'conditions' => ['Submit'],
        ],
        'approve_or_reject' => [
            'conditions' => ['Approve', 'Reject'],
        ],
        'create_job_post' => [
            'conditions' => ['Complete'],
        ],
        'broadcast' => [
            'conditions' => ['Complete'],
        ],
        // Add more actions as needed
    ],
];
