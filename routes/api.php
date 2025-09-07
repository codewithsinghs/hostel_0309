<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payment;
use PHPUnit\TextUI\Help;
use App\Services\SmsService;
use Illuminate\Http\Request;
use App\Services\MailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\BedController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\MessController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PaytmController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\NoticeController;
use App\Http\Middleware\CheckValidReferer;
use App\Http\Controllers\FeeHeadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ResidentController;
use App\Notifications\CustomAppNotification;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\FacultiesController;
use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoomChangeController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\FeeExceptionController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AccessoryHeadController;
use App\Http\Controllers\Api\V1\ReportController;
use App\Http\Controllers\Apis\V1\LoginController;
use App\Http\Controllers\Vue\Api\NAuthController;
use App\Http\Controllers\StudentAccessoryController;
use App\Http\Controllers\RoomChangeMessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



// Route::middleware(['auth:sanctum'])->get('/admin-dashboard', function () {
//     return response()->json(['message' => 'Welcome Admin']);
// });

Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
Route::get('/faculties/{faculty_id}/departments', [DepartmentController::class, 'getActiveDepartments']);
Route::get('/departments/{department_id}/courses', [CourseController::class, 'getActiveCourses']);


Route::post('/login', [AuthController::class, 'login']);
// Guest Routes 
Route::post('/guests', [GuestController::class, 'register']); // Guest registers
Route::post('/guest/login', [LoginController::class, 'guestLogin'])->name('guest.guest_login');

Route::get('accessories/active/{facultyId}', [AccessoryController::class, 'getPublicActiveAccessories']);

//Guest API Auth
Route::middleware(['guest_api_auth'])->group(function () {
    Route::get('/guest/profile', [GuestController::class, 'getGuestProfile']);
    Route::post('guest-authentication', [LoginController::class, 'AuthenticateGuests']);
    Route::get('/guest/approved-rejected-guest', [GuestController::class, 'getApprovedOrRejectedGuests']);
    Route::get('/guest/total-amount', [GuestController::class, 'getGuestTotalAmount']);
    Route::get('/guests/paid', [GuestController::class, 'getPaidGuests']);
    Route::get('/guests/pending', [GuestController::class, 'pendingGuests']);
    Route::get('guests/accessories/active', [AccessoryController::class, 'guestActiveAccessories']);

    Route::post('guests/guest-payments', [PaymentController::class, 'guestPayment']); // Guest makes payment

    Route::post('payments/initiate', [GuestController::class, 'initiateGuestPayment'])->name('payments.initiate');
    Route::get('/guests/payment/status', [PaytmController::class, 'PaymentStatus']);
});

Route::post('admin/login', [LoginController::class, 'adminLogin']);
Route::post('logout', [LoginController::class, 'logout']);

