# Reporting query notes

Reports and dashboard cards are calculated from Eloquent queries against PostgreSQL; no operational count is hard-coded in the UI.

```sql
-- Families by governorate
select governorate, count(*) as total
from families
where deleted_at is null
group by governorate
order by total desc;

-- Families without an active provider
select count(*)
from families
where deleted_at is null
  and provider_status in ('no_provider', 'deceased_provider', 'missing_provider', 'disabled_provider');

-- Monthly aid distributions
select count(*)
from aid_distributions
where distribution_date >= date_trunc('month', current_date)
  and distribution_date < date_trunc('month', current_date) + interval '1 month';
```

Eloquent uses `with()`/`withCount()` for related list pages to avoid N+1 queries, `paginate()` for bounded result sets, and `cursor()` for streaming CSV exports.
