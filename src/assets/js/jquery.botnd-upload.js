
;(function ( $, window, undefined ) {

	$.fn.botndUpload = function(options) {

		var defaults = {
			title: 'Обновить изображение',// title кнопки
			btnClass: 'btn-primary',// класс кнопки
			btnText: 'Upload',		// текст на кнопке
			cancelText: 'Cancel',	// текст на кнопке отмены
			url: 'upload.php',		// url аплоадера
			fieldname: 'userfile[]',// имя поля инпута формы
			data:         null,		// доп. дата в $_POST
			multiple:     true,		// мультизагрузка
			show_block:   false,    // класс btn-block кнопке
			dynamicData:  null,		// динамические данные 
			showFileList: true, 	// показать список файлов
			progressbars: 'files', 	// прогрессбары: 'files' - для каждого файла, 'total' - для общего прогресса
			isProgressbars: false, 	// Отображать прогресс
			showErrors:   true,		// показывать ошибки загрузки
			allowedExts:  "",		// допустимые расширения файлов, через '|'
			allowedExtsError: "Invalid type of file",	// текст ошибки расширения
			allowedSize:  0,		// допустимый размер в МБ, 0 - анлим
			allowedSizeError: "Файл не соответствует необходимым требованиям",	// текст ошибки размера
			filesNumber:  0,		// допустимое кол-во файлов
			filesNumberError: "Too many files",	// текст ошибки кол-ва файлов
			inlineButton: false,
			onSubmit:  function(file, data) {},
			onError:   function(file, error) {},
			onSuccess: function(file, data) {},
			onTotalSuccess: function() {},
			setData:   function(data) {}
		};

		var options = $.extend(true, defaults, options);

		var obj,
			file = new Object,
			stop = false;

		this.each(function(){	

			obj = $(this);

			if (options.multiple===true) 
				obj.attr('multiple', 'multiple');
			else 
				obj.removeAttr('multiple');

			var link = $('<a href="javascript:void(0)" class="btn '+options.btnClass+' btn-upload">'+options.btnText+'</a>');

			var dataAttrs = obj.data();

			for (var key in dataAttrs) {
				link.attr('data-'+key, dataAttrs[key]);
			}

			if (options.show_block===true)
				link.addClass('btn-block');
			
			obj.after(link);
			if (options.inlineButton!=true)
				link.after($('<div class="upload-hold"></div>'));
			obj.css({
				"opacity":'0',
				"visibility":'hidden',
				'position':'absolute',
				'top':'-9999px'
			});
			

			link.click(function(){
				var data = link.data();
				for (var key in data) {
					options.data[key] = data[key];
				}
				obj.click();
			});

			obj.on('change', function(){

				options.onSubmit();

				stop = false;

				obj.siblings('.upload-hold:first')
					.empty()
					.append('<div ><p><span class="file-counter">0</span>/'+obj[0].files.length+'</p></div>');

				obj.addClass('upload-active');

				if (options.filesNumber>0) {
					if (obj[0].files.length>options.filesNumber) { 
						obj.siblings('.upload-hold:first').html("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button>"+options.filesNumberError+"</div>");
						obj.removeClass('upload-active');
						return;
					}
				}

				/*if ($('.cancel-upload').length==0) {
					var cancel = $("<a href='javascript:void(0)' class='btn btn-danger btn-admin-red cancel-upload'>"+options.cancelText+"</a>");
					obj.siblings('.upload-hold:first').before(cancel);					
					cancel.click(function(){
						stop = true;					
					});				
				}*/

				if (options.isProgressbars && options.progressbars=='total') {
					var progressbar = "<div class='file-item'><div class='file-item-name'></div><div class='progress progress-striped'><div class='bar' style='width:0%;'><span class='badge badge-info'></span></div></div></div>";
					obj.siblings('.upload-hold:first').append(progressbar);
				}

				(function(){

					var i = 0;

					function uploadFiles() {

						if (i<obj[0].files.length&&stop===false) {

							file.name = obj[0].files[i].name;
							file.size = obj[0].files[i].size;
							
							if (options.allowedSize>0) {
								if (!validateSize(file.size)) {
									obj.siblings('.upload-hold:first').append("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button>Error! "+obj[0].files[i].name+" "+options.allowedSizeError+"</div>");
									i++; uploadFiles(); return;
								}
							}
							
							if (options.allowedExts!='') {
								if (!validateExt(file.name) ) {
									obj.siblings('.upload-hold:first').append("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button>Error! "+obj[0].files[i].name+" "+options.allowedExtsError+"</div>");
									i++; uploadFiles(); return;
								}
							}
		
							
		
							if (options.isProgressbars && options.progressbars=='files') {
								var progressbar = "<div class='file-item'><div class='file-item-name'>"+file.name+"</div><div class='progress progress-striped'><div class='bar' style='width:0%;'><span class='badge badge-info'></span></div></div></div>";
								obj.siblings('.upload-hold:first').append(progressbar);
							}

							if (options.dynamicData) {
								for( var key in options.dynamicData) {
									options.data[key] = $("#"+options.dynamicData[key]).val();
								}
							}
		
							var formData = new FormData();
		
							formData.append(options.fieldname, obj[0].files[i]);
				
							formData.append('data', JSON.stringify(options.data) );
				
							$.ajax({
								url: options.url,
								type:"POST",
								data: formData,
								dataType: 'json',
								cache: false,
								contentType: false,
								processData: false,
								success: function(data) {
									if (options.isProgressbars) {
										var percent,
											obj = $("input.upload-active");

										if (options.progressbars=='files') percent = 100;
										if (options.progressbars=='total') {
											percent = (((i+1)/obj[0].files.length)*100).toFixed(0);										
										}
					
										obj.siblings('.upload-hold:first')
											.find('.file-item:last')
											.children('.file-item-name')
												.text(file.name);

										var bar = obj.siblings('.upload-hold:first')
													.find('.file-item:last')
														.children('.progress.progress-striped')
															.children('.bar');

											bar.width(percent+'%');
											
											bar.children('span').html(percent+"%");

											if (options.progressbars=='files') {
												bar.addClass('bar-success')
												bar.children('span').toggleClass('badge-info badge-success');
											}
									}


									if (data.status=='ok') {
										options.onSuccess(data.file, data);
									} else {
										options.onError(data.file, data);
				
										obj.siblings('.upload-hold:first').empty();
				
										if (options.showErrors==true) 
											obj.siblings('.upload-hold:first').append("<div class='alert alert-error'><button type='button' class='close' data-dismiss='alert'>&times;</button>"+options.allowedSizeError+"</div>");
																						
									}
				
								},
								xhr: function() {
									xhr = $.ajaxSettings.xhr();
									if (options.isProgressbars && xhr.upload) {
										xhr.upload.addEventListener('progress', progressHandler, false);
									}
									return xhr;
								},
								complete: function(jqXHR, textStatus) {
									if (textStatus=='success') {
										i++; uploadFiles();
										$(".file-counter").html(i);
									}
								}
				
							});
	
						} else {
							obj.removeClass('upload-active');
							obj.siblings('.cancel-upload:first').remove();
							if (options.isProgressbars && options.progressbars=='total') {
								obj.siblings('.upload-hold:first')
									.find('.file-item:last')
										.children('.progress.progress-striped')
											.children('.bar')
												.addClass('bar-success')
												.children('span')
													.toggleClass('badge-info badge-success');								
							}
							options.onTotalSuccess();
						}
					}
					uploadFiles();
				})();
			});
		});

		function validateSize(size) {
			if ((size/1024)/1024>options.allowedSize)				
				return false;
			return true;
		}

		function validateExt(name) {
			if ( $.inArray( name.split('.').pop(), options.allowedExts.split('|') ) === -1 ) 
				return false;
			return true;
		}


		function progressHandler(e) {
			if(e.lengthComputable){
				var total = e.total,
					loaded = e.loaded,
					obj = $("input.upload-active");
			
				
				if (options.isProgressbars && options.progressbars=='files') {
					var percent = Number(((e.loaded * 100)/e.total).toFixed(0));
					obj.siblings('.upload-hold:first')
						.find('.file-item:last')
						.children('.progress.progress-striped')
							.children('.bar')
								.width(percent+'%')
								.children('span')
									.html(percent+"%");
				}
        	}
		}

	}

}(jQuery, window));
