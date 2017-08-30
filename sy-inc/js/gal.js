function loadsytistphoto(pickey)  { 
	// $("#log").show().append("key: "+pickey+" full screen: "+$("#slideshow").attr("fullscreen")+" disable: "+$("#slideshow").attr("disablenav")+" ");
	if($("#photo-"+pickey).length) { 
		 if($("#slideshow").attr("fullscreen") == 1) { 
			navgallery(pickey,'0');
		 } else { 
			$("#slideshow").attr("curphoto", pickey);
			 clickThumb(pickey);
		 }
	} else { 
		getsytistphoto(pickey, function() { 
		
			 if($("#slideshow").attr("fullscreen") == 1) { 
				// $("#log").show().append("Nav ");
				navgallery(pickey,'0');
			 } else { 
				photo = document.location.hash;
				photo = photo.replace("#","");
				p = photo.split("=");
				if(p[0] == "photo" || p[0] == "gti" && p[1] !== "thumbs") { 
					$("#slideshow").attr("curphoto", pickey);
				//	$("#log").show().append("HERE, hash: "+document.location.hash+" p[0] =  "+p[0]);

					 clickThumb(pickey);
				}
			 }
			 enableenlargephoto();
		});
	}
}
function docallback() { 

}

function getsytistphoto(pickey,callback) { 
	$.get(tempfolder+"/sy-inc/store/photo.php?pic_key="+pickey+"&date_id="+$("#vinfo").attr("did")+"&sub_id="+$("#vinfo").attr("sub_id")+"&view="+$("#vinfo").attr("view")+"&kid="+$("#vinfo").attr("kid")+"&keyWord="+$("#vinfo").attr("keyWord")+"&passcode="+$("#vinfo").attr("passcode")+"&view="+$("#vinfo").attr("view")+"&from_time="+$("#vinfo").attr("from_time")+"&search_date="+$("#vinfo").attr("search_date")+"&search_length="+$("#vinfo").attr("search_length"), function(data) {
		if(data == "notfound") { 
			// alert("NOT FOUND");
		} else { 
			// alert(data);
			$("#slideshow").append(data);
			 enableenlargephoto();
			 if(callback) {
				callback(docallback);
			 }
			if(norightclick == '1') { 
				disablerightclick();
			}

			return true;
		}
	});
}


function clickThumb(pos){

	arrowNavSS();
	$("#photo-"+pos).attr("src", $("#photo-"+pos).attr("thissrc"));
	$("#photo-"+pos).removeClass("photo").addClass("photofull");
	stillload  = setTimeout("slideshowloading()",500);
	$("#slideshow").attr("curphoto", pos);
	fullScreen();
}



function closeFullScreenPhoto() { 
	window.parent.location="#photo=thumbs";
}
function clickthumbnail(key) { 
	window.parent.location="#photo="+key;
}

function navthumbnails(n,stop) { 
	 if(stop == "1") { 
		 stopSlideshow();
	 }

	if(n == "next") { 
		next = $("#photo-"+$("#slideshow").attr("curphoto")).attr("data-next");
	}
	if(n == "prev") { 
		next = $("#photo-"+$("#slideshow").attr("curphoto")).attr("data-previous");
	}

	window.parent.location="#photo="+next;
}

