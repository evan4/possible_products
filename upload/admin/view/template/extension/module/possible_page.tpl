<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
    <div class="container-fluid">
        <div class="pull-left">
          <h1><?php echo $heading_title; ?></h1>
          <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
      </div>
		<div class="container-fluid">
		
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
				</div>

					<div class="alert alert-dismissible fade in" role="alert" id="result">
						<button type="button" class="close alert-close" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<strong></strong>
					</div>
					<div class="alert alert-info">
						<i class="fa fa-info-circle"></i> Адрес вводить без домена, как /skates/child/
					</div>
          <div id="block_form_edit mb10">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="block_title">Заголовок</label>
									<input type="test" class="form-control" value="<?php echo $page['title']; ?>" id="block_title" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="block_url">Адрес</label>
									<input type="url" class="form-control" value="<?php echo $page['url']; ?>"  id="block_url" required>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-primary" id="block">Сохранить</button>
					</div>

					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active">
							<a href="#products" aria-controls="products" role="tab" data-toggle="tab">Товары</a>
						</li>
						<li role="presentation">
							<a href="#tags" aria-controls="tags" role="tab" data-toggle="tab">Теги</a>
						</li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="products">
							<div>
								<div class="form-group">
									<label for="product">Выберете товар</label>
									<input type="text" name="filter_name" value="" id="input-name" data-id="<?php echo $page['id']; ?>"
									class="form-control" />
								</div>
								<button type="button" id="products_form" class="btn btn-primary">Добавить</button>
							</div>

							<table class="table table-striped" id="list_products">
								<thead>
									<tr>
										<th>Название товара</th>
										<th>Количество</th>
										<td>Действие</td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($products as $product) { ?>
										<tr>
											<td>
												<a href="<?=$product['href']; ?>"><?=$product['name']; ?></a>
											</td>
											<td><?=$product['quantity']; ?></td>
											<td class="text-left">
												<button type="button" data-id="<?=$product['id']?>"
													title="удалить" class="btn btn-danger product_remove">
													<i class="fa fa-minus-circle"></i>
												</button>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<div role="tabpanel" class="tab-pane fade" id="tags">
							<div id="tag_form">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="title">Название</label>
											<input type="test" class="form-control" id="title" required>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="url">Адрес</label>
											<input type="url" class="form-control" id="url" required>
										</div>
									</div>
								</div>
								<button type="button" class="btn btn-primary" id="products_tag">Добавить</button>
							</div>

							<table class="table table-striped" id="list_tags">
								<thead>
									<tr>
										<th>Текст</th>
										<th>Адрес</th>
										<td>Действие</td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($tags as $tag) { ?>
									<tr>
										<td>
											<input type="text" value="<?=$tag['title']; ?>" class="form-control tags_input"
											data-id="title-<?=$tag['id']?>">
										</td>
										<td>
											<input type="text" value="<?=$tag['url']; ?>" class="form-control tags_input"
											 data-id="url-<?=$tag['id']?>">
										</td>
										<td class="text-left">
											<button type="button" data-id="<?=$tag['id']?>"
												title="удалить" class="btn btn-danger tags_remove">
												<i class="fa fa-minus-circle"></i>
											</button>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<script>

//blocks
$('#block').on('click', function (e) {
	e.preventDefault();

	const title = $('#block_title');
	const url = $('#block_url');

	if(title.val() && url.val()){
		const data = {
			title: title.val().trim(),
			url: url.val().trim(),
			id: <?php echo $page['id']; ?>
		};
		$.ajax({
			url: 'index.php?route=extension/module/possible_products/updatePage&token=<?php echo $token; ?>',
			data,
			beforeSend: function() {
				$('#block').prop("disabled", true);
			}
		}).done(function(res) {
			if (res) {
				resultOutput('Данные успешно сохранены', 'success');
			}else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}
			$('#block').prop("disabled", false);
		});
	}
});

//products
$('#products_form').on('click', function (e) {
	e.preventDefault();
	const input = $('#input-name');

	if(input.val()){
		$.ajax({
			url: 'index.php?route=extension/module/possible_products/addProduct&token=<?php echo $token; ?>',
			data: {
				product_name: input.val().trim(),
				page_id: +input.data('id')
			},
			beforeSend: () => {
				$(this).prop("disabled", true);
			}
		}).done((res) => {
			const {id, product, result} = res;
			
			if (res.result === true) {
				$template = `
				<tr>
					<td>
						<a href="${product['href']}">${product['name']}</a>
					</td>
					<td>${product['quantity']}</td>
					<td class="text-left">
						<button type="button" data-id="${id}"
							title="удалить" class="btn btn-danger product_remove">
							<i class="fa fa-minus-circle"></i>
						</button>
					</td>
				</tr>
				`;
				$('#list_products').find('tbody').append($template);
				resultOutput('Данные успешно сохранены', 'success');
			} else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}
			input.val('');
			$(this).prop("disabled", false);
		}); 
	}
});

