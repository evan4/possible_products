<?php
class ControllerExtensionModulePossibleProducts extends Controller {
	private $error = array(); // This is used to set the errors, if any.
	
	public function index() {
		$this->load->model('extension/module/possible_common');

		if(!$this->model_extension_module_possible_common->isTableExists('possible_pages')){
			$this->install();
		}
		
    // Loading the language file of helloworld
		$this->load->language('extension/module/possible_products');
		$this->document->addScript('view/javascript/possible_products.js');
		$this->document->addStyle('view/stylesheet/possible_products.css');

		// Set the title of the page to the heading title in the Language file i.e., Hello World
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module');
		$this->load->model('extension/module/possible_products');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_extension_module->addModule('possible_products', $this->request->post);
			} else {
				$this->model_extension_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
			
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_name_tag'] = $this->language->get('entry_name_tag');
		$data['entry_categories'] = $this->language->get('entry_categories');
		$data['entry_categories_tag'] = $this->language->get('entry_categories_tag');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['name_tag'])) {
			$data['error_name_tag'] = $this->error['name_tag'];
		} else {
			$data['error_name_tag'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/possible_products', 'token=' . $this->session->data['token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/possible_products', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/possible_products', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/possible_products', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_extension_module->getModule($this->request->get['module_id']);
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['name_tag'])) {
			$data['name_tag'] = $this->request->post['name_tag'];
		} elseif (!empty($module_info)) {
			$data['name_tag'] = $module_info['name_tag'];
		} else {
			$data['name_tag'] = '';
		}

		$this->load->model('extension/module/possible_pages');
		$this->load->model('extension/module/possible_products');
		$this->load->model('extension/module/possible_tags');

		$data['pages'] = $this->model_extension_module_possible_pages->getPages()->rows;
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info) && isset($module_info['status'])) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}
		$data['edit'] = $this->url->link('extension/module/possible_products/edit', 'token=' . $this->session->data['token']. '&module_id=' . $this->request->get['module_id'], true);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('extension/module/possible_products', $data));
	}

	public function edit(){
		if (isset($this->request->get['page_id']) && (intval($this->request->get['page_id']) > 0)) {
			
			$page_id = intval($this->request->get['page_id']);
		
			$this->load->model('extension/module/possible_pages');
			$this->load->model('extension/module/possible_products');
			$this->load->model('extension/module/possible_tags');
			$this->load->model('extension/module');
			$this->load->model('extension/module/possible_products');
			// Loading the language file of helloworld
			$this->load->language('extension/module/possible_products');
			$this->document->addScript('view/javascript/possible_products.js');
			$this->document->addStyle('view/stylesheet/possible_products.css');

			$this->document->setTitle($this->language->get('heading_title'));
	
			$data['breadcrumbs'] = array();
	
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
	
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
			);
	
			if (!isset($this->request->get['module_id'])) {
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/module/possible_products', 'token=' . $this->session->data['token'], true)
				);
			} else {
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('extension/module/possible_products', 'token=' . $this->session->data['token'] . '&module_id=' . $this->request->get['module_id'], true)
				);
			}

			$data['page'] = $this->model_extension_module_possible_pages->getById($page_id)->row;
			$data['possible_products'] = $this->model_extension_module_possible_products->getByPageId($page_id)->rows;
			$data['tags'] = $this->model_extension_module_possible_tags->getByPageId($page_id)->rows;
			$data['products'] = $this->getProducts($data['possible_products']);
			
			$data['heading_title'] = $this->language->get('heading_title');
	
			$data['text_edit'] = $this->language->get('text_edit');
			$data['text_enabled'] = $this->language->get('text_enabled');
			$data['text_disabled'] = $this->language->get('text_disabled');
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');
	
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_name_tag'] = $this->language->get('entry_name_tag');
			$data['entry_categories'] = $this->language->get('entry_categories');
			$data['entry_categories_tag'] = $this->language->get('entry_categories_tag');
			$data['entry_status'] = $this->language->get('entry_status');
	
			$data['token'] = $this->session->data['token'];
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			$this->response->setOutput($this->load->view('extension/module/possible_page', $data));
		}
		
	}

	public function addPage(){
		if($this->checkAjax()){
			$data['title'] = stripcslashes($this->request->post['title']);
			$data['url'] = stripcslashes($this->request->post['url']);
			$this->response->addHeader('Content-Type: application/json');

			$this->load->model('extension/module/possible_pages');
			$result = $this->model_extension_module_possible_pages->add($data);
			$data['result'] = $result['res'];
			$data['id'] = $result['id'];
			
			$this->response->setOutput(json_encode($data));
		}
	}

	public function updatePage(){
		if($this->checkAjax()){
			$data['title'] = stripcslashes($this->request->post['title']);
			$data['url'] = stripcslashes($this->request->post['url']);
			$data['id'] = intval($this->request->post['id']);

			$this->response->addHeader('Content-Type: application/json');

			$this->load->model('extension/module/possible_pages');
			$result = $this->model_extension_module_possible_pages->update($data);
			
			$this->response->setOutput(json_encode($result));
		}
	}

	public function deletePage(){
		if($this->checkAjax()){
			$id = (int) $this->request->post['id'];

			$this->response->addHeader('Content-Type: application/json');
			$this->load->model('extension/module/possible_pages');

			$res = $this->model_extension_module_possible_pages->delete($id);

			$this->response->setOutput(json_encode($res));
		}
	}

	public function addTag(){
		if($this->checkAjax()){

			$data['title'] = stripcslashes($this->request->post['title']);
			$data['url'] = stripcslashes($this->request->post['url']);
			$data['page_id'] = intval($this->request->post['page_id']);

			$this->response->addHeader('Content-Type: application/json');

			$this->load->model('extension/module/possible_tags');
			$result = $this->model_extension_module_possible_tags->add($data);
			$data['result'] = $result['res'];
			$data['id'] = $result['id'];
			
			$this->response->setOutput(json_encode($data));
		}
	}

	public function updateTag(){
		if($this->checkAjax()){

			$data['id'] = (int) $this->request->post['id'];
			$data['type'] = trim(stripcslashes($this->request->post['type']));
			$data['value'] = trim(stripcslashes($this->request->post['value']));

			$this->response->addHeader('Content-Type: application/json');
			$this->load->model('extension/module/possible_tags');

			$res = $this->model_extension_module_possible_tags->update($data);

			$this->response->setOutput(json_encode($res));
		}
	}

	public function deleteTag(){
		if($this->checkAjax()){

			$id = (int) $this->request->post['id'];

			$this->response->addHeader('Content-Type: application/json');
			$this->load->model('extension/module/possible_tags');

			$res = $this->model_extension_module_possible_tags->delete($id);

			$this->response->setOutput(json_encode($res));
		}
	}

	public function addProduct(){
		if($this->checkAjax()){

			$name = $this->request->post['product_name'];
			$product['page_id'] = $this->request->post['page_id'];

			$this->load->model('catalog/product');

			$data['product'] = $this->getProduct($name);
			$product['product_id'] = $data['product']['product_id'];

			$this->load->model('extension/module/possible_products');

			$result = $this->model_extension_module_possible_products->add($product);
			$data['result'] = $result['res'];
			$data['id'] = $result['id'];

			$this->response->addHeader('Content-Type: application/json');

			$this->response->setOutput(json_encode($data));
		}
	}

	public function deleteProduct(){
		if($this->checkAjax()){
			$id = (int) $this->request->post['id'];

			$this->response->addHeader('Content-Type: application/json');
			$this->load->model('extension/module/possible_products');

			$res = $this->model_extension_module_possible_products->delete($id);

			$this->response->setOutput(json_encode($res));
		}
	}

	public function install() {

		$this->load->model('user/user_group');

		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/module/possible_products');
		$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/module/possible_products');
		
		$this->load->model('extension/module/possible_common');

		$this->model_extension_module_possible_common->install();

	}

	public function uninstall() {
 
    $this->load->model('extension/extension');
 
    $this->load->model('extension/module');
 
		$this->model_extension_extension->uninstall('extension/module', 'possible_products');

		$this->model_extension_module->deleteModulesByCode('possible_products');

		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSetting('possible_products');

		$this->load->model('user/user_group');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'access', 'extension/module/possible_products');
		$this->model_user_user_group->removePermission($this->user->getGroupId(), 'modify', 'extension/module/possible_products');
		
		$this->load->model('extension/module/possible_common');

		$this->model_extension_module_possible_common->uninstall();
		
	}

	private function getProducts($products)
	{
		$data = [];

		$this->load->model('catalog/product');

		$url = '';
		if(!empty($products)){
			
			foreach ($products as $product) {
				$result = $this->model_catalog_product->getProduct($product['product_id']);
				$result['id'] = $product['id'];
				$result['href'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'] . $url, true);
				
				$data[] = $result;

			}
		}

		return $data;
	}

	private function getProduct($name)
	{
		$data = [];

		$this->load->model('catalog/product');

		$url = '';
		if(!empty($name)){
			$filter_data = array(
				'filter_name'  => $name
			);

			$data = $this->model_catalog_product->getProducts($filter_data)[0];

			$data['href'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $data['product_id'] . $url, true);

		}

		return $data;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/possible_products')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	private function checkAjax(): bool
	{
			if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
					strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
					return true;
			}
			
			return false;
	}

}