function navgallery(pos,stop) {
	if(sytiststore == 1) { 
		removeFilterPhoto();
	}

	 if($("#slideshow").attr("disablenav") !== "1") { ;

		curphoto = $("#slideshow").attr("curphoto");
		// $("#log").show().append(":"+curphoto+" - ");

		if(curphoto != pos) { 
			stillload  = setTimeout("slideshowloading()",500);
					 $("#slideshow").attr("disablenav","1");

/*			if(pos  >= totalphotos) { 
				 $("#ssNextPhoto").hide();
			 } else { 
				 $("#ssNextPhoto").show();
			 }
			 if(pos  <= 1) { 
				 $("#ssPrevPhoto").hide();
			 } else { 
				 $("#ssPrevPhoto").show();
			 }
*/
		// alert(n+" "+curphoto+" -  photo-"+pos+" width"+$("#photo-"+pos).attr("src"));

			if($("#slideshow").attr("fullscreen") == 1) { 
				$("#photo-"+pos).removeClass("photo").addClass("photofull");
			} else { 
				$("#photo-"+pos).removeClass("photofull").addClass("photo");
			}

			// $("#th-"+curphoto).removeClass("thumbon");
			// $("#th-"+pos).addClass("thumbon");


			$("#photo-"+pos).imagesLoaded(function() {
				sizePhoto(pos);
				slideshowdoneloading();
				selectGSbackground('photofull',false);
				if(sytiststore == 1) { 
					if($("#photo-"+pos).attr("fav") == "1") { 
						$(".photo-fav").removeClass("icon-heart-empty").addClass("icon-heart");
					} else { 
						$(".photo-fav").removeClass("icon-heart").addClass("icon-heart-empty");
					}	
					if($("#photo-"+pos).attr("compare") == "1") { 
						$(".checked").show();
					} else { 
						$(".checked").hide();
					}	

					if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pl")) > 0 && $("#vinfo").attr("prodplace")=="0") { 
						$("#buy-photo").show();
					} else { 
						$("#buy-photo").hide();
					}
					if(Math.abs($("#photo-"+pos).attr("pkgs")) > 0) { 
						$("#buy-packages").show();
					} else { 
						$("#buy-packages").hide();
					}

					if(Math.abs($("#photo-"+pos).attr("share")) > 0) { 
						$("#shareoptions").show();
					} else { 
						$("#shareoptions").hide();
					}

					if(Math.abs($("#photo-"+pos).attr("fd")) > 0) { 
						$("#free-photo").show();
					} else { 
						$("#free-photo").hide();
					}

					if(Math.abs($("#photo-"+pos).attr("pic_hide")) > 0) { 
						$("#hidden-full-photo").show();
					} else { 
						$("#hidden-full-photo").hide();
					}
					$("#phototopcount").html(""+$("#photo-"+pos).attr("pos")+"/"+totalphotos+"");
					$("#photofilename").html($("#photo-"+pos).attr("org"));
					$("#photopagelink").html('<a href="'+$("#photo-"+pos).attr("pagelink")+'">'+$("#photo-"+pos).attr("pagetitle")+'</a>');

					if($("#photo-"+pos).attr("photofav") == "1") { 
						$("#photographerfavoritefullstar").show();
					} else { 
						$("#photographerfavoritefullstar").hide();
					}

				}


				if (typeof stillload!=="undefined") {
					clearInterval(stillload);
				 }


				$("#photocount").html(""+$("#photo-"+pos).attr("pos")+"/"+totalphotos+"");
				if($("#slideshow").attr("sson") == "1"){ 
					$("#photo-"+pos+"-container").fadeIn(sstransition, function() { 
						if($("#photo-"+pos).attr("data-next") !== "") { 
							getsytistphoto($("#photo-"+pos).attr("data-next"));
						}
						 $("#slideshow").attr("disablenav","0");
						if(thumbnails == 1) {
							 // $('#th-'+pos).scrollView();
						}
						$("#slideshow").attr("disablenav","0");
						if($("#slideshow").attr("fullscreen") == 1) { 
							if($("#photo-"+pos).attr("ww") > $("#photo-"+pos).width()) {  
								$("#photo-"+pos).addClass("zoomCur");
							} else { 
								$("#photo-"+pos).removeClass("zoomCur");
							}
							if($("#vinfo").attr("proofing") == "1") { 
								$.get(tempfolder+"/sy-inc/store/store_photos_menu.php?date_id="+$("#photo-"+pos).attr("did")+"&sub_id="+$("#vinfo").attr("sub_id")+"&view="+$("#vinfo").attr("view")+"&need-login="+$("#vinfo").attr("need-login")+"&pic="+$("#photo-"+pos).attr("pkey"), function(data) {
									$("#ssheader").html(data);
									$("#phototopcount").html(""+$("#photo-"+pos).attr("pos")+"/"+totalphotos+"");
									$("#photofilename").html($("#photo-"+pos).attr("org"));
									$("#photopagelink").html('<a href="'+$("#photo-"+pos).attr("pagelink")+'">'+$("#photo-"+pos).attr("pagetitle")+'</a>');

							});
							}

							if($("#vinfo").attr("viewing-store-photo-prod") == "1") { 
								storeproductnexttophoto(pos);
							} else if($("#vinfo").attr("view_package") == "1") { 
								packagenexttophoto(pos);
							} else { 
								//	alert("product next to photo");

								productsnexttophoto(pos);
							}
						}
					});
					$("#photo-"+curphoto+"-container").fadeOut(sstransition);
				} else { 
					$("#photo-"+pos+"-container").fadeIn(10, function() { 
					getSSCaption("photo-"+pos);
						if($("#photo-"+pos).attr("data-next") !== "") { 
							getsytistphoto($("#photo-"+pos).attr("data-next"));
						}
						if(thumbnails == 1) {
							 // $('#th-'+pos).scrollView();
						}
						$("#slideshow").attr("disablenav","0");
						if($("#slideshow").attr("fullscreen") == 1) { 
					

							if($("#photo-"+pos).attr("ww") > $("#photo-"+pos).width()) {  
								$("#photo-"+pos).addClass("zoomCur");
							} else { 
								$("#photo-"+pos).removeClass("zoomCur");
							}
							if($("#vinfo").attr("proofing") == "1") { 
								$.get(tempfolder+"/sy-inc/store/store_photos_menu.php?date_id="+$("#photo-"+pos).attr("did")+"&sub_id="+$("#vinfo").attr("sub_id")+"&view="+$("#vinfo").attr("view")+"&need-login="+$("#vinfo").attr("need-login")+"&pic="+$("#photo-"+pos).attr("pkey"), function(data) {
									$("#ssheader").html(data);
									$("#phototopcount").html(""+$("#photo-"+pos).attr("pos")+"/"+totalphotos+"");
									$("#photofilename").html($("#photo-"+pos).attr("org"));
									$("#photopagelink").html('<a href="'+$("#photo-"+pos).attr("pagelink")+'">'+$("#photo-"+pos).attr("pagetitle")+'</a>');
								});
							}

							if($("#slideshow").attr("fullscreen") == 1)  {
								if($("#vinfo").attr("viewing-store-photo-prod") == "1") { 
									storeproductnexttophoto(pos);
								} else if($("#vinfo").attr("view_package") == "1") { 
									packagenexttophoto(pos);
								} else { 

									productsnexttophoto(pos);
								}
							}
						}
					});
					$("#photo-"+curphoto+"-container").fadeOut(10, function() { 
						$("#photo-"+curphoto+"-container").remove();
					});
				}
				if($("#slideshow").attr("sson") == "1"){ 
					SSslideshowtimer = window.setTimeout("navSlides('next')",ssspeed);
				}


				if($("#photo-"+pos).attr("data-next") == "") { 
					 $("#ssNextPhoto").hide();
				 } else { 
					 $("#ssNextPhoto").show();
				 }
				if($("#photo-"+pos).attr("data-previous") == "") { 
					 $("#ssPrevPhoto").hide();
				 } else { 
					 $("#ssPrevPhoto").show();
				 }





				$("#photo-"+(pos + 1)).attr("src", $("#photo-"+(pos + 1)).attr("thissrc"));
				if(pos > 0) {
					// alert(src);
					src = $("#photo-"+(pos - 1)).attr("src");
					if (typeof src === "undefined") {
		//				alert($("#photo-"+(pos)).attr("thissrc"));
						$("#photo-"+(pos - 1)).attr("src", $("#photo-"+(pos - 1)).attr("thissrc"));
					}
					}
				$("#slideshow").attr("curphoto",pos);
				if($("#vinfo").attr("has_package_one") == 1 && $("#vinfo").attr("has_package") == "1") { 
					// showPackageOne();
				}

				if(thumbnails !== 1) { 
					 sizeContainer();
				}

			});
		}
	 }
}


