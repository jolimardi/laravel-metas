<?php

namespace JoliMardi\Metas\Services;

use Illuminate\Support\Facades\Route;
use Symfony\Component\Yaml\Yaml;

class MetasService {

    public static function loadMetasYaml($custom_path = false) {

        $path = '../config/metas.yml';
        if ($custom_path) {
            $path = $custom_path;
        }

        if (!is_file($path)) {
            throw new \ErrorException('\JoliMardi\MetasService : ' . $path . ' introuvable');
        }
        return Yaml::parseFile($path, Yaml::PARSE_OBJECT_FOR_MAP);
    }

    public static function getRouteName(): string {
        $routename = Route::currentRouteName();
        if ($routename === null) {
            return '';
        }
        return $routename;
    }


    public static function getTitle(array $dynamic_vars_array = [], $force_routename = false): string {

        $key = self::getRouteName();
        $metas = self::loadMetasYaml();
        $title = self::getDefaultTitle();

        if (!empty($force_routename)) {
            $key = $force_routename;
        }

        if (isset($metas->$key->title)) {
            $title = $metas->$key->title;
        }

        // Remplacement des variables de type {{ $post->title }}
        if (count($dynamic_vars_array) > 0) {
            $title = self::replaceDynamicContent($title, $dynamic_vars_array);
        }

        // Title par défaut
        return $title;
    }

    public static function getDescription(array $dynamic_vars_array = [], $force_routename = false): string {

        $key = self::getRouteName();
        $metas = self::loadMetasYaml();
        $description = self::getDefaultDescription();

        if (!empty($force_routename)) {
            $key = $force_routename;
        }

        if (isset($metas->$key->description)) {
            $description = $metas->$key->description;
        }

        // Remplacement des variables de type {{ $post->title }}
        if (count($dynamic_vars_array) > 0) {
            $description = self::replaceDynamicContent($description, $dynamic_vars_array);
        }

        // Title par défaut
        return $description;
    }

    public static function getDefaultTitle() {
        $metas = self::loadMetasYaml();
        if (isset($metas->default->title)) {
            return $metas->default->title;
        } else {
            throw new \ErrorException('\JoliMardi\MetasService : /config/metas.yml : default title introuvable (ajouter un item default: [title:"titre par défaut", description: "description par défaut]');
            return '';
        }
    }
    public static function getDefaultDescription() {
        $metas = self::loadMetasYaml();
        if (isset($metas->default->description)) {
            return $metas->default->description;
        } else {
            throw new \ErrorException('\JoliMardi\MetasService : /config/metas.yml : default description introuvable (ajouter un item dans le yaml : [default: [title:"titre par défaut", description: "description par défaut]]');
            return '';
        }
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
}
