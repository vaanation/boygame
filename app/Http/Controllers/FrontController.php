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