function sizeGalPhoto(pickey) { 
	closeenlargephoto();
	if($("#slideshow").attr("fullscreen") == 1) { 
	var photospacing = 32;
	} else { 
	var photospacing = 0;
	}

	if($("#slideshow").attr("fullscreen") == 1) { 
		add_margin = add_margin_full;
		headerheight = $("#ssheader").height();
		if($("#slideshow").attr("scrollthumbs") == "1" && thumbnails == 0) { 
			sth =  $("#thumbscroller").height() + add_margin_full + photospacing;
		} else { 
			sth = 0;
		}

	} else {
		add_margin = add_margin_page;
		sth = 0;
		headerheight = 0;
	}
	add_margin = add_margin + photospacing;

	if($("#slideshow").attr("fullscreen") !== "1") { 
		sizecontainer = 1;
	} else { 
		sizecontainer = 0;
	}
	if($("#slideshow").attr("fullscreen") == 0) { 
		if(first_landscape_width > 0 && use_first_lanscape_height == 1) { 
			sizecontainer = 0;
			if((first_landscape_width +  Math.abs(add_margin)) > $("#slideshow").width()) { 
				wwp = $("#slideshow").width() / (first_landscape_width +  Math.abs(add_margin));
				wh = (first_landscape_height * wwp) +  Math.abs(add_margin);
			} else { 
				wh = first_landscape_height;

			}
			$("#slideshow").css("height",wh + (add_margin / 2) +"px");
		} else { 
			wh = $(window).height();
		}
	} else { 
		wh = $(window).height();
	}


	$("#photo-"+pickey).css({"left":"0px", "top":"0px", "margin":"0 0 0 0"});
	dim = $("#photo-"+pickey).getHiddenDimensions();
	//alert(dim.width);
	 ww = Math.abs($("#photo-"+pickey).attr("ww"));
	 hh = Math.abs($("#photo-"+pickey).attr("hh"));
	 sth = Math.abs(sth);

	 if(ww <=1) { 
		ww = dim.width;
		$("#photo-"+pickey).attr("ww",ww)
	 }
	 if(hh <=1) { 
		hh = dim.height;
		$("#photo-"+pickey).attr("hh",hh)
	}


	if((ww + add_margin) > $("#photo-"+pickey+"-container").parent().width() ||  (hh + (add_margin*2) + sth) + 20  > (wh - sth)) {
		nwn = Math.abs(ww) + Math.abs(add_margin);
		wp = $("#photo-"+pickey+"-container").parent().width() / nwn;
		hp = wh / (Math.abs(hh) + sth + headerheight + add_margin);


		
		if(hp > wp) { 


		$("#photo-"+pickey).css({"width":($("#photo-"+pickey+"-container").parent().width() - add_margin)+"px",
				"height":hh * wp
			});
			nw = ($("#photo-"+pickey+"-container").parent().width() - add_margin);
			nwp = nw / Math.abs(ww);
			nh = Math.abs(hh) * nwp;
		} else { 
//		$("#log").html( "header height: "+headerheight+" dim width: "+ww +" dim height: "+hh+" add margin: "+add_margin+" photo spacing" + photospacing+" > "+$("#photo-"+pickey+"-container").parent().width()+" || "+(hh + add_margin + sth +  photospacing)+" > "+wh+" sth: "+sth+" ww: "+ww+" add_margin: "+add_margin+"  photospacing: "+photospacing+" hh: "+hh);


			nw = nwn * hp;
			nh = Math.abs(hh) * hp;
			left = ($("#photo-"+pickey+"-container").parent().width() -(nw)) / 2;
			if($("#ssheader").css("display") == "block") { 
				mh = $("#ssheader").height();
			} else { 
				mh = 0;
			}
			$("#photo-"+pickey).css({"height":(wh - add_margin - mh - sth)+"px",
				"width":"auto",
				"left":left+"px"
			});
	//		$("#log").show();
		//	$("#log").html(wh+" - "+add_margin+" - "+mh+" - "+sth+" - "+photospacing);

			nh = wh - add_margin - mh - sth;
			nhp = nh / Math.abs(hh);
			nw = Math.abs(ww) * nhp;
		}

	} else { 

	//	alert($("#photo-"+pickey+"-container").parent().width()+" - "+ww);
		if($("#photo-"+pickey).width() < ww) { 
			$("#photo-"+pickey).css({"width":ww+"px","height":"auto"});
		}
		left = ($("#photo-"+pickey+"-container").parent().width() - Math.abs(ww)) / 2;
		$("#photo-"+pickey).css({"left":left+"px"});
		nw = ww;
		nh = hh;

	}

	if($("#slideshow").attr("fullscreen") == "1") { 

		if(nh  < ($("#slideshowcontainer").height() + sth + add_margin)) { 
			mt = ($("#slideshowcontainer").height() - headerheight - nh);
			if(mt < 0) { 
				mt = 0;
			}
			new_top = headerheight + (mt / 2) - (sth / 2) ;
			$("#photo-"+pickey).css({"top":new_top +"px"});
		} else { 
			$("#photo-"+pickey).css({"top":"0px"});
		}
		left = ($("#photo-"+pickey+"-container").parent().width() - Math.abs(nw)) / 2 - 4;

	//	$("#log").show();
	//	$("#log").html($("#photo-"+pickey+"-container").parent().width()+" - "+Math.abs(nw)+" - "+add_margin);

		$("#photo-"+pickey).css({"left":left+"px"});

	}
}



