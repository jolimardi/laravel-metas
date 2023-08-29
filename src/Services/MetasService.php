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


    public static function getTitle($force_routename = false): string {

        $key = self::getRouteName();
        $metas = self::loadMetasYaml();

        if (!empty($force_route)) {
            $key = $force_route;
        }

        // S'il y a un signe + un title spécifique, on retourne le #signe remplacé
        if (isset($metas->$key->title)) {
            return $metas->$key->title;
        }

        // Title par défaut
        return self::getDefaultTitle();
    }

    public static function getDescription($force_routename = false): string {

        $key = self::getRouteName();
        $metas = self::loadMetasYaml();

        if (!empty($force_route)) {
            $key = $force_route;
        }

        // S'il y a un signe + un title spécifique, on retourne le #signe remplacé
        if (isset($metas->$key->description)) {
            return $metas->$key->description;
        }

        // Title par défaut
        return self::getDefaultDescription();
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
}
