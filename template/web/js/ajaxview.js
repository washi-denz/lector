
/*++++++++++++++++++++++++++++++++++++++++++
+                                          +
+	Create 	: 	Josue Mazco Puma		   +
+	E-mail 	: 	JossMP@gmail.com		   +
+	Twitter : 	@JossMP777                 +
+   Adapted :   Washi Llacsa M. (Paralel)  +
+                                          +
++++++++++++++++++++++++++++++++++++++++++*/

(function($){

	$.fn.extend({
		ajaxview: function(options){
			var $rtn     = [];
			var cto      = null;              // clearTimeout
			var defaults = {
				ajaxserialize : [],           // ID Formulario
				ajaxdestine	  : [],		      // URL
				ajaxdata      : [],			  // Datos extras a Enviar			
				ajaxasync     : true,		  // async
				GetData		  : function(){}, // Funccion
				complete	  : function(){}, // Funccion
				success		  : function(){}  // Funccion
			};

			var opts = $.extend(defaults,options);

			defaults.onload = function(str){

				var str = (str!='auto')? '#'+str : 'body_auto';
				str     = str.split("_");
				var selector  = str[0];
				var type      = str[1];

				$(selector).css({"position":"relative"});

				$(selector).fadeIn(50,function(){

					if(type == 'top')
					{
						$(this).html('<div class="load-top" id="onload"><i class="icon-spin6 animate-spin"></i></div>');
					}
					else if(type == 'center'){
						$(this).html('<div class="load-center" id="onload"><i class="icon-spin2 animate-spin"></i></div>');
					}
					else if(type == 'bottom')
					{
						$(this).html('<i class="icon-spin6 animate-spin onload-icon" id="onload"></i>');
					}
					else if(type == 'auto'){
						$(this).prepend('<div class="load-center" id="onload"><i class="icon-spin4 animate-spin"></i></div>');
					}
				});
			}

			defaults.offload = function(){				
				$("#onload").fadeOut(100,function(){
					$(this).remove();
				});				
			}

			defaults.strTemporaryNotific = function(cont='',type=''){
				var str = '';
				if(type  == 'notific-top')
				{
					var str ='<div class="container-notification top" id="containerNotification">'+cont+'</div>';
				}
				else if(type == 'notific-bottom')
				{
					var str ='<div class="container-notification" id="containerNotification">'+cont+'</div>';
				}
				$("body").prepend(str);
			}

			defaults.showTemporaryNotific__ = function(cont='',type='',time=0){
	
				if(!$("#containerNotification").hasClass("container-notification")){
					opts.initTemporaryNotific();
					time = (time != 0)? time : 3000;

					opts.strTemporaryNotific(cont,type);

					cto = setTimeout(function(){
						$("#containerNotification").fadeOut(1000,function(){
							opts.hideTemporaryNotific();
						});
					},time);

				}else{
					opts.initTemporaryNotific();
										time = (time != 0)? time : 3000;

					opts.strTemporaryNotific(cont,type);

					cto = setTimeout(function(){
						$("#containerNotification").fadeOut(1000,function(){
							opts.hideTemporaryNotific();
						});
					},time);
				}

			}

			defaults.showTemporaryNotific_2 = function(cont='',type='',time=0){
					
					time = (time != 0)? time : 3000;

					opts.initTemporaryNotific(0);
					opts.strTemporaryNotific(cont,type);

					cto = setTimeout(function(){
						$("#containerNotification").fadeOut(500,function(){
							opts.initTemporaryNotific(100);
						});
					},time);

				

			}
			
			defaults.hideTemporaryNotific__ = function(){

				$("#containerNotification").fadeOut(100,function(){
					//opts.hideTemporaryNotific();

					$("#containerNotification").remove();
					window.clearTimeout(cto);
					clearTimeout(cto);
				});
				
			}

			defaults.initTemporaryNotific = function(fo){
				$("#containerNotification").fadeOut(fo,function(){		
					window.clearTimeout(cto);
					clearTimeout(cto);
					$("#containerNotification").remove();
				});
			}

			defaults.serializefiles = function(id){
				var obj = $(id);
				
				var formData = new FormData();
				$.each($(obj).find("input[type='file']"), function(i, tag) {
					$.each($(tag)[0].files, function(i, file) {
						formData.append(tag.name, file);
					});
				});

				var params = $(obj).serializeArray();
				$.each(params, function (i, val){
					formData.append(val.name, val.value);
				});
				return formData;
			}

			this.each(function(){

				var $this      = $(this);
				var $serialize = $this.data('serialize'); // ID de Form para Serializar sin incluir #
				var $destine   = $this.data('destine');	  // Requerido: url, si no existe usa el 'href'
				var $data	   = $this.data('data');      // Datos extra en json (Ejem: "{'a':'b','b':'c'}")

				var $formData  = new FormData();

				if(typeof($serialize)!='undefined')
				{
					$formData    = opts.serializefiles("#"+$serialize);
					$contentType = 'multipart/form-data';
				}

				if(typeof($destine)=='undefined')
				{				
					$destine = opts.ajaxdestine;
				}

				if(typeof($destine)!='undefined')
				{
					var destine = $destine.split("/");
					$destine    = _URL+destine[0]+"/json/"+destine[1];
				}

				if(typeof($data)!='undefined')
				{
					$json=$data;
					$.each($json, function (i, val)
					{   								
						$formData.append(i, val);
						
					});
				}else{
					$json=opts.ajaxdata;				
					for(var i in $json)
					{
						$formData.append(i,$json[i]);
					}				
				}

				if($formData.get('redirect') == 'auto'){				
					var url = window.location;
					$formData.set('redirect',url);
				}

				if($formData.get('edit') == 'true'){				
					$formData.set('json',JSON.stringify(examen));
				}

				//imprimir formData
				//for(var pair of $formData.entries()){ console.log(pair[0]+ '='+ pair[1]);}
				//	console.log("destine="+$destine)
	
				$.ajax({
					url: $destine,
					data: $formData,
					cache: false,
					contentType: false,
					processData: false,
					type: "POST",
					//contentType: $contentType,
					//mimeType: $contentType,
					async:opts.ajaxasync,
					dataType: "json",
					beforeSend: function()
					{
						($formData.get("load")!= null)? opts.onload($formData.get("load")) : '';
					},
					complete: function(x,s)
					{
						opts.offload();
					},
					error: function()
					{
						//alert("ERROR: Parece que el servidor no responde...");
						console.log('%c_ERROR: Parece que el servidor no responde...','color:red');
					},
					success: function(respuesta)
					{						
						if(!respuesta['success'] || respuesta['success'])
						{
							if(respuesta['return'] != undefined && respuesta['return'] != ''){
								$rtn = respuesta['return'];
							}
							if(respuesta['update'] != undefined && respuesta['update'] != '')
							{
								for(var i=0; i<respuesta['update'].length; i++)
								{
									if(respuesta['update'][i]['action']=="prepend")
									{
										if(typeof(respuesta['update'][i]['selector']) != 'undefined'){
											$(respuesta['update'][i]['selector']).prepend(respuesta['update'][i]['value']);
										}else{
											$("#"+respuesta['update'][i]['id']).prepend(respuesta['update'][i]['value']);
										}
									}
									else if(respuesta['update'][i]['action']=="append")
									{
										if(respuesta['update'][i]['type'] == 'class'){
											$("."+respuesta['update'][i]['class']).append(respuesta['update'][i]['value']);
										}else{
											$("#"+respuesta['update'][i]['id']).append(respuesta['update'][i]['value']);
										}
									}
									else if(respuesta['update'][i]['action']=="replaceWith")
									{
										$("#"+respuesta['update'][i]['id']).replaceWith(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="html")
									{
										if(respuesta['update'][i]['type'] == 'class'){
											$("."+respuesta['update'][i]['class']).html(respuesta['update'][i]['value']);
										}
										else if(typeof(respuesta['update'][i]['selector']) != 'undefined'){
											$(respuesta['update'][i]['selector']).html(respuesta['update'][i]['value']);
										}
										else{
											$("#"+respuesta['update'][i]['id']).html(respuesta['update'][i]['value']);	
										}
									
									}	
									else if(respuesta['update'][i]['action']=="val")
									{
										if(respuesta['update'][i]['type'] == 'class'){
											$("."+respuesta['update'][i]['class']).val(respuesta['update'][i]['value']);
										}else{
											$("#"+respuesta['update'][i]['id']).val(respuesta['update'][i]['value']);	
										}										
									}
									else if(respuesta['update'][i]['action']=="hide")
									{
										$("#"+respuesta['update'][i]['id']).hide();
									}
									else if(respuesta['update'][i]['action']=="show")
									{
										$("#"+respuesta['update'][i]['id']).show();
									}
									else if(respuesta['update'][i]['action']=="remove")
									{
										if(typeof(respuesta['update'][i]['selector']) != 'undefined'){
											$(respuesta['update'][i]['selector']).remove();
										}else{
											$("#"+respuesta['update'][i]['id']).remove();
										}										
									}
									else if(respuesta['update'][i]['action']=="addClass")
									{
										$("#"+respuesta['update'][i]['id']).addClass(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="removeClass")
									{
										if(typeof(respuesta['update'][i]['querySelector']) != 'undefined'){
											$(respuesta['update'][i]['querySelector']).removeClass(respuesta['update'][i]['value']);
										}else{
											$("#"+respuesta['update'][i]['id']).removeClass(respuesta['update'][i]['value']);
										}

									}
									else if(respuesta['update'][i]['action']=="toggleClass")
									{
										$("#"+respuesta['update'][i]['id']).toggleClass(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="css")
									{
										$("#"+respuesta['update'][i]['id']).css(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="attr")
									{
										if(typeof(respuesta['update'][i]['querySelector']) != 'undefined'){											
											$(respuesta['update'][i]['querySelector']).attr(respuesta['update'][i]['value1'],respuesta['update'][i]['value2']);
										}else{
											$("#"+respuesta['update'][i]['id']).attr(respuesta['update'][i]['value1'],respuesta['update'][i]['value2']);
										}									
									}		
									else if(respuesta['update'][i]['action']=="attrHTML")
									{
										if(respuesta['update'][i]['type'] == 'addClass'){
											$("["+respuesta['update'][i]['attr']+"="+respuesta['update'][i]['value']+"]").addClass(respuesta['update'][i]['class']);
										}	
										if(respuesta['update'][i]['type'] == 'css'){
											$("["+respuesta['update'][i]['attr']+"="+respuesta['update'][i]['value']+"]").css(respuesta['update'][i]['json']);
										}								
									}
									else if(respuesta['update'][i]['action']=="showModal"){
										
										$("#modalFooter").html("");
										$("#modalFooterLeft").html("");

										if(!$(".modal").is("#"+respuesta['update'][i]['id'])){
											$("body").append(str_modal(respuesta['update'][i]['type'],respuesta['update'][i]['id']));	
										}
									}
									else if(respuesta['update'][i]['action']=="openModal"){

										$id = "#"+respuesta['update'][i]['id'];

										$($id+">div").removeClass("modal-sm");
										$($id+">div").removeClass("modal-lg");
										$($id+">div").removeClass("modal-xl");

										$($id+">div").removeClass("modal-fullscreen");
										$($id+">div").removeClass("modal-fullscreen-sm-down");
										$($id+">div").removeClass("modal-fullscreen-md-down");
										$($id+">div").removeClass("modal-fullscreen-lg-down");
										$($id+">div").removeClass("modal-fullscreen-xl-down");

										$(".modal-msg").html("");

										if(typeof(respuesta['update'][i]['style'])!='undefined')
										{
											$($id+">div").addClass(respuesta['update'][i]['style']);
										}

										$($id).modal('show');
										
										if(typeof(pushbar) != 'undefined') pushbar.close();

										//opts.hideTemporaryNotific();
										opts.initTemporaryNotific();

									}
									else if(respuesta['update'][i]['action']=="closeModal"){
										$("#"+respuesta['update'][i]['id']).modal('hide');
									}
									else if(respuesta['update'][i]['action']=="before"){
										$("#"+respuesta['update'][i]['id']).before(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="after"){
										
										$("#"+respuesta['update'][i]['id']).after(respuesta['update'][i]['value']);
									}													
									else if(respuesta['update'][i]['action']=="setItem"){
										sessionStorage.setItem(respuesta['update'][i]['name'],respuesta['update'][i]['value']);																										;
									}
									else if(respuesta['update'][i]['action']=="removeItem"){
										sessionStorage.removeItem(respuesta['update'][i]['value']);																										
									}
									else if(respuesta['update'][i]['action']=="sessionStorage"){
										$("#"+respuesta['update'][i]['id']).html(sessionStorage.getItem(respuesta['update'][i]['value']));
									}
									else if(respuesta['update'][i]['action']=="download"){
										location.href=respuesta['update'][i]['href'];
									}
									else if(respuesta['update'][i]['action']=="notification"){

										if(respuesta['update'][i]['type'] == "notific-top")
										{
											opts.showTemporaryNotific_2(respuesta['update'][i]['value'],respuesta['update'][i]['type'],respuesta['update'][i]['time']);
										}
										else if(respuesta['update'][i]['type'] == "notific-bottom"){
											opts.showTemporaryNotific_2(respuesta['update'][i]['value'],respuesta['update'][i]['type'],respuesta['update'][i]['time']);
										}
										else if(typeof(respuesta['update'][i]['delay']) != 'undefined'){
											opts.showTemporaryNotific_2(respuesta['update'][i]['value'],'notific-bottom',respuesta['update'][i]['delay']);
										}
										else{											
											opts.showTemporaryNotific_2(respuesta['update'][i]['value'],'notific-bottom',respuesta['update'][i]['time']);
										}
									}								
									else if(respuesta['update'][i]['action']=="redirection"){
										if(respuesta['update'][i]['type'] == "delay"){

											let time = (Number.isInteger(respuesta['update'][i]['time']))?respuesta['update'][i]['time']:2000;
											let url  = respuesta['update'][i]['value'];
											setTimeout(function(){
												top.location.href = url;
											},time);
											
										}
										else if(respuesta['update'][i]['type'] == "auto"){
											top.location.href = window.location;
										}
										else{
											top.location.href = respuesta['update'][i]['value'];	
										}
									}
									else if(respuesta['update'][i]['action']=="this"){

										if(respuesta['update'][i]['type'] == "attr")
										{
											$this.attr(respuesta['update'][i]['value1'],respuesta['update'][i]['value2']);
										}
										else if(respuesta['update'][i]['type'] == "prop")
										{
											$this.prop(respuesta['update'][i]['value1'],respuesta['update'][i]['value2']);
										}
										else if(respuesta['update'][i]['type'] == "remove")
										{
											$this.remove();
										}
										else if(respuesta['update'][i]['type'] == "html")
										{
											$this.html(respuesta['update'][i]['value']);
										}
										else if(respuesta['update'][i]['type'] == "after")
										{
											$this.after(respuesta['update'][i]['value']);
										}
										else if(respuesta['update'][i]['type'] == "before")
										{
											$this.before(respuesta['update'][i]['value']);
										}
										else if(respuesta['update'][i]['type']=="addClass")
										{
											$this.addClass(respuesta['update'][i]['value']);
										}
										else if(respuesta['update'][i]['type']=="removeClass")
										{
											$this.removeClass(respuesta['update'][i]['value']);
										}
										else{
											$this.html(respuesta['update'][i]['value']);
										}

									}
									else if(respuesta['update'][i]['action']=="clearTimeout"){
										clearTimeout(_RELOJ);
										clearTimeout(_RELOJBAR);
									}
									else if(respuesta['update'][i]['action']=="clearInterval"){
										window.clearInterval(respuesta['update'][i]['value']);
									}
									else if(respuesta['update'][i]['action']=="reset"){
										var select = $("#"+respuesta['update'][i]['id']);									
										select.val($('option:first', select).val());
									}
									else if(respuesta['update'][i]['action']=="focus"){

										if(typeof(respuesta['update'][i]['delay']) != 'undefined'){

											let time = (Number.isInteger(respuesta['update'][i]['delay']))?respuesta['update'][i]['delay']:500;																
											const id = respuesta['update'][i]['id'];

											setTimeout(function(){
												console.log("DOS");
												document.getElementById(id).focus();
											},time);

										}else{
											$("#"+respuesta['update'][i]['id']).focus().select();	
											document.elementById(respuesta['update'][i]['id']).focus();
										}										
									}
									else if(respuesta['update'][i]['action'] == "include"){

										$(respuesta['update'][i]['selector']).each(function(){

											let str = $(this).attr('class');
											    str = str.split(" ");

											for(var j=0;j<str.length;j++){
												if(str[j].includes(respuesta['update'][i]['patron'])) 
													str[j] = respuesta['update'][i]['value'];
											}

											let unir = str.join(' ');
											$(this).attr('class',unir);
											console.log(unir);
										});

									}

								}
								/////////////////////
							}
							if(typeof(respuesta['notification'])!='undefined')
							{
								alert(respuesta['notification']);
							}
							if(typeof(respuesta['redirection'])!='undefined')
							{
								top.location.href = respuesta['redirection'];
							}
							
							opts.success();
						}
					}
				});

			});

			return $rtn;
		}
	});

	$(document).on("click",".send", function(e)
	{
		e.preventDefault();
		$(this).ajaxview({
			"success":function(){

				$modal = $(this).data("target");
				$type  = $(this).data("target-type");

				$($modal+">div").removeClass("modal-sm");
				$($modal+">div").removeClass("modal-lg");
				$($modal+">div").removeClass("modal-full");

				$($modal+" #modalMsj").html("");

				if(typeof($type)!='undefined')
				{
					$($modal+">div").addClass($type);
				}
				
			}
		});
	});

	$(document).on("click",".send-nopd", function(e)
	{
		//e.preventDefault();
		$(this).ajaxview({});
	});

	$(document).on('click','.open-modal', function(e)
	{
		e.preventDefault();
		
		$this = this;

		$(this).ajaxview({
			"success":function()
			{
				$Modal = $($this).data("target");
				
				$type = $($this).data("target-type");

				$($Modal+">div").removeClass("modal-sm");
				$($Modal+">div").removeClass("modal-lg");
				$($Modal+">div").removeClass("modal-full");

				$($Modal+" #modalMsj").html("");

				if(typeof($type)!='undefined')
				{
					$($Modal+">div").addClass($type);
				}
				
				$($Modal).modal('show');
				
				$Form = $($this).data("serialize");

				if(typeof($Form)!='undefined')
				{
					$("#"+$Form+":not(.filter) :input:visible:enabled:first").focus();
				}
			},
			"complete":function(){
				/*
				$('.modal .input-daterange').datepicker({
					format: "yyyy-mm-dd",
					//startDate: "today",
					autoclose: true,
					todayHighlight: true
				});
				$("form:not(.filter) :input:visible:enabled:first").focus();
				$Form = $(".modal form").attr("id");
				if(typeof($Form)!='undefined')
				{
					$("#"+$Form+":not(.filter) :input:visible:enabled:first").focus();
				}
				$("[data-inputmask]").inputmask();
				*/
			}
		});
	});

	$(document).on('click','.close-modal', function(e)
	{
		$this=this;
		e.preventDefault();
		$(this).ajaxview({
			"success":function(){
				$Modal = $($this).data("target");
				$($Modal).modal('hide');
			}
		});
	});

})(jQuery);

// paralel