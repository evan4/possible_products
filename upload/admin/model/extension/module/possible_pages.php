<?php
class ModelExtensionModulePossiblePages extends Model {
  
  private $table = DB_PREFIX .'possible_pages';

  public function getPages(){
    return $this->db->query("SELECT id, title, url FROM " . $this->table . " ORDER BY title");
  }

  public function getById(int $id){
    return $this->db->query("SELECT id, title, url FROM " . $this->table . " WHERE id = " . $id);
  }

  public function add(array $page) {
    $data = [];

    if(!empty($page['title']) && !empty($page['url'])){
      $data['res'] =  $this->db->query("INSERT INTO " . $this->table . " SET 
      title = '" . $this->db->escape($page['title']) . "',
      url = '" . $this->db->escape($page['url']) . "'
      ");
    }

    $data['id'] = $this->db->getLastId();
    return $data;
  }

  public function update(array $page){
    $res = null;
    
    $sql =  "UPDATE " . $this->table . " SET 
    title = '" . $this->db->escape($page['title']) . "',
    url = '" . $this->db->escape($page['url']) . "'
    WHERE id = '" . (int) $page['id'] . "'";
  
    $res = $this->db->query($sql);

    return $res;
  }

  public function delete(int $id){
    $res = null;

    if($id > 0){
      $res = $this->db->query("DELETE FROM " . $this->table . " WHERE id = '" . (int) $id . "'");;
    }

    return $res;
  }

}