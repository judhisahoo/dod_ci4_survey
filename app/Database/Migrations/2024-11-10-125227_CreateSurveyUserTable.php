<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSurveyUserTable extends Migration
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
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' =>true
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' =>true
            ],'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '12',
                'null' =>true
            ],'name_of_business' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' =>true
            ],
            'sector' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' =>true
            ],
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => '300',
                'null' =>true
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
        $this->forge->createTable('survey_users');
    }

    public function down()
    {
        $this->forge->dropTable('survey_users');
    }
}
