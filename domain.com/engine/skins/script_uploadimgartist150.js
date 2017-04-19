$(document).ready(function() {

			var button = $('#uploadButton150'), interval;

			$.ajax_upload(button, {
						action : 'engine/modules/mservice/ajax.php',
						data : {act:'50',artid:$("#artid_number").text()},
						name : 'image',
						onSubmit : function(file, ext) {
							// показываем картинку загрузки файла
							$("img#load150").attr("src", "engine/skins/images/load.gif");
							$("#uploadButton150 font").text('Uploading');

							/*
							 * Выключаем кнопку на время загрузки файла
							 */
							this.disable();

						},
						onComplete : function(file, response) {
							// убираем картинку загрузки файла
							$("img#load150").attr("src", "engine/skins/images/loadstop.gif");
							$("#uploadButton150").html('<font color="blue">Upload yet</font><img id="load150" src="/engine/skins/images/loadstop.gif"/>');

							// снова включаем кнопку
							this.enable();

							// показываем что файл загружен
							if (response != '') {
                $("#response150").html('');
                $("<img src='/uploads/artists/"+response+"'>").appendTo("#response150");
                $("#uploadButton150").html('<font color="green">Upload ok, picture saved!</font>');
                $("#files_types150").html('');
                this.disable();
							}
							else $('<font color="red"><li>Loading error</li></font>').appendTo("#response150");
              
						}
					});
		});
