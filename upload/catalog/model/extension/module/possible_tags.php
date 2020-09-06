<?php
class ModelExtensionModulePossibleTags extends Model {
  
  private $table = DB_PREFIX .'possible_tags';

  public function getByPageId(int $id){
    return $this->db->query("SELECT title, url, possible_pages_id FROM " . $this->table . " WHERE possible_pages_id = '" . (int) $id . "'");;
  }
  
}