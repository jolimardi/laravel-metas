<?php

namespace App\Nova;

use App\Nova\Resource;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class Meta extends Resource {

    public static function label() {
        return 'Métas (title, desc)'; // Titre dans le menu
    }


    /*  ---------    Config   ----------------------------------------  */

    public static $model = \App\Models\Meta::class;

    public static $title = 'routename';     // C'est le field qui sera utilisé pour "résumer" la ressource
    public static $search = ['routename', 'uri', 'title'];  // fields searchables
    public static $orderBy = "routename asc";  // default order by

    // Menu
    public static $displayInNavigation = true;

    /*  ---------    Fields   ----------------------------------------  */

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @return array
     */
    public function fields($request) {
        return [
            ID::make()->sortable(),

            Text::make('Routename', 'routename')
                ->sortable()
                ->help('Ne pas changer')
                ->rules('required', 'max:255'),

            Text::make('URI', 'uri')
                ->sortable()
                ->readonly()
                ->nullable()
                ->rules('required', 'max:255'),

            Text::make('Titre', 'title')
                ->sortable()
                ->maxlength(60)
                ->rules('required', 'max:255'),

            Textarea::make('Description', 'description')
                ->rules('nullable')
                ->maxlength(140)
                ->rows(2),

            Textarea::make('Aide', 'help')
                ->rules('nullable'),
        ];
    }
}
