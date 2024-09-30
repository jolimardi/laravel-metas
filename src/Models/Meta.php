<?php

namespace App\Models;


// Vérifie si BaseModel existe et l'importe si disponible
if (class_exists(\App\Models\BaseModel::class)) {
    class MetaParentModel extends BaseModel {
    }
} else {
    class MetaParentModel extends \Illuminate\Database\Eloquent\Model {
    }
}


class Meta extends MetaParentModel {

    public $timestamps = true;
    protected $table = 'metas';

    protected $fillable = ['routename', 'uri', 'title', 'description'];

}
