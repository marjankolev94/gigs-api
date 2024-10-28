<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CompanyRepositoryInterface;

class CompanyController extends Controller
{
    protected $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }
    
    public function index(): JsonResponse
    {
        $companies = $this->companyRepository->allWithGigsInfo();

        return response()->json($companies);
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        $company = $this->companyRepository->create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'user_id' => Auth::id(), // Associate the company with the logged-in user
        ]);

        return response()->json(['message' => 'Company created successfully!', 'company' => $company], 201);
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        // Check if the authenticated user is the owner of the company
        if ($company->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->companyRepository->update($company, [
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
        ]);

        return response()->json(['message' => 'Company updated successfully!', 'company' => $company], 200);
    }

    public function destroy(Company $company): JsonResponse
    {
        if ($company->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->companyRepository->delete($company);

        return response()->json(['message' => 'Company deleted successfully!'], 200);
    }
}
