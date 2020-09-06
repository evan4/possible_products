<?php
class ModelExtensionModulePossibleProducts extends Model {
  
  private $table = DB_PREFIX .'possible_products';

  public function getByPageId(int $id){
    return $this->db->query("SELECT id, product_id, possible_pages_id FROM " . $this->table . " WHERE possible_pages_id = '" . (int) $id . "'");;
  }

  public function add(array $product) {
    $data = [];

    if(!empty($product['product_id']) && !empty($product['page_id'])){
      $data['res'] =  $this->db->query("INSERT INTO " . $this->table . " SET 
      product_id = '" . (int) $product['product_id'] . "',
      possible_pages_id = '" . (int) $product['page_id'] . "'
      ");
    }
    
    $data['id'] = $this->db->getLastId();
    return $data;
  }

  public function delete(int $id){
    $res = null;

    if($id > 0){
      $res =  $this->db->query("DELETE FROM " . $this->table . " WHERE id = '" . (int) $id . "'");;
    }

    return $res;
  }

}