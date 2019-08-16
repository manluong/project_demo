<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Import_user extends MX_Controller {
    protected $module = 'import_user';
    protected $is_testing = false;
    
    function __construct(){
        parent::__construct();
        $this->load->helper('url_helper');
        $this->load->library('excel');
        
        $this->load->model('Import_user_model','model');
        $this->load->model('banker/banker_model');
        $this->load->model('student/student_model');
        $this->template->set_template('admin');
        $this->template->write('title','Import');
        
        $data['module'] = $this->module;
        $this->load->vars($data);
    }
    
    function admincp_index() {
        
        // TODO - TEST - DEBUG
        $data['is_testing'] = (int)$this->input->get('test');

        $this->template->write_view('content','BACKEND/index',$data);
        $this->template->render();
    }
   
    function fetch()
    {
        $data = $this->model->select();
        $output = '
        <h3 align="center">Total Data - '.$data->num_rows().'</h3>
        <table class="table table-striped table-bordered">
            <tr>
                <th>Customer Name</th>
                <th>Address</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Country</th>
            </tr>
        ';
        foreach($data->result() as $row)
        {
            $output .= '
            <tr>
                <td>'.$row->customer_name.'</td>
                <td>'.$row->address.'</td>
                <td>'.$row->city.'</td>
                <td>'.$row->postal_code.'</td>
                <td>'.$row->country.'</td>
            </tr>
            ';
        }
        $output .= '</table>';
        echo $output;
    }

    function import()
    {
        if(isset($_FILES["file"]["name"]))
        {
            $path = $_FILES["file"]["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            foreach($object->getWorksheetIterator() as $worksheet)
            {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                for($row=2; $row<=$highestRow; $row++)
                {
                    $customer_name = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                    $address = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $city = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $postal_code = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $country = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $data[] = array(
                        'customer_name'      =>  $customer_name,
                        'address'           =>  $address,
                        'city'              =>  $city,
                        'postal_code'        =>  $postal_code,
                        'country'           =>  $country
                    );
                }
            }
            /*print("<pre>".print_r($data,true)."</pre>");
            die;*/
            $this->model->insert($data);
            echo 'Data Imported successfully';
        }   
    }
    
    
}