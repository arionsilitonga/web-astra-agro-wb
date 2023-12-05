<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upgrade extends CI_Migration {

    public function updrateTable()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'username' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => '50',
            ),
            'menu' => array(
                'type' => 'TEXT',
                'null' => FALSE,
                'constraint' => '100',
            ),'action' => array(
                'type' => 'TEXT',
                'null' => FALSE,
                'constraint' => '100',
            ),'form_data' => array(
                'type' => 'JSON',
                'null' => FALSE,
                'constraint' => '100',
            ),'created_at' => array(
                'type' => 'datetime',
                'null' => FALSE,
                'constraint' => '100',
            ),
        ));
        
        $this->dbforge->create_table('tr_log');
    }

}