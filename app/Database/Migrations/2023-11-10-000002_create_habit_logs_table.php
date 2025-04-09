<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHabitLogsTable extends Migration
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
            'date' => [
                'type' => 'DATE',
            ],
            'count' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey(['habit_id', 'date'], false, true); // Benzersiz kısıtlama
        $this->forge->addForeignKey('habit_id', 'habits', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('habit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('habit_logs');
    }
} 