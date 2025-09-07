<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    use \App\Traits\ApiResponse; // Assuming your success, error, dataTableResponse traits are here
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Event::query()->select(['id', 'title', 'content', 'category', 'author', 'date', 'status', 'image']);
            return $this->dataTableResponse($query, $request);
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch events', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        Log::info($request->all());
        try {
            $validated = $request->validate([
                'title'    => 'required|string|max:255',
                'content'  => 'required|string',
                'category' => 'required|string|max:100',
                'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'date'     => 'required|date',
                'status'   => 'required|boolean',
                'author'   => 'required|string|max:150',
            ]);

            // Handle file upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('blogs', $filename, 'public');
                $validated['image'] = $path;
            }

            $event = Event::create($validated);

            return $this->success(
                ['event' => $event],
                'Event created successfully',
                201
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error('Database error: '.$e->getMessage(), 500);
        } catch (\Exception $e) {
            return $this->error('Unexpected error: '.$e->getMessage(), 500);
        }
    }

    /**
     * Show a specific event
     */
    public function show(Event $event)
    {
        Log::info('hitted');
        Log::info($event->toArray());
        try {
            return $this->success(['data' => $event], 'Event fetched successfully');
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch event', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Update a event
     */
    public function update(Request $request, Event $event)
    {
        Log::info($request->all());
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|string|max:255',
                'image' => 'nullable|image|max:2048',
                'date' => 'required|date',
                'status' => 'required|boolean',
                'author' => 'required|string|max:255',
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/blogs', $filename);
                $validated['image'] = $filename;
            }

            $event->update($validated);

            return $this->success(['event' => $event], 'Event updated successfully');
        } catch (ValidationException $ve) {
            return $this->error('Validation failed', 422, $ve->errors());
        } catch (QueryException $qe) {
            return $this->error('Database error: ' . $qe->getMessage(), 500);
        } catch (\Throwable $e) {
            return $this->error('Failed to update event', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Delete a event
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();
            return $this->success([], 'Event deleted successfully');
        } catch (QueryException $qe) {
            return $this->error('Database error: ' . $qe->getMessage(), 500);
        } catch (\Throwable $e) {
            return $this->error('Failed to delete event', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Toggle status
     */
    // public function toggleStatus(Request $request, Event $event)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'status' => 'required|boolean',
    //         ]);
        
    //         $event->update(['status' => $validated['status']]);

    //         return $this->success(['data' => $event], 'Status updated successfully');

    //     } catch (ValidationException $e) {
    //         return $this->error('Validation failed', 422, $e->errors());
    //     } catch (QueryException $e) {
    //         return $this->error('Database error while updating status', 500, ['exception' => $e->getMessage()]);
    //     } catch (\Throwable $e) {
    //         return $this->error('Unexpected error while updating status', 500, ['exception' => $e->getMessage()]);
    //     }
    // }
    // app/Http/Controllers/Admin/EventController.php


    public function toggleStatus(Request $request, Event $event)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|boolean',
            ]);

            $event->status = $validated['status'];
            $event->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status'  => $event->status,
            ]);
        } catch (\Throwable $e) {
            Log::error("Status toggle failed", [
                'event_id' => $event->id,
                'error'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }
    }
}



