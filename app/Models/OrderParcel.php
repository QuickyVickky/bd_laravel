<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderParcel extends Model
{
    use HasFactory;

     protected $fillable = [
        'id',
        'no_of_parcel',
        'goods_type_id',
        'goods_weight',
        'total_weight',
        'order_id',
        'is_active',
        'tempo_charge',
        'service_charge',
        'delivery_charge',
        'other_text',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $table = 'tbl_order_parcel_details';
    

    public function orderparcel(){
        return $this->hasMany(OrderParcel::class, 'order_id', 'id');
    }

    public function goodsType(){
        return $this->hasOne(GoodsType::class, 'id', 'goods_type_id');
    }


}
