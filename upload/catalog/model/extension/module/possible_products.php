<?php
class ModelExtensionModulePossibleProducts extends Model {
  
  private $table = DB_PREFIX .'possible_products';

  public function getByPageId(int $id){
    return $this->db->query("SELECT product_id, possible_pages_id FROM " . $this->table . " WHERE possible_pages_id = '" . (int) $id . "'");;
  }

}