<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Account extends Model {
    protected $guarded = ['id'];
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(AccountImage::class); }
    public function views() { return $this->hasMany(AccountView::class); }
    public function shares() { return $this->hasMany(AccountShare::class); }
}