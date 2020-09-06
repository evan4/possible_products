<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-banner" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
				<h1><?php echo $heading_title; ?></h1>
				<ul class="breadcrumb">
					<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="container-fluid">
			<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php } ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
				</div>
				<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-hok-sharing" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status">Статус</label>
						<div class="col-sm-10">
							<select name="status" id="input-status" class="form-control">
									<option value="1" <?php echo ($status == "1" ? "selected" : ""); ?>><?php echo $text_enabled; ?></option>
									<option value="0" <?php echo ($status == "0" ? "selected" : ""); ?>><?php echo $text_disabled; ?></option>
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name" id="nput-name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
							<?php if ($error_name) { ?>
							<div class="text-danger"><?php echo $error_name; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-name_tag"><?php echo $entry_name_tag; ?></label>
						<div class="col-sm-10">
							<input type="text" name="name_tag" id="nput-name_tag" value="<?php echo $name_tag; ?>" placeholder="<?php echo $entry_name_tag; ?>" id="input-name_tag" class="form-control" />
							<?php if ($error_name_tag) { ?>
							<div class="text-danger"><?php echo $error_name_tag; ?></div>
							<?php } ?>
						</div>
					</div>
				</form>
				<div>
					<div class="alert alert-dismissible fade in" role="alert" id="result">
						<button type="button" class="close alert-close" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<strong></strong>
					</div>
					<h3>Добавить страницу</h3>
					<div class="alert alert-info">
						<i class="fa fa-info-circle"></i> Адрес вводить без домена, как /skates/child/
					</div>
					<div id="block_form mb10">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="block_title">Заголовок</label>
									<input type="test" class="form-control" id="block_title" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="block_url">Адрес</label>
									<input type="url" class="form-control" id="block_url" required>
								</div>
							</div>
						</div>
						<button type="button" class="btn btn-primary" id="block">Добавить</button>
					</div>

					<h3>Список страниц</h3>

					<table class="table table-striped" id="list_blocks">
						<thead>
							<tr>
								<th>Текст</th>
								<th>Адрес</th>
								<td>Действие</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($pages as $page) { ?>
							<tr>
								<td>
									<a href="<?php echo $edit.'&page_id='.$page['id']; ?>" title="Редактировать"><?=$page['title']; ?></a>
								</td>
								<td>
									<a href="<?=$page['url']; ?>" target="_blank"><?=$page['url']; ?></a>
								</td>
								<td class="text-left">
									<button type="button" data-id="<?=$page['id']?>"
										title="удалить" class="btn btn-danger block_remove">
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
<script>
//blocks
$('#block').on('click', function (e) {
	e.preventDefault();

	const block_title = $('#block_title');
	const block_url = $('#block_url');

	if(block_title.val() && block_url.val()){
		const data = {
			title: block_title.val().trim(),
			url: block_url.val().trim()
		};
		$.ajax({
			url: 'index.php?route=extension/module/possible_products/addPage&token=<?php echo $token; ?>',
			data,
			beforeSend: function() {
				$('#block').prop("disabled", true);
			}
		}).done((res) =>{
			const {id, title, url, result} = res;
			if (res.result === true) {
				$template = `
				<tr>
					<td>
						<a href="<?php echo $edit;?>&page_id=${id}" title="Редактировать">${title}</a>
					</td>
					<td>
						<a href="${url}" target="_blank">${url}</a>
					</td>
					<td class="text-left">
						<button type="button" data-id="${id}"
							title="удалить" class="btn btn-danger block_remove">
							<i class="fa fa-minus-circle"></i>
						</button>
					</td>
				</tr>
				`;
				$('#list_blocks').find('tbody').append($template);
				block_title.val('');
				block_url.val('');
				resultOutput('Данные успешно сохранены', 'success');
			}else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}
			$('#block').prop("disabled", false);
		});
	}
});

$(document).on('click', '.block_remove', function() {
	const data = {
		id: +$(this).data('id')
	};
	
	$.ajax({
			url: 'index.php?route=extension/module/possible_products/deletePage&token=<?php echo $token; ?>',
			data,
			beforeSend: () => {
				$(this).prop("disabled", true);
			}
		}).done((res) => {
			if (res === true) {
				
				$(this).closest('tr').remove();
				resultOutput('Данные успешно сохранены', 'success');
			} else {
				resultOutput('Произошла ошибка. Попробуйте еще раз', 'warning');
			}

			$(this).prop("disabled", false);
		});


});
</script>
<?php echo $footer; ?>