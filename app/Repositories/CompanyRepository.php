<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function allWithGigsInfo(): Collection
    {
        return Company::withCount([
            'gigs as posted_gigs_count' => function ($query) {
                $query->where('status', true);
            },
            'gigs as started_gigs_count' => function ($query) {
                $query->where('start_time', '<=', Carbon::now())
                      ->where('end_time', '>=', Carbon::now());
            },
        ])->where('user_id', auth()->id())->get();
    }

    public function create(array $data): Company
    {
        return Company::create($data);
    }

    public function update(Company $company, array $data): bool
    {
        return $company->update($data);
    }

    public function delete(Company $company): bool
    {
        return $company->delete();
    }
}