<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre',
        'description',
        'categorie',
        'prix',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    
    public function primaryImage()
    {
        return $this->hasOne(Image::class)->where('is_primary', true);
    }
}