<?php

namespace App\Http\Controllers\Api\V1;


use Exception;
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

        /**
         * Standard success response
         */
        protected function success($data = [], $message = 'Success', $status = 200)
        {
            return response()->json([
                'status' => true,
                'message' => $message,
                'data' => $data
            ], $status);
        }
    
        /**
         * Standard error response
         */
        protected function error($message = 'Error', $status = 500, $errors = [])
        {
            return response()->json([
                'status' => false,
                'message' => $message,
                'errors' => $errors
            ], $status);
        }
 
 
    





  
    // ---------------------------
    // Index: For datatable
    // ---------------------------
    // public function index(Request $request)
    // {
    //     try {
    //         $reports = Report::query()
    //             ->select('id', 'title', 'content', 'status', 'created_at')
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //             Log::info('reports', $reports->toArray());

    //         // Format response for frontend datatable
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Reports fetched successfully',
    //             'data' => $reports->map(function ($report) {
    //                 return [
    //                     'id' => $report->id,
    //                     'title' => $report->title,
    //                     'content' => $report->content,
    //                     'status' => (bool) $report->status,
    //                     'created_at' => $report->created_at->toDateTimeString(),
    //                 ];
    //             }),
    //         ]);
    //     } catch (QueryException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Database error fetching reports',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unexpected error fetching reports',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // ---------------------------
    // Show single report
    // ---------------------------
    // public function show(Report $report)
    // {
    //     try {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Report fetched successfully',
    //             'data' => ['report' => $report],
    //         ]);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to fetch report',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // ---------------------------
    // Store: Create new report
    // ---------------------------
    // public function store(Request $request)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'title' => 'required|string|max:255',
    //             'content' => 'required|string',
    //             'status' => 'boolean',
    //         ]);

    //         $report = Report::create($validated);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Report created successfully',
    //             'data' => ['report' => $report],
    //         ]);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (QueryException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Database error creating report',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unexpected error creating report',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // ---------------------------
    // Update report
    // ---------------------------
    // public function update(Request $request, Report $report)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'title' => 'required|string|max:255',
    //             'content' => 'required|string',
    //             'status' => 'boolean',
    //         ]);

    //         $report->update($validated);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Report updated successfully',
    //             'data' => ['report' => $report],
    //         ]);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (QueryException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Database error updating report',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unexpected error updating report',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // ---------------------------
    // Delete report
    // ---------------------------
    // public function destroy(Report $report)
    // {
    //     try {
    //         $report->delete();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Report deleted successfully',
    //         ]);
    //     } catch (QueryException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Database error deleting report',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unexpected error deleting report',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // ---------------------------
    // Toggle status
    // ---------------------------
    // public function toggleStatus(Request $request, Report $report)
    // {
    //     try {
    //         $report->status = $request->boolean('status');
    //         $report->save();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Status updated',
    //             'data' => ['report' => $report],
    //         ]);
    //     } catch (QueryException $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Database error updating status',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unexpected error updating status',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    // List reports (datatable-ready)
    public function index(Request $request)
    {
        try {
            $reports = Report::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Reports fetched successfully',
                'data' => $reports->map(fn($r) => [
                    'id' => $r->id,
                    'title' => $r->title,
                    'content' => $r->content,
                    'status' => (bool) $r->status,
                    'created_at' => $r->created_at->toDateTimeString(),
                ]),
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error fetching reports',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error fetching reports',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show single report
    public function show(Report $report)
    {
        return response()->json([
            'success' => true,
            'message' => 'Report fetched successfully',
            'data' => $report,
        ]);
    }

    // Create report
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'boolean',
            ]);

            $report = Report::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Report created successfully',
                'data' => $report,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException|Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update report
    public function update(Request $request, Report $report)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'boolean',
            ]);

            $report->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Report updated successfully',
                'data' => $report,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (QueryException|Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete report
    public function destroy(Report $report)
    {
        try {
            $report->delete();

            return response()->json([
                'success' => true,
                'message' => 'Report deleted successfully',
            ]);
        } catch (QueryException|Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete report',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Toggle status
    public function toggleStatus(Request $request, Report $report)
    {
        try {
            $report->status = $request->boolean('status');
            $report->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated',
                'data' => $report,
            ]);
        } catch (QueryException|Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

