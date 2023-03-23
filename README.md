<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Laravel Template Repository

Laravel template repository which serves as a starting point for new projects


### Table of Contents
<ul>
    <li>
        <a href="#technology-stack">Technology Stack</a>
    </li>
    <li>
        <a href="#getting-started">Getting started</a>
    </li>
    <li>
        <a href="#usage">Usage</a>
        <ul>
            <li>
                <a href="#setup-project">Setup Project</a>
            </li>
            <li>
                <a href="#stubs-and-configurations">Stubs And Configurations</a>
            </li>
            <li>
                <a href="#default-user">Default User</a>
            </li>
            <li>
                <a href="#roles-and-permissions">Roles and Permissions</a>
            </li>
            <li>
                <a href="#filament">Filament</a>
            </li>
            <li>
                <a href="#settings">Settings</a>
            </li>
            <li>
                <a href="#assets">Assets</a>
            </li>
            <li>
                <a href="#blade">Blade</a>
            </li>
            <li>
                <a href="#handling-requests">Handling Requests</a>
            </li>
            <li>
                <a href="#code-structure-and-splitting">Code Structure and Splitting</a>
            </li>
            <li>
                <a href="#code-styling-and-consintency">Code Styling and Consintency</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#staging">Staging</a>
    </li>
    <li>
        <a href="#production">Production</a>
    </li>
</ul>


### Technology Stack

