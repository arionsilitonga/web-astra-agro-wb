<?php
namespace App\Models;

use CodeIgniter\Model;

class UpgradeModel extends MyBaseModel{
	public function __construct(){
	}

	public function upgradeDB(){

		$this->load->dbforge();
        
        // $this->dbforge->add_field(array(
        //     'id' => array(
        //         'type' => 'INT',
        //         'constraint' => 5,
        //         'unsigned' => TRUE,
        //         'auto_increment' => TRUE
        //     ),
        //     'username' => array(
        //         'type' => 'VARCHAR',
        //         'null' => FALSE,
        //         'constraint' => '50',
        //     ),
        //     'menu' => array(
        //         'type' => 'TEXT',
        //         'null' => FALSE,
        //         'constraint' => '100',
        //     ),'action' => array(
        //         'type' => 'TEXT',
        //         'null' => FALSE,
        //         'constraint' => '100',
        //     ),'form_data' => array(
        //         'type' => 'JSON',
        //         'null' => FALSE,
        //         'constraint' => '100',
        //     ),'created_at' => array(
        //         'type' => 'datetime',
        //         'null' => FALSE,
        //         'constraint' => '100',
        //     ),
        // ));
        
        // $this->dbforge->create_table('tr_log2');
    } 
	
}