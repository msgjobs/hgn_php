<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php

class Ajax extends CI_Controller {

    public function readById() {
        $this->load->model('database_model');
        $this->load->model('system_model');
        $this->load->model('ajax_model');

        $cbObject = $this->uri->segments['3'];
        $cbMethod = $this->uri->segments['4'];
        $table = $this->uri->segments['5'];
        $id = $this->uri->segments['6'];
//        isset($this->uri->segments['5']) ? $parms = $this->uri->segments['5'] : $parms = null;
//        $data['tableMeta'] = $this->database_model->readTableMetaData($table);
        $result = $data['tableData'] = $this->ajax_model->readById($table, $id);
        $result ? $data['response']['success'] = true : $data['response']['success'] = false;
        $data['response']['cbObject'] = $cbObject;
        $data['response']['cbMethod'] = $cbMethod;

        echo json_encode($data);
        return;
    }

    public function index() {
        $this->load->model('database_model');
        $this->load->model('system_model');

        $module = $this->uri->segments['3'];
        $moduleRecord = $this->system_model->readModule($module);
        $model = $moduleRecord['model'];
        $headerTable = $moduleRecord['headerTable'];
        $detailTable = $moduleRecord['detailTable'];
        $method = $this->uri->segments['4'];
        isset($this->uri->segments['5']) ? $parms = $this->uri->segments['5'] : $parms = null;

        $this->load->model($model);

        if(isset($_POST['data']) and ( $_POST['data'])){
            $postData = json_decode($_POST['data'], true);
            $responseCode = $this->$model->$method($postData);
            if($responseCode['success']){
                $headerId = $postData['headerId'];
                $headerMethod = $moduleRecord['headerMethod'];
                $detailMethod = $moduleRecord['detailMethod'];
                $data['headerData'] = $this->$model->$headerMethod($headerTable, 'id', $headerId);
                $data['detailData'] = $this->$model->$detailMethod($headerId);
                $data['response'] = $responseCode;
            } else {
                $data['response'] = $responseCode;
            }
        } else {
            $data['tableMeta'] = $database_model->readTableMetaData($table);
            $data['tableData'] = $this->$model->$method($parms);
        }
        echo json_encode($data);
        return;
    }

}
