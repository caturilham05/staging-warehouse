<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reset extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        show_404();
    }

    public function demo()
    {
        if (DEMO) {
            $this->db->truncate('categories');
            $this->db->truncate('check_in');
            $this->db->truncate('check_in_items');
            $this->db->truncate('check_out');
            $this->db->truncate('check_out_items');
            $this->db->truncate('customers');
            $this->db->truncate('items');
            $this->db->truncate('login_attempts');
            $this->db->truncate('sessions');
            $this->db->truncate('settings');
            $this->db->truncate('suppliers');
            $this->db->truncate('users');
            $this->db->truncate('user_logins');

            $file = file_get_contents('./files/demo.sql');
            $this->db->conn_id->multi_query($file);
            $this->db->conn_id->close();
            $this->load->dbutil();
            $this->dbutil->optimize_database();

            redirect('login');
        } else {
            echo '<!DOCTYPE html>
            <html>
                <head>
                    <title>Stock Manager Advance</title>
                    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
                    <style>
                        html, body { height: 100%; }
                        body { margin: 0; padding: 0; width: 100%; display: table; font-weight: 72; font-family: \'Lato\'; }
                        .container { text-align: center; display: table-cell; vertical-align: middle; }
                        .content { text-align: center; display: inline-block; }
                        .title { font-size: 72px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="content">
                            <div class="title">Demo is disabled!</div>
                        </div>
                    </div>
                </body>
            </html>
            ';
        }
    }
}
