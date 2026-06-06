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
        $request->validate(['title' => 'required']);
        $path = '';
        if ($request->filled('cropped_image')) {
            $image_parts = explode(";base64,", $request->cropped_image);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $path = 'banners/' . uniqid() . '.webp';
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $image_base64);
            }
        } else {
            return redirect()->back()->withErrors(['image' => 'Anda wajib mengunggah gambar dan menekan tombol "Potong & Simpan".']);
        }
        
        Banner::create(['title' => $request->title, 'image_path' => $path, 'link' => $request->link, 'is_active' => $request->is_active ?? true, 'order' => $request->order ?? 0]);
        return redirect()->back()->with('success', 'Banner berhasil ditambahkan.');
    }
    public function update(Request $request, Banner $banner) {
        $request->validate(['title' => 'required']);
        $data = $request->only(['title', 'link', 'is_active', 'order']);
        
        if ($request->filled('cropped_image')) {
            $image_parts = explode(";base64,", $request->cropped_image);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $data['image_path'] = 'banners/' . uniqid() . '.webp';
                \Illuminate\Support\Facades\Storage::disk('public')->put($data['image_path'], $image_base64);
            }
        } elseif($request->hasFile('image')) {
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