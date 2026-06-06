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
            'whatsapp_number' => 'nullable|string',
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
            'whatsapp_number' => 'nullable|string',
        ]);

        $account->update($request->only(['title', 'category_id', 'price', 'description', 'status', 'whatsapp_number']));

        // Process combined image order
        $newUploadedPaths = [];
        if($request->hasFile('images')) {
            foreach($request->file('images') as $image) {
                $path = $imageService->compressAndSave($image, 'accounts');
                $newUploadedPaths[$image->getClientOriginalName()] = $path;
            }
        }

        if ($request->has('media_order') && $request->media_order != '') {
            $mediaOrder = json_decode($request->media_order, true);
            $existingImages = \App\Models\AccountImage::where('account_id', $account->id)->get()->keyBy('id');
            $finalImagePaths = [];
            
            if (is_array($mediaOrder)) {
                foreach ($mediaOrder as $item) {
                    if (str_starts_with($item, 'existing_')) {
                        $id = str_replace('existing_', '', $item);
                        if (isset($existingImages[$id])) {
                            $finalImagePaths[] = $existingImages[$id]->image_path;
                            unset($existingImages[$id]);
                        }
                    } else if (str_starts_with($item, 'new_')) {
                        $filename = str_replace('new_', '', $item);
                        if (isset($newUploadedPaths[$filename])) {
                            $finalImagePaths[] = $newUploadedPaths[$filename];
                        }
                    }
                }
            }
            
            // Delete unused existing images physically
            foreach ($existingImages as $unusedImg) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($unusedImg->image_path)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($unusedImg->image_path);
                }
            }
            // Delete all existing rows
            \App\Models\AccountImage::where('account_id', $account->id)->delete();
            
            // Recreate in order
            foreach ($finalImagePaths as $path) {
                $account->images()->create(['image_path' => $path]);
            }
        } else {
            // Fallback for old way
            if ($request->has('deleted_images')) {
                foreach ($request->deleted_images as $imageId) {
                    $image = \App\Models\AccountImage::where('id', $imageId)->where('account_id', $account->id)->first();
                    if ($image) {
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_path)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
                        }
                        $image->delete();
                    }
                }
            }
            foreach($newUploadedPaths as $path) {
                $account->images()->create(['image_path' => $path]);
            }
        }

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }
    public function destroy(Account $account) {
        $account->delete();
        return redirect()->back()->with('success', 'Akun berhasil dihapus.');
    }

    public function deleteImage($id) {
        $image = \App\Models\AccountImage::findOrFail($id);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();
        return response()->json(['success' => true]);
    }
}