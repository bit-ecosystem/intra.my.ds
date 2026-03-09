<?php

namespace Database\Seeders;

use App\Enums\CourseGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['code' => 'HSE-101', 'title' => 'Workplace Health & Safety Essentials', 'coursegroup' => CourseGroup::SAFETY, 'description' => 'Foundational safety protocols for the manufacturing floor.', 'status' => 'published'],
            ['code' => 'LOTO-201', 'title' => 'Lockout/Tagout: Control of Hazardous Energy', 'coursegroup' => CourseGroup::SAFETY, 'description' => 'Procedures to disable machinery during maintenance.', 'status' => 'published'],
            ['code' => 'FIRE-01', 'title' => 'Emergency Evacuation & Fire Training', 'coursegroup' => CourseGroup::SAFETY, 'description' => 'Procedures for fire emergencies and equipment use.', 'status' => 'published'],
            ['code' => 'PPE-05', 'title' => 'Personal Protective Equipment Basics', 'coursegroup' => CourseGroup::SAFETY, 'description' => 'Selection and maintenance of safety gear for operators.', 'status' => 'published'],

            ['code' => 'ISO-9001', 'title' => 'Quality Management System Awareness', 'coursegroup' => CourseGroup::COMPLIANCE, 'description' => 'Understanding ISO standards for production quality.', 'status' => 'published'],
            ['code' => 'LEGAL-01', 'title' => 'Anti-Bribery & Corruption Policy', 'coursegroup' => CourseGroup::COMPLIANCE, 'description' => 'Legal guidelines on corporate ethics and reporting.', 'status' => 'published'],
            ['code' => 'DATA-02', 'title' => 'Data Privacy & Document Control', 'coursegroup' => CourseGroup::COMPLIANCE, 'description' => 'Handling sensitive production data and documentation.', 'status' => 'published'],

            ['code' => 'QC-101', 'title' => 'Quality Control Basics', 'coursegroup' => CourseGroup::QUALITY, 'description' => 'Introduction to inspection standards and defect identification.', 'status' => 'published'],
            ['code' => 'TQM-02', 'title' => 'Total Quality Management (TQM)', 'coursegroup' => CourseGroup::QUALITY, 'description' => 'Long-term approach to success through customer satisfaction.', 'status' => 'published'],
            ['code' => 'SPC-201', 'title' => 'Statistical Process Control', 'coursegroup' => CourseGroup::QUALITY, 'description' => 'Using math and charts to monitor and control production quality.', 'status' => 'published'],
            ['code' => 'CAL-05', 'title' => 'Instrument Calibration & Care', 'coursegroup' => CourseGroup::QUALITY, 'description' => 'Proper handling and zeroing of calipers, micrometers, and gauges.', 'status' => 'published'],
            ['code' => 'REJ-101', 'title' => 'Non-Conformance & Rejection Handling', 'coursegroup' => CourseGroup::QUALITY, 'description' => 'Protocol for tagging and reporting defective materials.', 'status' => 'published'],

            ['code' => 'SOP-CORE', 'title' => 'Standard Operating Procedures (SOP) Basics', 'coursegroup' => CourseGroup::TECHNICAL, 'description' => 'Mastering standard workflows for production stations.', 'status' => 'published'],
            ['code' => 'MACH-01', 'title' => 'Basic Machine Setup & Calibration', 'coursegroup' => CourseGroup::TECHNICAL, 'description' => 'Hands-on guide to starting and calibrating equipment.', 'status' => 'published'],
            ['code' => 'MAIN-202', 'title' => 'Preventative Maintenance for Operators', 'coursegroup' => CourseGroup::TECHNICAL, 'description' => 'Routine checks to prevent machine downtime.', 'status' => 'published'],
            ['code' => 'BLUE-05', 'title' => 'Reading Technical Blueprints', 'coursegroup' => CourseGroup::TECHNICAL, 'description' => 'Interpreting engineering drawings and technical specs.', 'status' => 'published'],
            
            // ---------------- New OSAT: PRODUCT (material-focused) ----------------
            ['code' => 'PROD-QFN',     'title' => 'QFN/DFN Packaging Fundamentals',   'coursegroup' => CourseGroup::PRODUCT, 'description' => 'Product/material-focused competencies and standards for QFN/DFN families.', 'status' => 'published'],
            ['code' => 'PROD-BGA',     'title' => 'BGA/uBGA/FBGA Packaging Basics',   'coursegroup' => CourseGroup::PRODUCT, 'description' => 'Product/material-focused competencies and standards for BGA families.', 'status' => 'published'],
            ['code' => 'PROD-WLCSP',   'title' => 'WLCSP/CSP Handling & Reliability', 'coursegroup' => CourseGroup::PRODUCT, 'description' => 'Product/material-focused competencies for wafer-level CSPs.', 'status' => 'published'],
            ['code' => 'PROD-SIP',     'title' => 'SiP/Module Assembly Overview',     'coursegroup' => CourseGroup::PRODUCT, 'description' => 'Product/material-focused competencies for SiP and modules.', 'status' => 'published'],
            ['code' => 'PROD-POWER',   'title' => 'Power Package Families',           'coursegroup' => CourseGroup::PRODUCT, 'description' => 'Product/material-focused competencies for power packages (QFN, TO, D2PAK).', 'status' => 'published'],
            ['code' => 'PROD-MEMS',    'title' => 'MEMS/Sensor Packaging Essentials', 'coursegroup' => CourseGroup::PRODUCT, 'description' => 'Product/material-focused competencies for MEMS/sensor packages.', 'status' => 'published'],

            // ---------------- New OSAT: PROCESS (segments / workflows) ----------------
            ['code' => 'PROC-DICING',     'title' => 'Wafer Dicing & Singulation',     'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: wafer dicing/saw.', 'status' => 'published'],
            ['code' => 'PROC-DIE-ATTACH', 'title' => 'Die Attach Process Fundamentals', 'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: epoxy/solder die attach.', 'status' => 'published'],
            ['code' => 'PROC-FLIP-CHIP',  'title' => 'Flip Chip Attach & Reflow',      'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: flip‑chip attach.', 'status' => 'published'],
            ['code' => 'PROC-WIRE-BOND',  'title' => 'Wire Bonding (Ball/Wedge)',      'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: Au/Cu/Al wire bond.', 'status' => 'published'],
            ['code' => 'PROC-UNDERFILL',  'title' => 'Underfill & Encapsulation',      'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: capillary/mold underfill.', 'status' => 'published'],
            ['code' => 'PROC-MOLD',       'title' => 'Molding/Encapsulation Basics',   'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: transfer/compression mold.', 'status' => 'published'],
            ['code' => 'PROC-PLATING',    'title' => 'Strip/Panel Plating',            'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: NiPdAu/Ag/Sn plating.', 'status' => 'published'],
            ['code' => 'PROC-TRIM-FORM',  'title' => 'Trim & Form Operations',         'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: trim/form/singulate.', 'status' => 'published'],
            ['code' => 'PROC-MARK',       'title' => 'Package Marking (Laser/Ink)',    'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: marking/traceability.', 'status' => 'published'],
            ['code' => 'PROC-SINGULATION', 'title' => 'Post-Mold Singulation',          'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: saw/router singulation.', 'status' => 'published'],
            ['code' => 'PROC-AOI',        'title' => 'Automated Optical Inspection',   'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: AOI.', 'status' => 'published'],
            ['code' => 'PROC-XRAY',       'title' => 'X‑ray Inspection for Packages',  'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: 2D/3D X‑ray.', 'status' => 'published'],
            ['code' => 'PROC-TEST',       'title' => 'Electrical Test (FT/CP)',        'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: automated test.', 'status' => 'published'],
            ['code' => 'PROC-BURNIN',     'title' => 'Burn‑in & Reliability Stress',   'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: stress screening.', 'status' => 'published'],
            ['code' => 'PROC-REWORK',     'title' => 'Rework/Repair Practices',        'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: selective rework.', 'status' => 'published'],
            ['code' => 'PROC-SMT',        'title' => 'SMT for SiP/Module',             'coursegroup' => CourseGroup::PROCESS, 'description' => 'Process segments and manufacturing workflows: print, PnP, reflow.', 'status' => 'published'],

            // ---------------- New OSAT: EQUIPMENT (classes / operation / maintenance) ----------------
            ['code' => 'EQ-DICER',       'title' => 'Wafer Dicer Operation',        'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: wafer dicer.', 'status' => 'published'],
            ['code' => 'EQ-DIE-BONDER',  'title' => 'Die Bonder Operation',         'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: die bonder.', 'status' => 'published'],
            ['code' => 'EQ-FLIP-CHIP',   'title' => 'Flip Chip Bonder Operation',   'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: flip-chip bonder.', 'status' => 'published'],
            ['code' => 'EQ-WIRE-BONDER', 'title' => 'Wire Bonder Operation',        'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: wire bonder.', 'status' => 'published'],
            ['code' => 'EQ-MOLD',        'title' => 'Molding Press Operation',      'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: molding press.', 'status' => 'published'],
            ['code' => 'EQ-TRIM-FORM',   'title' => 'Trim & Form Machine Operation', 'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: trim & form.', 'status' => 'published'],
            ['code' => 'EQ-PLATING',     'title' => 'Plating Line Operation',       'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: plating line.', 'status' => 'published'],
            ['code' => 'EQ-MARK',        'title' => 'Laser Marker Operation',       'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: laser marker.', 'status' => 'published'],
            ['code' => 'EQ-SINGULATION', 'title' => 'Singulation Saw/Router Operation',   'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: singulation saw/router.', 'status' => 'published'],
            ['code' => 'EQ-AOI',         'title' => 'AOI Machine Operation',        'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: AOI machine.', 'status' => 'published'],
            ['code' => 'EQ-XRAY',        'title' => 'X‑ray Inspection Machine Operation',  'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: X‑ray inspection machine.', 'status' => 'published'],
            ['code' => 'EQ-TESTER',      'title' => 'Automated Test Equipment Operation',  'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: automated test equipment.', 'status' => 'published'],
            ['code' => 'EQ-BURNIN',      'title' => 'Burn‑in Chamber Operation',   'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: burn‑in chamber.', 'status' => 'published'],
            ['code' => 'EQ-REWORK',      'title' => 'Rework Station Operation',    'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: rework station.', 'status' => 'published'],
            ['code' => 'EQ-SMT',         'title' => 'SMT Line Operation for SiP/Module',  'coursegroup' => CourseGroup::EQUIPMENT, 'description' => 'Equipment classes, operation, and maintenance topics: SMT line for SiP/Module.', 'status' => 'published'],

            ['code' => 'LEAN-01', 'title' => 'Introduction to Lean Manufacturing', 'coursegroup' => CourseGroup::EFFICIENCY, 'description' => 'Reducing waste and optimizing production flow.', 'status' => 'published'],
            ['code' => '5S-WORK', 'title' => '5S Methodology: Workspace Organization', 'coursegroup' => CourseGroup::EFFICIENCY, 'description' => 'Standardizing workplace cleanliness and efficiency.', 'status' => 'published'],
            ['code' => 'RCA-202', 'title' => 'Root Cause Analysis (RCA)', 'coursegroup' => CourseGroup::EFFICIENCY, 'description' => 'Problem-solving using the 5 Whys and Fishbone methods.', 'status' => 'published'],

            ['code' => 'ERP-101', 'title' => 'ERP Navigation & Data Entry', 'coursegroup' => CourseGroup::DIGITAL, 'description' => 'Using the company software to log production and inventory.', 'status' => 'published'],
            ['code' => 'MES-05', 'title' => 'Mobile Production Tracking (MES)', 'coursegroup' => CourseGroup::DIGITAL, 'description' => 'Real-time tracking of manufacturing execution on tablets.', 'status' => 'published'],
            ['code' => 'CYB-01', 'title' => 'Cybersecurity for Factory Workers', 'coursegroup' => CourseGroup::DIGITAL, 'description' => 'Protecting industrial systems from digital threats.', 'status' => 'published'],

            ['code' => 'COMM-01', 'title' => 'Handover Communication & Scrums', 'coursegroup' => CourseGroup::SOFT_SKILLS, 'description' => 'Ensuring clear information flow during shift changes.', 'status' => 'published'],
            ['code' => 'TIME-02', 'title' => 'Time Management for Production', 'coursegroup' => CourseGroup::SOFT_SKILLS, 'description' => 'Prioritizing tasks to meet strict manufacturing deadlines.', 'status' => 'published'],

            ['code' => 'LEAD-01', 'title' => 'Operator to Supervisor Transition', 'coursegroup' => CourseGroup::LEADERSHIP, 'description' => 'Skills for moving into a management role on the floor.', 'status' => 'published'],
            ['code' => 'COACH-05', 'title' => 'Performance Coaching & Feedback', 'coursegroup' => CourseGroup::LEADERSHIP, 'description' => 'Techniques for managing and developing direct reports.', 'status' => 'published'],
            ['code' => 'TEAM-201', 'title' => 'Managing Multi-Shift Teams', 'coursegroup' => CourseGroup::LEADERSHIP, 'description' => 'Coordination and communication across different shifts.', 'status' => 'published'],

            ['code' => 'INTRO-01', 'title' => 'Company Mission & Vision', 'coursegroup' => CourseGroup::ONBOARDING, 'description' => 'Introduction to corporate culture and long-term goals.', 'status' => 'published'],
            ['code' => 'SEC-02', 'title' => 'Site Access & Security Protocols', 'coursegroup' => CourseGroup::ONBOARDING, 'description' => 'Rules for facility entry and physical asset security.', 'status' => 'published'],
            ['code' => 'HAND-01', 'title' => 'Employee Handbook Review', 'coursegroup' => CourseGroup::ONBOARDING, 'description' => 'Overview of company policies and employee rights.', 'status' => 'published'],
        ];

        foreach ($courses as $course) {
            DB::table('l_courses')->updateOrInsert(
                ['code' => $course['code']],
                [
                    'title' => $course['title'],
                    'category' => $course['coursegroup']->value,
                    'description' => $course['description'],
                    'status' => $course['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
