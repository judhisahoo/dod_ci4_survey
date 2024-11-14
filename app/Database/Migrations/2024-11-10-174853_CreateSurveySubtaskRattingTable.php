<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSurveySubtaskRattingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'survey_subtask_id' => [
                'type' => 'INT',
                'constraint' => '3',
                'default' => "0", 
            ],
            'ratting_id' => [
                'type' => 'INT',
                'constraint' => '9',
                'default' => "0", 
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => '1',
                'default' => "0", 
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('survey_subtask_ratting');
    }

    public function down()
    {
        $this->forge->dropTable('survey_subtask_ratting');
    }
}
