<?php

$models = [
    'Category' => <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model {
    protected $guarded = ['id'];
    public function accounts() { return $this->hasMany(Account::class); }
}
PHP,
    'Account' => <<<'PHP'
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
PHP,
    'AccountImage' => <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AccountImage extends Model {
    protected $guarded = ['id'];
    public function account() { return $this->belongsTo(Account::class); }
}
PHP,
    'Banner' => <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Banner extends Model {
    protected $guarded = ['id'];
}
PHP,
    'TopupPackage' => <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TopupPackage extends Model {
    protected $guarded = ['id'];
}
PHP,
    'Setting' => <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Setting extends Model {
    protected $guarded = ['id'];
}
PHP,
    'AccountView' => <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AccountView extends Model {
    protected $guarded = ['id'];
    public function account() { return $this->belongsTo(Account::class); }
}
PHP,
    'AccountShare' => <<<'PHP'
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AccountShare extends Model {
    protected $guarded = ['id'];
    public function account() { return $this->belongsTo(Account::class); }
}
PHP,
];

foreach($models as $name => $content) {
    file_put_contents(__DIR__ . '/app/Models/' . $name . '.php', $content);
}

echo "Models generated.\n";
