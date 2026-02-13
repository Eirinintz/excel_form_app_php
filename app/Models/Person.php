<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'people';

    protected $primaryKey = 'ari8mosEisagoghs';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'ari8mosEisagoghs',
        'hmeromhnia_eis',
        'syggrafeas',
        'koha',
        'titlos',
        'ekdoths',
        'ekdosh',
        'etosEkdoshs',
        'toposEkdoshs',
        'sxhma',
        'selides',
        'tomos',
        'troposPromPar',
        'ISBN',
        'sthlh1',
        'sthlh2',
    ];
}
