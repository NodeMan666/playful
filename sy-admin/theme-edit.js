function editArea(div,area) { 
	$("#mainstyle").show();
	$("#additionalcss").hide();
	$(".menu-item").removeClass("menuon");
	$(".editoption").hide();
	$(".sub").hide();
	$("#"+div).fadeIn(100);
	$("#menu-"+div).addClass("menuon");
	$("#menu-"+div).parent().children(".sub").show();
	if(area !== "") { 
		$(".editarea").hide();
		$("#"+area).show();
	}
}
function editAreaSub(div,area) { 
	$(".editoption").hide();
	$("#"+div).fadeIn(100);
	$("#menu-"+div).addClass("menuon");
	$("#menu-"+div).parent().children(".sub").show();
	if(area !== "") { 
		$(".editarea").hide();
		$("#"+area).show();
	}
}
function editadditionalcss() { 
	$("#additionalcss").show();
	$("#mainstyle").hide();

}

function resizeVG(bgImg,where) {
		$("#bgFade").css({
			width:$("#themepreview").width()+'px',
			height:$("#themepreview").height()+'px',
			"bottom":"0",
			"right":"0"
		});
} 

function hex2rgba(x,a) {
  var r=x.replace('#','').match(/../g),g=[],i;
  for(i in r){g.push(parseInt(r[i],16));}g.push(a);
  return 'rgba('+g.join()+')';
}

function resizeOnPhotoPhoto() { 
	$(".onphotophoto").each(function(i){
		var this_id = this.id;
       	var this_src =  $("#"+this_id).attr("src"); 
		var image = new Image();
		image.onload = function() {
			pic_width = Math.abs($("#"+this_id).attr("ww"));
			pic_height = Math.abs($("#"+this_id).attr("hh"));
		
			newwidthpercent = Math.abs($("#on_photo_width").val()) / Math.abs(pic_width);
			newheightpercent = Math.abs($("#on_photo_height").val()) / Math.abs(pic_height);
		//	$("#log").html(Math.abs($("#on_photo_width").val())+" / "+Math.abs(pic_width)+" = "+Math.abs($("#on_photo_width").val()) / Math.abs(pic_width));


			if(newwidthpercent > newheightpercent) { 
				newheight =  pic_height * newwidthpercent;
				newwidth = $("#on_photo_width").val();
					if(newheight < $("#on_photo_height").val()) { 
						newheight = $("#on_photo_height").val();
						newwidth = pic_width * ($("#on_photo_height").val()  / pic_height);
					}
				margintop = (newheight - $("#on_photo_height").val()) / 2;
				$("#"+this_id).css({
					"height":newheight+"px",
					"width":newwidth+"px",
					"margin-top":"-"+margintop+"px"
				});

			} else { 
				newwidth =  pic_width * newheightpercent;
				newheight = $("#on_photo_height").val();
				if(pic_height < $("#on_photo_height").val()) { 
					newheight = $("#on_photo_height").val();
					newwidth = pic_width * ($("#on_photo_height").val()  / pic_height);
				}
				marginleft = (newwidth - $("#on_photo_width").val()) / 2;
				$("#"+this_id).css({
					"width":newwidth+"px",
					"height":newheight+"px",
					"margin-left":"-"+marginleft+"px"
				});
			}


			

			$("#"+this_id).fadeIn('fast', function() { });
	//		$("#log").html(pic_width+" X "+pic_height+" - "+newwidthpercent+" X "+newheightpercent+" - "+$("#on_photo_width").val()+" X "+$("#on_photo_height").val());
		};
		image.src =this_src;
      }).promise().done( function(){ 
		
	} );

}
function openBackgroud() { 
	$("#selectbackground").fadeIn('fast', function() {
		$.get("w-backgrounds.php?noclose=1&nojs=1", function(data) {
			$("#selectbackground").html(data);
	//		setTimeout(hideLoadingMore,1000);
		});
	});
}

function openHeader() { 
	$("#shadepagecontainer").fadeIn('fast');
	$("#shadepagecontent").fadeIn('fast');
	$("#shadepageenter").fadeIn('fast');
	$("#selectbackground").css({"width":"1050px", "margin-left":"-512px", "z-index":"500"});
	$("#selectbackground").fadeIn('fast', function() {
		$("#selectbackground").html('<iframe name=windowlargeframe width='+$("#page_width").val()+'px height=100% src=w-header-edit.php?noclose=1&height='+$("#header_height").val()+' frameborder=0>');
	});
}
function editHeader() { 
	$("#headercontent").toggle();
	$("#headeredit").toggle();
		$("#headercontent").html($("#header_html").val());
		alert($("#header_html").val());
}

function openBackgroudFolder(folder) { 
	$.get("w-backgrounds.php?noclose=1&nojs=1&folder="+folder, function(data) {
		$("#selectbackground").html(data);
	});
}

function closeBackground() { 
	window.parent.$("#selectbackground").fadeOut('fast', function() { 
		window.parent.$("#selectbackground").html('<div  class="loading"></div>');
	});
}
function closeHeader() { 
	window.parent.$("#shadepagecontainer").fadeOut('fast');
	window.parent.$("#shadepagecontent").fadeOut('fast');
	window.parent.$("#shadepageenter").fadeOut('fast');
	window.parent.$("#selectbackground").fadeOut('fast', function() { 
		window.parent.$("#selectbackground").html('<div  class="loading"></div>');
	});
}


function deleteBackground(file,num) { 
	$.get("w-backgrounds.php?noclose=1&action=deleteBackground&file="+file, function(data) {
		$("#background-"+num).fadeOut(100);
	});
}

