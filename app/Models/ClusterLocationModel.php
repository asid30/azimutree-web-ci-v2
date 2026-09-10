<?php

namespace App\Models;

use CodeIgniter\Model;

class ClusterLocationModel extends Model
{
    protected $table            = 'cluster_locations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'cl_location',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id'          => 'permit_empty|integer',
        'cl_location' => 'required|max_length[150]|is_unique[cluster_locations.cl_location,id,{id}]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    public function options(): array
    {
        return $this->orderBy('cl_location', 'ASC')->findAll();
    }

    public function findOrCreate(string $location): array
    {
        $location = trim($location);
        $existing = $this->where('cl_location', $location)->first();

        if ($existing) {
            return $existing;
        }

        $id = $this->insert([
            'cl_location' => $location,
        ], true);

        return $this->find($id);
    }
}
