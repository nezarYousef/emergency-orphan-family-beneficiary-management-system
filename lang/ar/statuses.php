<?php

return [
    'provider' => [
        'has_provider' => 'يوجد معيل',
        'no_provider' => 'لا يوجد معيل',
        'deceased_provider' => 'المعيل متوفى',
        'missing_provider' => 'المعيل مفقود',
        'disabled_provider' => 'المعيل من ذوي الإعاقة',
    ],
    'vulnerability' => [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'مرتفعة',
        'critical' => 'حرجة',
    ],
    'sponsorship' => [
        'sponsored' => 'مكفول',
        'not_sponsored' => 'غير مكفول',
        'pending' => 'قيد الانتظار',
    ],
    'aid' => [
        'cash' => 'مساعدة نقدية',
        'food' => 'مساعدة غذائية',
        'medical' => 'مساعدة طبية',
        'education' => 'مساعدة تعليمية',
        'hygiene' => 'مستلزمات النظافة',
    ],
    'roles' => [
        'admin' => 'مدير النظام',
        'data_entry' => 'موظف إدخال البيانات',
        'viewer' => 'مستخدم للعرض فقط',
    ],
    'gender' => [
        'male' => 'ذكر',
        'female' => 'أنثى',
    ],
    'beneficiary_type' => [
        'child' => 'طفل',
        'adult' => 'بالغ',
        'elderly' => 'كبير سن',
        'person_with_disability' => 'شخص من ذوي الإعاقة',
        'caregiver' => 'مقدم رعاية',
        'other' => 'أخرى',
    ],
    'orphan' => [
        'paternal' => 'يتيم الأب',
        'maternal' => 'يتيم الأم',
        'double_orphan' => 'يتيم الأبوين',
        'active' => 'نشط',
    ],
    'parent' => [
        'alive' => 'على قيد الحياة',
        'deceased' => 'متوفى',
        'missing' => 'مفقود',
    ],
    'relationship' => [
        'child' => 'ابن أو ابنة',
        'family member' => 'فرد من الأسرة',
        'relative' => 'أحد الأقارب',
    ],
    'school' => [
        'enrolled' => 'ملتحق بالمدرسة',
        'needs follow-up' => 'بحاجة إلى متابعة',
    ],
    'education' => [
        'enrolled' => 'ملتحق بالتعليم',
        'needs follow-up' => 'بحاجة إلى متابعة',
        'unknown' => 'غير محدد',
    ],
    'health' => [
        'unknown' => 'غير محدد',
    ],
    'housing' => [
        'unknown' => 'غير محدد',
    ],
    'account' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        1 => 'نشط',
        0 => 'غير نشط',
    ],
    'disability' => [
        1 => 'نعم',
        0 => 'لا',
    ],
    'audit_action' => [
        'created' => 'إنشاء',
        'updated' => 'تحديث',
    ],
    'model_type' => [
        'Family' => 'أسرة',
        'Beneficiary' => 'مستفيد',
        'Orphan' => 'يتيم',
        'AidDistribution' => 'توزيع مساعدة',
        'User' => 'مستخدم',
    ],
    'governorate' => [
        'North Gaza' => 'شمال غزة',
        'Gaza' => 'غزة',
        'Deir al-Balah' => 'دير البلح',
        'Khan Younis' => 'خان يونس',
        'Rafah' => 'رفح',
    ],
    'unknown' => 'غير محدد',
];
