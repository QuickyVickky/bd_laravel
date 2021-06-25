<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'vehicle_id',
        'img',
        'img_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $table = 'tbl_vehicle_files';


    public function filelabel(){
        return $this->hasOne(ShortHelper::class, 'short', 'img_type')->where('is_active',constants('is_active_yes'))->where('type','vehicle_file_type')->select('tbl_short_helper.short','tbl_short_helper.name','tbl_short_helper.classhtml'); 
    }


    

}
