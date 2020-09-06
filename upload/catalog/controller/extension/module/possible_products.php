<?php
class ControllerExtensionModulePossibleProducts extends Controller {
	public function index($setting) {
		$this->document->addStyle('catalog/view/stylesheet/possible_products.css');
		$data=[];

		$this->load->model('extension/module/possible_pages');
		$this->load->model('extension/module/possible_products');
		$this->load->model('extension/module/possible_tags');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($setting['name'])) {
				$data['title'] = $setting['name'];
		}

		if (isset($setting['name_tag'])) {
			$data['title_tag'] = $setting['name_tag'];
		}

		$data['url'] = $_SERVER['REQUEST_URI'];
	
		$page = $this->model_extension_module_possible_pages->getByUrl($data['url'])->row;
		
		$data['possible_products'] = [];
		$data['tags'] = [];
		
		if($page) {
			$products  = $this->model_extension_module_possible_products->getByPageId($page['id'])->rows;

			if (isset($products)) {
				shuffle($products);
	
				if(count($products) > 4){
					$products = array_slice($products, 0, 4);
				}

				foreach ($products as $product) {
					$data['possible_products'][] = $this->getProducts($product['product_id']);
				}
			
			}
			
			$data['tags'] = $this->model_extension_module_possible_tags->getByPageId($page['id'])->rows;
			
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/possible_products.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/possible_products.tpl', $data);
		} else {
			return $this->load->view('extension/module/possible_products.tpl', $data);
		}
	}
	
	private function getProducts($product){
		$data = [];

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$url = '';
		
		$result = $this->model_catalog_product->getProduct($product);
		
		$result['href'] = $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url);

		if ($result['image']) {
			$result['thumb'] = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
		} else {
			$result['thumb'] = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_product_width'), $this->config->get($this->config->get('config_theme') . '_image_product_height'));
		}
		
		if ($this->config->get('config_tax')) {
			$result['tax'] = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
		} else {
			$result['tax'] = false;
		}

		if ($result['quantity'] <= 0) {
			$result['stock'] = $result['stock_status'];
		} elseif ($this->config->get('config_stock_display')) {
			$result['stock'] = $result['quantity'];
		} else {
			$result['stock'] = 'В наличии';
		}
		
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$result['price'] = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
		} else {
			$result['price'] = false;
		}

		$result['description'] = utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..';

		return $result;
	}

}