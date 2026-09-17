<?php

return [
    'title' => 'Users',
    'eyebrow' => 'Access control',
    'introduction' => 'Manage workspace access and account status.',
    'caption' => 'Workspace users',
    'actions' => [
        'add' => 'Add user',
        'edit' => 'Edit',
        'edit_user' => 'Edit user',
        'deactivate' => 'Deactivate',
        'search' => 'Search',
        'back' => 'Back to users',
        'save' => 'Save user',
        'cancel' => 'Cancel',
    ],
    'filters' => [
        'label' => 'Search users',
        'placeholder' => 'Name or email',
        'clear' => 'Clear search',
    ],
    'fields' => [
        'name' => 'Name',
        'full_name' => 'Full name',
        'email' => 'Email',
        'role' => 'Role',
        'status' => 'Status',
        'actions' => 'Actions',
        'password' => 'Password',
        'new_password' => 'New password',
        'account_status' => 'Account status',
    ],
    'form' => [
        'introduction' => 'Keep roles and account status explicit.',
        'password_help' => 'At least 8 characters.',
    ],
    'confirm_deactivate' => 'Deactivate this user?',
    'empty' => [
        'heading' => 'No users found',
        'description' => 'Add a workspace user to get started.',
    ],
];
