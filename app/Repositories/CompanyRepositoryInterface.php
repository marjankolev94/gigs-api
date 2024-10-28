<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyRepositoryInterface
{
    public function allWithGigsInfo(): Collection;
    public function create(array $data): Company;
    public function update(Company $company, array $data): bool;
    public function delete(Company $company): bool;
}