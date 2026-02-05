<?php

namespace App\Http\Controllers;

use App\Models\AttendanceImport;
use App\Models\MonthlyAttendance;
use App\Imports\MonthlyAttendanceImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceImportController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $attendances = MonthlyAttendance::with('staff')
            ->forPeriod($year, $month)
            ->orderBy('staff_id')
            ->paginate(20);

        return view('attendance.index', compact('attendances', 'year', 'month'));
    }

    public function showImport()
    {
        return view('attendance.import');
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $file = $request->file('file');

        // Create import record
        $import = AttendanceImport::create([
            'file_name' => $file->hashName(),
            'original_file_name' => $file->getClientOriginalName(),
            'year' => $validated['year'],
            'month' => $validated['month'],
            'imported_by' => auth()->id(),
            'status' => 'processing',
        ]);

        try {
            $importer = new MonthlyAttendanceImport(
                $validated['year'],
                $validated['month'],
                $import->id
            );

            Excel::import($importer, $file);

            $import->markAsCompleted(
                $importer->getSuccessCount(),
                $importer->getErrorCount()
            );

            if ($importer->getErrors()) {
                $import->update(['error_log' => $importer->getErrors()]);
            }

            $message = "Imported {$importer->getSuccessCount()} records successfully.";
            if ($importer->getErrorCount() > 0) {
                $message .= " {$importer->getErrorCount()} records had errors.";
            }

            return redirect()->route('attendance.index', [
                'year' => $validated['year'],
                'month' => $validated['month'],
            ])->with('success', $message);

        } catch (\Exception $e) {
            $import->markAsFailed([$e->getMessage()]);

            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $templatePath = storage_path('app/templates/attendance_import_template.xlsx');

        if (!file_exists($templatePath)) {
            // Generate template dynamically
            return Excel::download(
                new \App\Exports\AttendanceTemplateExport(),
                'attendance_import_template.xlsx'
            );
        }

        return response()->download($templatePath);
    }

    public function history(Request $request)
    {
        $imports = AttendanceImport::with('importer')
            ->orderByDesc('created_at')
            ->paginate(20);

        $stats = [
            'successful' => AttendanceImport::where('status', 'completed')->count(),
            'partial' => AttendanceImport::where('status', 'partial')->count(),
            'failed' => AttendanceImport::where('status', 'failed')->count(),
        ];

        return view('attendance.history', compact('imports', 'stats'));
    }

    public function show(AttendanceImport $import)
    {
        $import->load(['importer', 'monthlyAttendances.staff']);

        return view('attendance.show', compact('import'));
    }

    public function destroy(AttendanceImport $import)
    {
        $import->monthlyAttendances()->delete();
        $import->delete();

        return redirect()->route('attendance.history')
            ->with('success', 'Import and all associated records have been deleted.');
    }

    public function updateRecord(Request $request, MonthlyAttendance $record)
    {
        $validated = $request->validate([
            'days_present' => 'required|integer|min:0',
            'days_absent' => 'required|integer|min:0',
            'days_leave' => 'required|integer|min:0',
            'days_late' => 'required|integer|min:0',
            'total_working_days' => 'required|integer|min:1',
        ]);

        $record->update($validated);

        return redirect()->back()->with('success', 'Attendance record updated.');
    }

    public function destroyRecord(MonthlyAttendance $record)
    {
        $record->delete();

        return redirect()->back()->with('success', 'Attendance record deleted.');
    }
}
