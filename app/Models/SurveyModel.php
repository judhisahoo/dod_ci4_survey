<?php

namespace App\Models;

use CodeIgniter\Model;

class SurveyModel extends Model
{
    protected $table            = 'survey_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'phone', 'name_of_business', 'sector', 'address', 'status'];

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


    function getAllSubTaskAndRatting($taskId)
    {
        $db = \Config\Database::connect();
        $data = $db->table('subtasks AS st')->select('st.name as subtask_name,st.Id AS subtask_id,str.id AS subtask_ratting_id,str.name as ratting_name')->join(
            'subtasksrattings AS str',
            'st.id=str.subtask_id'
        )->where('st.task_id', $taskId)->where('st.status', 1)->get()->getResultArray();

        $subtasks = [];
        foreach ($data as $row) {
            $subtask_id = $row['subtask_id'];

            if (!isset($subtasks[$subtask_id])) {
                $subtasks[$subtask_id] = [
                    'subtask_id' => $subtask_id,
                    'subtask_name' => $row['subtask_name'],
                    'subtask_rattings' => []
                ];
            }

            if ($row['subtask_ratting_id'] !== null) { // Check if there are any subtaskratting entries
                $subtasks[$subtask_id]['subtask_rattings'][] = [
                    'subtask_ratting_id' => $row['subtask_ratting_id'],
                    'ratting_name' => $row['ratting_name'],
                ];
            }
        }

        // Convert the structured array to an indexed array if needed
        $subtasks = array_values($subtasks);

        // Output the structured data array
        return $subtasks;
    }

    function getSubTaskRattingDetails($subtask_id)
    {
        $db = \Config\Database::connect();
        $data = $db->table('subtasks AS st')->select('st.name as subtask_name,str.id AS subtask_ratting_id,str.name as ratting_name')->join(
            'subtasksrattings AS str',
            'st.id=str.subtask_id'
        )->where('st.id', $subtask_id)->where('st.status', 1)->get()->getResultArray();
        return $data;
    }

    public function getAllSurveys($perPage = 10)
    {
        return $this
            ->select('survey_users.*, mg.name AS topGroupName, smg.name AS majorGroupName, t.name AS subMajorGroupName') //, st.name AS MinorGroupName
            ->join('survey_user_survey AS sus', 'survey_users.id = sus.survey_user_id', 'left')
            //->join('survey_subtask AS ss', 'sus.id = ss.survey_user_survey_id', 'left')
            ->join('majorgroups AS mg', 'mg.id = sus.major_group_id', 'left')
            ->join('submajorgroups AS smg', 'smg.id = sus.submajor_group_id', 'left')
            ->join('tasks AS t', 't.id = sus.task_id', 'left')
            //->join('subtasks AS st', 'st.id = ss.subtask_id', 'left')
            ->where('survey_users.status', 1)
            ->orderBy('survey_users.id', 'DESC')
            ->paginate($perPage);  // Use paginate() here for pagination support
    }

    public function getAllSurveysWithoutPagination()
    {
        return $this
            ->select('survey_users.*, mg.name AS topGroupName, smg.name AS majorGroupName, t.name AS subMajorGroupName') //, st.name AS MinorGroupName
            ->join('survey_user_survey AS sus', 'survey_users.id = sus.survey_user_id', 'left')
            //->join('survey_subtask AS ss', 'sus.id = ss.survey_user_survey_id', 'left')
            ->join('majorgroups AS mg', 'mg.id = sus.major_group_id', 'left')
            ->join('submajorgroups AS smg', 'smg.id = sus.submajor_group_id', 'left')
            ->join('tasks AS t', 't.id = sus.task_id', 'left')
            //->join('subtasks AS st', 'st.id = ss.subtask_id', 'left')
            ->where('survey_users.status', 1)
            ->orderBy('survey_users.id', 'DESC')
            ->findAll(); // Use findAll() to get all records without pagination
    }
}
