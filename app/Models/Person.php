<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Person extends Model
{
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['ari8mosEisagoghs','hmeromhnia_eis', 'syggrafeas','koha','titlos','ekdoths','ekdosh','etosEkdoshs','toposEkdoshs','sxhma','selides','tomos','troposPromPar','ISBN','sthlh1','sthlh2',])
            ->logOnlyDirty()
            ->useLogName('book');
    }
}
