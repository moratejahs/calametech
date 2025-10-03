<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Backup;
use App\Models\SOS;
use App\Models\Incident;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaintenanceController extends Controller
{
    use CreatesBackups;
    public function exportSosCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sos_export.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id','lat','long','status','type','image_path','address','user_id','created_at']);
            SOS::with('user')->orderBy('id')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->lat,
                        $r->long,
                        $r->status,
                        $r->type,
                        $r->image_path,
                        $r->address,
                        $r->user_id,
                        $r->created_at,
                    ]);
                }
            });
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportIncidentsCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="incidents_export.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id','user_id','barangay_id','image','description','status','lat','long','created_at']);
            Incident::orderBy('id')->chunk(500, function ($rows) use ($handle) {
                foreach ($rows as $r) {
                    fputcsv($handle, [
                        $r->id,
                        $r->user_id,
                        $r->barangay_id,
                        $r->image,
                        $r->description,
                        $r->status,
                        $r->lat,
                        $r->long,
                        $r->created_at,
                    ]);
                }
            });
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function runBackup()
    {
        // Generate backup, save to storage and DB, then download the saved file
        try {
            $backup = $this->createBackupToStorage();
            return Storage::disk($backup->disk)->download($backup->path, $backup->filename);
        } catch (\Throwable $e) {
            return redirect()->route('admin.backups.index')->with('error', 'Backup currently unavailable on this server.');
        }
    }

    public function viewBackup()
    {
        try {
            $sql = $this->buildDatabaseSqlDump();
            $filename = 'backup_'.date('Ymd_His').'.sql';
            return response($sql, 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('admin.backups.index')->with('error', 'Backup view is unavailable on this server.');
        }
    }

    private function findMysqldump(): string
    {
        // Use env override if provided
        $envPath = env('MYSQLDUMP_PATH');
        if ($envPath && file_exists($envPath)) {
            return $envPath;
        }
        // Try which/where
        try {
            $finder = stripos(PHP_OS, 'WIN') === 0 ? 'where mysqldump' : 'which mysqldump';
            $proc = Process::fromShellCommandline($finder);
            $proc->setTimeout(5);
            $proc->run();
            $out = trim($proc->getOutput());
            if ($proc->isSuccessful() && $out) {
                $path = preg_split('/\r?\n/', $out)[0];
                if ($path && file_exists($path)) return $path;
            }
        } catch (\Throwable $e) {}
        // Common paths (Windows Laragon + Linux distros)
        $guesses = [
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.31-winx64\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.28-winx64\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];
        foreach ($guesses as $g) {
            if (file_exists($g)) return $g;
        }
        throw new \RuntimeException('Backup tool is unavailable');
    }

    // --------- File-based backups UI ---------
    public function backupsIndex()
    {
        $disk = Storage::disk('local');
        $disk->makeDirectory('backups');
        $backups = Backup::orderByDesc('created_at')->get();
        return view('admin.backups.index', compact('backups'));
    }

    public function backupsGenerate()
    {
        try {
            $backup = $this->createBackupToStorage();
            return redirect()->route('admin.backups.index')->with('success', 'Backup created: '.$backup->filename);
        } catch (\Throwable $e) {
            return redirect()->route('admin.backups.index')->with('error', 'Backup currently unavailable on this server.');
        }
    }

    public function backupsDownload(int $id)
    {
        $backup = Backup::findOrFail($id);
        if (!Storage::disk($backup->disk)->exists($backup->path)) { abort(404); }
        return Storage::disk($backup->disk)->download($backup->path, $backup->filename);
    }

    public function backupsDelete(int $id)
    {
        $backup = Backup::findOrFail($id);
        Storage::disk($backup->disk)->delete($backup->path);
        $backup->delete();
        return redirect()->route('admin.backups.index')->with('success', 'Backup deleted');
    }
}

// Internal helpers (same namespace)
trait CreatesBackups
{
    private function createBackupToStorage(): \App\Models\Backup
    {
        $sql = $this->buildDatabaseSqlDump();

        $disk = Storage::disk('local');
        $dir = 'backups';
        $disk->makeDirectory($dir);
        $filename = 'backup_'.date('Ymd_His').'.sql.gz';
        $path = $dir.'/'.$filename;
        $gz = gzopen($disk->path($path), 'w9');
        if ($gz === false) { throw new \RuntimeException('Unable to open gzip stream'); }
        gzwrite($gz, $sql);
        gzclose($gz);

        $size = $disk->size($path);
        return \App\Models\Backup::create([
            'filename' => $filename,
            'disk' => 'local',
            'path' => $path,
            'size_bytes' => $size,
        ]);
    }

    private function buildDatabaseSqlDump(): string
    {
        $connectionName = Config::get('database.default');
        $pdo = DB::connection($connectionName)->getPdo();
        $database = Config::get('database.connections.'.$connectionName.'.database');

        $pdo->exec('SET NAMES utf8mb4');
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

        $dump = '';
        $dump .= "-- Database: `{$database}`\n";
        $dump .= "-- Generated: ".date('Y-m-d H:i:s')."\n\n";
        $dump .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $dump .= "SET time_zone = '+00:00';\n\n";

        $tablesStmt = $pdo->query('SHOW TABLES');
        $tables = $tablesStmt->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $createStmt = $pdo->query('SHOW CREATE TABLE `'.$table.'`');
            $createRow = $createStmt->fetch(\PDO::FETCH_ASSOC);
            $createSql = $createRow['Create Table'] ?? ($createRow['Create View'] ?? '');
            if ($createSql !== '') {
                $dump .= "\n--\n-- Table structure for table `{$table}`\n--\n\n";
                $dump .= 'DROP TABLE IF EXISTS `'.$table.'`;' . "\n";
                $dump .= $createSql . ";\n\n";
            }

            $rowsStmt = $pdo->query('SELECT * FROM `'.$table.'`');
            $rows = $rowsStmt->fetchAll(\PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $dump .= "--\n-- Dumping data for table `{$table}`\n--\n\n";
                foreach ($rows as $row) {
                    $columns = array_map(fn($c) => '`'.$c.'`', array_keys($row));
                    $values = array_map(function ($v) use ($pdo) {
                        if ($v === null) return 'NULL';
                        return $pdo->quote($v);
                    }, array_values($row));
                    $dump .= 'INSERT INTO `'.$table.'` ('.implode(',', $columns).') VALUES ('.implode(',', $values).');' . "\n";
                }
                $dump .= "\n";
            }
        }

        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
        return $dump;
    }
}