Route::middleware(['admin_api_auth'])->group(function () {

    Route::post('authenticate-users', [LoginController::class, 'AuthenticateUsers']);

    //Super Admin Routes
    Route::prefix('superadmin')->group(function () {

        Route::get('profile', [AdminController::class, 'getAdminProfile']);

        //Super Admin Routes    
        Route::post('universities/create', [UniversityController::class, 'store']);
        Route::get('universities', [UniversityController::class, 'index']);
        Route::put('universities/{id}', [UniversityController::class, 'update']);

        Route::post('create-admin', [SuperAdminController::class, 'createAdmin']);
        Route::get('admins', [SuperAdminController::class, 'getAdmins']);
        Route::get('admins/{id}', [SuperAdminController::class, 'getAdmin']);
        Route::put('admins/{id}', [SuperAdminController::class, 'updateAdmin']);
        // Route::delete('admin/admins/{id}', [SuperAdminController::class, 'deleteAdmin']);
    });

    Route::prefix('hod')->group(function () {
        Route::get('leave-requests', [LeaveRequestController::class, 'allLeaveRequests']);
        Route::patch('leave-requests/{id}/hod-approve', [LeaveRequestController::class, 'hodApprove']);
        Route::patch('leave-requests/{id}/hod-deny', [LeaveRequestController::class, 'hodDeny']);
    });
    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('roles', [AdminController::class, 'getRoles']); // Get all buildings
        Route::get('staff-roles', [AdminController::class, 'getStaffRoles']); // Get all buildings
        Route::get('buildings', [BuildingController::class, 'index']); // Get all buildings
        Route::post('buildings/create', [BuildingController::class, 'store']);
        Route::get('buildings/{id}', [BuildingController::class, 'show']); // Get single building
        Route::put('buildings/{id}', [BuildingController::class, 'update']); // Update building
        Route::delete('buildings/{id}', [BuildingController::class, 'destroy']); // Delete building

        //Faculties Apis
        Route::get('faculties', [FacultiesController::class, 'index']); // Get all buildings
        Route::post('faculties/create', [FacultiesController::class, 'store']);
        Route::get('faculties/{id}', [FacultiesController::class, 'show']); // Get single building
        Route::put('faculties/{id}', [FacultiesController::class, 'update']); // Update building
        Route::delete('faculties/{id}', [FacultiesController::class, 'destroy']); // Delete building

        // Departments Apis
        Route::get('departments', [DepartmentController::class, 'index']);
        Route::post('departments/create', [DepartmentController::class, 'store']);
        Route::get('departments/{id}', [DepartmentController::class, 'show']);
        Route::put('departments/{id}', [DepartmentController::class, 'update']);
        Route::delete('departments/{id}', [DepartmentController::class, 'destroy']); // Delete department

        // Courses Apis
        Route::get('courses', [CourseController::class, 'index']);
        Route::post('courses/create', [CourseController::class, 'store']);
        Route::get('courses/{id}', [CourseController::class, 'show']);
        Route::put('courses/{id}', [CourseController::class, 'update']);
        Route::delete('courses/{id}', [CourseController::class, 'destroy']); // Delete department

        // Route::apiResource('rooms', RoomController::class);
        Route::get('buildings/{id}/rooms', [RoomController::class, 'getRooms']); //Total Rooms status of a building
        Route::get('rooms', [RoomController::class, 'index']); // Get all rooms from all buildings
        Route::get('rooms/{id}', [RoomController::class, 'show']); // Get single room
        Route::post('rooms/create', [RoomController::class, 'store']); // Create room
        Route::put('rooms/{id}', [RoomController::class, 'update']); // Update room
        Route::delete('rooms/{id}', [RoomController::class, 'destroy']); // Delete room

        // Route::get('/check-rooms', [AdminController::class, 'checkAvailableRooms']); // Check available rooms

        // Route::apiResource('beds', BedController::class);
        Route::get('beds', [BedController::class, 'index']); // Get all beds
        Route::get('beds/{id}', [BedController::class, 'show']); // Get single bed
        Route::post('beds/create', [BedController::class, 'store']); // Create bed
        Route::put('beds/update/{id}', [BedController::class, 'update']); // Update bed
        Route::delete('beds/{id}', [BedController::class, 'destroy']); // Delete bed
        Route::get('rooms/{room_id}/available-beds', [BedController::class, 'getAvailableBeds']);

        //FETCH RESIDENTS
        Route::get('residents', [ResidentController::class, 'getAllResidents']);
        Route::get('residents/unassigned', [ResidentController::class, 'getUnassignedResidents']);
        Route::get('residents/{id}', [ResidentController::class, 'getResidentById']);
        Route::post('/assign-bed', [ResidentController::class, 'assignBed']); // Assign bed to resident

        //For Guests Apis
        Route::get('/guests/pending', [GuestController::class, 'pendingGuestsForAccountant']);
        Route::get('/guests', [GuestController::class, 'guestsStatus']);
        Route::get('/guests/{guest_id}', [GuestController::class, 'guestDetails']);
        Route::get('/guests/status', [GuestController::class, 'guestsStatus']);
        Route::post('/approve-guest', [AdminController::class, 'guestApproval']); // Send payment request
        Route::get('/guest/{guest}/fee-exception-details', [FeeExceptionController::class, 'getFeeExceptionDetailsForEdit']);

        //For Waiver Guests
        // Route::post('/approved-waiver', [FeeExceptionController::class, 'adminWaiverApproved']); // Send payment request
        Route::post('/modify-waiver/payments', [FeeExceptionController::class, 'store']); // Send payment request
        Route::post('/reject-waiver', [FeeExceptionController::class, 'waiverRejected']); // Send payment request

        Route::get('/allPendingPayments', [PaymentController::class, 'getAllPendingPayments']);

        // Acccessories
        Route::get('accessories', [AccessoryController::class, 'getAllAccessories']);
        Route::post('accessories/create-or-update', [AccessoryController::class, 'createOrUpdate']);
        // Route::put('accessories/{id}', [AccessoryController::class, 'update']);
        Route::post('assign-accessories', [StudentAccessoryController::class, 'adminSendAccessoryToResident']);
        Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
        Route::get('accessories/{resident_id}', [CheckoutController::class, 'getAccessoryByResidentId']);
        // Route::get('default/accessories/{resident_id}', [CheckoutController::class, 'getDefaultAccessoryByResidentId']);



        //Accessory head 
        Route::prefix('accessories-master')->group(function () {
            Route::post('/add', [AccessoryHeadController::class, 'store']);
            Route::post('/update/{id}', [AccessoryHeadController::class, 'update']); // No PUT, use POST
            Route::get('/', [AccessoryHeadController::class, 'index']);
        });

        Route::post('admin/create', [StaffController::class, 'createAdmin']);
        Route::get('admin-list', [StaffController::class, 'getAllAdmin']);
        Route::get('admin/{id}', [StaffController::class, 'getAdminDetails']);
        Route::put('admin/update/{id}', [StaffController::class, 'updateAdmin']);

        Route::post('staff/create', [StaffController::class, 'createStaff']);
        Route::get('staff-list', [StaffController::class, 'getAllStaff']);
        Route::get('staff/{id}', [StaffController::class, 'getStaffDetails']);
        Route::put('staff/update/{id}', [StaffController::class, 'updateStaff']);

        Route::post('hods/create', [StaffController::class, 'createHod']);
        Route::get('hods-list', [StaffController::class, 'getAllHods']);
        Route::get('hods/{id}', [StaffController::class, 'getHodDetails']);
        Route::put('hods/update/{id}', [StaffController::class, 'updateHod']);

        // Admin fetches all room change requests
        Route::get('room-change/requests', [RoomChangeController::class, 'getAllRoomChangeRequests']);
        // Route::get('room-change/requests', [RoomChangeController::class, 'getAllRequests']);

        // Admin responds with available/not available + optional remark
        Route::post('room-change/respond/{request_id}', [RoomChangeController::class, 'respondToRequest']);
        // Send a message in the conversation
        Route::post('room-change/message/{request_id}', [RoomChangeMessageController::class, 'sendMessage']);
        // Fetch all messages in a conversation
        Route::get('room-change/all-messages/{request_id}', [RoomChangeMessageController::class, 'getMessages']);

        // ➡️ Final Approval by Admin when resident has agreed
        Route::post('room-change/final-approval/{request_id}', [RoomChangeController::class, 'finalApproval']);
        Route::put('room-change/deny/{request_id}', [RoomChangeController::class, 'denyRoomChangeByAdmin']);

        //Checkout Process
        Route::put('accessory/checking/{residentId}', [CheckoutController::class, 'adminAccessoryChecking']);
        Route::put('checkout/admin-approval/{id}', [CheckoutController::class, 'adminApproval']); // Admin final approval
        Route::get('resident/all-checkout-requests', [CheckoutController::class, 'getAllCheckoutRequests']);
        Route::get('resident-checkout-logs/{residentId}', [CheckoutController::class, 'adminGetCheckoutLogs']);

        // leave request APIs
        Route::get('leave-requests', [LeaveRequestController::class, 'allLeaveRequests']);
        Route::patch('leave-requests/{id}/admin-approve', [LeaveRequestController::class, 'adminApprove']);
        Route::patch('leave-requests/{id}/admin-deny', [LeaveRequestController::class, 'adminDeny']);
        Route::get('residents/{residentId}/leave-requests', [LeaveRequestController::class, 'leaveReqById']);



        // Admin Created Resident
        Route::post('residents/create', [AdminController::class, 'createResident']);
        Route::get('profile', [AdminController::class, 'getAdminProfile']);

        Route::post('fine', [FineController::class, 'adminSetFine']);

        // Fetch all grievances for the admin
        Route::get('/grievances', [GrievanceController::class, 'getAllGrievances']);
        // Fetch grievance by ID for admin/resident
        Route::get('grievances/{id}', [GrievanceController::class, 'getGrievanceById']);
        // Admin can respond to a grievance
        Route::post('grievances/respond', [GrievanceController::class, 'respondToGrievance']);

        Route::get('/feedbacks', [FeedbackController::class, 'feedbacksForAdmin']);   // View All Feedbacks

        Route::post('/notices', [NoticeController::class, 'store']); // Create Notice
        Route::get('/notices/{id}', [NoticeController::class, 'show']); // Get Single Notice
        Route::put('/notices/{id}', [NoticeController::class, 'update']); // Update Notice
        Route::delete('/notices/{id}', [NoticeController::class, 'destroy']); // Delete Notice
        Route::get('notices', [NoticeController::class, 'index']); // Get All Notices

    });

    Route::prefix('accountant')->group(function () {
        // Accountant Routes
        Route::get('checkout-requests', [CheckoutController::class, 'getAllCheckoutRequests']);
        Route::put('checkout/account-approval/{id}', [CheckoutController::class, 'accountApproval']); // Accounts approval
        Route::post('update-guest-status', [FeeExceptionController::class, 'updateGuestStatusWithRemark']);
        Route::get('resident-checkout-logs/{residentId}', [CheckoutController::class, 'adminGetCheckoutLogs']);
        Route::get('residents', [ResidentController::class, 'getAllResidents']);
        Route::get('resident/{resident_id}/subscription', [SubscriptionController::class, 'getResidentSubscriptions']);
        Route::get('/pending/{resident_id}/subscription', [SubscriptionController::class, 'getPendingResidentSubscriptions']);
        Route::post('subscribe/pay', [PaymentController::class, 'accountSubscribePay']);
        // Acccessories
        Route::get('/resident/{resident_id}/accessories', [PaymentController::class, 'getAccessoryPendingPayments']);
        Route::post('/residents/{resident_id}/accessories/{accessory_id}/pay', [StudentAccessoryController::class, 'payAccessory']);
        Route::get('/payments/resident/{id}', [PaymentController::class, 'getPaymentsByResident']);

        //Fee
        Route::get('/fees', [FeeController::class, 'getAllFees']);
        Route::get('/activeFees', [FeeController::class, 'getAllActiveFees']);
        Route::post('/admin/addOrUpdateFees', [FeeController::class, 'createOrUpdate']);
        Route::get('/fee-heads', [FeeHeadController::class, 'index']);
        //Fee head
        Route::post('/fee-heads', [FeeHeadController::class, 'store']);
        Route::put('/fee-heads/{id}', [FeeHeadController::class, 'update']);

        Route::get('/guest/{guest}/fee-exception', [FeeExceptionController::class, 'getFeeExceptionDetailsForEdit']);

        // Fine Routes
        Route::post('set-fine-amount', [FineController::class, 'accountantSetFineAmount']);
        Route::get('view-fine-details', [FineController::class, 'viewAllFineDetails']);
    });

    Route::prefix('admission')->group(function () {
        Route::put('/verify-guest/{guest_id}', [AdminController::class, 'guestUpdateAndVerification']); // Send payment request
        Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
        Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
        Route::get('/faculties/{faculty_id}/departments', [DepartmentController::class, 'getActiveDepartments']);
        Route::get('/departments/{department_id}/courses', [CourseController::class, 'getActiveCourses']);
    });

    Route::prefix('resident')->group(function () {
        //Resident Routes
        //Leave Request
        Route::post('leave', [LeaveRequestController::class, 'store']);
        Route::get('leave-requests', [LeaveRequestController::class, 'leaveRequestByResident']);
        // Resident requests room change
        Route::post('room-change/request', [RoomChangeController::class, 'requestRoomChange']);
        // Resident fetches their room change requests
        Route::get('room-change/requests', [RoomChangeController::class, 'getRoomChangeRequests']);
        Route::get('room-change/requests/{id}', [RoomChangeController::class, 'getRoomChangeRequestsById']);
        Route::get('{residentId}/room-change-requests', [RoomChangeController::class, 'getRoomChangeRequestsByResidentId']);

        //Messages in Room Change Requests
        Route::get('room-change/all-messages/{request_id}', [RoomChangeMessageController::class, 'getMessages']);
        Route::post('room-change/message/{request_id}', [RoomChangeMessageController::class, 'sendMessage']);

        Route::post('feedbacks', [FeedbackController::class, 'store']);  // Submit Feedback

        Route::get('notices', [NoticeController::class, 'index']); // Get All Notices
        Route::get('pending/subscription', [SubscriptionController::class, 'getResidentSubscriptions']);
        // // Resident respond 
        // Route::post('resident/room-change/respond-to-admin/{request_id}', [RoomChangeController::class, 'respondToAdmin']);

        // Resident sends agree/deny
        Route::post('room-change/confirm-by-resident/{request_id}', [RoomChangeController::class, 'confirmRoomChange']);

        Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);

        //Accessory Routes
        // Route::get('accessories', [AccessoryController::class, 'ResidentAccessories']);
        Route::post('accessories', [StudentAccessoryController::class, 'addAccessory']); // Resident Add Accessory
        Route::get('accessories-pending-payments', [PaymentController::class, 'getAccessoryPendingPayments']);
        Route::post('accessories/{accessory_id}/pay', [StudentAccessoryController::class, 'payAccessory']);

        Route::post('checkout/request', [CheckoutController::class, 'requestCheckout']);

        Route::get('checkout-status', [CheckoutController::class, 'getCheckoutStatus']);
        Route::get('checkout-logs', [CheckoutController::class, 'getCheckoutLogs']);
        Route::get('profile', [ResidentController::class, 'getResidentProfile']);

        Route::prefix('grievances')->group(function () {
            // Submit a grievance
            Route::post('/submit', [GrievanceController::class, 'submitGrievance']);

            // Resident can respond with a message and can agree/disagree with the response
            Route::post('/respond/{id}', [GrievanceController::class, 'residentRespond']);

            // Fetch all grievances for the admin
            Route::get('/', [GrievanceController::class, 'getGrievancesByResident']);

            // Fetch grievance by ID for admin/resident
            Route::get('/{id}', [GrievanceController::class, 'getGrievanceById']);

            // Close grievance by resident (final resolution)
            Route::put('/close/{id}', [GrievanceController::class, 'closeGrievance']);
        });
    });
});


