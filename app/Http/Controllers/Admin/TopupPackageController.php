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