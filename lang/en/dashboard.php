<?php

return [
    'breadcrumb' => 'Overview',
    'workspace' => ':role workspace',
    'headings' => [
        'admin' => 'Management dashboard',
        'data_entry' => 'Data Entry Workspace',
        'viewer' => 'Read-only Overview',
    ],
    'introduction' => [
        'viewer' => 'Explore live humanitarian data without changing records.',
        'operations' => 'A live view of your humanitarian operations.',
    ],
    'access' => [
        'admin' => [
            'heading' => 'Administration access.',
            'description' => 'You can manage users, records, exports, and audit history.',
        ],
        'data_entry' => [
            'heading' => 'Data entry access.',
            'description' => 'You can create and update operational records. Destructive actions remain admin-only.',
        ],
        'viewer' => [
            'heading' => 'Read-only access.',
            'description' => 'You can search, review, and report on records. Write actions are restricted.',
        ],
    ],
    'actions' => [
        'add_family' => 'Add family',
        'view_reports' => 'View reports',
        'full_report' => 'Full report',
        'view_all' => 'View all',
    ],
    'today' => 'Today',
    'entry_activity' => 'Your entry activity',
    'entries_today' => 'Records created today: :count',
    'metrics' => [
        'heading' => 'Key metrics',
        'families' => 'Total families',
        'beneficiaries' => 'Beneficiaries',
        'orphans' => 'Orphan records',
        'without_provider' => 'Families without provider',
        'aid_this_month' => 'Aid this month',
        'recorded_distributions' => 'Recorded distributions',
        'sponsored_orphans' => 'Active sponsored orphans',
        'active_sponsorships' => 'Active sponsorship records',
    ],
    'regions' => [
        'heading' => 'Families by region',
        'family_share' => 'Share of families in :region',
    ],
    'recent' => [
        'families' => 'Recent families',
        'aid' => 'Recent aid distributions',
    ],
    'empty' => [
        'regions' => 'No regional data yet.',
        'families' => 'No families have been recorded yet.',
        'aid' => 'No aid distributions have been recorded yet.',
    ],
    'fallback' => [
        'unlinked_case' => 'Unlinked case',
        'no_reference' => 'No reference',
    ],
];
