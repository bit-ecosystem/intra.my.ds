<?php

declare(strict_types=1);

namespace App\Enums;

enum AssetCategory: string
{
    case RM = 'RM'; // Raw Material
    case SF = 'SF'; // Semi-Finished
    case FG = 'FG'; // Finished Good
    case SP = 'SP'; // Spare Part
    case CO = 'CO'; // Consumable
    case TL = 'TL'; // Tool
    case EQ = 'EQ'; // Equipment
    case PQ = 'PQ'; // Personal Equipment
    case AS = 'AS'; // Asset
    case ST = 'ST'; // Storage
    case PK = 'PK'; // Packaging
    case DG = 'DG'; // Digital Item
    case SV = 'SV'; // Service
    case UT = 'UT'; // Utility
    case HR = 'HR'; // Organizational Resource
    case QI = 'QI'; // Quality Item
    case LG = 'LG'; // Logistics Unit
    case FI = 'FI'; // Financial Item
    case MD = 'MD'; // Meta Definition

    public function nameLabel(): string
    {
        return match ($this) {
            self::RM => 'Raw Material',
            self::SF => 'Semi-Finished',
            self::FG => 'Finished Good',
            self::SP => 'Spare Part',
            self::CO => 'Consumable',
            self::TL => 'Tool',
            self::EQ => 'Equipment',
            self::PQ => 'Personal Equipment',
            self::AS => 'Asset',
            self::ST => 'Storage',
            self::PK => 'Packaging',
            self::DG => 'Digital Item',
            self::SV => 'Service',
            self::UT => 'Utility',
            self::HR => 'Organizational Resource',
            self::QI => 'Quality Item',
            self::LG => 'Logistics Unit',
            self::FI => 'Financial Item',
            self::MD => 'Meta Definition',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::RM => 'Primary materials used in production',
            self::SF => 'Intermediate production outputs',
            self::FG => 'Products sold to customers',
            self::SP => 'Replacement parts for equipment',
            self::CO => 'Items consumed during operations',
            self::TL => 'Reusable tools for production',
            self::EQ => 'Production equipment and machinery',
            self::PQ => 'Personal equipment and tools',
            self::AS => 'Capital assets and infrastructure',
            self::ST => 'Storage facility',
            self::PK => 'Packaging materials',
            self::DG => 'Non-physical digital deliverables',
            self::SV => 'Internal or external services',
            self::UT => 'Utilities consumed in production',
            self::HR => 'Human or organizational resources',
            self::QI => 'Quality and compliance materials',
            self::LG => 'Handling and transport units',
            self::FI => 'Commercial and financial objects',
            self::MD => 'System definitions and configurations',
        };
    }

    /** @return string[] */
    public function exampleItems(): array
    {
        return match ($this) {
            self::RM => ['Steel', 'Aluminium', 'Plastic Resin', 'Chemicals', 'Grain', 'Oil'],
            self::SF => ['Machined Parts', 'Molded Plastic Parts', 'Dough Mix'],
            self::FG => ['Bottled Drinks', 'Machines', 'Furniture', 'Electronics'],
            self::SP => ['Bearings', 'Motors', 'Valves', 'Sensors'],
            self::CO => ['Lubricants', 'Gloves', 'Welding Rods', 'Batteries'],
            self::TL => ['Jigs', 'Fixtures', 'Molds', 'Measuring Instruments'],
            self::EQ => ['CNC Machines', 'Robots', 'Forklifts'],
            self::PQ => ['Safety Helmets', 'Gloves', 'Tool Kits', 'Uniforms', 'Smocks', 'Shoes'],
            self::AS => ['Buildings', 'Vehicles', 'Production Lines'],
            self::ST => ['Warehouses', 'Shelves', 'Bins', 'Racks', 'Cold Rooms', 'Lockers'],
            self::PK => ['Bottles', 'Cartons', 'Labels', 'Pallets'],
            self::DG => ['CAD Files', 'SOP Docs', 'Software Licenses'],
            self::SV => ['Maintenance', 'Calibration', 'Transport'],
            self::UT => ['Electricity', 'Water', 'Steam', 'Compressed Air'],
            self::HR => ['Operators', 'Engineers', 'Departments'],
            self::QI => ['Test Samples', 'Reference Standards'],
            self::LG => ['Pallets', 'Containers', 'RFID Tags'],
            self::FI => ['Subscriptions', 'Service Contracts'],
            self::MD => ['Recipes', 'Workflows', 'Process Definitions'],
        };
    }

    /** Convenience: returns [value => label] for UI selects */
    public static function options(): array
    {
        $out = [];
        foreach (self::cases() as $case) {
            $out[$case->value] = $case->nameLabel();
        }

        return $out;
    }

    /** Safe factory from string (returns null if invalid) */
    public static function tryFromValue(?string $value): ?self
    {
        return $value ? self::tryFrom($value) : null;
    }
}