function fullScreen() { 
	$("#photo-"+$("#slideshow").attr("curphoto")).removeClass("photo").addClass("photofull");
	$("#photo-"+$("#slideshow").attr("curphoto")+"-container").hide();
	$("#slideshow").attr("fullscreen", "1");
	$(".ssnavigation").hide();
	$(".photocaptioncontainer").hide();
	$("#ssbackground").fadeIn(200, function() { 

		if($("#vinfo").attr("viewing-store-photo-prod") == "1") { 
			storeproductnexttophoto($("#slideshow").attr("curphoto"));
		} else if($("#vinfo").attr("view_package") == "1") { 
			packagenexttophoto($("#slideshow").attr("curphoto"));
		} else { 
			productsnexttophoto($("#slideshow").attr("curphoto"));
		}
	if(sytiststore == 1 && $("#vinfo").attr("plid") > 0) { 
		$("#ssheader").fadeIn(100);
		$.get(tempfolder+"/sy-inc/store/store_photos_menu.php?date_id="+$("#photo-"+$("#slideshow").attr("curphoto")).attr("did")+"&sub_id="+$("#vinfo").attr("sub_id")+"&view="+$("#vinfo").attr("view")+"&need-login="+$("#vinfo").attr("need-login")+"&pic="+$("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey"), function(data) {
			$("#ssheader").html(data);
			if(mobile == "1") { 
				checkforpackages();
				checkforpackagesone();
			}
			adjustsite();
			$("#ssClose").css({"top":$("#ssheader").height()+"px"});
			pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
			if($("#photo-"+$("#slideshow").attr("curphoto")).attr("fav") == "1") { 
				$(".photo-fav").removeClass("icon-heart-empty").addClass("icon-heart");
			} else { 
				$(".photo-fav").removeClass("icon-heart").addClass("icon-heart-empty");
			}

			if($("#photo-"+$("#slideshow").attr("curphoto")).attr("compare") == "1") { 
				$(".checked").show();
			} else { 
				$(".checked").hide();
			}

			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pl")) > 0 && $("#vinfo").attr("prodplace")=="0") { 
				$("#buy-photo").show();
			} else { 
				$("#buy-photo").hide();
			}

			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pkgs")) > 0) { 
				$("#buy-packages").show();
			} else { 
				$("#buy-packages").hide();
			}

			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("share")) > 0) { 
				$("#shareoptions").show();
			} else { 
				$("#shareoptions").hide();
			}
			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("fd")) > 0) { 
				$("#free-photo").show();
			} else { 
				$("#free-photo").hide();
			}
			if(Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).attr("pic_hide")) > 0) { 
				$("#hidden-full-photo").show();
			} else { 
				$("#hidden-full-photo").hide();
			}
			$("#phototopcount").html(""+$("#photo-"+$("#slideshow").attr("curphoto")).attr("pos")+"/"+totalphotos+"");
			$("#photofilename").html($("#photo-"+$("#slideshow").attr("curphoto")).attr("org"));
			$("#photopagelink").html('<a href="'+$("#photo-"+$("#slideshow").attr("curphoto")).attr("pagelink")+'">'+$("#photo-"+$("#slideshow").attr("curphoto")).attr("pagetitle")+'</a>');
			if($("#photo-"+$("#slideshow").attr("curphoto")).attr("photofav") == "1") { 
				$("#photographerfavoritefullstar").show();
			} else { 
				$("#photographerfavoritefullstar").hide();
			}




		});
	}
	$("#fullscreen").hide();
		$("#shopmenucontainer").hide();


		$("#ssholder").css({"width":$("#slideshowcontainer").width()+"px","height":$("#slideshowcontainer").height()+"px"});
		$("#ssholder").show();

		$("#slideshowcontainer").addClass("sscontainerfull");

		$("#ssNextPhoto").addClass("the-icons-fullscreen");
		$("#ssPrevPhoto").addClass("the-icons-fullscreen");
		$("#ssClose").addClass("the-icons-fullscreen");
		$("#ssPlay").addClass("the-icons-fullscreen");
		$("#ssPause").addClass("the-icons-fullscreen");

		setTimeout(function(){

			$("#photo-"+$("#slideshow").attr("curphoto")).imagesLoaded(function() {
				// $("#log").show().append("image loaded: "+$("#slideshow").attr("curphoto")+" ");
			sizePhoto($("#slideshow").attr("curphoto"));
			selectGSbackground('photofull',false);
			slideshowdoneloading();
			if (typeof stillload!=="undefined") {
				clearInterval(stillload);
			 }

			$("#photo-"+$("#slideshow").attr("curphoto")+"-container").fadeIn(200, function() { 
			//	fixbackground();
				if($("#photo-"+$("#slideshow").attr("curphoto")).attr("data-next") !== "") { 
					getsytistphoto($("#photo-"+$("#slideshow").attr("curphoto")).attr("data-next"));
				}
				if($("#photo-"+$("#slideshow").attr("curphoto")).width() < 100) { sizePhoto($("#slideshow").attr("curphoto"));  } 

		// $("#log").html($("#photo-"+pos+"-container").parent().width()+" - "+Math.abs(nw)+" - "+add_margin);

				if($("#photo-"+$("#slideshow").attr("curphoto")).attr("ww") > $("#photo-"+$("#slideshow").attr("curphoto")).width()) {  
					$("#photo-"+$("#slideshow").attr("curphoto")).addClass("zoomCur");
				} else { 
					$("#photo-"+$("#slideshow").attr("curphoto")).removeClass("zoomCur");
				}

				placeNav($("#slideshow").attr("curphoto"));
				getSSCaption("photo-"+$("#slideshow").attr("curphoto"));
				$(".ssnavigation").fadeIn(100);
				headerheight = $("#ssheader").height();
				$("#ssClose").css({"top":headerheight+"px"});

				$("#ssClose").fadeIn(100);

				if(thumbnails == 1) { 
					placeNav($("#slideshow").attr("curphoto"));
					if($("#slideshow").attr("sson") == "1") { 
						$("#ssPlay").hide();
						$("#ssPause").show();
					} else { 
						$("#ssPlay").show();
						$("#ssPause").hide();
					}
					if($("body").width() <= 800) { 
						$("#controls").hide();
					} else { 
						$("#controls").children().hide();
					}
					pos = Math.abs($("#slideshow").attr("curphoto")) + 1;

					$("#photo-"+pos).attr("src", $("#photo-"+pos).attr("thissrc"));

					 $("#controls").mouseover(function(){
					 $(this).children().addClass("controlsbg");
					 $(this).children().show();
					}).mouseout(function(){ 
					 $(this).children().removeClass("controlsbg");
					 $(this).children().hide();});
				
				}

				if(thumbnails == 0) { 
					fullScreenThumbScroller();
				}

				if($("#photo-"+$("#slideshow").attr("curphoto")).attr("data-next") == "") { 
					 $("#ssNextPhoto").hide();
				 } else { 
					 $("#ssNextPhoto").show();
				 }
				if($("#photo-"+$("#slideshow").attr("curphoto")).attr("data-previous") == "") { 
					 $("#ssPrevPhoto").hide();
				 } else { 
					 $("#ssPrevPhoto").show();
				 }


				$(".photocaptioncontainer").fadeIn(200);
			});
			});
		},500)

	});
}



