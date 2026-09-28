# GreenDot — CLAUDE.md

GreenDot is a Laravel 13 web application for PlayStation players to discover and connect with other players. It acts as a "doorgeefluik": players find each other here, actual contact happens via PSN. See `green-dot-plan.md` for the full product plan.

## Stack

- **PHP 8.5** / Laravel 13
- **MySQL** (production), SQLite (local dev / tests)
- **Vite** for asset bundling
- **SCSS** for all styling — never plain CSS, never inline styles
- **lorisleiva/laravel-actions** for business logic
- **IGDB API** (via Twitch credentials) for the game database
- **Laravel Scout + Meilisearch** for search

## Commands

```bash
php artisan serve                   # Local dev server (http://127.0.0.1:8000)
npm run dev                         # Vite dev server with hot reload
npm run build                       # Production asset build
php artisan test                    # Run test suite
./vendor/bin/pint                   # Fix code style (Laravel Pint)
php artisan migrate                 # Run pending migrations
php artisan migrate:fresh --seed    # Reset database with seeders
```

## Architecture

### Controllers — HTTP only

Controllers handle HTTP concerns only: receive a request, delegate to one Action, return a response. No business logic, no database queries, no conditionals beyond choosing a response type.

```php
// Correct
class ProfileController extends Controller
{
    public function store(StoreProfileRequest $request): RedirectResponse
    {
        CreateProfileAction::run($request->validated());

        return redirect()->route('profiles.show', auth()->user());
    }
}

// Wrong — business logic belongs in an Action
class ProfileController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([...]);
        $profile = Profile::create([...]);
        $profile->games()->attach($request->games);
        ...
    }
}
```

Controllers may only contain the seven standard resource methods: `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`. Any route that does not map to one of these gets a dedicated single-action controller with `__invoke`.

### Actions — all business logic

Business logic lives in Action classes using `lorisleiva/laravel-actions`. One action per operation, named after what it does.

**Location:** `app/Actions/{Domain}/{ActionName}.php`

```php
use Lorisleiva\Actions\Concerns\AsAction;

class CreateProfileAction
{
    use AsAction;

    public function handle(array $data, User $user): Profile
    {
        $profile = $user->profile()->create([...]);
        $profile->games()->attach($data['games'] ?? []);

        event(new ProfileCreated($profile));

        return $profile;
    }
}
```

Call with `CreateProfileAction::run(...)`. Actions may dispatch Events for side effects. They never return HTTP responses.

### Form Requests — all validation

All input validation lives in Form Request classes, never in controllers or actions.

**Location:** `app/Http/Requests/{Domain}/{OperationName}Request.php`

Examples: `StoreProfileRequest`, `UpdateGameListRequest`, `VerifyPsnRequest`

### Policies — all authorization

All authorization checks go through Policy classes. Never check permissions inline in a controller or Blade view.

### Events & Listeners — side effects

Sending emails, updating counters, logging: these are side effects that belong in Listeners triggered by Events, not directly in Actions or Controllers.

### Models — data mapping only

Models contain: relationships, scopes, casts, accessors/mutators. No business logic. Never put complex operations or multi-step processes inside a model method.

### DTOs

Use simple readonly PHP classes when passing structured data between layers. Place them in `app/Data/`.

## Styling

- **SCSS only.** All styles in `resources/scss/`. No plain `.css` files.
- **No inline styles.** `style=""` attributes are never allowed in Blade views or components.
- **No hardcoded values.** Colors, spacing, and breakpoints are defined as SCSS variables in `_variables.scss`. Never write raw hex codes or pixel values elsewhere.

Directory structure:

```
resources/scss/
  app.scss              # Entry point — imports everything
  _variables.scss       # Color tokens, spacing, typography
  _base.scss            # Reset and base HTML element styles
  components/           # Per-component stylesheets (_card.scss, _button.scss)
  layouts/              # Page layout styles (_nav.scss, _sidebar.scss)
```

## PHP Conventions

Use PHP 8.5 features throughout:

- **Constructor property promotion** for all value objects and simple classes
- **Enums** for any fixed set of values (e.g. post types, verification status, region)
- **Match expressions** instead of switch/else chains
- **Readonly properties** for DTOs and value objects
- **Typed properties** — all class properties must carry a type declaration
- **Return types** — all methods declare a return type, including `void`
- **Named arguments** where they improve readability over positional ones

Do not use `array` as a return type when a typed collection or DTO is feasible.

## File & Class Naming

| Type | Location | Example |
|---|---|---|
| Controller | `app/Http/Controllers/{Domain}/` | `ProfileController` |
| Single-action controller | `app/Http/Controllers/{Domain}/` | `VerifyPsnController` |
| Action | `app/Actions/{Domain}/` | `CreateProfileAction` |
| Form Request | `app/Http/Requests/{Domain}/` | `StoreProfileRequest` |
| Policy | `app/Policies/` | `ProfilePolicy` |
| Event | `app/Events/` | `ProfileCreated` |
| Listener | `app/Listeners/` | `SendWelcomeNotification` |
| DTO | `app/Data/` | `ProfileData` |
| Enum | `app/Enums/` | `PostType`, `VerificationStatus` |
| Model | `app/Models/` | `Profile`, `Game` |

## Hard rules

- No `style=""` attributes anywhere in Blade views or components
- No business logic in controllers — one Action call maximum per controller method
- No raw SQL when Eloquent handles it; use query scopes for reusable filters
- No plain `.css` files — use `.scss`
- No `dd()` or `dump()` in committed code
- No credentials or API keys hardcoded — always `.env`
- No `Request $request` with inline `$request->validate()` in controllers — always use Form Requests
- No `switch` statements — use `match` expressions
