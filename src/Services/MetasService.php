<?php

namespace JoliMardi\Metas\Services;

use Illuminate\Support\Facades\Route;
use App\Models\Meta;

class MetasService {




    public static function getMeta(array $dynamic_vars_array = [], $force_routename = false): ?Meta {

        // On récupère la route actuelle
        if (!$force_routename) {
            $routename = self::getRouteName();
        } else {
            $routename = $force_routename;
        }


        // On récupère la méta pour cette route ou la méta par défaut
        $meta = Meta::where('routename', $routename)->select('title', 'description')->first();
        if (!$meta) {
            $meta = self::getDefautMeta();
            // Si pas de défaut, on lance une erreur
            if (!$meta) {
                throw new \ErrorException('No default meta found. Run `php artisan metas:update` to create a default meta and add routes to db');
            }
        }
        return $meta;
    }


    public static function getTitle(array $dynamic_vars_array = [], $force_routename = false): string {
        $meta = self::getMeta($dynamic_vars_array, $force_routename);
        if(empty($meta->title)) {
            $meta = self::getDefautMeta();
        }

        $title = $meta->title;

        // Remplacement des variables de type {{ $post->title }}
        if (count($dynamic_vars_array) > 0) {
            $title = self::replaceDynamicContent($title, $dynamic_vars_array);
        }
        return $title ?? '';
    }


    public static function getDescription(array $dynamic_vars_array = [], $force_routename = false): string {
        $meta = self::getMeta($dynamic_vars_array, $force_routename);
        if(empty($meta->description)) {
            $meta = self::getDefautMeta();
        }

        $description = $meta->description;

        // Remplacement des variables de type {{ $post->title }}
        if (count($dynamic_vars_array) > 0) {
            $description = self::replaceDynamicContent($description, $dynamic_vars_array);
        }
        return $description ?? '';
    }



    // Les variables doivent êtres passées en tableau comme pour les views, avec compact();
    public static function replaceDynamicContent($string, $array_of_data_vars) {
        if (count($array_of_data_vars) > 0) {
            $string = preg_replace_callback('/{{ (.*?) }}/', function ($matches) use ($array_of_data_vars) {
                extract($array_of_data_vars);
                $placeholder = $matches[0];
                $new_value = false;

                // On n'affiche pas les erreurs
                @eval('$new_value = ' . $matches[1] . ';');

                return $new_value ?? $placeholder;
            }, $string);
        }
        return $string;
    }



    public static function getDefautMeta(): Meta {
        $meta = Meta::where('routename', '_default_')->select('title', 'description')->first();
		if(!$meta){
			$meta = new Meta();
		}
        return $meta;
    }


    public static function getRouteName(): ?string {
        $routename = Route::currentRouteName();
        return $routename;
    }
}