- [Laravel framework](https://laravel.com/docs/9.x) v9.x
- [PHP](https://www.php.net) v8.1
- [Vite.js](https://vitejs.dev) v3.x
- [Tailwind CSS](https://tailwindcss.com) v3.x
- [Alpine.js](https://alpinejs.dev) v3.x
- [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) v2.x
- [maatwebsite/excel](https://github.com/SpartnerNL/Laravel-Excel) v2.x
- [filament/filament](https://github.com/filamentphp/filament) v2.x
- [spatie/laravel-sluggable](https://github.com/spatie/laravel-sluggable) v3.x


### Getting started

1. Create a new repo from GitHub using this one as a template
2. Clone the new repository locally
    ```bash
    git clone repo_url
    ```

3. Copy *`.env.example`* in *`.env`*
    ```bash
    cp .env.example .env
    ```

4. Install dependencies from *`composer.json`* and *`package.json`* and compile assets using Vite
    ```bash
    composer install && npm i && npm run build
    ```

5. Generate **APP_KEY** in *`.env`* file
    ```bash
    php artisan key:generate
    ```

6. [not mandatory] Run *`valet secure`* command (this will generate a TLS certificate for the application, and serve via https)
    ```bash
    valet secure app_name
    ```

7. Configure **APP_URL** in *`.env`* file (if you ran point 6 command, you need to modify to https)
    ```bash
    APP_URL=http://app_name.test
    ```

8. Configure **host** in *`vite.config.js`* file (if you ran point 6 command, you need to modify to https)
    ```js
    let host = 'http://app_name.test'
    ```

9. Configure database connection in *`.env`* file
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=database_name_here
    DB_USERNAME=root
    DB_PASSWORD=
    ```

10. Run migrations and seeders
    ```bash
    php artisan migrate --seed
    ```

11. Run filament-shield install command and press enter `↵` on the confirmation prompt, and then type `1` to assign the role of `super_admin`
    ```bash
    php artisan shield:install --fresh
    ```

12. Run `role:assign` command to assign the role of `admin` to the correct user
    ```bash
    php artisan role:assign admin 2
    ```

13. Open the browser and visit your fresh new app 🔥
    ```txt
    app_name.test
    ```

### Usage

#### Setup Project
To start working you can generate your models with artisan command:
```bash
php artisan make:model ModelName -mfs
```
Next configure your models (define all relationships with correct return types) and migrations (define all tables columns).

Next use the command below that bootstraps the entire project base complete with Filament resources, Service classes with validation rules, Api Controllers, JSON resources and form requests.
To use this command, it is necessary that all models be set as well as all the migrations because it reades directly from the database tables.
It's also important that all models have the proper relationships set up with the correct return type.
```bash
php artisan project:setup --filament --service --api --resource
```

#### Stubs And Configurations
All the files generated from artisan commands are created using the `stubs` which are customized a little in this template. <br>
The base is coming from [spatie/laravel-stubs](https://github.com/spatie/laravel-stubs) and it's customized on top. <br>
What's changed:
- Model
    - `HasSlug` trait and `getSlugOptions()` function to automatically generate slug.
    - `SoftDeletes` trait.
    - `HasHelperFunctionsAndScopes` trait that contains a bunch of useful functions and eloquent scopes.
    - `HasExportColumns` trait. You can optionally define the `$exportColumns` protected property as an array of model attributes that should be used when exporting excel file from `FilamentExcelExport`. You can also add relationships, labels and choose if the values from the relation should be summed (`sum_values` is `false` by default so it's not necessary to specify it).
    - `HasRelationships` trait contains a `getRelationships()` method that returns a list of relationships with return types
        ```php
        // suppose this is an export from UserResource for an ecommerce order
        protected $exportColumns = [
            [
                'value' => 'order.id',
                'label' => 'order n°',
            ],
            [
                'value' => 'order.date',
                'label' => 'date',
            ],
            [
                'value' => 'order_items.name',
                'label' => 'products',
            ],
            [
                'value' => 'order_items.final_price',
                'label' => 'price',
            ],
            [
                'value' => 'order.total',
                'label' => 'order total',
            ],
            [
                'value' => 'order_items.discount_value',
                'label' => 'total discount',
                'sum_values' => true
            ],
            'name',
            'email',
        ];
        ```
        The labels will be transformed using `str()->title()` or `str()->headline()`.
        The relationships that have multiple values will be concatened as a string on new lines but you can customize that to your liking in `config/filament-admin` by modifying:
        ```php
        'concatenate_relations_with' => "\n"
        ```
        If `$exportColumns` is not provided the export will default to the table schema of the resource. <br> This default behaviour can be changed in `config/excel` by setting `'default_export_from_table' => false` in which case the export will default to `$fillable` attributes.
    - by default all attributes are mass assignable except `id, created_at and updated_at`. It's best practice that you go ahead and remove `$guarded` and populate `$fillable` with all attributes (if you don't and you have other fields that you don't want to be mass assignable remember to add them in `$guarded`)
        ```php
        protected $guarded = [
            'id',
            'created_at',
            'updated_at',
        ];
        ```
    - `AppServiceProvider` is configured with `Model::shouldBeStrict(app()->isLocal())` which will make Laravel throw an exception when attempting to fill an unfillable attribute which would otherwise be discarded silently, or when trying to lazy load a relationship or when trying to access a missing attribute.
    - remember to eager load relationships when you need them like this:
        ```php
        User::with('roles')->get();
        ```
    - populate `$casts` with the correct values to have the expected behaviour when you use eloquent built-in functions like `$model->wasChanged()`, for example:
        ```php
        protected $casts = [
            'last_login' => 'datetime',
            'active' => 'boolean',
        ];
        ```
- Controller
    - extend `Controller` class.
    - api controller has a boilerplate for api resource.
- JSONResource
    - excludes default attributes using `HasAttributesToExclude` trait.
    - can exclude additional attributes using `getAdditionalAttributesToExclude()` function.
    - automatically includes relations with their JsonResource when loaded using `CanIncludeRelationships` trait.
- Migration
    - `created_by`, `updated_by` are populated automatically using `HasSignature` trait.
    - `deleted_at` uses laravel default `SoftDeletes` trait.
- Filament
    - Resource
        - `getModelLabel()` and `getPluralModelLabel()` to automatically set the labels based on the model class name.
        - `getRecordTitleAttribute()` to automatically find the `$recordTitleAttribute`.
        - `FilamentExcelExport` excel export with custom class and logic.
            - You can optionally define the `$exportColumns` protected property on the model class as an array of attributes that should be used when exporting the excel file. <br> If `$exportColumns` is not provided the export will default to the table schema of the resource. <br> This default behaviour can be changed in `config/filament-admin` by setting.
                ```php
                'excel' => [
                    'export_from_table' => false,
                ],
                ```
                in which case the export will default to `$fillable` attributes.
                You can configure this export to open a modal with a default form (select range of dates that queries the model using `->whereBetween()` and the date column that is detected from `->getDateColumn()` function from the `HasHelperFunctionsAndScopes` trait) using:
                ```php
                ExportAction::make('export_with_dates')
                    ->exports([
                        FilamentExcelExport::make('modal')
                            ->setDefaultFormSchema()
                    ])
                ```
        - table actions are configured to hide the text and render only the icon.
            ```php
            Action::configureUsing(function (Action $action) {
                $action->iconButton();
            });
            ```
            if you want to disable this behaviour simply comment out this configuration in `AppServiceProvider`.
    - ResourceListPage
        - `ImportAction` allows you to import records from a `.xlsx` file by defining the fields like this: 
            ```php
            ImportAction::make()
                ->fields([
                    ImportField::make('name'),
                    ImportField::make('email'),
                    ImportField::make('password'),
                ])
            ```
        - If needed you can mutate data before saving like this
            ```php
            ->mutateBeforeCreate(function ($row) {
                $row['password'] = Hash::make($row['password']);

                return $row;
            })
            ```
    - UserResource
        - the table it's configured with `Impersonate` action which let's a user act as another without using the login credentials. The permissions are controlled by two functions in the `User` model:
            ```php
            public function canImpersonate(): bool
            {
                $roles = [
                    'allowed' => [
                        'super_admin',
                    ],
                ];

                return $this->canAccessFilament()
                    && $this->hasRole($roles['allowed']);
            }

            public function canBeImpersonated(): bool
            {
                $roles = [
                    'not_allowed' => [
                        'super_admin',
                    ],
                    'allowed' => [
                        'admin',
                    ],
                ];

                return $this->canAccessFilament()
                    && $this->hasRole($roles['allowed'])
                    && !$this->hasRole($roles['not_allowed']);
            }
            ```
    - Macro
        - Columns
            - Column
                - `->linkRecord()` custom function to allow click on the column value to link to the related model edit form.
                    ```php
                    TextColumn::make('category.name')
                        ->linkRecord(),
                    ```
            - IconColumn
                - `->toggle()` custom function to configure a column so the user can toggle between `true` and `false` values on a boolean field without entering the form.
                    ```php
                    IconColumn::make('active')
                        ->boolean()
                        ->toggle(),
                    ```

#### Default User
After running migrations and seeders you can access filament admin panel with the default credentials, that you can find in `config/users`. If `APP_ENV=local` the login form will be prepopulated with the default credentials. <br>
Make sure to customize the rule/function [canAccessFilament()](https://filamentphp.com/docs/2.x/admin/users#authorizing-access-to-the-admin-panel) in the User model that checks permissions for users to enter the admin panel. By default the access is checked on the user role/roles defined in `$allowedRoles`.

#### Roles and Permissions
`spatie/laravel-permission` is installed together with `bezhansalleh/filament-shield` to manage roles and permissions. <br>
If you want to generate permissions/policies for all entities you can run `php artisan shield:generate --all`. <br>
There is a custom console command that you can use to assign a role to one or multiple users ex. `php artisan role:assign admin 2 3 4` will assign admin role to user with id 2, 3, 4. <br>
Insted of the `user_ids` you can pass the `--all` or `-A` flag to assign that role to all current users in the database.
```bash
php artisan role:assign {role} {user_ids}
```

#### Filament
To generate a filament resource run:
```bash
php artisan make:filament-resource ResourceName --generate --soft-deletes
```
- Theming
    - the `primary`, `secondary` and `accent` color of filament admin panel it's taken from `CompanySettings`.
    - it's possible to customize colors of filament admin panel by changing this configuration in `tailwind.config.js`, you can choose any color from tailwind [colors](https://tailwindcss.com/docs/customizing-colors).
        ```js
        colors: { 
            primary: {
                50: 'rgb(var(--theme-primary-color-var-50) / <alpha-value>)',
                100: 'rgb(var(--theme-primary-color-var-100) / <alpha-value>)',
                200: 'rgb(var(--theme-primary-color-var-200) / <alpha-value>)',
                300: 'rgb(var(--theme-primary-color-var-300) / <alpha-value>)',
                400: 'rgb(var(--theme-primary-color-var-400) / <alpha-value>)',
                500: 'rgb(var(--theme-primary-color-var-500) / <alpha-value>)',
                600: 'rgb(var(--theme-primary-color-var-600) / <alpha-value>)',
                700: 'rgb(var(--theme-primary-color-var-700) / <alpha-value>)',
                800: 'rgb(var(--theme-primary-color-var-800) / <alpha-value>)',
                900: 'rgb(var(--theme-primary-color-var-900) / <alpha-value>)',
            },
            secondary: {
                50: 'rgb(var(--theme-secondary-color-var-50) / <alpha-value>)',
                100: 'rgb(var(--theme-secondary-color-var-100) / <alpha-value>)',
                200: 'rgb(var(--theme-secondary-color-var-200) / <alpha-value>)',
                300: 'rgb(var(--theme-secondary-color-var-300) / <alpha-value>)',
                400: 'rgb(var(--theme-secondary-color-var-400) / <alpha-value>)',
                500: 'rgb(var(--theme-secondary-color-var-500) / <alpha-value>)',
                600: 'rgb(var(--theme-secondary-color-var-600) / <alpha-value>)',
                700: 'rgb(var(--theme-secondary-color-var-700) / <alpha-value>)',
                800: 'rgb(var(--theme-secondary-color-var-800) / <alpha-value>)',
                900: 'rgb(var(--theme-secondary-color-var-900) / <alpha-value>)',
            },
            accent: {
                50: 'rgb(var(--theme-accent-color-var-50) / <alpha-value>)',
                100: 'rgb(var(--theme-accent-color-var-100) / <alpha-value>)',
                200: 'rgb(var(--theme-accent-color-var-200) / <alpha-value>)',
                300: 'rgb(var(--theme-accent-color-var-300) / <alpha-value>)',
                400: 'rgb(var(--theme-accent-color-var-400) / <alpha-value>)',
                500: 'rgb(var(--theme-accent-color-var-500) / <alpha-value>)',
                600: 'rgb(var(--theme-accent-color-var-600) / <alpha-value>)',
                700: 'rgb(var(--theme-accent-color-var-700) / <alpha-value>)',
                800: 'rgb(var(--theme-accent-color-var-800) / <alpha-value>)',
                900: 'rgb(var(--theme-accent-color-var-900) / <alpha-value>)',
            },
            success: colors.green,
            warning: colors.yellow,
            danger: colors.rose,
        },
        ```
        in the frontend you can use `primary`, `secondary` and `accent` colors.
        ```html
        <button class="bg-primary-200">Click me</button>
        <span class="text-secondary-500">I'm awesome!</span>
        <span class="text-accent-700">Let's go!</span>
        ```
    - you can choose to show a logo in the top left of the admin panel sidebar in `config/filament-admin`. Logo can be configured in `brand.blade.php`.
        ```php
        'sidebar' => [
            'should_show_logo' => true
        ],
        ```
        ```html
        <img src="{{ asset('/images/logo.svg') }}" alt="{{ str(config('app.name') . ' logo')->slug() }}" class="h-10">
        ```

#### Settings &bull; [Documentation](https://filamentphp.com/docs/2.x/spatie-laravel-settings-plugin/getting-started)
- CompanySettings
    - is using [Spatie's laravel settings](https://github.com/spatie/laravel-settings) and the [Filament spatie laravel settings plugin](https://github.com/filamentphp/spatie-laravel-settings-plugin) to create a settings page to manage basic configuration settings for the company. <br>
    If you need to use these data anywhere in the application you can do it like so:
        ```php
        $companySettings = CompanySettings::make();
        $companyColors = $companySettings->colors;
        ```

#### Assets
When developing frontend you want to leave the Vite server running using `npm run dev` command, but when your finished remember to run `npm run build` to build your assets.

#### Blade
- Directives
    - `@money` will format numbers as money (ex: `@money($number, '€')`). 
    Accepts two arguments:
        1. `$number`
        2. `$currency` (optional, default is `€`)

#### Handling Requests
Inside `docs` folder there's a `.excalidraw` file that contains the basic logic flow of how the requests should be handled
To view and modify the file inside VS Code you can install this [extension](https://marketplace.visualstudio.com/items?itemName=pomdtr.excalidraw-editor).

#### Code Structure and Splitting &bull; [Refactoring example](https://laravel-news.com/controller-refactor)
The goal of structuring the code using multiple classes like `Services`, `Actions`, `FormRequests`, `Events`, `Listeners`, `Observers`, `Traits`, `Interfaces` is to achieve a codebase that doesn't repeat methods in multiple places and it's also easier to understand and fix or to implement new features.

- Controllers
    - Controller methods need to do three things:
        - Accept the parameters from routes or other inputs
        - Call some logic classes/methods, passing those parameters
        - Return the result: view, redirect, JSON return, etc.
    
    So, controllers are calling the methods, not implementing the logic inside the controller itself.

- Form Requests
    - Can be generated using artisan `make:request` command
        ```bash
        php artisan make:request StoreUserRequest
        ```
    - Move our validation rules from the controller to that class.

- Services
    - Base `Service` class under `App\Services` namespace.
    - Services methods should act like a "black box" that just accepts the parameters and doesn't know where those come from. So this method would be possible to be called from a Controller, from Artisan command, or a Job, in the future.
    - Methods should follow the `Single Responsibility` principle. <br>
    All methods should accept parameters, perfom one action/task using those parameters and return something.
    - `php artisan make:service` command that creates a new service class from stub. <br>
        the first example generate a service class for the `User` model, while the second generates a basic service class:
        ```bash
        php artisan make:service UserService --model
        or
        php artisan make:service MailHelper
        ```
        optionally (but highly recommended) you can pass the `--validation` flag to automatically create the validation rules for that model (this flag works only when creating a service for a model, ex: UserService will work, HelperService won't work)
        ```bash
        php artisan make:service UserService --validation
        ```
    - Utilization method 1
        ```php
        $user = User::find($userId);
        $userService = UserService::make($user);

        // then you can call the methods by chaining onto the instanciated service 
        // (inside the function in the service you can refer to the model instance using $this->modelInstance)
        $userService->customFunction();
        ```
    - Utilization method 2 (preferred as follows the `Single Responsibility` principle explained earlier)
        ```php
        // you instanciate the service without passing something in the make() constructor
        $userService = UserService::make();

        // method injection docs (https://laravel.com/docs/9.x/container#automatic-injection)
        // or you can instanciate the service by type-hinting it inside the method in which you call it
        public function store(UserService $userService): void
        {
            $userService->customFunction();
        }

        // then you call the functions passing all the parameters
        $userService->customFunction();
        ```

- Actions
    - `php artisan make:action` command that creates a new action class from stub.
    - Actions are classes whose only purpose is to execute one single action (ex: `UpdateUserSubscriptionAction`).
    - Actions should not be responsible for firing events. They should just execute all the logic for a particular task and then return `true` or `false` so the event could be fired from the `Service` class where that action is called.
    - To use it type-hint the action in the method you need and call the execute function on the action instance:
        ```php
        public function handle(UpdateUserSubscriptionAction $updateUserSubscriptionAction)
        {
            // pass whatever params you need in the execute method
            $updateUserSubscriptionAction->execute();
        }
        ```

- Events
    - [Fire](https://laravel.com/docs/9.x/events#dispatching-events) custom events to maintain the code clean.
    - Events can be fired using something like the syntax provided by the `attribute-events` package shown below or from anywhere in the application, like from a `Service`.
        ```php
        OrderShipped::dispatch($order);
        OrderShipped::dispatchIf($condition, $order);
        OrderShipped::dispatchUnless($condition, $order);
        ```
    - The syntax below takes advantage of the [attribute-events](https://attribute.events/) package:
        ```php
        class Order extends Model
        {
            protected $dispatchesEvents = [
                'status:shipped' => OrderShipped::class,
                'note:*' => OrderNoteChanged::class,
            ];
        }
        ```

- Listeners
    - You can create one or multiple listenersto handle the fired event.
    - Listeners needs to be registered in `EventServiceProvider`.
    - ex: `UpdateUserAttributesOnLoginListener` or `DispatchLoginNotificationsListener` that handle the `Login` event fired from Laravel.
        ```php
        protected $listen = [
            Login::class => [
                UpdateUserAttributesOnLoginListener::class,
                DispatchLoginNotificationsListener::class,
            ],
        ];
        ```

- Observers
    - Observers let you handle model lifecycle hooks.

- Traits
    - `php artisan make:trait` command that creates a new action class from stub.
    - Traits are a mechanism for code reuse, is intended to reduce some limitations of single inheritance by enabling a developer to reuse sets of methods freely in several independent classes living in different class hierarchies.

- Contracts (Interfaces)
    - `php artisan make:interface` command that creates a new action class from stub.
    - Interfaces allow you to create code which specifies which methods a class must implement, without having to define how these methods are implemented.

#### Code Styling and Consintency
- Laravel Pint
    - `pint.yml` is a GitHub workflow that will run every time you push code on the remote repo and makes sure that the code is consintent and if not makes a new commit with the changes. So every time you start a new session of work run `git pull` to make sure you have the latest versionà

### Staging
Copy `.env.staging` and rename it `.env` to match staging requirements (you still need to configure points 2, 3, 4):
1. Environment `APP_ENV=staging`
2. Debug `APP_DEBUG=false`
3. Default user config
    ```bash
    DEFAULT_USER_STAGING_EMAIL="staging@test.com"
    DEFAULT_USER_STAGING_PASSWORD=
    ```
4. Database config
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    ```
5. Mail config
    ```bash
    MAIL_MAILER=smtp
    MAIL_HOST=0.0.0.0
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="staging@test.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

### Production
Copy `.env.production` and rename it `.env` to match production requirements (you still need to configure points 2, 3, 4):
1. Environment `APP_ENV=production`
2. Debug `APP_DEBUG=false`
3. Default user config
    ```bash
    DEFAULT_USER_PRODUCTION_EMAIL="production@test.com"
    DEFAULT_USER_PRODUCTION_PASSWORD=
    ```
4. Database config
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=
    ```
5. Mail config
    ```bash
    MAIL_MAILER=smtp
    MAIL_HOST=0.0.0.0
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="production@test.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```
