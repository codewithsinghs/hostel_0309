<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Report;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ReportController extends Controller
{
    use ApiResponse;

    // public function index(Request $request)
    // {
    //     try {
    //         $query = Report::query();

    //         return $this->dataTableResponse($query, $request, function ($report) {
    //             return [
    //                 'id' => $report->id,
    //                 'title' => $report->title,
    //                 'content' => $report->content,
    //                 'status' => (bool) $report->status,
    //                 'created_at' => $report->created_at->toDateTimeString(),
    //                 'updated_at' => $report->updated_at->toDateTimeString(),
    //             ];
    //         });

    //     } catch (QueryException $e) {
    //         return $this->error('Database error while fetching reports', 500, ['exception' => $e->getMessage()]);
    //     } catch (\Throwable $e) {
    //         return $this->error('Unexpected error while fetching reports', 500, ['exception' => $e->getMessage()]);
    //     }
    // }

    public function index(Request $request)
{
    try {
        $reports = Report::all();

        // Ensure frontend receives an array
        return $this->success(['reports' => $reports], 'Reports fetched successfully');
    } catch (\Throwable $e) {
        return $this->error('Failed to fetch reports', 500, ['exception' => $e->getMessage()]);
    }
}


    public function show(Report $report)
    {
        Log::info('showing nothing');
        try {
            return $this->success(['report' => $report], 'Report retrieved successfully');
        } catch (\Throwable $e) {
            return $this->error('Unexpected error while retrieving report', 500, ['exception' => $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string|max:255',
                'status' => 'nullable|boolean',
            ]);

            $report = Report::create($validated);

            return $this->success(['report' => $report], 'Report created successfully', 201);

        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (QueryException $e) {
            return $this->error('Database error while creating report', 500, ['exception' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->error('Unexpected error while creating report', 500, ['exception' => $e->getMessage()]);
        }
    }

    public function update(Request $request, Report $report)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string|max:255',
                'status' => 'nullable|boolean',
            ]);

            $report->update($validated);

            return $this->success(['report' => $report], 'Report updated successfully');

        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (QueryException $e) {
            return $this->error('Database error while updating report', 500, ['exception' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->error('Unexpected error while updating report', 500, ['exception' => $e->getMessage()]);
        }
    }

    public function toggleStatus(Request $request, Report $report)
    {
        try {
            $request->validate(['status' => 'required|boolean']);

            $report->update(['status' => $request->status]);

            return $this->success(['report' => $report], 'Status updated successfully');

        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (QueryException $e) {
            return $this->error('Database error while updating status', 500, ['exception' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->error('Unexpected error while updating status', 500, ['exception' => $e->getMessage()]);
        }
    }

    public function destroy(Report $report)
    {
        try {
            $report->delete();
            return $this->success([], 'Report deleted successfully');
        } catch (QueryException $e) {
            return $this->error('Database error while deleting report', 500, ['exception' => $e->getMessage()]);
        } catch (\Throwable $e) {
            return $this->error('Unexpected error while deleting report', 500, ['exception' => $e->getMessage()]);
        }
    }
}
