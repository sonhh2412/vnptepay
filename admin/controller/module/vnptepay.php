<?php
class ControllerModuleVnptepay extends Controller {
    private $error = array(); // This is used to set the errors, if any.
 
    public function index() {
        // Loading the language file of vnptepay
        $this->load->language('module/vnptepay'); 
     
        // Set the title of the page to the heading title in the Language file i.e., Hello World
        $this->document->setTitle($this->language->get('heading_title'));
     
        // Load the Setting Model  (All of the OpenCart Module & General Settings are saved using this Model )
        $this->load->model('setting/setting');
     
        // Start If: Validates and check if data is coming by save (POST) method
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            // Parse all the coming data to Setting Model to save it in database.
            $this->model_setting_setting->editSetting('vnptepay', $this->request->post);
     
            // To display the success text on data save
            $this->session->data['success'] = $this->language->get('text_success');
     
            // Redirect to the Module Listing
            $this->response->redirect($this->url->link('module/vnptepay', 'token=' . $this->session->data['token'], 'SSL'));
        }
     
        // Assign the language data for parsing it to view
        $data['heading_title'] = $this->language->get('heading_title');
     
        $data['text_edit']    = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_content_top'] = $this->language->get('text_content_top');
        $data['text_content_bottom'] = $this->language->get('text_content_bottom');      
        $data['text_column_left'] = $this->language->get('text_column_left');
        $data['text_column_right'] = $this->language->get('text_column_right');
        $data['text_ws_url'] = $this->language->get('text_ws_url');
        $data['text_partner_name'] = $this->language->get('text_partner_name');
        $data['text_partner_password'] = $this->language->get('text_partner_password');
        $data['text_key_sofpin'] = $this->language->get('text_key_sofpin');
        $data['text_time_out'] = $this->language->get('text_time_out');
     
        $data['entry_code'] = $this->language->get('entry_code');
        $data['entry_layout'] = $this->language->get('entry_layout');
        $data['entry_position'] = $this->language->get('entry_position');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_sort_order'] = $this->language->get('entry_sort_order');
     
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['button_add_module'] = $this->language->get('button_add_module');
        $data['button_remove'] = $this->language->get('button_remove');
         
        // This Block returns the warning if any
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
     
        // This Block returns the error code if any
        if (isset($this->error['code'])) {
            $data['error_code'] = $this->error['code'];
        } else {
            $data['error_code'] = '';
        }     
     
        // Making of Breadcrumbs to be displayed on site
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/vnptepay', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
          
        $data['action'] = $this->url->link('module/vnptepay', 'token=' . $this->session->data['token'], 'SSL'); // URL to be directed when the save button is pressed
     
        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'); // URL to be redirected when cancel button is pressed
              
        // This block checks, if the hello world text field is set it parses it to view otherwise get the default 
        // hello world text field from the database and parse it
        if (isset($this->request->post['vnptepay_ws_url'])) {
            $data['vnptepay_ws_url'] = $this->request->post['vnptepay_ws_url'];
        } else {
            $data['vnptepay_ws_url'] = $this->config->get('vnptepay_ws_url');
        }   

        if (isset($this->request->post['vnptepay_partner_name'])) {
            $data['vnptepay_partner_name'] = $this->request->post['vnptepay_partner_name'];
        } else {
            $data['vnptepay_partner_name'] = $this->config->get('vnptepay_partner_name');
        }   

        if (isset($this->request->post['vnptepay_partner_password'])) {
            $data['vnptepay_partner_password'] = $this->request->post['vnptepay_partner_password'];
        } else {
            $data['vnptepay_partner_password'] = $this->config->get('vnptepay_partner_password');
        }   

        if (isset($this->request->post['vnptepay_key_sofpin'])) {
            $data['vnptepay_key_sofpin'] = $this->request->post['vnptepay_key_sofpin'];
        } else {
            $data['vnptepay_key_sofpin'] = $this->config->get('vnptepay_key_sofpin');
        }   

        if (isset($this->request->post['vnptepay_time_out'])) {
            $data['vnptepay_time_out'] = $this->request->post['vnptepay_time_out'];
        } else {
            $data['vnptepay_time_out'] = $this->config->get('vnptepay_time_out');
        }   
          
        // This block parses the status (enabled / disabled)
        if (isset($this->request->post['vnptepay_status'])) {
            $data['vnptepay_status'] = $this->request->post['vnptepay_status'];
        } else {
            $data['vnptepay_status'] = $this->config->get('vnptepay_status');
        }
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/vnptepay.tpl', $data));

    }

    /* Function that validates the data when Save Button is pressed */
    protected function validate() {
 
        // Block to check the user permission to manipulate the module
        if (!$this->user->hasPermission('modify', 'module/vnptepay')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
 
        // Block to check if the vnptepay_text_field is properly set to save into database,
        // otherwise the error is returned
        if (!$this->request->post['vnptepay_ws_url']) {
            $this->error['code'] = $this->language->get('error_code');
        }
        if (!$this->request->post['vnptepay_partner_name']) {
            $this->error['code'] = $this->language->get('error_code');
        }
        if (!$this->request->post['vnptepay_partner_password']) {
            $this->error['code'] = $this->language->get('error_code');
        }
        if (!$this->request->post['vnptepay_key_sofpin']) {
            $this->error['code'] = $this->language->get('error_code');
        }
        if (!$this->request->post['vnptepay_time_out']) {
            $this->error['code'] = $this->language->get('error_code');
        }
        /* End Block*/
 
        // Block returns true if no error is found, else false if any error detected
        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
