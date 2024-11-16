<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateAndAddfieldToSurveyUsersTable extends Migration
{
    public function up()
    {
        ## Add age column
        $addfields = [
            'user_type' => [
                  'type' => 'INT',
                  'constraint' => '3',
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
       ];

       $this->forge->addColumn('survey_users', $addfields);
    }

    public function down()
    {
        $this->forge->dropColumn('survey_users', ['user_type','password']);
    }
}
