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