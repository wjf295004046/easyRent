<?php

namespace App\Models\House;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $table = "houses";
    protected $fillable = [
        "name", "address_id", "landlord_id", "price", "max_people", "sum", "deposit", "city", "house_type", "house_type_detail", "house_area", "rent_type", "bed_type", "change_bed", "supporting_facilities", "desc", "internal_situation", "traffic_condition", "peripheral_condition", "cook_fee", "clean_fee", "other_fee"
    ];
    public function address() {
        return $this->hasOne('App\Models\House\Address', 'id', 'address_id');
    }
}
