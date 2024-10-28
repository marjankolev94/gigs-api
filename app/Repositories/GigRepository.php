<?php

namespace App\Repositories;

use App\Models\Gig;
use Illuminate\Support\Collection;

class GigRepository implements GigRepositoryInterface
{
    public function all(): Collection
    {
        return Gig::all();
    }
    
    public function create(array $data): Gig
    {
        return Gig::create($data);
    }

    public function update(Gig $gig, array $data): bool
    {
        return $gig->update($data);
    }

    public function delete(Gig $gig): bool
    {
        return $gig->delete();
    }

    public function getByCompanyId($companyIds)
    {
        return Gig::whereIn('company_id', $companyIds)->get();
    }

    public function filterGigs($filters): Collection
    {
        $query = Gig::query();

        // Filter by Company
        if (isset($filters['company_id'])) {
            $query->where('company_id', $filters['company_id']);
        }

        // Filter by Progress
        if (isset($filters['progress'])) {
            switch ($filters['progress']) {
                case 'not_started':
                    $query->where('start_time', '>', now());
                    break;
                case 'started':
                    $query->where('start_time', '<=', now())
                        ->where('end_time', '>=', now());
                    break;
                case 'finished':
                    $query->where('end_time', '<', now());
                    break;
            }
        }

        // Filter by Status
        if (isset($filters['status'])) {
            $filters['status'] === 'posted' ? $query->where('status', true) : $query->where('status', '!=', true);
        }

        // Filter by Search parameter
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->get();
    }

}