Route::get('/accountant/guests/pending', [GuestController::class, 'pendingGuestsForAccountant']);






Route::get('/feedbacks', [FeedbackController::class, 'index']);   // View All Feedbacks
Route::get('/feedbacks/{id}', [FeedbackController::class, 'show']); // View Single Feedback







Route::get('/fees', [FeeController::class, 'getAllFees']);
Route::put('/fees/{id}', [FeeController::class, 'updateFeeById']);
Route::post('/admin/add-fees', [FeeController::class, 'addOrUpdateFees']);


//Fee
Route::get('/fees', [FeeController::class, 'getAllFees']);
Route::get('accountant/activeFees', [FeeController::class, 'getAllActiveFees']);
Route::post('/admin/addOrUpdateFees', [FeeController::class, 'createOrUpdate']);
Route::get('accountant/fee-heads', [FeeHeadController::class, 'index']);
//Fee head
Route::post('accountant/fee-heads', [FeeHeadController::class, 'store']);
Route::put('/fee-heads/{id}', [FeeHeadController::class, 'update']);


// Payment Routes
Route::post('/resident/pay', [PaymentController::class, 'payAsResident']);
Route::post('/payment/reject', [AdminController::class, 'rejectPaymentRequest']);



Route::post('/payments/{payment_id}/pay', [PaymentController::class, 'subscribePay']);
Route::get('/allPendisgPayments', [PaymentController::class, 'getAllPendingPayments']);



