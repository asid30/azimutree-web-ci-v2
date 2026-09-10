<?php

namespace App\Models;

use CodeIgniter\Model;

class ArchiveGroupModel extends Model
{
    protected $table            = 'archive_groups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'user_id',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'name'    => 'required|max_length[150]',
        'user_id' => 'required|integer',
    ];

    public function publicList(): array
    {
        return $this->select('archive_groups.id, archive_groups.name, archive_groups.user_id, users.name AS owner_name, users.username AS owner_username, users.institution AS owner_institution, users.public_contact AS owner_public_contact, COUNT(archives.id) AS file_count, MAX(archives.upload_date) AS latest_upload')
            ->join('users', 'users.id = archive_groups.user_id', 'left')
            ->join('archives', 'archives.archive_group_id = archive_groups.id', 'left')
            ->groupBy('archive_groups.id, archive_groups.name, archive_groups.user_id, users.name, users.username, users.institution, users.public_contact')
            ->orderBy('latest_upload', 'DESC')
            ->orderBy('archive_groups.updated_at', 'DESC')
            ->findAll();
    }

    public function ownedList(int $userId): array
    {
        return $this->select('archive_groups.id, archive_groups.name, archive_groups.user_id, COUNT(archives.id) AS file_count, MAX(archives.upload_date) AS latest_upload')
            ->join('archives', 'archives.archive_group_id = archive_groups.id', 'left')
            ->where('archive_groups.user_id', $userId)
            ->groupBy('archive_groups.id, archive_groups.name, archive_groups.user_id')
            ->orderBy('archive_groups.updated_at', 'DESC')
            ->findAll();
    }

    public function findOwned(int $groupId, int $userId): ?array
    {
        return $this->where('id', $groupId)
            ->where('user_id', $userId)
            ->first();
    }
}
