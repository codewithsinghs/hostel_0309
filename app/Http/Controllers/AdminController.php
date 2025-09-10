<?php

namespace App\Http\Controllers;

use App\Models\University;
use App\Models\User;
use App\Models\Resident;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Exception;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Accessory;
use App\Models\GuestAccessory;
use Carbon\Carbon;

class AdminController extends Controller
{
    private function apiResponse($success, $message, $data = null, $statusCode = 200, $errors = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        $response['data'] = $data !== null ? $data : null;
        $response['errors'] = $errors !== null ? $errors : null;

        return response()->json($response, $statusCode);
    }

    public function getRoles()
    {
        try {
            $roles = Role::whereNotIn('name', ['admin', 'super_admin','resident','warden','security','hod'])->get();
            return $this->apiResponse(true, 'Roles fetched successfully.', $roles);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch roles.', null, 500, ['error' => $e->getMessage()]);
        }
    }
    public function getStaffRoles()
    {
        try {
            $roles = Role::whereIn('name', ['warden','security'])->get();
            return $this->apiResponse(true, 'Roles fetched successfully.', $roles);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch roles.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function getAdminProfile(Request $request)
    {
        try {
            $admin = User::Where('id', $request->header('auth-id'))
                ->first();

            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Admin not found',
                    'data' => null,
                    'errors' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin profile fetched successfully',
                'data' => $admin,
                'errors' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Admin profile',
                'data' => null,
                'errors' => ['exception' => $e->getMessage()],
            ], 500);
        }
    }


    public function getUniversity()
    {
        try {
            $admin = auth()->user();
            $university = University::findOrFail($admin->university_id);

            // Wrap university in data key
            return $this->apiResponse(true, 'University details fetched successfully.', [
                'university' => $university
            ]);
        } catch (Exception $e) {
            return $this->apiResponse(false, 'Failed to fetch university details.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function createResident(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'bed_id' => 'required|exists:beds,id',
            ]);

            $adminId = $request->header('auth-id'); // Admin ID is passed in header
            if (!$adminId) {
                return $this->apiResponse(false, 'Unauthorized.', null, 401);
            }

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $residentRole = Role::where('name', 'resident')->first();
            if (!$residentRole) {
                return $this->apiResponse(false, 'Resident role not found.', null, 500);
            }

            $user->assignRole($residentRole);

            $resident = Resident::create([
                'user_id' => $user->id,
                'bed_id' => $validatedData['bed_id'],
                'created_by' => $adminId,
            ]);

            // Return only safe data for user (hide password etc)
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ];

