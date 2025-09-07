<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function index()
    {
        Log::info('report index');
        return Report::latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'boolean',
        ]);

        $report = Report::create($validated);

        return response()->json($report, 201);
    }

    public function show(Report $report)
    {
        return $report;
    }

    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'boolean',
        ]);

        $report->update($validated);

        return response()->json($report);
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return response()->json(null, 204);
    }
}
