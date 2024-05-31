<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserImportController extends Controller
{
    public function showImportForm()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|file|mimes:xls,xlsx',
        ]);

        try {
            $import = new UsersImport;
            Excel::import($import, $request->file('excelFile'));

            $flash_message = "Success import total " . $import->getRowCount() . " data users";
            return redirect()->back()->with('success', $flash_message);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Import Error: ' . $e->getMessage());

            // Set a flash message for the error
            $error_message = "An error occurred during import: " . $e->getMessage();
            return redirect()->back()->with('error', $error_message);
        }
    }
}
