<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'notification_text',
        'notification_link',
        'created_at',
    ];



    protected $table = 'tbl_admin_notifications';


    public $timestamps = false;

    
}
