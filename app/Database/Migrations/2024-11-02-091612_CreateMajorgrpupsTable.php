<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMajorgrpupsTable extends Migration
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
                'constraint' => '250',
                'unique'     => true,
            ],
            'code' => [
                'type' => 'INT',
                'constraint' => '3',
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
        $this->forge->createTable('majorgroups');
    }

    public function down()
    {
        $this->forge->dropTable('majorgroups');
    }
}
