<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends MY_Controller {

    private $logPath;

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            redirect('login');
        }
        if (!($this->Admin)) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
        $this->logPath = APPPATH . 'logs/';
    }

    public function index() {

    }

    public function email($sdate = NULL) {

        $this->load->helper('file');

        if ($sdate) {
            $logs = read_file(APPPATH . 'logs/email-' . $sdate.'.php');
        } else {
            $sdate = date('Y-m-d');
            $logs = read_file(APPPATH . 'logs/email-' . $sdate . '.php');
        }
        $logs = explode("_|_", $logs);

        $this->data['logs'] = $logs;
        $this->data['log_date'] = $sdate;
        $this->data['page_title'] = lang('email_logs');
        $this->data['page'] = 'email';
        $this->page_construct('tasks/logs', $this->data);

    }

    public function close($sdate = NULL) {

        $this->load->helper('file');

        if ($sdate) {
            $logs = read_file(APPPATH . 'logs/task-' . $sdate.'.php');
        } else {
            $today = date('Y-m-d');
            $logs = read_file(APPPATH . 'logs/task-' . $today . '.php');
            $log_date = $today;
        }
        $logs = explode("_|_", $logs);

        $this->data['logs'] = $logs;
        $this->data['log_date'] = $sdate;
        $this->data['page_title'] = lang('close_logs');
        $this->data['page'] = 'close';
        $this->page_construct('tasks/logs', $this->data);

    }

    public function delete($type, $date = NULL) {
        if($this->input->post('ddate')) {
            $date = strtotime($this->input->post('ddate', TRUE));
            foreach (glob($this->logPath . $type . "*.php") as $filename) {
                if (filemtime($filename) < $date) {
                    unlink($filename);
                }
            }
            $this->session->set_flashdata('message', lang("log_deleted"));
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            $this->session->set_flashdata('error', lang("please_select_date"));
            redirect($_SERVER["HTTP_REFERER"]);
        }
    }

}
