<?php

declare(strict_types=1);

namespace App\Enums;

enum DocType: string
{
    // --- Core QMS hierarchy (ISO 10013 / ISO 9001 documented information) ---
    case QualityManual = 'quality_manual';
    case Policy = 'policy';
    case Procedure = 'procedure';
    case WorkInstruction = 'work_instruction';
    case SOP = 'sop'; // Standard Operating Procedure (optional if you use WI only)

    // --- Supporting / external documents referenced by the QMS ---
    case UserManual = 'user_manual';
    case AdminManual = 'admin_manual';
    case Specification = 'specification';
    case Standard = 'standard';       // external standards (e.g., ISO, ASTM)

    // ---- Labels ----
    public function getLabel(): string
    {
        return match ($this) {
            self::QualityManual => 'Quality Manual',
            self::Policy => 'Policy',
            self::Procedure => 'Procedure',
            self::WorkInstruction => 'WI',
            self::SOP => 'SOP',
            self::UserManual => 'User Guide',
            self::AdminManual => 'Admin Guide',
            self::Specification => 'Spec',
            self::Standard => 'Standard',
        };
    }

    public function pluralLabel(): string
    {
        return match ($this) {
            self::QualityManual => 'Quality Manuals',
            self::Policy => 'Policies',
            self::Procedure => 'Procedures',
            self::WorkInstruction => 'WI',
            self::SOP => 'SOP',
            self::UserManual => 'User Guides',
            self::AdminManual => 'Admin Guides',
            self::Specification => 'Specs',
            self::Standard => 'Standards',
        };
    }

    // ---- Hierarchy helpers ----
    public function isCore(): bool
    {
        return in_array($this, [
            self::QualityManual,
            self::Policy,
            self::Procedure,
            self::WorkInstruction,
            self::SOP,
        ], true);
    }

    public function isSupporting(): bool
    {
        return in_array($this, [
            self::UserManual,
            self::AdminManual,
            self::Specification,
            self::Standard,
        ], true);
    }
}
