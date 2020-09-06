<?php
class ModelExtensionModulePossibleCommon extends Model {
  public function install() {
    $this->load->model('extension/module/possible_pages');
		$this->load->model('extension/module/possible_products');
    $this->load->model('extension/module/possible_tags');
    
    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "possible_pages` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(256) NOT NULL,
        `url` VARCHAR(256) NOT NULL,
        PRIMARY KEY (`id`)
      )
    ");

    if(!$this->isEmptyTable('possible_pages')) {
			$this->model_extension_module_possible_pages->add([
				'title' => 'С этими товарами ищут',
				'url' => '/skates/child/',
			]);
    }

    $this->db->query("
      CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "possible_tags` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `title` VARCHAR(256) NOT NULL,
      `url` VARCHAR(256) NOT NULL,
      `possible_pages_id` INT UNSIGNED NOT NULL,
      PRIMARY KEY (`id`),
      INDEX `fk_possible_tags_possible_pages_idx` (`possible_pages_id` ASC),
      CONSTRAINT `fk_possible_tags_possible_pages`
        FOREIGN KEY (`possible_pages_id`)
        REFERENCES `" . DB_PREFIX . "possible_pages` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
    ");
      
    $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "possible_products` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `product_id` VARCHAR(256) NOT NULL,
      `possible_pages_id` INT UNSIGNED NOT NULL,
      PRIMARY KEY (`id`),
      INDEX `fk_possible_products_possible_pages1_idx` (`possible_pages_id` ASC),
      CONSTRAINT `fk_possible_products_possible_pages1`
        FOREIGN KEY (`possible_pages_id`)
        REFERENCES `" . DB_PREFIX . "possible_pages` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)");
      
      if(!$this->isEmptyTable('possible_tags')) {
  
        $tags = [
          ['Профессиональные детские ролики', 'https://rollbay.ru/skates/skates-pro-child/'],
          ['Профессиональные ролики', 'https://rollbay.ru/skates/professionalnye-roliki/'],
          ['Внедорожные роликовые коньки', 'https://rollbay.ru/skates/skates-suv/'],
          ['3-колесные роликовые коньки', 'https://rollbay.ru/skates/skates-triskates'],
          ['4-колесные роликовые коньки', 'https://rollbay.ru/skates/4-kolesnye/'],
          ['Квады', 'https://rollbay.ru/skates/kvady/'],
          ['Flying Eagle', 'https://rollbay.ru/skates/skates-fe/'],
          ['Powerslide', 'https://rollbay.ru/skates/skates-ps/'],
          ['Коньки ледовые', 'https://rollbay.ru/ice-skates/'],
          ['Колёса для роликов', 'https://rollbay.ru/wheels/'],
          ['Подшипники для роликов', 'https://rollbay.ru/bearings/'],
          ['Защита для роллера', 'https://rollbay.ru/armor/'],
          ['Рамы и ходовые для роликов', 'https://rollbay.ru/frames/'],
          ['Рюкзаки и сумки для роликов', 'https://rollbay.ru/bags/'],
          ['Аксессуары для роллера ','https://rollbay.ru/accessory/'],
          ['Запчасти для роликов', 'https://rollbay.ru/parts/'],
          ['Инструменты для роликов', 'https://rollbay.ru/tools/'],
          ['Одежда для роллера', 'https://rollbay.ru/wear/'],
          ['Ролики Агрессив', 'https://rollbay.ru/agressive/'],
          ['Самокаты', 'https://rollbay.ru/scooter/'],
          ['Лонгборды', 'https://rollbay.ru/longbordy/']
        ];
  
        foreach ($tags as $tag) {
          $data = [
            'title' => $tag[0],
            'url' => $tag[1],
            'page_id' => 1
          ];
  
          $this->model_extension_module_possible_tags->add($data);
        }
      }
  }

  public function uninstall() {
    $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "possible_products`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "possible_tags`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "possible_pages`;");
  }

  private function isEmptyTable($table): int{
		return $this->db->query("select exists(select 1 from ".DB_PREFIX.$table.") as output")->row['output'];
  }

	private function isTableExists($table){
		return $this->db->query("SELECT EXISTS(
			SELECT * FROM information_schema.tables 
			WHERE table_schema = '".$this->getCurrentDbName()."' 
			AND table_name = '".DB_PREFIX.$table."'
		) as output")->row['output'];
  }
  
}