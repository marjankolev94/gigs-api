<?php

namespace App\Http\Controllers\Api;

use App\Models\Gig;
use App\Http\Controllers\Controller;
use App\Http\Requests\GigRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\GigRepositoryInterface;

class GigController extends Controller
{
    protected $gigRepository;

    public function __construct(GigRepositoryInterface $gigRepository)
    {
        $this->gigRepository = $gigRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['company_id', 'progress', 'status', 'search']);
       
        if(empty($filters)) {
            // Get gigs associated with the authenticated user's company
            $companyIds = Auth::user()->companies()->pluck('id');

            $gigs = $this->gigRepository->getByCompanyId($companyIds);
        } else {
            // Filter Gigs by Parameters or by Search parameters
            $gigs = $this->gigRepository->filterGigs($filters);
        }
        

        return response()->json($gigs);
    }

    public function store(GigRequest $request): JsonResponse
    {
        if (!$request->user()->companies()->where('id', $request->company_id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $gig = $this->gigRepository->create($request->validated());

        return response()->json(['message' => 'Gig created successfully!', 'gig' => $gig], 201);
    }

    public function update(GigRequest $request, Gig $gig): JsonResponse
    {
        if ($gig->company->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->gigRepository->update($gig, $request->validated());

        return response()->json(['message' => 'Gig updated successfully!', 'gig' => $gig], 200);
    }

    public function destroy(Gig $gig): JsonResponse
    {
        if ($gig->company->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->gigRepository->delete($gig);

        return response()->json(['message' => 'Gig deleted successfully!'], 200);
    }
}
