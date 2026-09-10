<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'username',
        'password',
        'email',
        'institution',
        'public_contact',
        'role',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'id'       => 'permit_empty|integer',
        'name'     => 'permit_empty|max_length[150]',
        'username' => 'required|max_length[100]|is_unique[users.username,id,{id}]',
        'password' => 'required|min_length[8]|max_length[255]',
        'email'    => 'required|valid_email|max_length[150]|is_unique[users.email,id,{id}]',
        'institution' => 'permit_empty|max_length[150]',
        'public_contact' => 'permit_empty',
        'role' => 'permit_empty|in_list[user,admin]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        if (! isset($data['data']['password']) || $data['data']['password'] === '') {
            return $data;
        }

        if (password_get_info($data['data']['password'])['algoName'] !== 'unknown') {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

        return $data;
    }
}
