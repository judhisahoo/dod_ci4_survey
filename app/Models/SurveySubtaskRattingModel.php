<?php

namespace App\Models;

use CodeIgniter\Model;

class SurveySubtaskRattingModel extends Model
{
    protected $table            = 'survey_subtask_ratting';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['survey_subtask_id','ratting_id','ratting_value'];

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

    /***
     * sub task
     * 
     */
    public function getAllRattingByMinorId($id,$userType,$taskType='subtask'){
        $db = \Config\Database::connect();
            if($userType==1){
                $rattingColumnName ="employer_ratting";
            }else{
                $rattingColumnName = "institution_ratting";
            }

            if($taskType=='subtask'){
                $taskTypeStr='ss.subtask_id';
            }else{
                $taskTypeStr='sus.task_id';
            }
            $sqlQuery="select s.name,ssr.ratting_id,round(sum(ssr.ratting_value)/count(ssr.ratting_id )) AS $rattingColumnName from survey_subtask_ratting as ssr
left join survey_subtask as ss on(ssr.survey_subtask_id=ss.id)
left join survey_user_survey sus on(ss.survey_user_survey_id =sus.id)
left  join survey_users su on(sus.survey_user_id=su.id)
left join subtasksrattings s on(ssr.ratting_id =s.id)
where $taskTypeStr=$id and su.user_type=$userType group by ssr.ratting_id order by ssr.ratting_id";
            //echo $sqlQuery;die;
            return $db->query($sqlQuery)->getResultArray();
    }
}
