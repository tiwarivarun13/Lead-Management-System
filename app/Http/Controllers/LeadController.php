<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class LeadController extends Controller
{
    //
    public function showUploadForm() {
        return view('leads.upload');
    }

    public function import(Request $request) {
        // Validate file upload
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048'// Max size in KB (2048 KB = 2MB)
        ]);
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xlsx,csv',
        ]);
        $file = $request->file('file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $successful = 0;
            $failed = 0;
            $errors = [];

            // Skip first row (header)
            foreach (array_slice($rows, 1) as $index => $row) {
                $name = $row[0] ?? null;
                $email = $row[1] ?? null;
                $phone = $row[2] ?? null;
                $status = $row[3] ?? null;

                // Validate row data
                $validator = Validator::make([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'status' => $status,
                ], [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:leads,email',
                    'phone' => 'required|regex:/^[0-9]{10,15}$/',
                    'status' => ['required', Rule::in(['New', 'In Progress', 'Closed'])],
                ]);

                if ($validator->fails()) {
                    $failed++;
                    $errors[] = "Row " . ($index + 2) . " failed: " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Save to database
                Lead::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'status' => $status,
                ]);

                $successful++;
            }
           
            return redirect()->back()->with('success', "Import complete! Successful: $successful, Failed: $failed")->with('error', implode('<br>', $errors));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $lead = Lead::findOrFail($id);
        return view('leads.edit', compact('lead'));
    }
    
    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email,' . $id,
            'phone' => 'required|regex:/^[0-9]{10,15}$/',
            'status' => 'required|in:New,In Progress,Closed',
        ]);
    
        $lead = Lead::findOrFail($id);
        $lead->update($request->all());
    
        return redirect()->route('dashboard')->with('success', 'Lead updated successfully!');
    }
    
    public function destroy($id) {
        Lead::destroy($id);
        return redirect()->route('dashboard')->with('success', 'Lead deleted successfully!');
    }
    public function updateStatus(Request $request, $id)
{
    $lead = Lead::find($id);
    if (!$lead) {
        return response()->json(['success' => false, 'message' => 'Lead not found'], 404);
    }

    $lead->status = $request->status;
    $lead->save();

    return response()->json(['success' => true, 'message' => 'Status updated successfully']);
}
public function exportLeadsToExcel()
{
    $leads = Lead::select('name', 'email', 'phone', 'status', 'created_at')->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set header row
    $sheet->setCellValue('A1', 'Name');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Phone');
    $sheet->setCellValue('D1', 'Status');
    $sheet->setCellValue('E1', 'Date Added');

    // Fill data rows
    $row = 2;
    foreach ($leads as $lead) {
        $sheet->setCellValue('A' . $row, $lead->name);
        $sheet->setCellValue('B' . $row, $lead->email);
        $sheet->setCellValue('C' . $row, $lead->phone);
        $sheet->setCellValue('D' . $row, $lead->status);
        $sheet->setCellValue('E' . $row, $lead->created_at->format('Y-m-d'));
        $row++;
    }

    // Create and return Excel file
    $fileName = 'leads_export.xlsx';
    $writer = new Xlsx($spreadsheet);
    $filePath = storage_path($fileName);
    $writer->save($filePath);

    return Response::download($filePath)->deleteFileAfterSend(true);
}
}