Route::post('/payments/pay', [PaymentController::class, 'makePayment']);
Route::get('/payments/pending/{resident_id}', [PaymentController::class, 'getPendingPayments']);

Route::get('/payments', [PaymentController::class, 'getAllPayments']);


Route::post('/residents/subscribe', [SubscriptionController::class, 'subscribeToService']);
Route::post('/subscription/pay', [PaymentController::class, 'subscribePay']);
Route::get('/pending/{resident_id}/subscription', [SubscriptionController::class, 'getPendingResidentSubscriptions']);

Route::get('/payments/all/resident/{resident_id}', [PaymentController::class, 'getAllPaymentsByResidentId']);
Route::get('/combined/pending/subscription', [SubscriptionController::class, 'getCombinedSubscription']);
Route::post('/admin/subscribe-resident', [SubscriptionController::class, 'adminSubscribeResident']);



Route::get('/resident/all', [StaffController::class, 'getresidents']);

Route::get('/subscription_payment', function () {
    return view('resident.subscription_payment');
})->name('resident.subscription.payment');




Route::get('/get-payment-id', function (Request $request) {
    $residentId = $request->query('resident_id');
    $subscriptionId = $request->query('subscription_id');

    //Log::info("Fetching payment ID for Resident ID: $residentId, Subscription ID: $subscriptionId ");

    $payment = Payment::where('resident_id', $residentId)
        ->where('subscription_id', $subscriptionId)
        ->first();

    if ($payment) {
        // Log::info("Payment ID found: " . $payment->id);
        return response()->json(['payment_id' => $payment->id]);
    } else {
        // Log::warning("No payment ID found for Resident ID: $residentId, Subscription ID: $subscriptionId");
        return response()->json(['error' => 'Payment ID not found'], 404);
    }
});


