<?php

namespace App\Models;

use CodeIgniter\Model;

class SubtaskModel extends Model
{
    protected $table            = 'subtasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['task_id', 'name', 'code', 'status', 'created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function getLatestIdBasedOnParent($task_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table . ' t');
        $builder->where('task_id',$task_id)->orderBy('id','DESC')->limit('1');
        $query = $builder->get();
        //echo $db->getLastQuery();die;
        return $query->getRowArray();
    }
}
