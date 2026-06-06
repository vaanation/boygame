<?php
namespace App\Http\Controllers;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountFrontController extends Controller {
    public function index(Request $request) {
        $query = Account::with('images');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $accounts = $query->latest()->paginate(12)->appends($request->all());
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('front.accounts.index', compact('accounts', 'categories'));
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

    public function share($slug) {
        $account = Account::where('slug', $slug)->firstOrFail();
        $account->increment('shares');
        return response()->json(['success' => true, 'shares' => $account->shares]);
    }
}