Route::get('/messes', [MessController::class, 'index']);



//In app notifications
Route::post('/send-notification', function (Request $request) {
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'message' => 'required|string'
    ]);

    $user = User::find($request->user_id);
    $user->notify(new CustomAppNotification($request->message));

    return response()->json(['success' => true, 'message' => 'Notification sent.']);
});



//AWS SMS Service for test
Route::post('/send-sms', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'phone' => 'required|string',
        'message' => 'required|string|max:160'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422);
    }

    $response = SmsService::send($request->phone, $request->message);

    return response()->json([
        'success' => $response['success'],
        'message' => $response['success'] ? 'SMS sent successfully' : 'SMS failed to send',
        'data' => $response,
    ]);
});



// //AWS Mail Service for test
// Route::post('/send-mail', function (Request $request) {
//     $validator = Validator::make($request->all(), [
//         'email' => 'required|email',
//         'subject' => 'required|string',
//         'name' => 'required|string',
//         'body' => 'required|string',
//     ]);

//     if ($validator->fails()) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Validation failed',
//             'errors' => $validator->errors()
//         ], 422);
//     }

//     $response = MailService::send(
//         $request->email,
//         $request->subject,
//         'emails.hostel_welcome',
//         [
//             'name' => $request->name,
//             'body' => $request->body
//         ]
//     );

