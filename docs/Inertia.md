# Inertia.js Integration

## Page Resolution

The package automatically discovers Inertia pages from module directories.

```php
return Inertia::render('Billing::Dashboard');
```

The `Billing::Dashboard` syntax resolves to:

```
Modules/Billing/Resources/js/Pages/Dashboard.tsx
```

### Nested Pages

```php
return Inertia::render('Billing::Invoices/Index');
```

Resolves to:

```
Modules/Billing/Resources/js/Pages/Invoices/Index.tsx
```

### Supported Extensions

- `.tsx`
- `.jsx`
- `.ts`
- `.js`

## No Manual Registry

Unlike other solutions, you never need to edit:

```tsx
// resources/js/app.tsx
```

or register pages manually.

The package discovers all module pages automatically at runtime (development) or from the manifest (production).

## Helper API

```php
use Synetro\LaravelModules\Facades\Modules;

// Check if a page exists
$page = Modules::frontend()->resolve('Billing', 'Dashboard');

// Get all pages
$pages = Modules::frontend()->pages('Billing');
```

## Module Metadata

Declare module frontend configuration in `module.json`:

```json
{
    "name": "Billing",
    "slug": "billing",
    "frontend": {
        "entry_points": ["Resources/js/Pages"],
        "layouts": ["Resources/js/Layouts"]
    }
}
```

## TypeScript Support

Module pages are full TypeScript. No extra configuration needed.

Use module-local components with clean imports:

```tsx
import InvoiceCard from '@/Modules/Billing/Components/InvoiceCard'
```

## Layouts

Module layouts work automatically:

```tsx
// Modules/Billing/Resources/js/Layouts/BillingLayout.tsx
export default function BillingLayout({ children }) {
    return <div className="billing-layout">{children}</div>;
}
```

## Hooks

Module-specific React hooks:

```
Modules/Billing/Resources/js/hooks/useInvoices.ts
```
