<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGoalsHistoryTable extends Migration
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
            'habit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'goal_days' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'is_completed' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'completion_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0.00,
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
        $this->forge->addForeignKey('habit_id', 'habits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('goals_history');
    }

    public function down()
    {
        $this->forge->dropTable('goals_history');
    }
} 