<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    use \App\Traits\ApiResponse; // Assuming your success, error, dataTableResponse traits are here
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Post::query()->select(['id', 'title', 'content', 'category', 'author', 'date', 'status', 'image']);
            return $this->dataTableResponse($query, $request);
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch posts', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created post
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

            $post = Post::create($validated);

            return $this->success(
                ['post' => $post],
                'Post created successfully',
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
     * Show a specific post
     */
    public function show(Post $post)
    {
        try {
            return $this->success(['post' => $post], 'Post fetched successfully');
        } catch (\Throwable $e) {
            return $this->error('Failed to fetch post', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Update a post
     */
    public function update(Request $request, post $post)
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

            $post->update($validated);

            return $this->success(['post' => $post], 'Post updated successfully');
        } catch (ValidationException $ve) {
            return $this->error('Validation failed', 422, $ve->errors());
        } catch (QueryException $qe) {
            return $this->error('Database error: ' . $qe->getMessage(), 500);
        } catch (\Throwable $e) {
            return $this->error('Failed to update post', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Delete a post
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return $this->success([], 'Post deleted successfully');
        } catch (QueryException $qe) {
            return $this->error('Database error: ' . $qe->getMessage(), 500);
        } catch (\Throwable $e) {
            return $this->error('Failed to delete post', 500, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * Toggle status
     */
    public function toggleStatus(Post $post)
    {
        try {
            $post->status = !$post->status;
            $post->save();
            return $this->success(['post' => $post], 'Post status updated');
        } catch (\Throwable $e) {
            return $this->error('Failed to update status', 500, ['exception' => $e->getMessage()]);
        }
    }
}
