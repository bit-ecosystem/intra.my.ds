
<?php

return [

    // Filter uf_hr_cmed_e by jgchar (returns selected columns)
    'ecology' => [
        'getTables' => [
            'connection' => 'external_ecology',
            'sql' => <<<'SQL'
                SELECT 
                   *
                FROM ?
                SQL,
            'params' => [
                'table' => 'table',
                'type' => 'string',
                'required' => true,
            ],
            'enabled' => true,
        ],

        'entitlement_by_jgchar' => [
            'connection' => 'external_ecology',
            'sql' => <<<'SQL'
                SELECT jgchar, category, entitlementcode, validasof
                FROM uf_hr_cmed_e
                WHERE jgchar = ?
                ORDER BY validasof DESC
                SQL,
            'params' => [
                ['name' => 'jgchar', 'type' => 'string', 'required' => true],
            ],
            'enabled' => true,
        ],

        // (Optional) latest entitlement per category by jgchar
        'latest_entitlement_by_category' => [
            'connection' => 'external_ecology',
            'sql' => <<<'SQL'
            SELECT jgchar, category, entitlementcode
            FROM (
                SELECT 
                    jgchar,
                    category,
                    entitlementcode,
                    validasof,
                    ROW_NUMBER() OVER (
                        PARTITION BY category
                        ORDER BY validasof DESC
                    ) AS rn
                FROM uf_hr_cmed_e
                WHERE jgchar = ?
            ) t
            WHERE t.rn = 1
            ORDER BY category
            SQL,
            'params' => [
                ['name' => 'category', 'type' => 'string', 'required' => true],
            ],
            'enabled' => true,
        ],

        'get_staff' => [
            'connection' => 'external_ecology',
            'sql' => <<<'SQL'
            SELECT
                E.locationdesc as Company,
                E.locationname as Office,
                A.uuid as uuID,
                A.workcode as EmployeeID,
                A.loginid as LoginID,
                A.email as Email,
                A.lastname as LastName,
                B.field9 as JobTitle,
                F.workcode as Manager_reportingTo,
                A.companystartdate as CompanyStartDate,
                B.field8 as ShiftCode,
                B.field3 as Category,
                A.sex as Gender,
                B.field4 as CostCenter,
                G.departmentname as Department
            FROM HrmResource A
            LEFT JOIN cus_fielddata B ON A.id = B.id
            LEFT JOIN HrmDepartment C ON A.departmentid = C.id
            LEFT JOIN HrmJobTitles D ON A.jobtitle = D.id
            LEFT JOIN HrmLocations E ON A.locationid = E.id
            LEFT JOIN HrmResource F ON A.managerid = F.id
            LEFT JOIN HrmDepartment G ON A.departmentid = G.id
            WHERE A.subcompanyid1 = 7
            AND A.status = 1
            ORDER BY A.id
            SQL,
            'params' => [],
            'enabled' => true,
        ],
        'get_cus_fields' => [
            'connection' => 'external_ecology',
            'sql' => <<<'SQL'
            SELECT *
            FROM cus_fielddata
            SQL,
            'params' => [],
            'enabled' => true,
        ],
        'get_job_titles' => [
            'connection' => 'external_ecology',
            'sql' => <<<'SQL'
            SELECT *
            FROM HrmJobTitles
            SQL,
            'params' => [],
            'enabled' => true,
        ],
        'get_resources' => [
            'connection' => 'external_ecology',
            'sql' => <<<'SQL'
            SELECT *
            FROM HrmResource
            SQL,
            'params' => [],
            'enabled' => true,
        ],
    ],
];
