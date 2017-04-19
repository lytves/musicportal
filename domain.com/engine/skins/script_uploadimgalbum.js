$(document).ready(function() {

			var button = $('#uploadButton'), interval;

			$.ajax_upload(button, {
						action : 'engine/modules/mservice/ajax.php',
						data : {act:'27',aid:$("#aid_number").text()},
						name : 'image',
						onSubmit : function(file, ext) {
							// показываем картинку загрузки файла
							$("img#load").attr("src", "engine/skins/images/load.gif");
							$("#uploadButton font").text('Uploading');

							/*
							 * Выключаем кнопку на время загрузки файла
							 */
							this.disable();

						},
						onComplete : function(file, response) {
							// убираем картинку загрузки файла
							$("img#load").attr("src", "engine/skins/images/loadstop.gif");
							$("#uploadButton").html('<font color="blue">Upload yet</font><img id="load" src="/engine/skins/images/loadstop.gif"/>');

							// снова включаем кнопку
							this.enable();

							// показываем что файл загружен
							if (response != '') {
                $("#response").html('');
                $("<img src='/uploads/albums/"+response+"'>").appendTo("#response");
                $("#uploadButton").html('<font color="green">Upload ok, picture saved!</font>');
                $("#files_types").html('');
                this.disable();
							}
							else $('<font color="red"><li>Loading error</li></font>').appendTo("#response");
              
						}
					});
		});
