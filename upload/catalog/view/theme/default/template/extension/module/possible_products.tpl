<!-- possible_products-->
<?php 
  if(!empty($possible_products)) {  ?>
<div class="possible_products">
  <h2  class="possible_products__caption"><?=$title?></h2>
  <div class="row">
    <?php foreach ($possible_products as $product) { ?>
    <div class="product-layout col-lg-3 col-md-4 col-sm-6 col-xs-12">
     <div class="product-thumb">
      <div class="image">
        <?php if ($product['rating']) { ?>
          <div class="rating">
            <?php for ($i = 5; $i >= 1; $i--) { ?>
            <?php if ($product['rating'] < $i) { ?>
            <i class="fa fa-star"></i>
            <?php } else { ?>
            <i class="fa fa-star active"></i>
            <?php } ?>
            <?php } ?>
          </div>
        <?php } ?>
        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
      </div>
      <div>
      <div class="caption">
        <a href="<?php echo $product['href']; ?>"<?php if ($product['special']) { ?> class="text-special"<?php } ?>>
        <?php if (substr($product['name'], -1) == " ") { echo $product['name']; ?>
        <?php echo $product['name']; ?></a>
        <?php } else { ?>
          <?php echo $product['meta_keyword']; ?></a>
        <?php } ?>
      </div>

      <div class="price-detached">
        <small><?php if ($product['price']) { ?><span class="price text-muted"><?php if (!$product['special']) { ?><?php echo $product['price']; ?><?php } else { ?><span class="price-new"><b><?php echo $product['special']; ?></b></span> <span class="price-old"><?php echo $product['price']; ?></span><?php } ?></span><?php } ?><?php if ($product['tax']) { ?><br /><span class="price-tax text-muted"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span><?php } ?></small>
      </div>

      <div class="btn-group btn-group-sm">

          <div class="btn btn-primary" <?php if ($product['stock'] === 'В наличии') { ?>data-toggle="modal" 
            data-target="#orderModal" data-order-mode="catalog" 
            data-order-product-id="<?php echo $product['product_id']; ?>" 
            title="<?php echo $product['stock'] === 'В наличии' ? 'Купить в 1 клик' : $product['stock']; ?>"
            data-order-title="<?php echo $product['name']; ?>" 
            data-order-img-src="<?php echo $product['thumb']; ?>" 
            data-order-price="<?php if (!$product['special']) { ?><?php echo $product['price']; ?><?php } else { ?><?php echo $product['special']; ?><?php } ?>"<?php } ?>><span data-toggle="tooltip" data-html="true" data-placement="bottom" 
            title="<?php if($product['stock'] === 'Закончился'){
            echo 'Нет на складе';
          }else{
            echo $product['stock']; 
          }?>"><i class="fa fa-fw fa-send"></i></span> Купить</div>

          <button type="button" data-toggle="tooltip" data-html="true" data-placement="bottom" 
          title="<?php echo $product['stock'] === 'В наличии' ? '' : $product['stock']; ?>" class="btn btn-default" onclick="cart.add('<?php echo $product['product_id']; ?>', '1');" 
          data-original-title=""><i class="fa fa-fw fa-shopping-cart"></i></button>
          
          <button type="button" class="btn btn-default" data-placement="bottom"
          data-toggle="tooltip" title="В закладки" 
          onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-fw fa-heart"></i></button>

          <button type="button" data-toggle="tooltip" title="Сравнить" class="btn btn-default"
          onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-fw fa-area-chart"></i> Сравнить</button>

        </div>
        <div class="additional">
          <span class="stock <?php echo $product['stock'] === 'В наличии' ? 'instock' : '' ?>"><span>
          <?php
          if($product['stock'] === 'Закончился'){
            echo 'Нет на складе';
          }else{
            echo $product['stock']; 
          }
           ?>
          </span>
          </span>
          <span class="code">Код товара: <span><?php echo $product['product_id']; ?></span></span>
        </div>
        <div class="description"><?php echo $product['description']; ?></div>
      </div>
     </div>
    </div>
    <?php } ?>
  </div>
</div>
<?php } ?>

<?php if(!empty($tags)) { ?>
<div class="categories-tags clearfix">
  <h2 class="possible_products__caption"><?=$title_tag?></h2>
  <div class="row">
    <?php foreach ($tags as $tag) { ?>
      <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
        <a class="btn categories-tags__link " 
        href="<?php echo $tag['url']; ?>"><?php echo $tag['title'] ?></a>
      </div>
    <?php } ?>
  </div>
</div>
<?php } ?>
<!-- possible_products -->