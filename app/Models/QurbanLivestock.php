<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QurbanLivestock extends Model
{
    use HasFactory;

    protected $table = 'qurban_livestock';

    protected $fillable = [
        'livestock_id',
        'price',
    ];

    public function livestock()
    {
        return $this->belongsTo(Livestock::class);
    }
}
