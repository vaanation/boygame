<?php

$controllers = [
    'Admin/DashboardController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Models\TopupPackage;
use App\Models\Banner;
use App\Models\AccountView;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'total_accounts' => Account::count(),
            'total_sold' => Account::where('status', 'Sold')->count(),
            'total_categories' => Category::count(),
            'total_topup' => TopupPackage::count(),
            'total_banners' => Banner::count(),
            'total_views' => AccountView::count(),
            'total_visitors' => AccountView::distinct('ip_address')->count('ip_address')
        ];
        return view('admin.dashboard', compact('stats'));
    }
}
PHP,
    'Admin/CategoryController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller {
    public function index() {
        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required|string|max:255|unique:categories']);
        Category::create(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan.');
    }
    public function update(Request $request, Category $category) {
        $request->validate(['name' => 'required|string|max:255|unique:categories,name,'.$category->id]);
        $category->update(['name' => $request->name, 'slug' => Str::slug($request->name)]);
        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }
    public function destroy(Category $category) {
        $category->delete();
        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
PHP,
    'Admin/AccountController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Category;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountController extends Controller {
    public function index() {
        $accounts = Account::with('category')->latest()->get();
        return view('admin.accounts.index', compact('accounts'));
    }
    public function create() {
        $categories = Category::all();
        return view('admin.accounts.create', compact('categories'));
    }
    public function store(Request $request, ImageService $imageService) {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'whatsapp_number' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $account = Account::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status ?? 'Tersedia',
            'whatsapp_number' => $request->whatsapp_number,
        ]);

        if($request->hasFile('images')) {
            foreach($request->file('images') as $image) {
                $path = $imageService->compressAndSave($image, 'accounts');
                $account->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }
    public function edit(Account $account) {
        $categories = Category::all();
        return view('admin.accounts.edit', compact('account', 'categories'));
    }
    public function update(Request $request, Account $account, ImageService $imageService) {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'whatsapp_number' => 'required|string',
        ]);

        $account->update($request->only(['title', 'category_id', 'price', 'description', 'status', 'whatsapp_number']));

        if($request->hasFile('images')) {
            foreach($request->file('images') as $image) {
                $path = $imageService->compressAndSave($image, 'accounts');
                $account->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }
    public function destroy(Account $account) {
        $account->delete();
        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }
}
PHP,
    'Admin/BannerController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller {
    public function index() {
        $banners = Banner::orderBy('order')->get();
        return view('admin.banners.index', compact('banners'));
    }
    public function store(Request $request) {
        $request->validate(['title' => 'required', 'image' => 'required|image']);
        $path = $request->file('image')->store('banners', 'public');
        Banner::create(['title' => $request->title, 'image_path' => $path, 'link' => $request->link, 'is_active' => $request->is_active ?? true, 'order' => $request->order ?? 0]);
        return redirect()->back()->with('success', 'Banner berhasil ditambahkan.');
    }
    public function update(Request $request, Banner $banner) {
        $request->validate(['title' => 'required']);
        $data = $request->only(['title', 'link', 'is_active', 'order']);
        if($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }
        $banner->update($data);
        return redirect()->back()->with('success', 'Banner diperbarui.');
    }
    public function destroy(Banner $banner) {
        $banner->delete();
        return redirect()->back()->with('success', 'Banner dihapus.');
    }
}
PHP,
    'Admin/TopupPackageController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\TopupPackage;
use Illuminate\Http\Request;

class TopupPackageController extends Controller {
    public function index() {
        $packages = TopupPackage::latest()->get();
        return view('admin.topup.index', compact('packages'));
    }
    public function store(Request $request) {
        $request->validate(['name' => 'required', 'coin_amount' => 'required|numeric', 'price' => 'required|numeric']);
        $data = $request->all();
        if($request->hasFile('image')) $data['image_path'] = $request->file('image')->store('topup', 'public');
        TopupPackage::create($data);
        return redirect()->back()->with('success', 'Paket ditambahkan.');
    }
    public function update(Request $request, TopupPackage $topup_package) {
        $data = $request->all();
        if($request->hasFile('image')) $data['image_path'] = $request->file('image')->store('topup', 'public');
        $topup_package->update($data);
        return redirect()->back()->with('success', 'Paket diperbarui.');
    }
    public function destroy(TopupPackage $topup_package) {
        $topup_package->delete();
        return redirect()->back()->with('success', 'Paket dihapus.');
    }
}
PHP,
    'Admin/SettingController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller {
    public function index() {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }
    public function update(Request $request) {
        foreach($request->except('_token') as $key => $value) {
            if($request->hasFile($key)) {
                $value = $request->file($key)->store('settings', 'public');
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->back()->with('success', 'Pengaturan diperbarui.');
    }
}
PHP,
    'FrontController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers;
use App\Models\Account;
use App\Models\Banner;
use App\Models\Setting;
use App\Models\AccountView;

class FrontController extends Controller {
    public function index() {
        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        $stats = [
            'total_accounts' => Account::count(),
            'total_sold' => Account::where('status', 'Sold')->count(),
            'total_visitors' => AccountView::distinct('ip_address')->count('ip_address')
        ];
        $latestAccounts = Account::with('images')->latest()->take(8)->get();
        $popularAccounts = Account::with('images')->orderByDesc('views')->take(8)->get();
        $soldAccounts = Account::with('images')->where('status', 'Sold')->latest()->take(4)->get();
        return view('front.home', compact('banners', 'stats', 'latestAccounts', 'popularAccounts', 'soldAccounts'));
    }
}
PHP,
    'AccountFrontController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountFrontController extends Controller {
    public function index(Request $request) {
        $accounts = Account::with('images')->latest()->paginate(12);
        return view('front.accounts.index', compact('accounts'));
    }
    public function show(Request $request, $slug) {
        $account = Account::with('images', 'category')->where('slug', $slug)->firstOrFail();
        
        if(!$request->session()->has('viewed_account_' . $account->id)) {
            $account->increment('views');
            $account->views()->create(['ip_address' => $request->ip(), 'session_id' => $request->session()->getId()]);
            $request->session()->put('viewed_account_' . $account->id, true);
        }

        $related = Account::where('category_id', $account->category_id)->where('id', '!=', $account->id)->take(4)->get();
        return view('front.accounts.show', compact('account', 'related'));
    }
}
PHP,
    'TopupFrontController.php' => <<<'PHP'
<?php
namespace App\Http\Controllers;
use App\Models\TopupPackage;

class TopupFrontController extends Controller {
    public function index() {
        $packages = TopupPackage::all();
        return view('front.topup.index', compact('packages'));
    }
}
PHP
];

foreach($controllers as $name => $content) {
    $dir = dirname(__DIR__ . '/app/Http/Controllers/' . $name);
    if(!is_dir($dir)) mkdir($dir, 0755, true);
    file_put_contents(__DIR__ . '/app/Http/Controllers/' . $name, $content);
}

// Create Service
$serviceDir = __DIR__ . '/app/Services';
if(!is_dir($serviceDir)) mkdir($serviceDir, 0755, true);
$imageService = <<<'PHP'
<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService {
    public function compressAndSave(UploadedFile $file, $directory = 'uploads') {
        $filename = Str::random(20) . '.webp';
        $path = $directory . '/' . $filename;
        $file->storeAs('public/' . $directory, $filename);
        return $directory . '/' . $filename;
    }
}
PHP;
file_put_contents($serviceDir . '/ImageService.php', $imageService);

echo "Controllers and Services generated.\n";
