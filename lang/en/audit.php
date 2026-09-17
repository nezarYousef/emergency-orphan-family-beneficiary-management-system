<?php

return [
    'title' => 'Audit logs',
    'eyebrow' => 'Accountability',
    'introduction' => 'Admin-only history of sensitive record changes.',
    'caption' => 'Audit log entries',
    'search' => 'Search',
    'system' => 'System',
    'filters' => [
        'label' => 'Filter audit logs',
        'placeholder' => 'Action, model, description',
        'clear' => 'Clear filters',
    ],
    'fields' => [
        'time' => 'Time',
        'user' => 'User',
        'action' => 'Action',
        'model' => 'Record type',
        'description' => 'Description',
        'ip' => 'IP address',
    ],
    'descriptions' => [
        'Family created' => 'Family created',
        'Family updated' => 'Family updated',
        'Beneficiary created' => 'Beneficiary created',
        'Beneficiary updated' => 'Beneficiary updated',
        'Orphan created' => 'Orphan created',
        'Orphan updated' => 'Orphan updated',
        'Aid distribution created' => 'Aid distribution created',
        'Aid distribution updated' => 'Aid distribution updated',
    ],
    'empty' => [
        'heading' => 'No audit entries found',
        'description' => 'Activity will appear here as records change.',
    ],
];
