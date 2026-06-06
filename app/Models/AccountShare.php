<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AccountShare extends Model {
    protected $guarded = ['id'];
    public function account() { return $this->belongsTo(Account::class); }
}