function backgroundStyle() { 
	 $("#bg_image_style").val($("#bg_image_style_select").val());
}


	function changeLook() { 
		if($("#add_bg_overlay").val() !== "0") { 
			$("#bgFade").show();
				bg_box_shadow = "0px 0px "+$("#add_bg_overlay").val()+"px 0px  #000000 inset";
				$("#bgFade").css({ "box-shadow":bg_box_shadow });
		} else { 
			$("#bgFade").hide();
		}
		$(".menusep").html($("#menu_sep").val());

		/* ###### FOOTER  ########### */
		if($("#footer_outside").val() == "1") { 
			$("#footerinside").hide();
			$("#footeroutside").show();
		} else { 
			$("#footerinside").show();
			$("#footeroutside").hide();
		}
		
		$(".footer").css({"background-color":"#"+$("#footer_bg").val(),"color":"#"+$("#footer_text_color").val(),"padding":$("#footer_padding").val()+"px", "font-size":$("#footer_font_size").val()+"px"});
		$(".footer a").css({"color":"#"+$("#footer_link_color").val()});
		$(".footer a:hover").css({"color":"#"+$("#footer_hover_color").val()});

		 resizeVG();
/*		 if($("#use_random_bg").attr('checked')){
			$("#backgroundOptions").hide();
		 } else { 
			$("#backgroundOptions").show();
		 }
*/

		if($("#outside_bg_image").val() !== "") { 
			if($("#bg_image_style").val() == "centercover") { 
				$("#themepreview").css("background","#"+$("#outsideBg").val()+" url("+$("#outside_bg_image").val()+") no-repeat center center fixed");
				$("#themepreview").css("background-size","cover");
			} else if($("#bg_image_style").val() == "topcover") { 
				$("#themepreview").css("background","#"+$("#outsideBg").val()+" url("+$("#outside_bg_image").val()+") no-repeat top center fixed");
				$("#themepreview").css("background-size","cover");
			} else { 
				$("#themepreview").css("background","#"+$("#outsideBg").val()+" url("+$("#outside_bg_image").val()+") "+$("#bg_image_style").val()+"");

			}
		} else { 
			$("#themepreview").css("background","#"+$("#outsideBg").val());
		}
		/*
		if($("#use_random_bg").attr("checked")) { 
			$("#themepreview").css("background","#"+$("#outsideBg").val()+" url('graphics/photo-large.jpg') no-repeat center center fixed");
			$("#themepreview").css("background-size","cover");
		}
		*/
		$("#headercontent").css({"font-family":$("#header_font").val(),"font-size":$("#header_font_size").val()+"px"});

		$(".editarea").css({
			"font-size":$("#fontSize").val()+"px"
		});

		$(".fontsize").css({
			"font-size":$("#fontSize").val()+"px"
		});
		$(".fontcolor").css({
			"color":"#"+$("#fontColor").val()
		});

		$("#inside_bg").css({
			"width":$("#page_width").val(),
			"max-width":$("#page_width_max").val()+"px",
			"color":"#"+$("#fontColor").val(),
			"font-size":$("#fontSize").val()+"px",
			"font-family":$("#cssFontFamilyMain").val()
			});
		$("#inside_bg_inner").css({
			"padding":$("#inside_padding").val()+"px"
		});

		$("#main_font_color").css({
			"font-size":$("#fontSize").val()+"px",
			"color":"#"+$("#fontColor").val()
		});


		$("#link_color").css({
			"color":"#"+$("#linkColor").val()
		});
		$("#link_color_hover").css({
			"color":"#"+$("#linkColorHover").val()
		});
		$("#page_dates").css({
			"color":"#"+$("pageDates").val()
		});

		if($("#underline_links").attr("checked")) { 
			$("#link_color").css("text-decoration", "underline");
		} else { 
			$("#link_color").css("text-decoration", "none");
		}



		 if($("#inside_bg_transparent").attr('checked')){
			$("#inside_bg").css({"background":"transparent", "margin-top":$("#inside_margin_top").val()+"px"});
			$("#inside_bg_options").hide();
		 } else { 
			$("#inside_bg").css({
				"background-color":hex2rgba($("#insideBg").val(),$("#inside_opacity").val()),
				"border":""+$("#inside_bg_border_size").val()+"px "+$("#inside_bg_border_style").val()+" #"+$("#inside_bg_border").val(),
				"margin-top":$("#inside_margin_top").val()+"px"
			});
			$("#inside_bg_options").show();

		 }

		if($("#menu_placement").val() == "right") { 
			$(".topmenulink").css({
				"color":"#"+$("#menuLinkColor").val(),
				"padding":$("#top_menu_spacing").val() / 2+"px",
				"margin":"0px",
				"font-size": $("#footer_menu_font_size").val()+"px"	

			});
		} else { 
			$(".topmenulink").css({
				"color":"#"+$("#menuLinkColor").val(),
				"padding":$("#top_menu_spacing").val() / 2+"px",
				"font-size": $("#footer_menu_font_size").val()+"px",
				"margin":"0px",

			});
		}

			$(".topmenulink .the-icons").css({
				"color":"#"+$("#menuLinkColor").val(),

				"font-size": $("#footer_menu_font_size").val()+"px"	

			});


		if($("#underline_menu_links").attr("checked")) { 
			$(".topmenulink").css("text-decoration", "underline");
		} else { 
			$(".topmenulink").css("text-decoration", "none");
		}

		if($("#top_menu_side_borders").attr("checked")) { 
			$("#topmenubgs").show();
			if($("#top_menu_button_transparent").attr("checked")) { 
				tmb = "transparent";
			} else { 
				tmb = "#"+$("#top_menu_bg").val();
			}
			$(".topmenulink").css({"background": tmb, "border-left":"solid 1px #"+$("#top_menu_border_l").val(),"border-right":"solid 1px #"+$("#top_menu_border_r").val(),"padding-top":$("#main_menu_padding").val()+"px","padding-bottom":$("#main_menu_padding").val()+"px"});
			
		} else { 
			$("#topmenubgs").hide();
			$(".topmenulink").css({"background": "transparent", "border-left":"0px","border-right":"0px"});
		}

			$("#mainMenuContainerOuter").css({
				"float": "left",
				"width":"100%",
				"position":"relative"
			});




		/* HEADER  BOX SHADOW */
			if($("#header-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#header-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#header-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#header-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#header-bs1-0").val()+"px "+$("#header-bs1-1").val()+"px "+$("#header-bs1-2").val()+"px "+$("#header-bs1-3").val()+"px  "+hex2rgba($("#header-bs1-color").val(),$("#header-bs1-5").val())+" "+bs16+"";
			}
			if($("#header-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#header-bs2-0").val()+"px "+$("#header-bs2-1").val()+"px "+$("#header-bs2-2").val()+"px "+$("#header-bs2-3").val()+"px  "+hex2rgba($("#header-bs2-color").val(),$("#header-bs2-5").val())+" "+bs26+"";
			}
			if($("#header-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#header-bs3-0").val()+"px "+$("#header-bs3-1").val()+"px "+$("#header-bs3-2").val()+"px "+$("#header-bs3-3").val()+"px  "+hex2rgba($("#header-bs3-color").val(),$("#header-bs3-5").val())+" "+bs36+"";
			}

			$("#headerContainerInner").css({ "box-shadow":"none" });
			$("#headerAndMenu").css({ "box-shadow":"none" });


			if($("#header-box-shadow-1").attr("checked")) { 
				$("#header-bs1").show();
			} else { 
				$("#header-bs1").hide();
			}

			if($("#header-box-shadow-2").attr("checked")) { 
				$("#header-bs2").show();
			} else { 
				$("#header-bs2").hide();
			}

			if($("#header-box-shadow-3").attr("checked")) { 
				$("#header-bs3").show();
			} else { 
				$("#header-bs3").hide();
			}

	/* END HEADER BOX SHADOW */ 


		/* ##############  HEADER MENU PLACEMENT ################# */

		if($("#menu_placement").val() == "right") { 
			$("#mainMenuContainerOuter").css({
				"float": "right",
				"width":"100%",
				"position":"relative"
			});

			$("#mainMenuContainer").css({ 
				"width":"auto",
				"position":"absolute",
				"bottom":"0",
				"right":"0",
				"left":"auto"
			});
			$("#main_menu").css({ 
				"width":"auto"
			});
			$("#header").css({ 
				"width":"auto"
			});
			$("#header").css({
				"float":"left",
				"width":"auto"
			});
			$("#headerAndMenu").css({
				"height":$("#header_height").val()+"px"
			});

			$("#headerAndMenu").css({
/*				"width": $("#page_width").val(), */
					"margin":"auto"
			});
		$("#headerContainer").css({
			"width":$("#page_width").val(),
			"max-width":$("#page_width_max").val()+"px",

			"margin":"auto"
		});


		if($("#header_wide").attr('checked')) { 
			$("#headerAndMenu").css({"width":"100%","max-width":"100%"});
		} else { 
			$("#headerAndMenu").css({"width":$("#page_width").val(),"max-width":$("#page_width_max").val()+"px"});
			$("#headerAndMenu").css({
				"margin":"auto",
				"float":"none"
			});
		}

			if($("#header-box-shadow-1").attr("checked")) { 
				$("#headerAndMenu").css({ "box-shadow":boxes_box_shadow });
			}


		 if($("#header_transparent").attr('checked')){
	//		$("#header").css("background","transparent");
			$("#headerAndMenu").css("background","transparent");
			$("#headerColors").hide();
		 } else { 
			$("#headerAndMenu").css({
				"background-color":hex2rgba($("#header_bg").val(),$("#header_opacity").val()),
				"height":$("#header_height").val()+"px"
			});
//			$("#header").css("background","#"+$("#header_bg").val());
			$("#headerColors").show();
		 }


		} else  if($("#menu_placement").val() == "left") { 
			$("#mainMenuContainerOuter").css({
				"float": "left",
				"width":"100%",
				"position":"relative"
			});

			$("#mainMenuContainer").css({ 
				"width":"auto",
				"position":"absolute",
				"bottom":"0",
				"left":"0",
				"right":"auto"
			});
			$("#main_menu").css({ 
				"width":"auto"
			});
			$("#header").css({ 
				"width":"auto"
			});
			$("#header").css({
				"float":"right",
				"width":"auto"
			});
			$("#headerAndMenu").css({
				"height":$("#header_height").val()+"px"
			});

			$("#headerAndMenu").css({
				"width": $("#page_width").val(),
				"max-width":$("#page_width_max").val()+"px",
					"margin":"auto"
			});
			$("#headerContainer").css({
				"width":$("#page_width").val(),
			"max-width":$("#page_width_max").val()+"px",

				"margin":"auto"
			});
		if($("#header_wide").attr('checked')) { 
			$("#headerAndMenu").css({"width":"100%","max-width":"100%"});
		} else { 
			$("#headerAndMenu").css({"width":$("#page_width").val(),"max-width":$("#page_width_max").val()+"px"});
			$("#headerAndMenu").css({
				"margin":"auto",
				"float":"none"
			});
		}


			if($("#header-box-shadow-1").attr("checked")) { 
				$("#headerAndMenu").css({ "box-shadow":boxes_box_shadow });
			}

		 if($("#header_transparent").attr('checked')){
	//		$("#header").css("background","transparent");
			$("#headerAndMenu").css("background","transparent");
			$("#headerColors").hide();
		 } else { 
			$("#headerAndMenu").css({
				"background-color":hex2rgba($("#header_bg").val(),$("#header_opacity").val()),
				"height":$("#header_height").val()+"px"
			});
//			$("#header").css("background","#"+$("#header_bg").val());
			$("#headerColors").show();
		 }

		} else  if($("#menu_placement").val() == "rightleft") { 
			$("#headerContainerInner").css({"height":"auto"});
			$("#mainMenuContainerOuter").css({
				"float": "left",
				"width":"auto",
				"position":"static"
			});

			$("#mainMenuContainer").css({ 
				"width":"auto",
				"position":"absolute",
				"bottom":"0",
				"right":"auto",
				"left":"auto"
			});
			$("#main_menu").css({ 
				"width":"auto"
			});
			$("#header").css({ 
				"width":"auto",
				"max-width":"auto"
			});
			$("#header").css({
				"float":"left",
				"width":"auto"
			});
			$("#headerAndMenu").css({
				"height":$("#header_height").val()+"px"
			});

			$("#headerAndMenu").css({
/*				"width": $("#page_width").val(), */
					"margin":"auto"
			});
		$("#headerContainer").css({
			"width":$("#page_width").val(),
			"max-width":$("#page_width_max").val()+"px",

			"margin":"auto"
		});


		if($("#header_wide").attr('checked')) { 
			$("#headerAndMenu").css({"width":"100%","max-width":"100%"});
		} else { 
			$("#headerAndMenu").css({"width":$("#page_width").val(),"max-width":$("#page_width_max").val()+"px"});
			$("#headerAndMenu").css({
				"margin":"auto",
				"float":"none"
			});
		}

			if($("#header-box-shadow-1").attr("checked")) { 
				$("#headerAndMenu").css({ "box-shadow":boxes_box_shadow });
			}


		 if($("#header_transparent").attr('checked')){
	//		$("#header").css("background","transparent");
			$("#headerAndMenu").css("background","transparent");
			$("#headerColors").hide();
		 } else { 
			$("#headerAndMenu").css({
				"background-color":hex2rgba($("#header_bg").val(),$("#header_opacity").val()),
				"height":$("#header_height").val()+"px"
			});
//			$("#header").css("background","#"+$("#header_bg").val());
			$("#headerColors").show();
		 }



		} else { 

			$("#headerContainer").css({
				"width":"100%",
				"margin":"auto"
			});

			$("#headerAndMenu").css({
				"height":$("#header_height").val()+"px"
			});

			$("#mainMenuContainer").css({ 
				"width":"auto",
				"position":"static",
				"bottom":"auto",
				"left":"auto",
				"right":"auto"

			});
			if($("#main_menu_wide").attr("checked")) { 
				$("#mainMenuContainer").css({"width":"100%","max-width":"100%"});
				$("#main_menu").css({
					"width":$("#page_width").val(),
			"max-width":$("#page_width_max").val()+"px",

					"margin":"auto"
				});
			} else { 
				$("#mainMenuContainer").css({"width":$("#page_width").val(),"max-width":$("#page_width_max").val()+"px"});
			}
			$("#header").css({
				"width":$("#page_width").val(),
			"max-width":$("#page_width_max").val()+"px"

			});

			$("#headerAndMenu").css("width","100%");
			$("#headerContainer").css("width","100%");
			$("#headerAndMenu").css("background","transparent");	
			$("#header").css({
				"float":"none"
			});

		if($("#header_wide").attr('checked')) { 
			$("#headerAndMenu").css({"width":"100%","max-width":"100%"});
		} else { 
		//	$("#headerAndMenu").css({"width":$("#page_width").val(),"max-width":$("#page_width_max").val()+"px"});
			$("#headerAndMenu").css({
				"margin":"auto",
				"float":"none"
			});
		}

			if($("#header_wide").attr('checked')) { 
				$("#headerContainerInner").css({"width":"100%","max-width":"100%"});
			} else { 
				$("#headerContainerInner").css({"width":$("#page_width").val(),"max-width":$("#page_width_max").val()+"px"});
				$("#headerContainerInner").css({
					"margin":"auto",
					"float":"none"
				});
			}
			if($("#header-box-shadow-1").attr("checked")) { 
				$("#headerContainerInner").css({ "box-shadow":boxes_box_shadow });
			}

		 if($("#header_transparent").attr('checked')){
	//		$("#header").css("background","transparent");
			$("#headerContainerInner").css("background","transparent");
			$("#headerColors").hide();
		 } else { 
			$("#headerContainerInner").css({
				"background-color":hex2rgba($("#header_bg").val(),$("#header_opacity").val()),
				"height":$("#header_height").val()+"px"
			});
//			$("#header").css("background","#"+$("#header_bg").val());
			$("#headerColors").show();
		 }


		}
		/* ############## END HEADER MENU PLACEMENT ################# */

		$("#shopmenuinner").css({
			"width":$("#page_width").val(),
			"max-width":$("#page_width_max").val()+"px",

			"margin":"auto",
			"color":"#"+$("#sm_text").val(),
			"font-family":$("#sm_font_family").val()
		});
		$("#shopmenuinner2").css({
		"padding":$("#sm_padding_tb").val()+"px "+$("#sm_padding_lr").val()+"px "+$("#sm_padding_tb").val()+"px "+$("#sm_padding_lr").val()+"px ",
		});

		if($("#sm_background_transparent").attr("checked")) { 
			$("#shopmenu").css({
				"background":"transparent",
				"border":"none"
			});
		} else { 
			$("#shopmenu").css({
				"background":"#"+$("#sm_background").val(),
				"border-top":"solid 1px #"+$("#sm_border_top").val(),
				"border-bottom":"solid 1px #"+$("#sm_border_bottom").val()
			});
		}
		$(".sm_link").css({
			"color":"#"+$("#sm_link_color").val()
		});

		 if($("#sm_text_shadow_on").attr('checked')){
			$("#shopmenuinner").css("text-shadow",$("#sm_text_shadow_h").val()+"px "+$("#sm_text_shadow_v").val()+"px "+$("#sm_text_shadow_b").val()+"px #"+$("#sm_text_shadow_c").val());
			$("#smmenuts").show();
		 } else { 
			$("#shopmenuinner").css("text-shadow","none");
			$("#smmenuts").hide();

		 }
		$(".shopmenuitem").css({
			"margin-left":$("#sm_spacing").val()+"px",
			"font-size":$("#sm_font_size").val()+"px"

		});


		$("#main_menu").css({
			"font-family":$("#menu_font").val(),
			"font-size": $("#footer_menu_font_size").val()+"px",				
			"color": "#"+$("#menu_font_color").val()
		});
		$("#main_menu_inner").css({
			"padding":"0px "+$("#top_menu_padding").val()+"px 0px "+$("#top_menu_padding").val()+"px",
			"font-size": $("#footer_menu_font_size").val()+"px"	

		});
		if($("#menu_use").val() == "topmain") { 
			$("#topmain-menu").show();
			$("#additional-menu").hide();
		} else { 
			$("#topmain-menu").hide();
			$("#additional-menu").show();
		}

		if($("#side_menu_use").val() == "topmain") { 
			$("#sidemenumain-menu").show();
			$("#sidemenuadditional-menu").hide();
		} else { 
			$("#sidemenumain-menu").hide();
			$("#sidemenuadditional-menu").show();
		}
	// $("#log").html($("#side_menu_use").val());



		if($("#menu_transparent").attr("checked")) { 
			$("#main_menu").css("background","transparent");
			$("#mainMenuContainer").css("background","transparent");
			$("#mainMenuContainer").css({
				"border-top": "0px",
				"border-bottom":"0px",
				"padding-top":$("#main_menu_padding").val()+"px",
				"padding-bottom":$("#main_menu_padding").val()+"px",
				"margin": "auto",
				"box-shadow":"none"
			});

			$("#menu_options").hide();
		} else { 

			$("#mainMenuContainer").css({
				"background-color":hex2rgba($("#menu_color").val(),$("#menu_opacity").val()),
			});
			$("#mainMenuContainer").css({
				"border-top": "solid 1px #"+$("#menu_border_a").val(),
				"border-bottom": "solid 1px #"+$("#menu_border_b").val(),
				"padding-top":$("#main_menu_padding").val()+"px",
				"padding-bottom":$("#main_menu_padding").val()+"px",
				"margin": "auto"
			});

			$("#menu_options").show();
		}

		if($("#menu_center").attr("checked")) { 
			$("#main_menu").css("text-align", "center");
		} else { 
			$("#main_menu").css("text-align", "left");
		}
		


		$("#header").css({
			"color":"#"+$("#header_font_color").val(),
			"margin": "auto",
			"height":$("#header_height").val()+"px"
			});


		 if($("#header_center").attr('checked')){
			$("#header").css("text-align","center");
		} else {
			$("#header").css("text-align","left");
		}

		$("#headerinner").css({
			"padding-left":$("#header_padding").val()+"px",
			"padding-right":$("#header_padding").val()+"px",
			"padding-top":$("#header_padding_tb").val()+"px",
			"padding-bottom":$("#header_padding_tb").val()+"px",
				
		});

		 if($("#header_text_shadow_on").attr('checked')){
			$("#header").css("text-shadow",$("#header_text_shadow_h").val()+"px "+$("#header_text_shadow_v").val()+"px "+$("#header_text_shadow_b").val()+"px #"+$("#header_text_shadow_c").val());
			$("#headerts").show();
		 } else { 
			$("#header").css("text-shadow","none");
			$("#headerts").hide();

		 }





		if($("#menu_text_shadow_on").attr("checked")) { 
			$("#main_menu").css("text-shadow",$("#menu_text_shadow_h").val()+"px "+$("#menu_text_shadow_v").val()+"px "+$("#menu_text_shadow_b").val()+"px #"+$("#menu_text_shadow_c").val());
			$("#menuts").show();
		} else { 
			$("#main_menu").css("text-shadow","none");
			$("#menuts").hide();
		}
		if($("#menu_upper").attr("checked")) { 
			$("#main_menu").css("text-transform","uppercase");
		} else { 
			$("#main_menu").css("text-transform","none");
		}

		if($("#title_text_shadow_on").attr("checked")) { 
			$("#titlets").show();
			$(".pagetitle").css("text-shadow",$("#title_text_shadow_h").val()+"px "+$("#title_text_shadow_v").val()+"px "+$("#title_text_shadow_b").val()+"px #"+$("#title_text_shadow_c").val());

		} else { 
			$("#titlets").hide();
			$(".pagetitle").css("text-shadow","none");

		}

		if($("#h1_upper").attr("checked")) { 
			$(".pagetitle").css("text-transform","uppercase");
		} else { 
			$(".pagetitle").css("text-transform","none");
		}


		$(".pagetitle").css({
			'font-size': $("#titleSize").val()+"px",
			"color": "#"+$("#pageTitleColor").val(),
			"font-family": $("#css_title_font_family_main").val()
		});
		$("#page_title_hover").css({
			'font-size': $("#titleSize").val()+"px",
			"color": "#"+$("#pageTitleHover").val(),
			"font-family": $("#css_title_font_family_main").val()
		});

		$("#h2").css({
			'font-size': $("#h2_size").val()+"px",
			"color": "#"+$("#pageTitleColor").val(),
			"font-family": $("#css_title_font_family_main").val()
		});
		$("#h3").css({
			'font-size': $("#h3_size").val()+"px",
			"color": "#"+$("#pageTitleColor").val(),
			"font-family": $("#css_title_font_family_main").val()
		});


		$("#form1").css({
			'font-size': $("#fontSize").val()+"px",
			"color": "#"+$("#form_color").val(),
			"font-family": $("#fontFamily").val(),
				"background-color":hex2rgba($("#form_bg").val(),$("#form_opacity").val()),
			"box-shadow": "none",
			"border": "solid 1px #"+$("#form_border").val()
		});

		$("#form2").css({
			'font-size': $("#fontSize").val()+"px",
			"color": "#"+$("#form_color").val(),
			"font-family": $("#fontFamily").val(),
				"background-color":hex2rgba($("#form_bg").val(),$("#form_opacity").val()),
			"box-shadow": "none",
			"border": "solid 1px #"+$("#form_border").val()
		});


		$("#form3").css({
			'font-size': $("#fontSize").val()+"px",
			"color": "#"+$("#form_color").val(),
			"font-family": $("#fontFamily").val(),
				"background-color":hex2rgba($("#form_bg").val(),$("#form_opacity").val()),
			"box-shadow": "none",
			"border": "solid 1px #"+$("#form_border").val()
		});

		$("#form4").css({
			'font-size': $("#fontSize").val()+"px",
			"color": "#"+$("#submit_text").val(),
			"font-family": $("#fontFamily").val(),
			"background": "#"+$("#submit_bg").val(),
			"box-shadow": "none",
			"border": "solid 1px #"+$("#submit_border").val()
		});

		$("#form5").css({
			'font-size': $("#fontSize").val()+"px",
			"color": "#"+$("#submit_disabled_text").val(),
			"font-family": $("#fontFamily").val(),
			"background": "#"+$("#submit_disabled_background").val(),
			"box-shadow": "none",
			"border": "solid 1px #"+$("#submit_disabled_border").val()
		});


		$("#form6").css({
			'font-size': $("#fontSize").val()+"px",
			"color": "#"+$("#submit_hover_text").val(),
			"font-family": $("#fontFamily").val(),
			"background": "#"+$("#submit_hover_background").val(),
			"box-shadow": "none",
			"border": "solid 1px #"+$("#submit_hover_border").val()
		});


		$("#photo_page").css({
			'padding': $("#photo_page_padding").val()+"px",
			"border": "solid "+$("#photo_page_border_size").val()+"px #"+$("#photo_page_border_color").val(),
			"background": "#"+$("#photo_page_background").val(),
			"box-shadow": "0px 1px "+$("#photo_page_shadow_size").val()+"px #"+$("#photo_page_shadow_color").val()
		});


		$("#enlarged_photo").css({
			'padding': $("#photo_padding").val()+"px",
			"border": "solid "+$("#photo_border_size").val()+"px #"+$("#photo_border_color").val(),
			"background": "#"+$("#photo_background").val()
			});

		$("#fullscreenbackground").css({
			"background":"#"+$("#full_screen_background").val(),
				"opacity":$("#full_screen_opacity").val()
		});



		$("#thumbnail_photo").css({
			"border": "solid 1px #"+$("#thumb_border").val()
		});

	$(".boxes_img").css({ 
		"width":$("#boxes_img_width").val()+"px",
		"height":"auto"
	});

		if($("#boxes_transparent").attr("checked")) { 

			$(".styled_content").css({
				'padding': $("#boxes_padding").val()+"px",
				"border": "solid 0px #"+$("#boxes_borders").val(),
				"background": "transparent",
				"box-shadow": "none",
				"color":"#"+$("#boxes_text").val(),
				"border-radius": "0px"
			});
			$("#boxes_options").hide();

			$(".styled_content_title").css({
				"color":"#"+$("#boxes_link").val(),
				"font-size":$("#boxes_title_size").val()+"px",
				"font-family":$("#css_title_font_family_main").val()

			});
		} else { 
	
			$(".styled_content").css({
				'padding': $("#boxes_padding").val()+"px",
				"background-color":hex2rgba($("#boxes_bg").val(),$("#boxes_opacity").val()),
				"border-radius": $("#boxes_rounded").val()+"px",
				"color":"#"+$("#boxes_text").val()
			});
			$(".styled_content_title").css({
				"color":"#"+$("#boxes_link").val(),
				"font-size":$("#boxes_title_size").val()+"px",
				"font-family":$("#css_title_font_family_main").val()

			});
			
			if($("#boxes_borders_where").val() == "1") { 
				$(".styled_content").css({ "border-bottom": ""+$("#boxes_borders_size").val()+"px "+$("#boxes_borders_style").val()+" #"+$("#boxes_borders").val() });
				$(".styled_content").css({ "border-top": "none" });
				$(".styled_content").css({ "border-left": "none" });
				$(".styled_content").css({ "border-right": "none" });
			} else { 
				$(".styled_content").css({ "border": ""+$("#boxes_borders_size").val()+"px "+$("#boxes_borders_style").val()+" #"+$("#boxes_borders").val() });
			}

		/* STYLED CONTENT BOX SHADOW */
			if($("#boxes-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#boxes-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#boxes-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#boxes-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#boxes-bs1-0").val()+"px "+$("#boxes-bs1-1").val()+"px "+$("#boxes-bs1-2").val()+"px "+$("#boxes-bs1-3").val()+"px  "+hex2rgba($("#boxes-bs1-color").val(),$("#boxes-bs1-5").val())+" "+bs16+"";
			}
			if($("#boxes-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#boxes-bs2-0").val()+"px "+$("#boxes-bs2-1").val()+"px "+$("#boxes-bs2-2").val()+"px "+$("#boxes-bs2-3").val()+"px  "+hex2rgba($("#boxes-bs2-color").val(),$("#boxes-bs2-5").val())+" "+bs26+"";
			}
			if($("#boxes-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#boxes-bs3-0").val()+"px "+$("#boxes-bs3-1").val()+"px "+$("#boxes-bs3-2").val()+"px "+$("#boxes-bs3-3").val()+"px  "+hex2rgba($("#boxes-bs3-color").val(),$("boxes-bs2-5").val())+" "+bs36+"";
			}

			if($("#boxes-box-shadow-1").attr("checked")) { 
				$(".styled_content").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$(".styled_content").css({ "box-shadow":"none" });
			}
			$("#boxes_options").show();
		}


			if($("#boxes-box-shadow-1").attr("checked")) { 
				$("#boxes-bs1").show();
			} else { 
				$("#boxes-bs1").hide();
			}

			if($("#boxes-box-shadow-2").attr("checked")) { 
				$("#boxes-bs2").show();
			} else { 
				$("#boxes-bs2").hide();
			}

			if($("#boxes-box-shadow-3").attr("checked")) { 
				$("#boxes-bs3").show();
			} else { 
				$("#boxes-bs3").hide();
			}

	/* END STYLED CONTENT BOX SHADOW */ 
			
		/* MAIN MENU BOX SHADOW */
			if($("#topmenu-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#topmenu-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#topmenu-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#topmenu-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#topmenu-bs1-0").val()+"px "+$("#topmenu-bs1-1").val()+"px "+$("#topmenu-bs1-2").val()+"px "+$("#topmenu-bs1-3").val()+"px  "+hex2rgba($("#topmenu-bs1-color").val(),$("#topmenu-bs1-5").val())+" "+bs16+"";
			}
			if($("#topmenu-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#topmenu-bs2-0").val()+"px "+$("#topmenu-bs2-1").val()+"px "+$("#topmenu-bs2-2").val()+"px "+$("#topmenu-bs2-3").val()+"px  "+hex2rgba($("#topmenu-bs2-color").val(),$("#topmenu-bs2-5").val())+" "+bs26+"";
			}
			if($("#topmenu-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#topmenu-bs3-0").val()+"px "+$("#topmenu-bs3-1").val()+"px "+$("#topmenu-bs3-2").val()+"px "+$("#topmenu-bs3-3").val()+"px  "+hex2rgba($("#topmenu-bs3-color").val(),$("#topmenu-bs3-5").val())+" "+bs36+"";
			}

			if($("#topmenu-box-shadow-1").attr("checked")) { 
				$("#mainMenuContainer").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$("#mainMenuContainer").css({ "box-shadow":"none" });
			}


			if($("#topmenu-box-shadow-1").attr("checked")) { 
				$("#topmenu-bs1").show();
			} else { 
				$("#topmenu-bs1").hide();
			}

			if($("#topmenu-box-shadow-2").attr("checked")) { 
				$("#topmenu-bs2").show();
			} else { 
				$("#topmenu-bs2").hide();
			}

			if($("#topmenu-box-shadow-3").attr("checked")) { 
				$("#topmenu-bs3").show();
			} else { 
				$("#topmenu-bs3").hide();
			}

	/* END STYLED CONTENT BOX SHADOW */ 
			

		/* CONTENT  BOX SHADOW */
			if($("#content-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#content-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#content-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#content-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#content-bs1-0").val()+"px "+$("#content-bs1-1").val()+"px "+$("#content-bs1-2").val()+"px "+$("#content-bs1-3").val()+"px  "+hex2rgba($("#content-bs1-color").val(),$("#content-bs1-5").val())+"  "+bs16+"";
			}
			if($("#content-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#content-bs2-0").val()+"px "+$("#content-bs2-1").val()+"px "+$("#content-bs2-2").val()+"px "+$("#content-bs2-3").val()+"px  "+hex2rgba($("#content-bs2-color").val(),$("#content-bs2-5").val())+"  "+bs26+"";
			}
			if($("#content-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#content-bs3-0").val()+"px "+$("#content-bs3-1").val()+"px "+$("#content-bs3-2").val()+"px "+$("#content-bs3-3").val()+"px  "+hex2rgba($("#content-bs3-color").val(),$("#content-bs3-5").val())+"  "+bs36+"";
			}

			if($("#content-box-shadow-1").attr("checked")) { 
				$("#inside_bg").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$("#inside_bg").css({ "box-shadow":"none" });
			}


			if($("#content-box-shadow-1").attr("checked")) { 
				$("#content-bs1").show();
			} else { 
				$("#content-bs1").hide();
			}

			if($("#content-box-shadow-2").attr("checked")) { 
				$("#content-bs2").show();
			} else { 
				$("#content-bs2").hide();
			}

			if($("#content-box-shadow-3").attr("checked")) { 
				$("#content-bs3").show();
			} else { 
				$("#content-bs3").hide();
			}

	/* END CONTENT BOX SHADOW */ 
		/* FULL SCREEN PHOTO  BOX SHADOW */
			if($("#fs-photos-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#fs-photos-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#fs-photos-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#fs-photos-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#fs-photos-bs1-0").val()+"px "+$("#fs-photos-bs1-1").val()+"px "+$("#fs-photos-bs1-2").val()+"px "+$("#fs-photos-bs1-3").val()+"px  "+hex2rgba($("#fs-photos-bs1-color").val(),$("#fs-photos-bs1-5").val())+"  "+bs16+"";
			}
			if($("#fs-photos-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#fs-photos-bs2-0").val()+"px "+$("#fs-photos-bs2-1").val()+"px "+$("#fs-photos-bs2-2").val()+"px "+$("#fs-photos-bs2-3").val()+"px  "+hex2rgba($("#fs-photos-bs2-color").val(),$("#fs-photos-bs2-5").val())+" "+bs26+"";
			}
			if($("#fs-photos-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#fs-photos-bs3-0").val()+"px "+$("#fs-photos-bs3-1").val()+"px "+$("#fs-photos-bs3-2").val()+"px "+$("#fs-photos-bs3-3").val()+"px  "+hex2rgba($("#fs-photos-bs3-color").val(),$("#fs-photos-bs3-5").val())+"  "+bs36+"";
			}

			if($("#fs-photos-box-shadow-1").attr("checked")) { 
				$("#enlarged_photo").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$("#enlarged_photo").css({ "box-shadow":"none" });
			}


			if($("#fs-photos-box-shadow-1").attr("checked")) { 
				$("#fs-photos-bs1").show();
			} else { 
				$("#fs-photos-bs1").hide();
			}

			if($("#fs-photos-box-shadow-2").attr("checked")) { 
				$("#fs-photos-bs2").show();
			} else { 
				$("#fs-photos-bs2").hide();
			}

			if($("#fs-photos-box-shadow-3").attr("checked")) { 
				$("#fs-photos-bs3").show();
			} else { 
				$("#fs-photos-bs3").hide();
			}

	/* END FULL SCREEN PHOTO  SHADOW */ 

		/* PHOTOS  BOX SHADOW */
			if($("#photos-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#photos-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#photos-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#photos-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#photos-bs1-0").val()+"px "+$("#photos-bs1-1").val()+"px "+$("#photos-bs1-2").val()+"px "+$("#photos-bs1-3").val()+"px  "+hex2rgba($("#photos-bs1-color").val(),$("#photos-bs1-5").val())+" "+bs16+"";
			}
			if($("#photos-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#photos-bs2-0").val()+"px "+$("#photos-bs2-1").val()+"px "+$("#photos-bs2-2").val()+"px "+$("#photos-bs2-3").val()+"px  "+hex2rgba($("#photos-bs2-color").val(),$("#photos-bs22-5").val())+"  "+bs26+"";
			}
			if($("#photos-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#photos-bs3-0").val()+"px "+$("#photos-bs3-1").val()+"px "+$("#photos-bs3-2").val()+"px "+$("#photos-bs3-3").val()+"px  "+hex2rgba($("#photos-bs3-color").val(),$("#photos-bs3-5").val())+"  "+bs36+"";
			}

			if($("#photos-box-shadow-1").attr("checked")) { 
				$("#photo_page").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$("#photo_page").css({ "box-shadow":"none" });
			}


			if($("#photos-box-shadow-1").attr("checked")) { 
				$("#photos-bs1").show();
			} else { 
				$("#photos-bs1").hide();
			}

			if($("#photos-box-shadow-2").attr("checked")) { 
				$("#photos-bs2").show();
			} else { 
				$("#photos-bs2").hide();
			}

			if($("#photos-box-shadow-3").attr("checked")) { 
				$("#photos-bs3").show();
			} else { 
				$("#photos-bs3").hide();
			}



		/* ON PHOTOS  BOX SHADOW */
			if($("#onphotos-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#onphotos-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#onphotos-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#onphotos-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#onphotos-bs1-0").val()+"px "+$("#onphotos-bs1-1").val()+"px "+$("#onphotos-bs1-2").val()+"px "+$("#onphotos-bs1-3").val()+"px  "+hex2rgba($("#onphotos-bs1-color").val(),$("#onphotos-bs1-5").val())+"  "+bs16+"";
			}
			if($("#onphotos-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#onphotos-bs2-0").val()+"px "+$("#onphotos-bs2-1").val()+"px "+$("#onphotos-bs2-2").val()+"px "+$("#onphotos-bs2-3").val()+"px  "+hex2rgba($("#onphotos-bs2-color").val(),$("#onphotos-bs2-5").val())+"  "+bs26+"";
			}
			if($("#onphotos-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#onphotos-bs3-0").val()+"px "+$("#onphotos-bs3-1").val()+"px "+$("#onphotos-bs3-2").val()+"px "+$("#onphotos-bs3-3").val()+"px  "+hex2rgba($("#onphotos-bs3-color").val(),$("#onphotos-bs3-5").val())+"  "+bs36+"";
			}

			if($("#onphotos-box-shadow-1").attr("checked")) { 
				$(".onphotopreview").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$(".onphotopreview").css({ "box-shadow":"none" });
			}


			if($("#onphotos-box-shadow-1").attr("checked")) { 
				$("#onphotos-bs1").show();
			} else { 
				$("#onphotos-bs1").hide();
			}

			if($("#onphotos-box-shadow-2").attr("checked")) { 
				$("#onphotos-bs2").show();
			} else { 
				$("#onphotos-bs2").hide();
			}

			if($("#onphotos-box-shadow-3").attr("checked")) { 
				$("#onphotos-bs3").show();
			} else { 
				$("#onphotos-bs3").hide();
			}

		/* THUMBNAIL LISTING  BOX SHADOW */
			if($("#thumblisting-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#thumblisting-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#thumblisting-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#thumblisting-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#thumblisting-bs1-0").val()+"px "+$("#thumblisting-bs1-1").val()+"px "+$("#thumblisting-bs1-2").val()+"px "+$("#thumblisting-bs1-3").val()+"px "+hex2rgba($("#thumblisting-bs1-color").val(),$("#thumblisting-bs1-5").val())+" "+bs16+"";
			}
			if($("#thumblisting-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#thumblisting-bs2-0").val()+"px "+$("#thumblisting-bs2-1").val()+"px "+$("#thumblisting-bs2-2").val()+"px "+$("#thumblisting-bs2-3").val()+"px  "+hex2rgba($("#thumblisting-bs2-color").val(),$("#thumblisting-bs2-5").val())+" "+bs26+"";
			}
			if($("#thumblisting-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#thumblisting-bs3-0").val()+"px "+$("#thumblisting-bs3-1").val()+"px "+$("#thumblisting-bs3-2").val()+"px "+$("#thumblisting-bs3-3").val()+"px  "+hex2rgba($("#thumblisting-bs3-color").val(),$("#thumblisting-bs3-5").val())+" "+bs36+"";
			}

			if($("#thumblisting-box-shadow-1").attr("checked")) { 
				$(".thumbnaillistingpreview").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$(".thumbnaillistingpreview").css({ "box-shadow":"none" });
			}


			if($("#thumblisting-box-shadow-1").attr("checked")) { 
				$("#thumblisting-bs1").show();
			} else { 
				$("#thumblisting-bs1").hide();
			}

			if($("#thumblisting-box-shadow-2").attr("checked")) { 
				$("#thumblisting-bs2").show();
			} else { 
				$("#thumblisting-bs2").hide();
			}

			if($("#thumblisting-box-shadow-3").attr("checked")) { 
				$("#thumblisting-bs3").show();
			} else { 
				$("#thumblisting-bs3").hide();
			}


		/* THUMBNAIL GALLERY BOX SHADOW */
			if($("#thumb_nails-bs1-6").attr("checked")) { 
				bs16 = "inset";
			} else { 
				bs16 = "";
			}
			if($("#thumb_nails-bs2-6").attr("checked")) { 
				bs26 = "inset";
			} else { 
				bs26 = "";
			}
			if($("#thumb_nails-bs3-6").attr("checked")) { 
				bs36 = "inset";
			} else { 
				bs36 = "";
			}

			if($("#thumb_nails-box-shadow-1").attr("checked")) { 
				boxes_box_shadow = ""+$("#thumb_nails-bs1-0").val()+"px "+$("#thumb_nails-bs1-1").val()+"px "+$("#thumb_nails-bs1-2").val()+"px "+$("#thumb_nails-bs1-3").val()+"px "+hex2rgba($("#thumb_nails-bs1-color").val(),$("#thumb_nails-bs1-5").val())+" "+bs16+"";
			}
			if($("#thumb_nails-box-shadow-2").attr("checked")) { 
				boxes_box_shadow += ", "+$("#thumb_nails-bs2-0").val()+"px "+$("#thumb_nails-bs2-1").val()+"px "+$("#thumb_nails-bs2-2").val()+"px "+$("#thumb_nails-bs2-3").val()+"px  "+hex2rgba($("#thumb_nails-bs2-color").val(),$("#thumb_nails-bs2-5").val())+" "+bs26+"";
			}
			if($("#thumb_nails-box-shadow-3").attr("checked")) { 
				boxes_box_shadow += ", "+$("#thumb_nails-bs3-0").val()+"px "+$("#thumb_nails-bs3-1").val()+"px "+$("#thumb_nails-bs3-2").val()+"px "+$("#thumb_nails-bs3-3").val()+"px  "+hex2rgba($("#thumb_nails-bs3-color").val(),$("#thumb_nails-bs3-5").val())+" "+bs36+"";
			}

			if($("#thumb_nails-box-shadow-1").attr("checked")) { 
				$(".thumb_nailspreview").css({ "box-shadow":boxes_box_shadow });
			} else { 
				$(".thumb_nailspreview").css({ "box-shadow":"none" });
			}


			if($("#thumb_nails-box-shadow-1").attr("checked")) { 
				$("#thumb_nails-bs1").show();
			} else { 
				$("#thumb_nails-bs1").hide();
			}

			if($("#thumb_nails-box-shadow-2").attr("checked")) { 
				$("#thumb_nails-bs2").show();
			} else { 
				$("#thumb_nails-bs2").hide();
			}

			if($("#thumb_nails-box-shadow-3").attr("checked")) { 
				$("#thumb_nails-bs3").show();
			} else { 
				$("#thumb_nails-bs3").hide();
			}



		$(".onphotopreview").css({
			"border-style":$("#on_photo_border_style").val(),
			"border-width":$("#on_photo_border_size").val()+"px",
			"border-color":"#"+$("#on_photo_border").val(),
			"border-radius":$("#on_photo_border_radius").val()+"px",
			"background":"#"+$("#on_photo_bg").val(),
			"float":"left",
			"position":"relative",
			"overflow":"hidden",
			"margin":$("#on_photo_margin").val()+"px",
			"padding":"0",
			"width":$("#on_photo_width").val()+"px",
			"height":$("#on_photo_height").val()+"px"
		});

		$(".onphotopreview-text").css({
			"position":"absolute",
			"bottom":"0",
			"left":(100 - Math.abs($("#on_photo_text_width").val())) / 2+"%",
			"background-color":hex2rgba($("#on_photo_text_bg").val(),$("#on_photo_text_opacity").val()),
			"width":$("#on_photo_text_width").val()+"%",
			"text-align":"left",
			"margin":"auto",
		});

		$(".onphotopreview-headline").css({
			"padding":"8px",
			"color":"#"+$("#on_photo_title").val(),
			"font-size":$("#on_photo_title_size").val()+"px",
			"font-family":$("#css_title_font_family_main").val()

		});
		$(".onphotopreview-previewtext").css({
			"color":"#"+$("#on_photo_text").val(),
			"padding":"8px"
		});


		$("#captionarea").css({
			"background-color":hex2rgba($("#caption_background").val(),$("#caption_opacity").val()),
			"color":"#"+$("#caption_text").val(),
			"text-align":$("#caption_align").val(),
			"padding":$("#caption_padding").val()+"px"
		});


	/* THUMBNAIL LISTING */

		$(".thumbnaillistingpreview").css({
			"border":""+$("#thumb_listing_border_size").val()+"px "+$("#thumb_listing_border_style").val()+" #"+$("#thumb_listing_border").val(),
			"border-radius":$("#thumb_listing_border_radius").val()+"px",
				"background-color":hex2rgba($("#thumb_listing_bg").val(),$("#thumb_listing_opacity").val()),
			"float":"left",
			"position":"relative",
			"overflow":"hidden",
			"margin":$("#thumb_listing_margin").val()+"px",
			"padding":$("#thumb_listing_padding").val()+"px",
			"width":$("#thumb_listing_width").val()+"px",
			"height":$("#thumb_listing_height").val()+"px"
		});

		$(".thumbnaillistingpreview-image").css({
			"width":$("#thumb_listing_thumb_width").val()+"px",
			"height":$("#thumb_listing_thumb_height").val()+"px",
			"line-height":$("#thumb_listing_thumb_height").val()+"px",
			"text-align":"center",
			"margin":"auto"
		});

		$(".tlthumbnail").css({
			"position":"relative",
			"text-align":"center",
			"vertical-align":"bottom",
			"border":"solid 1px #"+$("#thumb_listing_thumb_border").val()
		});
		$(".ls").css({
			"max-width":$("#thumb_listing_thumb_width").val()+"px",
			"height":"auto"
		});
		$(".pt").css({
			"max-height":$("#thumb_listing_thumb_height").val()+"px",
			"width":"auto"
		});



		$(".thumbnaillistingpreview-text").css({
			"text-align":"center",
			"margin":"auto",
			"padding-top":$("#thumb_listing_padding").val()+"px",
			"padding-bottom":$("#thumb_listing_padding").val()+"px"

		});

		$(".thumbnaillistingpreview-headline").css({
			"padding":"4px",
			"color":"#"+$("#thumb_listing_title").val(),
			"font-size":$("#thumb_listing_title_size").val()+"px",
			"font-family":$("#css_title_font_family_main").val()

		});
		$(".thumbnaillistingpreview-other").css({
			"color":"#"+$("#thumb_listing_text").val(),
			"padding":"4px"
		});


	/* THUMBNAIL GALLERY */

		$(".thumb_nailspreview").css({
			"border":""+$("#thumb_nails_border_size").val()+"px "+$("#thumb_nails_border_style").val()+" #"+$("#thumb_nails_border").val(),
			"border-radius":$("#thumb_nails_border_radius").val()+"px",
				"background-color":hex2rgba($("#thumb_nails_bg").val(),$("#thumb_nails_opacity").val()),
			"float":"left",
			"position":"relative",
			"overflow":"hidden",
			"margin":$("#thumb_nails_margin").val()+"px",
			"padding":$("#thumb_nails_padding").val()+"px",
			"width":$("#thumb_nails_width").val()+"px",
			"height":$("#thumb_nails_height").val()+"px"
		});

		$(".thumb_nailspreview-image").css({
			"width":$("#thumb_nails_thumb_width").val()+"px",
			"height":$("#thumb_nails_thumb_height").val()+"px",
			"line-height":$("#thumb_nails_thumb_height").val()+"px",
			"text-align":"center",
			"margin":"auto"
		});

		$(".tnthumbnail").css({
			"position":"relative",
			"text-align":"center",
			"vertical-align":"bottom",
			"border":"solid 1px #"+$("#thumb_nails_thumb_border").val()
		});
		$(".tnls").css({
			"max-width":$("#thumb_nails_thumb_width").val()+"px",
			"height":"auto"
		});
		$(".tnpt").css({
			"max-height":$("#thumb_nails_thumb_height").val()+"px",
			"width":"auto"
		});



		$(".thumb_nailspreview-text").css({
			"text-align":"center",
			"margin":"auto",
			"padding-top":$("#thumb_nails_padding").val()+"px",
			"padding-bottom":$("#thumb_nails_padding").val()+"px"

		});

		$(".thumb_nailspreview-headline").css({
			"padding":"4px",
			"color":"#"+$("#thumb_nails_title").val(),
			"font-size":$("#thumb_nails_title_size").val()+"px",
			"font-family":$("#css_title_font_family_main").val()

		});
		$(".thumb_nailspreview-other").css({
			"color":"#"+$("#thumb_nails_text").val(),
			"padding":"4px"
		});




// $("#log").html(hex2rgba($("#on_photo_text_bg").val(),$("#on_photo_text_opacity").val())+" left: "+(100 - Math.abs($("#on_photo_text_width").val())) / 2);



if($("#disable_side").attr("checked")) { 
	$("#sidemenu").hide();
	$("#sideMenuOptions").hide();
	$("#pagecontent").css({
		"width":"auto",
		"float":"none"
	});

} else { 
	$("#sideMenuOptions").show();
	$("#sidemenu").show();
	$("#sidemenu").css({
		"width":$("#side_menu_width").val()+"%",
		"float":$("#side_menu_align").val(),
	});
	$("#pagecontent").css({
		"width":(100 - $("#side_menu_width").val())+"%",
		"float":$("#side_menu_align").val(),
	});
	if($("#side_menu_align").val() == "left") { 
		$("#sidemenuinner").css({
			"margin":"0px "+$("#inside_padding").val()+"px  0px 0px"
		});
		$("#sidemenuinner2").css({
			"margin":"0px "+$("#inside_padding").val()+"px  0px 0px"
		});

	} else { 
		$("#sidemenuinner").css({
			"margin":"0px 0px 0px "+$("#inside_padding").val()+"px"
		});
		$("#sidemenuinner2").css({
			"margin":"0px 0px 0px "+$("#inside_padding").val()+"px"
		});

	}

	if($("#side_menu_transparent").attr("checked")) { 
	$("#sidemenuinner").css({
		"background":"transparent",
		"border":"none",
		"border-radius":"none",
		"color":"#"+$("#side_main_link").val(),
		"padding":$("#side_main_bg_padding").val()+"px"
	});
	$("#side_menu_options").hide();

	} else { 
	$("#sidemenuinner").css({
		"background":"#"+$("#side_main_bg").val(),
		"border":""+$("#side_main_border_size").val()+"px "+$("#side_main_border_style").val()+" #"+$("#side_main_border_color").val(),
		"border-radius":$("#side_main_border_radius").val()+"px",
		"color":"#"+$("#side_main_link").val(),
		"padding":$("#side_main_bg_padding").val()+"px"
	});
	$("#side_menu_options").show();

	}

	$("#sidemenuinner2").css({
		"color":"#"+$("#side_main_font").val(),
		"font-size":$("#fontSize").val()+"px"
	});

	$(".sidemenuitem").css({ 
		"border-bottom":""+$("#side_menu_link_border_size").val()+"px "+$("#side_menu_link_border_style").val()+" #"+$("#side_menu_link_border_b").val(),
		"padding": $("#side_menu_line_height").val()+"px "+$("#side_main_padding").val()+"px "+$("#side_menu_line_height").val()+"px "+$("#side_main_padding").val()+"px ",
		"font-size":$("#side_menu_font_size").val()+"px"

	});
}

$("#sidemenulabel").css({"font-size":$("#side_menu_label_size").val()+"px", "color":"#"+$("#side_main_font").val() });

$("#scrollthumbscontainer").css({
	"background":"#"+$("#scroller_bg").val(),
	"border":"solid 1px #"+$("#scroller_border").val(),
	"color":"#"+$("#scroller_text").val(),
	"background":"#"+$("#scroller_bg").val()
});

$("#sc").css({
	"background":"#"+$("#scroller_handle_bg").val()
});

$("#scroll_handle").css({
	"background":"#"+$("#scroller_handle").val(),
	"border":"solid 1px #"+$("#scroller_handle_border").val(),
});





/*



/*
		if(document.getElementById('text_shadow_on').checked == "1") { 
			document.getElementById('main_font_color').style.textShadow = document.getElementById('text_shadow_h').value+'px '+ document.getElementById('text_shadow_v').value+'px '+document.getElementById('text_shadow_b').value+'px  #'+document.getElementById('text_shadow_c').value;
			document.getElementById('textts').style.display = 'block';

		} else { 
			document.getElementById('main_font_color').style.textShadow = 'none';
			document.getElementById('textts').style.display = 'none';
		}
*/
		/*
		if(document.getElementById('link_text_shadow_on').checked == "1") { 
			document.getElementById('linkContainer').style.textShadow = document.getElementById('link_text_shadow_h').value+'px '+ document.getElementById('link_text_shadow_v').value+'px '+document.getElementById('link_text_shadow_b').value+'px  #'+document.getElementById('link_text_shadow_c').value;
			document.getElementById('linkts').style.display = 'block';

		} else { 
			document.getElementById('linkContainer').style.textShadow = 'none';
			document.getElementById('linkts').style.display = 'none';
		}
*/



/*
		if(document.getElementById('footer_text_shadow_on').checked == "1") { 
			document.getElementById('footer_text').style.textShadow = document.getElementById('footer_text_shadow_h').value+'px '+ document.getElementById('footer_text_shadow_v').value+'px '+document.getElementById('footer_text_shadow_b').value+'px  #'+document.getElementById('footer_text_shadow_c').value;
			document.getElementById('footerts').style.display = 'block';

		} else { 
			document.getElementById('footer_text').style.textShadow = 'none';
			document.getElementById('footerts').style.display = 'none';
		}

*/

	}



	function changeCaption(val) { 
		if(document.getElementById('caption_placement').value == "1") { 
			document.getElementById('photo_caption').style.bottom = '';
			document.getElementById('photo_caption').style.top = (document.getElementById('caption_top').value)+'px';
			document.getElementById('caption_placetop').style.display = 'block';
			document.getElementById('caption_placebottom').style.display = 'none';

		} else {
			document.getElementById('photo_caption').style.top = '';
			document.getElementById('photo_caption').style.bottom = (document.getElementById('caption_bottom').value)+'px';
			document.getElementById('caption_placetop').style.display = 'none';
			document.getElementById('caption_placebottom').style.display = 'block';
		}
		document.getElementById('photo_caption').style.background = '#'+document.getElementById('caption_background').value;
		document.getElementById('photo_caption').style.width = document.getElementById('caption_width').value+'%';
		document.getElementById('photo_caption').style.left = document.getElementById('caption_left').value+'%';
		document.getElementById('photo_caption').style.borderRadius = document.getElementById('caption_rounded').value+'px';
		document.getElementById('photo_caption').style.borderColor = '#'+document.getElementById('caption_border').value;
		document.getElementById('photo_caption').style.opacity = document.getElementById('caption_opacity').value;
		document.getElementById('photo_caption').style.color = '#'+document.getElementById('caption_text').value;

		document.getElementById('photo_caption').style.boxShadow = '0px 1px '+ document.getElementById('caption_shading_size').value+'px #'+document.getElementById('caption_shading').value;

	if((parseInt(document.getElementById('caption_width').value) + parseInt(document.getElementById('caption_left').value)) > 95) { 
		alert("WARNING!  Percentage From Left Of Screen + Width Percentage must equal 95 or less. \r\nPlease adjust the values for those options.");
		document.getElementById('wp').className="errorMessage";
		document.getElementById('pls').className="errorMessage";
		
	} else {
		document.getElementById('wp').className="";
		document.getElementById('pls').className="";
	}



}





function swapSections(id) { 
	if(document.getElementById(id).style.display == "none") { 
		document.getElementById(id).style.display = "block";
		document.getElementById(id+"+").innerHTML = "- ";

	} else { 
		document.getElementById(id).style.display = "none";
		document.getElementById(id+"+").innerHTML = "+ ";
	}
}
