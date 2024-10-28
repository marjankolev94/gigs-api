<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserProfileRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'User already registered with this email address.'], 409);
        }

        $user = $this->userRepository->register($request->validated());
        
        return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt($request->validated())) {
            $user = Auth::user();
            
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'User logged in successfully!',
                'user' => $user,
                'token' => $token
            ], 200);
        }
        
        return response()->json(['error' => 'Invalid credentials, or the User does not exists.'], 401);
    }

    public function getProfile(): JsonResponse
    {
        $user = Auth::user();

        return response()->json(['user' => $user], 200);
    }

    public function updateProfile(UserProfileRequest $request): JsonResponse
    {
        $user = Auth::user();

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $this->userRepository->updateUser($user, $request->validated());

        return response()->json(['message' => 'Profile updated successfully!', 'user' => $user], 200);
    }
}
