<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AccountImage extends Model {
    protected $guarded = ['id'];
    public function account() { return $this->belongsTo(Account::class); }
}