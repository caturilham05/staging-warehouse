<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->db->query('SET SESSION sql_mode = ""');
        $this->Settings = $this->settings_model->getSettings();
        if ($ssm_language = $this->input->cookie('ssm_language', true)) {
            $this->Settings->language = $ssm_language;
        }
        $this->config->set_item('language', $this->Settings->language);
        $this->lang->load('app', $this->Settings->language);
        $this->theme            = $this->Settings->theme . '/views/';
        $this->data['assets']   = base_url() . 'themes/default/assets/';
        $this->data['Settings'] = $this->Settings;
        $this->loggedIn         = $this->tec->logged_in();
        $this->data['loggedIn'] = $this->loggedIn;
        $this->Admin            = $this->tec->in_group('admin') ? true : null;
        $this->data['Admin']    = $this->Admin;
        $this->load->library('parser');
        $this->data['dt_lang'] = json_encode(lang('datatables_lang'));
        $this->m               = strtolower($this->router->fetch_class());
        $this->v               = strtolower($this->router->fetch_method());
        $this->data['m']       = $this->m;
        $this->data['v']       = $this->v;
    }

    public function page_construct($page, $data = [], $meta = [])
    {
        if (empty($meta)) {
            $meta['page_title'] = $data['page_title'];
        }
        $meta['message']       = $data['message'] ?? $this->session->flashdata('message');
        $meta['error']         = $data['error']   ?? $this->session->flashdata('error');
        $meta['warning']       = $data['warning'] ?? $this->session->flashdata('warning');
        $meta['ip_address']    = $this->input->ip_address();
        $meta['Admin']         = $data['Admin'];
        $meta['loggedIn']      = $data['loggedIn'];
        $meta['Settings']      = $data['Settings'];
        $meta['assets']        = $data['assets'];
        $meta['qty_alert_num'] = $this->settings_model->getQtyAlerts();
        $this->load->view($this->theme . 'header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'footer');
    }
}