//     return response()->json([
//         'success' => $response['success'],
//         'message' => $response['message'] ?? 'Mail operation done',
//         'data' => $response
//     ]);
// });



// Notification API Routes
Route::prefix('notifications')->group(function () {

    Route::get('resident/{residentId}', [NotificationController::class, 'getPaginatedResidentNotifications']);

    Route::get('resident/{residentId}/all', [NotificationController::class, 'getAllResidentNotifications']);

    Route::get('resident/{residentId}/unread', [NotificationController::class, 'getUnreadResidentNotifications']);

    Route::post('{id}/read', [NotificationController::class, 'markNotificationAsRead']);

    Route::get('payments', [NotificationController::class, 'getPaymentNotifications']);
});


Route::get('/accessories', [AccessoryController::class, 'index']);
Route::post('/register', [NAuthController::class, 'register']);
Route::post('/login', [NAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/ndashboard', [NAuthController::class, 'dashboard']);
    Route::post('/logout', [NAuthController::class, 'logout']);

     Route::patch('/users/{user}/toggle-status', [NAuthController::class, 'toggleStatus']);
    Route::delete('/users/{user}', [NAuthController::class, 'destroy']);
    Route::get('/users/{user}', [NAuthController::class, 'show']);
    Route::post('/users/store', [NAuthController::class, 'store']);

    
    // Route::prefix('admin')->group(function () {
    //     Route::apiResource('reports', ReportController::class);
    //     Route::patch('reports/{report}/toggle-status', [ReportController::class, 'toggleStatus'])->name('admin.reports.toggle-status');
    //     Route::apiResource('blogs', BlogController::class);
    //     Route::patch('blogs/{blog}/status', [BlogController::class, 'toggleStatus']);
    //     Route::apiResource('posts', PostController::class);
    //     Route::patch('posts/{post}/status', [PostController::class, 'toggleStatus']);
    //     Route::apiResource('events', EventController::class);
    //     Route::patch('events/{event}/status', [EventController::class, 'toggleStatus']);
    

    // });


    Route::prefix('admin')->group(function () {

        Route::middleware(['role:admin|editor|viewer'])->group(function () {
            Route::apiResource('reports', ReportController::class)->middleware('permission:reports.view');
            Route::patch('reports/{report}/toggle-status', [ReportController::class, 'toggleStatus'])->middleware('permission:reports.toggle-status');
    
            Route::apiResource('blogs', BlogController::class);
            Route::patch('blogs/{blog}/status', [BlogController::class, 'toggleStatus']);
    
            Route::apiResource('posts', PostController::class);
            Route::patch('posts/{post}/status', [PostController::class, 'toggleStatus']);
    
            Route::apiResource('events', EventController::class);
            Route::patch('events/{event}/status', [EventController::class, 'toggleStatus']);
        });
    });
    

});

// Route::middleware(['auth:sanctum', 'api.role:admin'])->prefix('admin')->group(function () {
//     Route::get('reports', [ReportController::class, 'index']);
//     Route::patch('reports/{report}/toggle-status', [ReportController::class, 'toggleStatus']);
//     Route::apiResource('blogs', BlogController::class);
// });
// // Route::middleware(['auth:sanctum', 'api.role:admin|manager'])->get('/dashboard', [DashboardController::class, 'index']);
// Route::middleware(['auth:sanctum', 'api.permission:reports.view'])
//     ->get('/admin/reports', [ReportController::class, 'index']);


// Route::apiResource('/admin/blogs', BlogController::class);
// Route::middleware('auth:sanctum')->get('/ndashboard', [NAuthController::class, 'dashboard']);
// Route::middleware('auth:sanctum')->get('/ndashboard', function (Request $request) {
//     return response()->json([
//         'status' => true,
//         'user'   => $request->user(),
//     ]);
// });

// Route::get('/ndashboard', [NAuthController::class, 'dashboard'])->middleware('auth:sanctum');