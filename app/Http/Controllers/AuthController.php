<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @group Authentication
 *
 * APIs for user authentication and registration
 */
class AuthController extends Controller
{
 /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user. It should not to be here (for example it should be in a panel), but for test we need to use it.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="username", type="string", example="john_doe"),
     *                 @OA\Property(property="password", type="string", example="password123"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful registration",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="token", type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="message", type="string", example="The given data was invalid."),
     *                 @OA\Property(property="errors", type="object", example={"username":{"The username field is required."},"password":{"The password field is required."}}),
     *             )
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|string|unique:users|max:32',
            'password' => 'required|string|min:8',            
        ])->validate();

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        // Generate API token
        $token = $user->createToken('authToken')->plainTextToken;

        return response(['user' => $user, 'token' => $token]);
    }

     /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login and get an API token",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="username", type="string", example="john_doe"),
     *                 @OA\Property(property="password", type="string", example="password123"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="user", ref="#/components/schemas/User"),
     *                 @OA\Property(property="token", type="string"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(property="message", type="string", example="Invalid credentials"),
     *             )
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ])->validate();

        $user = User::where('username', $credentials['username'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response(['message' => 'Invalid credentials'], 422);
        }

        // Generate API token
        $token = $user->createToken('authToken')->plainTextToken;

        return response(['user' => $user, 'token' => $token]);
    }
}
