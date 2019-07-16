@extends('admin::layouts.wrapper')
@section('page')
	<div class="content">
		<div class="content__inside">
			<p class='page_title'>{{ $page_title }}</p>
			<div class='row'>
				<div class='col-xs-3'>
					<p class='subrubric-title'>@lang('admin::main.available_aspects')</p>
					<form style='padding-left: 30px;' id='crop_form' method="POST" action="{{ route('admin.crop') }}">
						{{ csrf_field() }}
						<input type="hidden" name='img_big' value='{{ $data["img_big"] }}'>
						<input type="hidden" name='img_small' value='{{ $data["img_small"] }}'>
						<input type="hidden" name='img_thumb' value='{{ $data["img_thumb"] }}'>
						<input type="hidden" name='aspectable' value='{{ $data["aspectable"] }}'>
						<input type="hidden" name='back_url' value='{{ $data["back_url"] }}'>
						@if ($data['aspectable'] == 0)
							<input class="form-control text-center" type='text' disabled name='aspect' value='{{ $data["aspect"] }}'>
						@else
							<select id='aspect-select' class="form-control text-center" name="aspect">
								@for ($i = 0; $i < count($options['aspects']); $i++)
									<option class='text-center' {{ ($options['aspects'][$i] == $data['aspect']) ? 'selected' : '' }} value="{{ $options['aspects'][$i] }}">{{ $options['aspects'][$i] }}</option>
								@endfor
							</select>
						@endif
					</form>
				</div>
				<div class='col-xs-9'>
					<p>@lang('admin::main.how_to_crop')</p>
					<img class='img-responsive'  id='act_area' src='{{ $data["img_big"]."?rand=".rand(1,1000) }}' /><br />
					<a class="do_crop" href="#">@lang('admin::main.crop')</a>
					<a class="btn" href="{{ $data['back_url'] }}">@lang('admin::main.back')</a>
				</div>
			</div>
		</div>
	</div>
@stop

@section('links')
<link rel="stylesheet" type="text/css" href="{{ asset('public/demos/admin/css/imgareaselect-animated.css') }}" />
@stop

@section('scripts')
<script type="text/javascript" src="{{ asset('public/demos/admin/js/jquery.imgareaselect.pack.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#aspect-select").on('change', function() {
			$("#crop_form").submit();
		});
	});
	$(window).on('load', function(){
		var	original_width = "{{ $data['original_width'] }}",
			view_width = $("#act_area").width(),
			coef = parseInt(original_width)/parseInt(view_width);

		var aspect = "{{ $data['aspect'] }}",
			small_size = "{{ $options['small_size'] }}",
			thumb_size = "{{ $options['thumb_size'] }}",
			back_url = "{{ $data['back_url'] }}";

		var small_width = small_size;
		var	small_height = small_size;

		var thumb_width = thumb_size;
		var	thumb_height = thumb_size;

		var aspect_sizes = aspect.split(":");

		var aspect_width = parseInt(aspect_sizes[0]);
		var aspect_height = parseInt(aspect_sizes[1]);

		if (aspect_width > aspect_height) {
			small_height = small_size;
			small_width = (aspect_width*small_size)/aspect_height;
			thumb_height = thumb_size;
			thumb_width = (aspect_width*thumb_size)/aspect_height;
		} else if (aspect_width < aspect_height) {
			small_width = small_size;
			small_height = (aspect_height*small_size)/aspect_width;
			thumb_width = thumb_size;
			thumb_height = (aspect_height*thumb_size)/aspect_width;
		}

		small_width = parseInt(small_width);
		small_height = parseInt(small_height);
		thumb_width = parseInt(thumb_width);
		thumb_height = parseInt(thumb_height);

		var	out_width = thumb_width+1,
			out_height = thumb_height+1,
			x1 = 1,
			y1 = 1;
			$(window).scrollTop(0);

		$("#act_area").imgAreaSelect({
			aspectRatio:aspect,
			enable:true,
			hide:false,
			fadeSpeed:0.6,
			handles: true,
			instance:true,
			minWidth:parseInt(thumb_width/coef),
			minHeight:parseInt(thumb_height/coef),
			x1: 0, y1: 0, x2: parseInt(thumb_width/coef), y2: parseInt(thumb_height/coef),
			onSelectEnd: function (img, selection) {
				out_width = (selection.width);
				out_height = (selection.height);
				x1 = (selection.x1);
				y1 = (selection.y1);
			}
		});


		$(".do_crop").click(function (e) {
  			e.stopPropagation();
  			e.preventDefault();
  			var data = {
					"img_big":"{{ $data['img_big'] }}",
					"img_small":"{{ $data['img_small'] }}",
					"img_thumb":"{{ $data['img_thumb'] }}",
					"out_width":parseInt(out_width*coef),
					"out_height":parseInt(out_height*coef),
					"x1":parseInt(x1*coef),
					"y1":parseInt(y1*coef),
					"small_width":small_width,
					"small_height":small_height,
					"thumb_width":thumb_width,
					"thumb_height":thumb_height,
				};
  			$.ajax ({
				type:"POST",
				url: "{{ route('admin.doCrop') }}",
				data: ({"data" : data}),
				success: function (data) {
					if (data == "ok") {
						window.location.href = back_url;
					}
				}
			});
		});
	});
</script>
@stop
