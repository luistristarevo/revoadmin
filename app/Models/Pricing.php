<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class Pricing extends Model {
    public function getAllPricing(){
        $pricing_db = DB::table('pricing')
            ->join('price_table', 'price_table.idpt', '=', 'pricing.id_table')
            ->groupBy('price_table.tlabel')

        ;
        return $pricing_db;
    }
}
