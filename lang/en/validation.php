<?php

return array_replace(
    require __DIR__.'/../../vendor/laravel/framework/src/Illuminate/Translation/lang/en/validation.php',
    [
        'beneficiary_family_mismatch' => 'The beneficiary must belong to the selected family.',
        'orphan_requires_child' => 'Only beneficiaries classified as children can have an orphan record.',
        'custom' => [
            'date_of_birth' => [
                'before_or_equal' => 'The date of birth must be today or earlier.',
            ],
        ],
        'attributes' => array_replace((require __DIR__.'/exports.php')['headings'], [
            'family_id' => 'family',
            'beneficiary_id' => 'beneficiary',
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
            'role' => 'role',
            'is_active' => 'account status',
            'remember' => 'remember me',
            'search' => 'search',
        ]),
    ]
);
