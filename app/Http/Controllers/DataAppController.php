<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\DataApp;

class DataAppController extends Controller
{
    /**
     * Show settings form
     */
    public function index()
    {
        $dataApp = DataApp::getInstance();
        $pageTitle = 'Pengaturan Aplikasi';
        $breadcrumbs = [
            ['title' => 'Dashboard', 'url' => route('dashboard')],
            ['title' => 'Pengaturan', 'url' => '#'],
            ['title' => 'Pengaturan Aplikasi']
        ];

        return view('settings.data-app', compact('dataApp', 'pageTitle', 'breadcrumbs'));
    }

    /**
     * Update application settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_app' => 'required|string|max:100',
            'nama_instansi' => 'nullable|string|max:200',
            'copyright_text' => 'nullable|string',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:1024',
        ], [
            'nama_app.required' => 'Nama aplikasi wajib diisi',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.max' => 'Ukuran logo maksimal 2MB',
            'favicon.image' => 'Favicon harus berupa gambar',
            'favicon.max' => 'Ukuran favicon maksimal 1MB',
        ]);

        $dataApp = DataApp::getInstance();

        $data = [
            'nama_app' => $request->nama_app,
            'nama_instansi' => $request->nama_instansi,
            'copyright_text' => $request->copyright_text,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($dataApp->logo) {
                Storage::disk('public')->delete($dataApp->logo);
            }

            $logoPath = $request->file('logo')->store('app-assets', 'public');
            $data['logo'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            // Delete old favicon
            if ($dataApp->favicon) {
                Storage::disk('public')->delete($dataApp->favicon);
            }

            $faviconPath = $request->file('favicon')->store('app-assets', 'public');
            $data['favicon'] = $faviconPath;
        }

        $dataApp->update($data);

        // Clear cache after update
        Cache::forget('app_data_settings');

        return redirect()->back()->with('success', 'Pengaturan aplikasi berhasil diupdate');
    }

    /**
     * Remove logo
     */
    public function removeLogo()
    {
        $dataApp = DataApp::getInstance();

        if ($dataApp->logo) {
            Storage::disk('public')->delete($dataApp->logo);
            $dataApp->update(['logo' => null]);
            Cache::forget('app_data_settings');
        }

        return response()->json([
            'success' => true,
            'message' => 'Logo berhasil dihapus'
        ]);
    }

    /**
     * Remove favicon
     */
    public function removeFavicon()
    {
        $dataApp = DataApp::getInstance();

        if ($dataApp->favicon) {
            Storage::disk('public')->delete($dataApp->favicon);
            $dataApp->update(['favicon' => null]);
            Cache::forget('app_data_settings');
        }

        return response()->json([
            'success' => true,
            'message' => 'Favicon berhasil dihapus'
        ]);
    }
}