function closeFullScreen() { 
//	$("#thumbscroller").css("visibility", "hidden");
	if(sytiststore == 1) { 
		removeFilterPhoto();
		closepackagetab();
		closepackagetabone();
	}
	$("#vinfo").attr("view-photo-fixed","0");

	$("#vinfo").attr("viewing_prods", "0");

	$("#photoproductsnexttophoto").hide();
	$("#photoproductsnexttophoto .inner").html("");
	$("#photoproductsnexttophotobg").hide();

	closeenlargephoto();
	$("#shopmenucontainer").show();
	$("#photo-"+$("#slideshow").attr("curphoto")).removeClass("photofull").addClass("photo");
	$("#ssbackground").fadeOut(200, function() { 	
		if($("#page-wrapper").css("position") == "fixed") { 
			unfixbackground();
		}
		 $('#th-'+$("#slideshow").attr("curphoto")).scrollView();

	}); 
	$("#ssheader").fadeOut(200);

	stopSlideshow();

	$("#photo-"+$("#slideshow").attr("curphoto")+"-container").hide();
	$("#slideshow").attr("fullscreen", "0");
	$(".ssnavigation").hide();
	$(".photocaptioncontainer").hide();
	$("#fullscreen").show();
	$("#ssClose").hide();
	$("#ssholder").hide();
	$("#ssNextPhoto").removeClass("the-icons-fullscreen");
	$("#ssPrevPhoto").removeClass("the-icons-fullscreen");
	$("#ssClose").removeClass("the-icons-fullscreen");
	$("#ssPlay").removeClass("the-icons-fullscreen");
	$("#ssPause").removeClass("the-icons-fullscreen");

	$("#slideshowcontainer").removeClass("sscontainerfull");
	if(scrollthumbnails == 1) { 
		closeFullScreenThumbScroller();
	}
	if(thumbnails !== 1) { 
		setTimeout(function(){

			sizePhoto($("#slideshow").attr("curphoto"));
			$("#photo-"+$("#slideshow").attr("curphoto")+"-container").fadeIn(200, function() { 
				placeNav($("#slideshow").attr("curphoto"));
				getSSCaption("photo-"+$("#slideshow").attr("curphoto"));
				sizeContainer();
				$(".ssnavigation").fadeIn(200);
				$("#ssClose").fadeOut(200);

				$(".photocaptioncontainer").fadeIn(200);
			});
		},200)
	}
}
function closegsinfo() { 
	$("#gsinfocontainer").fadeOut(300);
}
function selectGSbackground(c,showload,file,fileid) { 
	if($("#selectgsbackground").length > 0) { 
		if(showload == true) { 
			$("#loading").show();
		}
		if(!file) { 
			$("body").append('<img id="gsbg" src="'+$("#gs-bgimage").val()+'" class="hide">');
		} else { 
			opengsbackground();
			$("body").append('<img id="gsbg" src="'+file+'" class="hide">');
			$("#gs-bgimage").val(file);
			$("#gs-bgimage-id").val(fileid);
			$(".gs-bgimage-id-free").val(fileid);
		}
		setTimeout(function() {
			$('#gsbg').imagesLoaded(function() {
				if(!file) { 
					$("."+c).css({"background-image":"url('"+$("#gs-bgimage").val()+"')","background-size":"cover","background-position":"center center"});
					$("#minigsbg").attr("src",$("#gs-bgimage").val());
				} else { 
					$(".photofull").css({"background-image":"url('"+file+"')","background-size":"cover","	background-position":"center center"});
					$("."+c).css({"background-image":"url('"+file+"')","background-size":"cover","	background-position":"center center"});
					$("#minigsbg").attr("src",file);
				}
				$("#loading").hide();
				$("#gsbg").remove();
			});
		},10);
	}
}

function opengsbackground() { 
	$("#selectgsbg").slideToggle(200);
}

