<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamillesFournitures extends Model
{
    use HasFactory;

    static function listeFamilles()
    {
        $FamillesFournitures = FamillesFournitures::select('*')->get();

        return $FamillesFournitures;
    }
}
