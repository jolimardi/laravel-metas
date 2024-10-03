<?php

namespace JoliMardi\Metas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Meta;
use Symfony\Component\Yaml\Yaml;

class UpdateMetaTable extends Command {

    protected $signature = 'metas:update {--load-config= : Spécifiez un fichier YML de configuration pour charger les metas}';


    protected $description = 'Remplir la table metas avec les routes actuelles sans modifier celles déjà existantes + charger les metas de config/metas.yml si dispo, ou option --load-config avec le chemin du fichier';


    public function handle() {

        $configFile = config_path('metas.yml');

        // Récupérer l'option --load-config
        if ($this->option('load-config')) {
            if (!file_exists($configFile)) {
                $this->error("Le fichier de configuration spécifié par --load-config n'existe pas : {$configFile}");
                return Command::FAILURE;
            }
            $configFile = $this->option('load-config');
        }

        // Il y a déjà des metas dans config/metas.yml ? On les importe
        $this->importMetasFromYml($configFile);


        // Récupérer toutes les routes
        $routes = Route::getRoutes();

        foreach ($routes as $route) {

            if (in_array('web', $route->middleware())) {  // Filtre sur les routes utilisant le middleware "web"

                // Récupérer le nom de la route
                $routeName = $route->getName();

                // Vérifier si la route a un nom, car certaines routes peuvent ne pas en avoir
                if (!$routeName) {
                    continue;
                }

                // Vérifier si une entrée existe déjà pour cette route
                $existingMeta = Meta::where('routename', $routeName)->first();

                if (!$existingMeta) {
                    // Créer une nouvelle entrée dans la table metas si elle n'existe pas
                    Meta::create([
                        'routename' => $routeName,
                        'uri' => $route->uri(),
                        'title' => null,
                        'description' => null,
                    ]);

                    $this->info("Meta ajoutée pour la route: $routeName");
                } else {
                    $this->info("Meta déjà existante pour la route: $routeName, aucune modification effectuée.");
                }
            }
        }

        return Command::SUCCESS;
    }


    /**
     * Importer les metas à partir du fichier config/metas.yml.
     */
    protected function importMetasFromYml($filePath) {

        // Vérifier si le fichier YML existe
        if (!file_exists($filePath)) {
            $this->info("Aucun fichier YML trouvé à l'emplacement: $filePath, on continue sans importer les metas du fichier YML.");
            return;
        }

        // Charger le fichier YML
        $metaData = Yaml::parseFile($filePath);

        // Anciennement 'default' devient '_default_'
        if (isset($metaData['default'])) {
            $metaData['_default_'] = $metaData['default'];
            unset($metaData['default']);
        }

        if (!isset($metaData['_default_'])) {
            $metaData['_default_'] = [
                'title' => 'Titre par défaut',
                'description' => 'Description par défaut',
            ];
        }
        // Traiter chaque route définie dans le fichier YML
        foreach ($metaData as $routeName => $meta) {
            // Vérifier si une entrée existe déjà pour cette route
            $existingMeta = Meta::where('routename', $routeName)->first();

            if (!$existingMeta) {
                // Créer une nouvelle entrée dans la table metas si elle n'existe pas
                Meta::create([
                    'routename' => $routeName,
                    'uri' => null, // On laisse null pour l'URI car ce n'est pas spécifié dans le fichier YML
                    'title' => isset($meta['title']) ? $meta['title'] : $metaData['_default_']['title'],
                    'description' => isset($meta['description']) ? $meta['description'] : $metaData['_default_']['description'],
                ]);

                $this->info("Meta ajoutée à partir du fichier YML pour la route: $routeName");
            } else {
                $this->info("Meta déjà existante pour la route (fichier YML): $routeName, aucune modification effectuée.");
            }
        }
    }



}