$(document).on('click', '.product_remove', function() {
	
	const data = {
		id: +$(this).data('id')
	};

	$.ajax({
			url: 'index.php?route=extension/module/possible_products/deleteProduct&token=<?php echo $token; ?>',
			data,
			beforeSend: () => {
				$(this).prop("disabled", true);
			}
		}).done((res) => {
			if (res === true) {
				resultOutput('Данные успешно удалены', 'success');
				$(this).closest('tr').remove();
			} else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}
		})
});

//tags
$('#products_tag').on('click', function (e) {
	e.preventDefault();

	const title_tag = $('#title');
	const url_tag = $('#url');
	
	if(title_tag.val() && url_tag.val()){
		const data = {
			title: title_tag.val().trim(),
			url: url_tag.val().trim(),
			page_id: <?php echo $page['id']; ?>
		};

		$.ajax({
			url: 'index.php?route=extension/module/possible_products/addTag&token=<?php echo $token; ?>',
			data,
			beforeSend: function() {
				$(this).prop("disabled", true);
			}
		}).done((res) => {
			const {id, title, url, result} = res;
			if (res.result === true) {
				
				$template = `
				<tr>
					<td>
						<input type="text" value="${title}" class="form-control tags_input" data-id="title-${id}">
					</td>
					<td>
						<input type="text" value="${url}" class="form-control tags_input" data-id="url-${id}">
					</td>
					<td class="text-left">
						<button type="button" data-id="${id}"
							title="удалить" class="btn btn-danger tags_remove">
							<i class="fa fa-minus-circle"></i>
						</button>
					</td>
				</tr>
				`;
				$('#list_tags').find('tbody').append($template);
				title_tag.val('');
				url_tag.val('');
				resultOutput('Данные успешно сохранены', 'success');
			} else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}
			
			$(this).prop("disabled", false);
		});
		
	}

});

$(document).on('change', '.tags_input', function(e) {
	const [type, id] = $(this).data('id').split('-');
	
	const data = {
		id,
		type,
		value: $(this).val()
	};

	if(data['value']){
		$.ajax({
			url: 'index.php?route=extension/module/possible_products/updateTag&token=<?php echo $token; ?>',
			data,
			beforeSend: function() {
				$('#list_tags').find('tbody').find('tr')
				.prop("disabled", true);
			}
		}).done(function(res) {
		
			if (res === true) {
				resultOutput('Данные успешно изменены', 'success');
			} else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}

			$('#list_tags').find('tbody').find('tr')
				.prop("disabled", false);
		});
		
	}
	
});

$(document).on('click', '.tags_remove', function() {
	const data = {
		id: +$(this).data('id')
	};
	
	if(data['id']){
		$.ajax({
			url: 'index.php?route=extension/module/possible_products/deleteTag&token=<?php echo $token; ?>',
			data,
			beforeSend: function() {
				$('#list_tags').find('tbody').find('tr')
				.prop("disabled", true);
			}
		}).done((res) =>{
		
			if (res === true) {
				resultOutput('Данные успешно удалены', 'success');
				$(this).closest('tr').remove();
			} else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}

			$('#list_tags').find('tbody').find('tr')
				.prop("disabled", false);
		});
		
	}

});

$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});
</script>
<?php echo $footer; ?>