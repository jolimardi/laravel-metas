# laravel-metas

Composant pour configurer les metas du site via un fichier `.yml`

```
composer require jolimardi/laravel-metas
```

Puis publish le package 

```
php artisan vendor:publish --provider="JoliMardi\Metas\MetasServiceProvider"
```

Et modifier `app/Http/Controllers/Controller.php` pour exemple : 

```
<?php

namespace App\Http\Controllers;

use App\Services\MetasService;
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
