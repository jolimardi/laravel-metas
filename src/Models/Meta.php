<?php

namespace JoliMardi\Metas\Models;

use App\Models\BaseModel;

class Meta extends BaseModel {

    public $timestamps = false;
    protected $table = 'metas';

    protected $fillable = ['routename', 'uri', 'title', 'description'];

}
