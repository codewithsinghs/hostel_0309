<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class BlogController extends Controller
{
    use \App\Traits\ApiResponse; // Assuming your success, error, dataTableResponse traits are here

    /**
     * Display a listing of the blogs (DataTable style)
     */
    // public function index(Request $request)
    // {
    //     Log::alert('hit');
    //     try {
    //         $query = Blog::query();
            
    //         return $this->dataTableResponse($query, $request, function ($blog) {
    //             return [
    //                 'id' => $blog->id,
    //                 'title' => $blog->title,
    //                 'content' => $blog->content,
    //                 'category' => $blog->category,
    //                 'image' => $blog->image,
    //                 'date' => $blog->date,
    //                 'status' => (bool)$blog->status,
    //                 'author' => $blog->author,
    //                 'created_at' => $blog->created_at->toDateTimeString(),
    //                 'updated_at' => $blog->updated_at->toDateTimeString(),
    //             ];
    //         });
    //     } catch (\Throwable $e) {
    //         return $this->error('Failed to fetch blogs', 500, ['exception' => $e->getMessage()]);
    //     }
    // }

    public function index(Request $request): JsonResponse
{
    try {
        $query = Blog::query()->select(['id', 'title', 'content', 'category', 'author', 'date', 'status', 'image']);
        return $this->dataTableResponse($query, $request);
    } catch (\Throwable $e) {
        return $this->error('Failed to fetch blogs', 500, $e->getMessage());
    }
}

    /**
     * Store a newly created blog
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

            $blog = Blog::create($validated);

            return $this->success(
                ['blog' => $blog],
                'Blog created successfully',
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
     * Show a specific blog
     */
    public function show(Blog $blog)
    {
        try {
            return $this->success(['blog' => $blog], 'Blog fetched successfully');
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch blog', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Update a blog
     */
    public function update(Request $request, Blog $blog)
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

            $blog->update($validated);

            return $this->success(['blog' => $blog], 'Blog updated successfully');
        } catch (ValidationException $ve) {
            return $this->error('Validation failed', 422, $ve->errors());
        } catch (QueryException $qe) {
            return $this->error('Database error: ' . $qe->getMessage(), 500);
        } catch (\Throwable $e) {
            return $this->error('Failed to update blog', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Delete a blog
     */
    public function destroy(Blog $blog)
    {
        try {
            $blog->delete();
            return $this->success([], 'Blog deleted successfully');
        } catch (QueryException $qe) {
            return $this->error('Database error: ' . $qe->getMessage(), 500);
        } catch (\Throwable $e) {
            return $this->error('Failed to delete blog', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Toggle status
     */
    public function toggleStatus(Blog $blog)
    {
        try {
            $blog->status = !$blog->status;
            $blog->save();
            return $this->success(['blog' => $blog], 'Blog status updated');
        } catch (\Throwable $e) {
            return $this->error('Failed to update status', 500, ['exception' => $e->getMessage()]);
        }
    }
}
