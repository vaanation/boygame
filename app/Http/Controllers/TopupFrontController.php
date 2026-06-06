<?php
namespace App\Http\Controllers;
use App\Models\TopupPackage;

class TopupFrontController extends Controller {
    public function index() {
        $packages = TopupPackage::all();
        return view('front.topup.index', compact('packages'));
    }
}