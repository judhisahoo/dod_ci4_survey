<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubTaskRattingTable extends Migration
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
            'subtask_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
            ],
            'code' => [
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
        $this->forge->addForeignKey('subtask_id', 'subtasks', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('subtasksrattings');
    }

    public function down()
    {
        $this->forge->dropTable('subtasksrattings');
    }
}
