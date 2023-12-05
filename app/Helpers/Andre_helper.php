<?php
use App\Models\LogModel;

class Andre {

    public function save($email, $menu, $action, $form_data){
        $username = explode('@', $email)[0];

        $data = [
            'username' => $username,
            'menu' => $menu,
            'action' => $action,
            'form_data' => json_encode($form_data),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $log = new LogModel();
        $log->insert($data);
    }
}