            return $this->apiResponse(true, 'Resident created and assigned to bed successfully.', [
                'user' => $userData,
                'resident' => $resident
            ], 201);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
        } catch (Exception $e) {
            return $this->apiResponse(false, 'An error occurred while creating resident.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function guestApproval(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'guest_id' => 'required|exists:guests,id'
            ]);

            $guest = Guest::findOrFail($validatedData['guest_id']);

            if ($guest->status === 'approved') {
                return $this->apiResponse(false, 'Payment request already approved.', null, 400);
            }

            $guest->status = 'approved';
            $guest->save();

            return $this->apiResponse(true, 'Guest approved successfully.', [
                'guest' => [
                    'id' => $guest->id,
                    'status' => $guest->status,
                ]
            ]);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
        } catch (Exception $e) {
            return $this->apiResponse(false, 'An error occurred while approving guest.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    public function guestUpdateAndVerification(Request $request,$guest_id)
    {
        // dd($request->all(), $request->file(), $request->headers->all());
        // Log::info($request->all());
        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',

                'email' => [
                    'sometimes',
                    'email',
                    Rule::unique('guests', 'email')->ignore($guest_id), // 👈 Ignore current guest
                ],
                'faculty_id' => 'sometimes|exists:faculties,id',
                'department_id' => 'sometimes|exists:departments,id',
                'course_id' => 'sometimes|exists:courses,id',
                'gender' => 'sometimes|in:Male,Female,Other',

                'scholar_number' => [
                    'sometimes',
                    Rule::unique('guests', 'scholar_number')->ignore($guest_id), // 👈 Ignore current guest
                ],

                'fathers_name' => 'sometimes|string|max:255',
                'mothers_name' => 'sometimes|string|max:255',
                'local_guardian_name' => 'nullable|string|max:255',
                'emergency_contact' => 'sometimes|string|max:20',
                'number' => 'nullable|string|max:20',
                'parent_no' => 'nullable|string|max:20',
                'guardian_no' => 'nullable|string|max:20',
                'room_preference' => 'sometimes|string|max:255',
                'food_preference' => 'sometimes|string|max:255',
                'months' => 'nullable|integer|min:1|max:12',
                'accessories_heads_id' => 'nullable|array',
                'accessories_heads_id.*' => 'exists:accessory_heads,id',
                'fee_waiver' => 'nullable|in:0,1',
                'bihar_credit_card' => 'nullable|in:0,1',
                'tnsd' => 'nullable|in:0,1',
                'is_verified' => 'nullable|in:0,1',
                'status' => 'nullable|in:pending,verified,rejected',
                'remarks' => [
                    'nullable',
                    'string',
                    'max:1000',
                    'required_if:fee_waiver,1',
                ],
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]);
            $adminId = $request->header('auth-id'); // Admin ID is passed in header
            if(!$adminId)
            {
                return $this->apiResponse(false, 'Unauthorized.', null, 401);
            }
            $guest = Guest::findOrFail($guest_id);  
            foreach ($validatedData as $key => $value) {
                if ($key !== 'accessories_heads_id' && $key !== 'attachment') { // Exclude accessory_head_ids and attachment from direct assignment
                    $guest->$key = $value;
                }
            }   
            // Handle file attachment if provided
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filePath = $file->store('guest_attachments', 'public'); // Store in 'public/guest_attachments' directory
                $guest->attachment_path = $filePath;
            }
            $guest->is_verified = $request->input('is_verified', $guest->is_verified); // Update verification status
            // $guest->status = 'verified'; // Update status to 'verified'
            $guest->created_by = $adminId; // Set the admin who verified
            $guest->save(); // Save the guest details
            // Handle accessory heads if provided
            // if (isset($validatedData['accessory_head_ids'])) {
            //     $accessoryHeadIds = $validatedData['accessory_head_ids'];
            //     $accessoryData = [];
            //     foreach ($accessoryHeadIds as $headId) {
                    
            //         $accessoryData[$headId] = [
            //             'from_date' => now()->toDateString(),
            //             'to_date' => null, // Set to null initially, can be updated later
            //             'price' => 0, // Default price, can be updated based on your logic
            //             'total_amount' => 0, // Default total amount, can be updated based on your logic
            //         ];
            //     }
            //     $guest->accessories()->sync($accessoryData); // Sync accessory heads
            // }

            // Handle accessories if provided
            $months = $validatedData['months'] ?? 3;
            if (!empty($validatedData['accessories_heads_id'])) {
                $fromDate = Carbon::now();
                $toDate = Carbon::now()->addMonths($months);

                foreach ($validatedData['accessory_head_ids'] as $headId) {
                    $accessory = Accessory::where('accessories_heads_id', $headId)
                        ->where('is_active', true)
                        ->latest('from_date')
                        ->first();

                    if ($accessory) {
                        GuestAccessory::updateOrCreate(
                        [
                            'guest_id' => $guest->id,
                            'accessories_heads_id' => $headId,   // 🔑 Unique pair
                        ],
                        [
                            'price'        => $accessory->price,
                            'total_amount' => $accessory->price * $months,
                            'from_date'    => $fromDate,
                            'to_date'      => $toDate,
                        ]);
                    }
                }

            }



            return $this->apiResponse(true, 'Guest details updated and verified successfully.', [
                'guest' => $guest->load('accessories.accessoryHead') // Load accessories with accessory head details
            ], 200);

        }
        catch(ValidationException $e)
        {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());              
        }
        catch(Exception $e)
        {
            return $this->apiResponse(false, 'An error occurred while updating guest details.', null, 500, ['error' => $e->getMessage()]);
        }
    }

    // public function rejectPaymentRequest(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'guest_id' => 'required|exists:guests,id'
    //         ]);

    //         $guest = Guest::findOrFail($validatedData['guest_id']);

    //         if ($guest->status === 'rejected') {
    //             return $this->apiResponse(false, 'Payment request already rejected.', null, 400);
    //         }

    //         $guest->status = 'rejected';
    //         $guest->save();

    //         return $this->apiResponse(true, 'Guest payment request rejected successfully.', [
    //             'guest' => [
    //                 'id' => $guest->id,
    //                 'status' => $guest->status,
    //             ]
    //         ]);
    //     } catch (ValidationException $e) {
    //         return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
    //     } catch (Exception $e) {
    //         return $this->apiResponse(false, 'An error occurred while rejecting guest payment request.', null, 500, ['error' => $e->getMessage()]);
    //     }
    // } without admin remakrs


    public function rejectPaymentRequest(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'guest_id' => 'required|exists:guests,id',
                'admin_remarks' => 'nullable|string|max:1000', // Make it nullable if not always required, otherwise 'required'
            ]);

            $guest = Guest::findOrFail($validatedData['guest_id']);

            if ($guest->status === 'rejected') {
                return $this->apiResponse(false, 'Payment request already rejected.', null, 400);
            }

            $guest->status = 'rejected';
            $guest->admin_remarks = $validatedData['admin_remarks'] ?? null; // Store the remarks
            $guest->save();

            return $this->apiResponse(true, 'Guest payment request rejected successfully.', [
                'guest' => [
                    'id' => $guest->id,
                    'status' => $guest->status,
                    'admin_remarks' => $guest->admin_remarks, // Include in response
                ]
            ]);
        } catch (ValidationException $e) {
            return $this->apiResponse(false, 'Validation failed.', null, 422, $e->errors());
        } catch (Exception $e) {
            // Log the exception for debugging purposes
            Log::error('Error rejecting guest payment request: ' . $e->getMessage(), [
                'guest_id' => $request->input('guest_id'),
                'exception' => $e
            ]);
            return $this->apiResponse(false, 'An error occurred while rejecting guest payment request.', null, 500, ['error' => $e->getMessage()]);
        }
    }




}
