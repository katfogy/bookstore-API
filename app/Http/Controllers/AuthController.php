<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Exception;
use Log;

/**
 * @OA\Info(
 *     title="Bookstore API",
 *     version="1.0",
 *     description="API documentation for the Bookstore application.",
 *     @OA\Contact(name="Foga Kater Amos", email="katfogy@gmail.com"),
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Create a new user by providing name, email, and password.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User has been registered successfully!"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Registration failed"
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($user) {
                return ResponseHelper::success(message: 'User has been registered successfully!', data: $user, statusCode: 201);
            }
            return ResponseHelper::error(message: 'Unable to Register new user, Please try again', statusCode: 400);
        } catch (Exception $e) {
            Log::error('Unable to register User:' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to Register new user, Please try again', statusCode: 400);
        }
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Login a user",
     *     description="Authenticate user by email and password.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="You are logged in successfully!"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return ResponseHelper::error(message: 'Unable to Login due to invalid Credentials', statusCode: 400);
            }

            $user = Auth::user();
            $token = $user->createToken('My API Token')->plainTextToken;
            $authUser = [
                'user' => $user,
                'token' => $token
            ];

            return ResponseHelper::success(message: 'You are logged in successfully!', data: $authUser, statusCode: 201);
        } catch (Exception $e) {
            Log::error('Unable to login User:' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to Login, Please try again', statusCode: 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/profile",
     *     tags={"Authentication"},
     *     summary="Get user profile",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User profile fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User Profile Fetch Successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Unable to Fetch User Profile"
     *     )
     * )
     */
    public function userProfile()
    {
        try {
            $user = Auth::user();
            if ($user) {
                return ResponseHelper::success(message: 'User Profile Fetch Successfully', data: $user, statusCode: 201);
            } else {
                return ResponseHelper::error(message: 'Unable to Fetch User Profile due to invalid records', statusCode: 400);
            }
        } catch (Exception $e) {
            Log::error('Unable to Fetch user data:' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to Fetch User Data', statusCode: 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="Logout a user",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Logout successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout Successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Unable to Logout due to invalid Token"
     *     )
     * )
     */
    public function userLogout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(message: 'Logout Successfully', data: $user, statusCode: 201);
            } else {
                return ResponseHelper::error(message: 'Unable to Logout due to invalid Token', statusCode: 400);
            }
        } catch (Exception $e) {
            Log::error('Unable to Logout due to:' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to Logout', statusCode: 500);
        }
    }
}

