# Database Compatibility Guide (SQLite vs MySQL)

## Overview
This guide explains the database compatibility fixes applied to ensure the application works correctly on both SQLite (development) and MySQL (production).

## Common Issues and Solutions

### 1. Date/Time Filtering

#### ❌ AVOID: `whereYear()` and `whereMonth()`
```php
// This generates different SQL in SQLite vs MySQL
Payment::whereYear('created_at', 2024)
    ->whereMonth('created_at', 1)
    ->sum('amount');
```

#### ✅ USE: `whereBetween()` with Carbon
```php
$startOfMonth = Carbon::create(2024, 1, 1)->startOfMonth();
$endOfMonth = Carbon::create(2024, 1, 1)->endOfMonth();

Payment::whereBetween('created_at', [$startOfMonth, $endOfMonth])
    ->sum('amount');
```

### 2. Date Comparisons

#### ❌ AVOID: `whereDate()` for single date comparisons
```php
// Less efficient and can have compatibility issues
Attendance::whereDate('date', $today)->count();
```

#### ✅ USE: Direct date comparison
```php
// More efficient and works consistently
$today = now()->toDateString(); // '2024-01-15'
Attendance::where('date', $today)->count();
```

### 3. Raw SQL Queries

#### ❌ AVOID: Database-specific SQL functions
```php
// SUBSTRING and CAST work differently in SQLite vs MySQL
Student::orderByRaw('CAST(SUBSTRING(registration_no, -4) AS UNSIGNED) DESC')
    ->first();
```

#### ✅ USE: Laravel Query Builder
```php
// Use standard Laravel methods
Student::orderBy('id', 'desc')->first();
```

## Quick Reference

### Monthly Data Queries

```php
// Get data for a specific month
$month = Carbon::create(2024, 1, 1);
$startOfMonth = $month->copy()->startOfMonth();
$endOfMonth = $month->copy()->endOfMonth();

$data = Model::whereBetween('date_column', [$startOfMonth, $endOfMonth])
    ->get();
```

### Yearly Data Queries

```php
// Get data for a specific year
$year = 2024;
$startOfYear = Carbon::create($year, 1, 1)->startOfYear();
$endOfYear = Carbon::create($year, 12, 31)->endOfYear();

$data = Model::whereBetween('date_column', [$startOfYear, $endOfYear])
    ->get();
```

### Current Month/Year Queries

```php
// Current month
$currentMonth = now();
$data = Model::whereBetween('created_at', [
    $currentMonth->copy()->startOfMonth(),
    $currentMonth->copy()->endOfMonth()
])->get();

// Current year
$data = Model::whereBetween('created_at', [
    now()->startOfYear(),
    now()->endOfYear()
])->get();
```

## Testing Checklist

When adding new date-based queries, test:

- [ ] Works on SQLite (local development)
- [ ] Works on MySQL (production)
- [ ] Returns correct data for edge cases (month boundaries, year boundaries)
- [ ] Performance is acceptable (use indexes on date columns)

## Best Practices

1. **Always use Carbon for date manipulation**
   - Provides consistent behavior across databases
   - Makes code more readable and maintainable

2. **Prefer `whereBetween()` for date ranges**
   - Works consistently across all databases
   - More explicit about what you're querying

3. **Use date strings for single date comparisons**
   - More efficient than `whereDate()`
   - Works consistently across databases

4. **Avoid raw SQL when possible**
   - Use Laravel's query builder methods
   - Ensures database compatibility

5. **Test on both databases**
   - SQLite for development
   - MySQL for production
   - Catch compatibility issues early

## Migration Notes

If you need to add new date-based queries:

1. Use `whereBetween()` with Carbon date ranges
2. Avoid `whereYear()`, `whereMonth()`, `whereDay()`
3. Test on both SQLite and MySQL
4. Add indexes on date columns for performance

## Support

If you encounter database compatibility issues:

1. Check this guide for solutions
2. Review the spec: `.kiro/specs/fix-database-compatibility.md`
3. Look at existing code for examples
4. Test on both databases before deploying
