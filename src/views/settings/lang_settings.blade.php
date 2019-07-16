<div class="langbox">
	<div class="langbox__title">@lang('admin::main.settings.new_lang')</div>
	<table class='langbox__table'>
		<tbody>
			<tr>
				<td width="10%">
					<input maxlength='2' class='langbox__input' id='new_lang_code' placeholder='@lang("admin::main.settings.code")'/>
				</td>
				<td width="70%">
					<input class='langbox__input' id='new_lang_name' placeholder='@lang('admin::main.name')'/>
				</td>
				<td width="20%">
					<label class="langbox__label">
						<input type="checkbox" id="new_lang_hidden" checked />
						&nbsp;@lang('admin::main.hidden')
					</label>
				</td>
			</tr>
		</tbody>
	</table>
	<a class='langbox__add-lang' href='#'>@lang('admin::main.add')</a>

	<div class="langbox__title">@lang('admin::main.settings.existing_langs')</div>
	<table id='existing_langs' class='langbox__table langbox__table--addlang'>
		<tbody>
			@foreach ($languages as $lang)
				<tr id='ln_{{ $lang->id }}'>
					<td>{{ $lang->order+1 }}</td>
					<td>{{ $lang->code }}</td>
					<td>
						<a href='#' class='lang_editable'  data-emptytext="@lang("admin::main.name")" data-field='name' data-id="{{ $lang->id }}" >{{ $lang->name }}</a>
					</td>
					<td style='width: 150px;'>
						<div style='margin: 0;' class="checkbox">
							<label class="langbox__label">
								<input type="checkbox" class="lang_checkbox" data-field="hidden" data-id="{{ $lang->id }}" {{ ($lang->hidden == 1) ? "checked='checked'" : "" }} >
								&nbsp;@lang('admin::main.hidden')
							</label>
						</div>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

@section('links')
@parent
	<link rel="stylesheet" type="text/css" href="{{ asset('public/demos/admin/css/jquery-ui.min.css') }}" />
	<style type="text/css">
		.ui-state-highlight{ height:40px; border-radius: 3px; background-color: rgb(200, 200, 200); }
	</style>
@stop

@section('scripts')
	@parent
	<script type="text/javascript" src="{{ asset('public/demos/admin/js/jquery-ui.min.js') }}"></script>
	<script type="text/javascript">
		function saveLang(id, field, value) {
			$.post('{{ route('admin.saveLanguage') }}', {'id': id,'field': field,'value': value, }, function(data) {
                alertify.success('{{ Lang::get('admin::main.saved') }}');
            });
		}

		$(document).ready(function() {
			$(".lang_editable").editable({
				send: "never"
			}).on('save', function(e, params) {
				var value = params.newValue,
					id = $(this).attr('data-id'),
					field = $(this).attr('data-field');
				saveLang(id, field, value);
			});

			$('.lang_checkbox').on('change', function() {
				var value = ($(this).is(":checked")) ? 1 : 0,
					id = $(this).attr('data-id'),
					field = $(this).attr('data-field');
				saveLang(id, field, value);
			});


			$('.langbox__add-lang').click(function (e) {
				e.stopPropagation();
				e.preventDefault();
				var code = $("#new_lang_code").val();
				var name = $("#new_lang_name").val();
				var hidden = ($("#new_lang_hidden").is(":checked")) ? 1 : 0;
				if ( $.trim(code) !='' && $.trim(name) !='') {
					$.post('{{ route('admin.addLanguage') }}', {'code': code, 'name': name, 'hidden': hidden }, function(data) {
		                window.location.reload(true);
		            });
				};
			});
			var fixHelper = function(e, ui) {ui.children().each(function() {$(this).width($(this).width());});return ui;};
			var allow_update = null;
			$("#existing_langs tbody").sortable({
				distance: 10,
				helper: fixHelper,
				placeholder: 'ui-state-highlight',
				start: function(e, ui) {
					clearTimeout(allow_update);
				},
				update: function(e, ui)  {
					allow_update = window.setTimeout(function() {
						var ord = $("#existing_langs tbody").sortable('toArray');
						$.post('{{ route('admin.ordLanguages') }}', {'ord': ord }, function(data) {
			                alertify.success('{{ Lang::get('admin::main.saved') }}');
			            });

					}, 1000);
				}
			});
		});

	</script>
@stop
