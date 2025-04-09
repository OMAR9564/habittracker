<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHabitsTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'level' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'current_goal' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 3, // Başlangıç hedefi 3 gün
            ],
            'current_goal_start_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'success_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 100.00,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('habits');
    }

    public function down()
    {
        $this->forge->dropTable('habits');
    }
} 