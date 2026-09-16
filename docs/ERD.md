# Data model

```mermaid
erDiagram
 USERS { bigint id PK string email UK string role boolean is_active }
 FAMILIES { bigint id PK string case_number UK string national_id UK string governorate string provider_status bigint created_by FK bigint updated_by FK }
 BENEFICIARIES { bigint id PK bigint family_id FK string beneficiary_number UK string national_id UK date date_of_birth string beneficiary_type bigint created_by FK bigint updated_by FK }
 ORPHANS { bigint id PK bigint beneficiary_id FK_UK bigint family_id FK string orphan_number UK string sponsorship_status }
 AID_DISTRIBUTIONS { bigint id PK bigint family_id FK bigint beneficiary_id FK string reference_number UK date distribution_date string aid_type bigint created_by FK bigint updated_by FK }
 AUDIT_LOGS { bigint id PK bigint user_id FK string action string model_type bigint model_id json old_values json new_values timestamp created_at }
 USERS ||--o{ FAMILIES : creates
 USERS ||--o{ BENEFICIARIES : creates
 USERS ||--o{ ORPHANS : creates
 USERS ||--o{ AID_DISTRIBUTIONS : records
 FAMILIES ||--o{ BENEFICIARIES : contains
 FAMILIES ||--o{ ORPHANS : contains
 BENEFICIARIES ||--o| ORPHANS : registers
 FAMILIES o|--o{ AID_DISTRIBUTIONS : receives
 BENEFICIARIES o|--o{ AID_DISTRIBUTIONS : receives
 USERS ||--o{ AUDIT_LOGS : performs
```

Core records use foreign keys, searchable indexes, PostgreSQL uniqueness constraints, and soft deletes for sensitive operational entities. Aid records must reference at least a family or a beneficiary. Nullable PostgreSQL unique columns allow multiple unknown values while preventing duplicate known identifiers.
