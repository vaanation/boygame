<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller {
    public function index() {
        return view('admin.backup.index');
    }
    public function backup() {
        $db = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $pass = env('DB_PASSWORD');
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/' . $filename);
        
        // Use full path for mysqldump on Laragon or standard command
        $mysqlDumpPath = 'mysqldump';
        if (file_exists('D:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe')) {
             $mysqlDumpPath = '"D:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe"';
        }

        $command = "{$mysqlDumpPath} --user={$user} --password={$pass} {$db} > \"{$path}\"";
        if(empty($pass)) $command = "{$mysqlDumpPath} --user={$user} {$db} > \"{$path}\"";
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = "cmd.exe /c \"$command\"";
        }
        
        exec($command . ' 2>&1', $output, $returnVar);
        
        if ($returnVar !== 0 || !file_exists($path) || filesize($path) == 0) {
            return redirect()->back()->withErrors(['Backup gagal, pastikan path mysqldump benar. Error: ' . implode(" ", $output)]);
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function restore(Request $request) {
        $request->validate(['backup_file' => 'required|file']);
        
        $file = $request->file('backup_file');
        
        try {
            $sql = file_get_contents($file->getRealPath());
            
            if (empty(trim($sql))) {
                return redirect()->back()->withErrors(['File backup kosong.']);
            }

            // Execute the raw SQL using DB facade
            \Illuminate\Support\Facades\DB::unprepared($sql);
            
            return redirect()->back()->with('success', 'Database berhasil direstore.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Restore gagal: ' . $e->getMessage()]);
        }
    }
}
