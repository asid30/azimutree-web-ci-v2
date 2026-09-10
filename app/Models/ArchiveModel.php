<?php

namespace App\Models;

use CodeIgniter\Model;

class ArchiveModel extends Model
{
    protected $table            = 'archives';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'archive_group_id',
        'cluster_code',
        'filename',
        'original_filename',
        'cl_location',
        'cl_location_id',
        'uploaded_by',
        'user_id',
        'upload_date',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'archive_group_id'   => 'required|integer',
        'cluster_code'       => 'required|integer',
        'filename'          => 'required|max_length[255]',
        'original_filename' => 'required|max_length[255]',
        'cl_location'       => 'permit_empty|max_length[150]',
        'cl_location_id'    => 'permit_empty|integer',
        'uploaded_by'       => 'required|max_length[100]',
        'user_id'           => 'required|integer',
        'upload_date'       => 'required|valid_date[Y-m-d H:i:s]',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    public function filesByGroup(int $groupId): array
    {
        return $this->select('archives.id, archives.archive_group_id, archives.cluster_code, archives.name, archives.original_filename, archives.uploaded_by, archives.user_id, archives.upload_date, users.name AS uploader_name, users.username AS uploader_username, users.institution AS uploader_institution, users.public_contact AS uploader_public_contact')
            ->join('users', 'users.id = archives.user_id', 'left')
            ->where('archives.archive_group_id', $groupId)
            ->orderBy('archives.cluster_code', 'ASC')
            ->findAll();
    }

    public function ownedFilesByGroup(int $groupId, int $userId): array
    {
        return $this->select('archives.id, archives.archive_group_id, archives.cluster_code, archives.name, archives.original_filename, archives.uploaded_by, archives.user_id, archives.upload_date, users.name AS uploader_name, users.username AS uploader_username, users.institution AS uploader_institution, users.public_contact AS uploader_public_contact')
            ->join('users', 'users.id = archives.user_id', 'left')
            ->where('archives.archive_group_id', $groupId)
            ->where('archives.user_id', $userId)
            ->orderBy('archives.cluster_code', 'ASC')
            ->findAll();
    }

    public function publicList()
    {
        return $this->select('archives.id, archives.archive_group_id, archives.cluster_code, archives.name, archives.original_filename, COALESCE(cluster_locations.cl_location, archives.cl_location) AS cl_location, archives.uploaded_by, archives.user_id, archives.upload_date, users.name AS uploader_name, users.username AS uploader_username, users.institution AS uploader_institution, users.public_contact AS uploader_public_contact')
            ->join('cluster_locations', 'cluster_locations.id = archives.cl_location_id', 'left')
            ->join('users', 'users.id = archives.user_id', 'left')
            ->orderBy('archives.upload_date', 'DESC')
            ->findAll();
    }

    public function ownedList(int $userId)
    {
        return $this->select('archives.id, archives.archive_group_id, archives.cluster_code, archives.name, archives.original_filename, COALESCE(cluster_locations.cl_location, archives.cl_location) AS cl_location, archives.uploaded_by, archives.user_id, archives.upload_date, users.name AS uploader_name, users.username AS uploader_username, users.institution AS uploader_institution, users.public_contact AS uploader_public_contact')
            ->join('cluster_locations', 'cluster_locations.id = archives.cl_location_id', 'left')
            ->join('users', 'users.id = archives.user_id', 'left')
            ->where('archives.user_id', $userId)
            ->orderBy('archives.upload_date', 'DESC')
            ->findAll();
    }

    public function findOwnedArchive(int $archiveId, int $userId)
    {
        return $this->where('id', $archiveId)
            ->where('user_id', $userId)
            ->first();
    }
}
