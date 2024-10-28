<?php

namespace App\Repositories;

use App\Models\Gig;
use Illuminate\Support\Collection;

interface GigRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Gig;
    public function update(Gig $gig, array $data): bool;
    public function delete(Gig $gig): bool;
    public function getByCompanyId($companyIds);
    public function filterGigs($filters);
}
