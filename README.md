# laravel-metas

Composant pour configurer les metas du site via un une table metas (et avec la Resource Nova associée)

```
composer require jolimardi/laravel-metas
php artisan vendor:publish --provider="JoliMardi\Metas\MetasServiceProvider"
php artisan migrate
php artisan metas:update
```

Et modifier `app/Http/Controllers/Controller.php` pour exemple :

```
<?php

namespace App\Http\Controllers;

use JoliMardi\Metas\Services\MetasService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public function __construct() {

        // Chargement des variables globales utilisables dans toutes les vues, et overridables dans les controllers
        View::share('title', MetasService::getTitle());
        View::share('description', MetasService::getDescription());
    }
}
``` 
