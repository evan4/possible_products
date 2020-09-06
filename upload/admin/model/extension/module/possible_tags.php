<?php
class ModelExtensionModulePossibleTags extends Model {
  
  private $table = DB_PREFIX .'possible_tags';

  public function getByPageId(int $id){
    return $this->db->query("SELECT id, title, url, possible_pages_id FROM " . $this->table . " WHERE possible_pages_id = '" . (int) $id . "'");;
  }

  public function add(array $tag) {
    $data = [];

    if(!empty($tag['title']) && !empty($tag['url'])){
      $data['res'] =  $this->db->query("INSERT INTO " . $this->table . " SET 
      title = '" . $this->db->escape($tag['title']) . "',
      url = '" . $this->db->escape($tag['url']) . "',
      possible_pages_id = '" . (int) $tag['page_id'] . "'
      ");
    }

    $data['id'] = $this->db->getLastId();
    return $data;
  }

  public function update(array $tag){
    $res = null;
    
    if(!empty($tag['value'])){
      if($tag['type'] === 'title'){
        $sql =  "UPDATE " . $this->table . " SET 
        title = '" . $this->db->escape($tag['value']) . "' WHERE id = '" . (int) $tag['id'] . "'";
      }elseif($tag['type'] === 'url'){
        $sql =  "UPDATE " . $this->table . " SET  
        url = '" . $this->db->escape($tag['value']) . "' WHERE id = '" . (int) $tag['id'] . "'";
      }

      $res = $this->db->query($sql);
    }

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