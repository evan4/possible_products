<?php
class ModelExtensionModulePossiblePages extends Model {
  
  private $table = DB_PREFIX .'possible_pages';

  public function getByUrl($url){
    return $this->db->query("SELECT id, title, url FROM " . $this->table . " WHERE url = '". $url . "'");
  }
  
}