(function($) {
$.fn.getHiddenDimensions = function(includeMargin) {
    var $item = this,
        props = { position: 'absolute', visibility: 'hidden', display: 'block' },
        dim = { width:0, height:0, innerWidth: 0, innerHeight: 0,outerWidth: 0,outerHeight: 0 },
        $hiddenParents = $item.parents().andSelf().not(':visible'),
        includeMargin = (includeMargin == null)? false : includeMargin;

    var oldProps = [];
    $hiddenParents.each(function() {
        var old = {};

        for ( var name in props ) {
            old[ name ] = this.style[ name ];
            this.style[ name ] = props[ name ];
        }

        oldProps.push(old);
    });

    dim.width = $item.width();
    dim.outerWidth = $item.outerWidth(includeMargin);
    dim.innerWidth = $item.innerWidth();
    dim.height = $item.height();
    dim.innerHeight = $item.innerHeight();
    dim.outerHeight = $item.outerHeight(includeMargin);

    $hiddenParents.each(function(i) {
        var old = oldProps[i];
        for ( var name in props ) {
            this.style[ name ] = old[ name ];
        }
    });

    return dim;
}
}(jQuery));

function sizePhoto(pos) { 
	closeenlargephoto();
	if($("#slideshow").attr("fullscreen") == 1) { 
	var photospacing = 32;
	} else { 
	var photospacing = 0;
	}

	$("#photo-"+pos).imagesLoaded(function() {


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


		// $("#photo-"+pos).css({"left":"0px", "top":"0px", "margin":"0 0 0 0"});
		// Removed this line to fix the bug in Firefox slideshow. Hopefully doesn't mess something else up. 
		
		
		
		
		dim = $("#photo-"+pos).getHiddenDimensions();
		//alert(dim.width);
		 ww = Math.abs($("#photo-"+pos).attr("ww"));
		 hh = Math.abs($("#photo-"+pos).attr("hh"));
		 sth = Math.abs(sth);

		 if(ww <=1) { 
			ww = dim.width;
			$("#photo-"+pos).attr("ww",ww)
		 }
		 if(hh <=1) { 
			hh = dim.height;
			$("#photo-"+pos).attr("hh",hh)
		}


		if((ww + add_margin) > $("#photo-"+pos+"-container").parent().width() ||  (hh + (add_margin*2) + sth) + 20  > (wh - sth)) {
			nwn = Math.abs(ww) + Math.abs(add_margin);
			wp = $("#photo-"+pos+"-container").parent().width() / nwn;
			hp = wh / (Math.abs(hh) + sth + headerheight + (add_margin * 4));


			// $("#log").show().html(hp+" > "+wp+" add: "+add_margin+" | " );
			
			if(hp > wp) { 


			$("#photo-"+pos).css({"width":($("#photo-"+pos+"-container").parent().width() - add_margin)+"px",
					"height":hh * wp
				});
				nw = ($("#photo-"+pos+"-container").parent().width() - add_margin);
				nwp = nw / Math.abs(ww);
				nh = Math.abs(hh) * nwp;
			} else { 


				nw = nwn * hp;
				nh = Math.abs(hh) * hp;
				left = ($("#photo-"+pos+"-container").parent().width() -(nw)) / 2;
				if($("#ssheader").css("display") == "block") { 
					mh = $("#ssheader").height();
				} else { 
					mh = 0;
				}
				$("#photo-"+pos).css({"height":(wh - add_margin - mh - sth)+"px",
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

		//	alert($("#photo-"+pos+"-container").parent().width()+" - "+ww);
			if($("#photo-"+pos).width() < ww) { 
				$("#photo-"+pos).css({"width":ww+"px","height":"auto"});
			}
			left = ($("#photo-"+pos+"-container").parent().width() - Math.abs(ww)) / 2;
			$("#photo-"+pos).css({"left":left+"px"});
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
				$("#photo-"+pos).css({"top":new_top +"px"});
			} else { 
				$("#photo-"+pos).css({"top":"0px"});
			}
			left = ($("#photo-"+pos+"-container").parent().width() - Math.abs(nw)) / 2 - 4;

			// $("#log").show();
			// $("#log").append($("#photo-"+pos+"-container").parent().width()+" - "+Math.abs(nw)+"<br>");

			$("#photo-"+pos).css({"left":left+"px"});

		}
	});
}

 function placeNav(pos) { 
	 if(disablecontrols !== 1) { 
		if($("body").width() <= 800) { 
			$("#controls").hide();
		} else { 

			$("#controls").css({"width":"600px", "margin-left":"-300px", "position":"fixed",  "left":"50%", "top":"45%", "z-index":"30", "text-align":"center", "height":$("#ssPause").height() * 2, "z-index":"60"});
		}
			 
		p = $("#slideshow").position();
		if(first_landscape_height > 0)  {
			ch = first_landscape_height;
			if( Math.abs(first_landscape_width) > Math.abs($("#slideshow").width())) { 
				ch = first_landscape_height * ($("#slideshow").width() / first_landscape_width);
			}
		} else { 
			ch = $("#photo-"+pos).outerHeight();
		}

			ch = Math.abs(ch);
			h = ch / 2 + p.top - (	$("#ssPrevPhoto").height() / 2);
			nextleft =  $("#slideshow").width() + p.left - $("#ssNextPhoto").width();

			$("#ssPrevPhoto").css({"position":"absolute", "left":p.left+"px", "top":h, "z-index":"30"});
			$("#ssNextPhoto").css({"position":"absolute",  "left":nextleft+"px", "top":h, "z-index":"30"});
			w = $("#slideshow").width();
			pp = ($("#slideshow").width() / 2)+ p.left - (w / 4);
			if($("body").width() <= 800) { 
				$("#controls").hide();
			} else { 
				$("#controls").css({"width":w/2+"px",  "position":"absolute","zoom":"1","margin-left":"0", "left":pp+"px", "top":h, "z-index":"30", "text-align":"center", "height":$("#ssPause").height() * 2, "z-index":"30"});
			}
		if(thumbnails !== 1) { 

			if($("body").width() <= 800) { 
				$("#controls").hide();
			} else { 
				$("#controls").fadeIn(100);
			}
		}
		if($("#slideshow").attr("curphoto") > 1) { 
			$("#ssPrevPhoto").fadeIn(100);
		}
		if($("#slideshow").attr("curphoto") < totalphotos) { 
			$("#ssNextPhoto").fadeIn(100);
		}

 		 if($("#slideshow").attr("fullscreen") == 1) { 

			$("#ssPrevPhoto").css({"position":"fixed","top":"45%", "z-index":"30", "left":"0px"});
			$("#ssNextPhoto").css({"position":"fixed",   "top":"45%", "z-index":"30", "left":$("#slideshow").width() - $("#ssNextPhoto").width() - 16+"px"});
			$("#controls").css({"position":"fixed",   "top":"45%", "z-index":"30"});
		 } 

 		 }
		if($("#photoproductsnexttophoto").css('display') == "none") { 
			sscloseright = 0;
		} else { 
			sscloseright = $("#photoproductsnexttophoto").css('width');
		}
		$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});

 }

function sizeContainer() { 
	if($("#slideshow").attr("fullscreen") == 1) { 
		add_margin = add_margin_full;
	} else {
		add_margin = add_margin_page;
	}
	if($("#slideshow").attr("fullscreen") !== "1") { 
		if(sizecontainer == "1") { 
			$("#slideshow").animate({"height":Math.abs($("#photo-"+$("#slideshow").attr("curphoto")).height()) + add_margin +"px"}, 200);
		}

	}
}


function enableenlargephoto() { 
	if(disable_enlarge !== "1") { 
		$(".enlarge").bind( "click", function() {
			 enlargephoto();
		});
	} else { 
		$(".enlarge").removeClass("zoomCur");
	}
}

function enlargephoto() { 
	if(truetablet !== "1") { 
		if($("#photo-"+$("#slideshow").attr("curphoto")).attr("ww") > $("#photo-"+$("#slideshow").attr("curphoto")).width()) {  
			fixbackground();
			$("#enlargephoto").html('<img src="'+$("#photo-"+$("#slideshow").attr("curphoto")).attr("src")+'">');
			nleft = $("#photo-"+$("#slideshow").attr("curphoto")).offset().left;

			 $("#enlargephoto").css({"margin-left":"0",  "left":nleft+"px", "top":$("#photo-"+$("#slideshow").attr("curphoto")).offset().top+"px"});
			$("#enlargephoto img").css({"width":$("#photo-"+$("#slideshow").attr("curphoto")).width()+"px","height":$("#photo-"+$("#slideshow").attr("curphoto")).height()+"px"});

			$("#enlargephoto").show();
			t = ($(window).height() - $("#photo-"+$("#slideshow").attr("curphoto")).attr("hh")) / 2;
			if(t < 0) { 
				t = 0;
			}
			if($("#photoproductsnexttophoto").css("display")!=="none") { 
				prodlistwidth = $("#photoproductsnexttophoto").width();
			} else { 
				prodlistwidth = 0;
			}
			a = $("#photo-"+$("#slideshow").attr("curphoto")).attr("ww") / 2;
			b = prodlistwidth / 2;
			eleft = a + b;
			$("#enlargephoto").animate({"top": t+"px", "left":"50%", "margin-left":"-"+eleft+"px"}, 200,  function() { 
				if($("#enlargephoto").offset().left < 0) { 
					$("#enlargephoto").animate({"left":"0", "margin-left":"0"}, 100);	
				}
			});
			$("#enlargephoto img").animate({"width":$("#photo-"+$("#slideshow").attr("curphoto")).attr("ww")+"px","height":$("#photo-"+$("#slideshow").attr("curphoto")).attr("hh")+"px"}, 200);
			$("#enlargephoto").unbind( "click" );
			$("#enlargephoto").bind( "click", function() {
				 closeenlargephoto();
			});
			disablerightclick();
		}
	}
}

function closeenlargephoto() { 
	if($("#enlargephoto").html()!=="") { 
		nleft = $("#photo-"+$("#slideshow").attr("curphoto")).offset().left;

		 $("#enlargephoto").animate({"margin-left":"0",  "left":nleft+"px", "top":$("#photo-"+$("#slideshow").attr("curphoto")).offset().top+"px"}, 200);
		$("#enlargephoto img").animate({"width":$("#photo-"+$("#slideshow").attr("curphoto")).width()+"px","height":$("#photo-"+$("#slideshow").attr("curphoto")).height()+"px"}, 200, function() { 
			$("#enlargephoto").html("");
			$("#enlargephoto").hide();
			if($("#vinfo").attr("view-photo-fixed") == "0") { 
				unfixbackground();
			}

		});

		$("#enlargephoto").unbind( "click" );
		$( "#enlargephoto" ).bind( "click", function() {
			 enlargephoto();
		});
	}
}



function navSlidesArrows(n) { 
	curphoto = $("#slideshow").attr("curphoto");
	if(n == "next") { 
		pos = Math.abs(curphoto) + 1;
		if(pos == totalphotos && loop == 0) { 
		 stopSlideshow();
		}
		 if(pos  >  totalphotos && loop == 1 && $("#slideshow").attr("sson") == 1) { 
			pos = 1;			
		 }
	}
	if(n == "prev") { 
		pos = Math.abs(curphoto) - 1;
		 if(pos  < 1) { 
			return false;
		 }
	}

	key = $("#photo-"+(pos)).attr("pkey");

	window.location.href="#"+key;

}


 function navSlides(n,stop) { 

	 if(thumbnails == 1) { 
		 navthumbnails(n,stop);
	 } else {
		 navslideshow(n,stop);
	}

 }

function navslideshow(n,stop) { 
	 if(stop == "1") { 
		 stopSlideshow();
	 }

	if(sytiststore == 1) { 
		removeFilterPhoto();
	}
	 if($("#slideshow").attr("disablenav") !== "1") { ;

		 $("#slideshow").attr("disablenav","1");
		curphoto = $("#slideshow").attr("curphoto");
		if(n == "start") { 
			pos = 1;
		}

		if(n == "next") { 
			pos = Math.abs(curphoto) + 1;
			if(pos == totalphotos && loop == 0) { 
			 stopSlideshow();
			}
			 if(pos  >  totalphotos && loop == 1 && $("#slideshow").attr("sson") == 1) { 
				pos = 1;			
			 }
		}
		if(n == "prev") { 
			pos = Math.abs(curphoto) - 1;
			 if(pos  < 1) { 
				return false;
			 }
		}

		if(n !=="prev" && n!=="next" && n!=="start") { 
			pos = Math.abs(n);
			src = $("#photo-"+(pos )).attr("src");
			if (typeof src === "undefined") {
				$("#photo-"+(pos)).attr("src", $("#photo-"+(pos)).attr("thissrc"));
			}

		}
		if(curphoto != pos) { 
			stillload  = setTimeout("slideshowloading()",500);
			if(disablecontrols !== 1) { 

				if(pos  >= totalphotos) { 
					 $("#ssNextPhoto").hide();
				 } else { 
					 $("#ssNextPhoto").show();
				 }
				 if(pos  <= 1) { 
					 $("#ssPrevPhoto").hide();
				 } else { 
					 $("#ssPrevPhoto").show();
				 }
			}
		// alert(n+" "+curphoto+" -  photo-"+pos+" width"+$("#photo-"+pos).attr("src"));

			if($("#slideshow").attr("fullscreen") == 1) { 
				$("#photo-"+pos).removeClass("photo").addClass("photofull");
			} else { 
				$("#photo-"+pos).removeClass("photofull").addClass("photo");
			}

			$("#th-"+curphoto).removeClass("thumbon");
			$("#th-"+pos).addClass("thumbon");


			$("#photo-"+pos).imagesLoaded(function() {
				sizePhoto(pos);
				slideshowdoneloading();
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

					$("#phototopcount").html(""+$("#photo-"+pos).attr("pos")+"/"+totalphotos+"");
					$("#photofilename").html($("#photo-"+pos).attr("org"));
					$("#photopagelink").html('<a href="'+$("#photo-"+pos).attr("pagelink")+'">'+$("#photo-"+pos).attr("pagetitle")+'</a>');

				}


				if (typeof stillload!=="undefined") {
					clearInterval(stillload);
				 }


				$("#photocount").html(""+pos+"/"+totalphotos+"");
				if($("#slideshow").attr("sson") == "1"){ 
					$("#photo-"+pos+"-container").fadeIn(sstransition, function() { 

						 $("#slideshow").attr("disablenav","0");
						if(thumbnails == 1) {
							// $('#styledthumb-'+pos).scrollView();
						}
						if($("#slideshow").attr("fullscreen") == 1) { 
							if($("#photo-"+pos).attr("ww") > $("#photo-"+pos).width()) {  
								$("#photo-"+pos).addClass("zoomCur");
							} else { 
								$("#photo-"+pos).removeClass("zoomCur");
							}
							if($("#vinfo").attr("proofing") == "1") { 
								$.get(tempfolder+"/sy-inc/store/store_photos_menu.php?date_id="+$("#vinfo").attr("did")+"&view="+$("#vinfo").attr("view")+"&need-login="+$("#vinfo").attr("need-login")+"&pic="+$("#photo-"+pos).attr("pkey"), function(data) {
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
								productsnexttophoto(pos);
							}
						}
					});
					$("#photo-"+curphoto+"-container").fadeOut(sstransition);
				} else { 

					$("#photo-"+pos+"-container").fadeIn(10, function() { 

						 $("#slideshow").attr("disablenav","0");
						if(thumbnails == 1) {
							// $('#styledthumb-'+pos).scrollView();
						}

						if($("#slideshow").attr("fullscreen") == 1) { 
					

							if($("#photo-"+pos).attr("ww") > $("#photo-"+pos).width()) {  
								$("#photo-"+pos).addClass("zoomCur");
							} else { 
								$("#photo-"+pos).removeClass("zoomCur");
							}
							if($("#vinfo").attr("proofing") == "1") { 
								$.get(tempfolder+"/sy-inc/store/store_photos_menu.php?date_id="+$("#vinfo").attr("did")+"&view="+$("#vinfo").attr("view")+"&need-login="+$("#vinfo").attr("need-login")+"&pic="+$("#photo-"+pos).attr("pkey"), function(data) {
									$("#ssheader").html(data);
									$("#phototopcount").html(""+$("#photo-"+pos).attr("pos")+"/"+totalphotos+"");
									$("#photofilename").html($("#photo-"+pos).attr("org"));
									$("#photopagelink").html('<a href="'+$("#photo-"+pos).attr("pagelink")+'">'+$("#photo-"+pos).attr("pagetitle")+'</a>');
								});
							}
							if($("#slideshow").attr("disablenav") !== "1" && $("#slideshow").attr("fullscreen") == 1)  {
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
					$("#photo-"+curphoto+"-container").fadeOut(10);
				}
				if($("#slideshow").attr("sson") == "1"){ 
					SSslideshowtimer = window.setTimeout("navSlides('next')",ssspeed);
				}

				setTimeout(function() {
				getSSCaption("photo-"+pos);
				}, 100);
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


function productsnexttophoto(pos) { 

	//alert($("#vinfo").attr("need-login"));
	if($("#vinfo").attr("prodplacedefault") == "1" && $("#photo-"+pos).attr("pl") > 0 && $("#vinfo").attr("need-login")!=="1") { 
			pic = $("#photo-"+pos).attr("pkey");
			date_id = $("#photo-"+pos).attr("did");
			sub_id = $("#vinfo").attr("sub_id");
			if($("#vinfo").attr("view") == "favorites") { 
				sub_id = $("#photo-"+pos).attr("subid");
			}
			group_id = $("#vinfo").attr("group-id");

			headerheight = Math.abs($("#ssheader").height()) + 12;

			fixbackground();
			$("#vinfo").attr("view-photo-fixed","1");
			$.get(tempfolder+"/sy-inc/store/store_photos_buy_v2_list_products.php?pid="+pic+"&date_id="+date_id+"&mobile="+ismobile+"&sub_id="+sub_id+"&color_id="+$("#filter").attr("color_id")+"&withphoto=1&group_id="+$("#vinfo").attr("group-id")+"&kid="+$("#vinfo").attr("kid")+"&keyWord="+$("#vinfo").attr("keyWord")+"&view="+$("#vinfo").attr("view")+"&from_time="+$("#vinfo").attr("from_time")+"&search_date="+$("#vinfo").attr("search_date")+"&passcode="+$("#vinfo").attr("passcode")+"&search_length="+$("#vinfo").attr("search_length"), function(data) {
				$("#slideshow").attr("disablenav","0");		
				// $("#log").show().append("enable ");
				// alert("Enabled!! : "+$("#slideshow").attr("disablenav"));

				photo = document.location.hash;
				photo = photo.replace("#","");
				p = photo.split("=");
				if(p[0] == "photo" || p[0] == "gti") { 
					// $("#log").append("hash: "+ document.location.hash+" ");

					$("#vinfo").attr("view_package", "0");
					$("#vinfo").attr("viewing_prods", "1");
					if($("#photoproductsnexttophoto").css('display') == "none") { 
						sscloseright = 0;
					} else { 
						sscloseright = $("#photoproductsnexttophoto").css('width');
					}
					$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
					$("#vinfo").attr("viewing-store-photo-prod","0");
					$("#photoproductsnexttophoto .inner").empty().append(data);
					if($("#vinfo").attr("prodplace") == "1") { 
						$("#slideshow").addClass("photowithproducts");
						$("#ssheader").addClass("ssheaderwithproducts");
						$("#photopackagetab").hide();
						$("#singlephotopackagetab").hide();
							$("#photoproductsnexttophoto").fadeIn(200, function() { 
								if($("#slideshow").attr("fullscreen") !== "1") {
									closeFullScreen();
								}
								 $("#slideshow").attr("disablenav","0");	
								placeNav($("#slideshow").attr("curphoto"));
								sizePhoto($("#slideshow").attr("curphoto"));
								setTimeout(function() {
									getSSCaption("photo-"+$("#slideshow").attr("curphoto"));
								}, 50);

								 
							});
							$("#photoproductsnexttophotobg").fadeIn(200);
						}
				}
		});
	} else { 
		$("#slideshow").removeClass("photowithproducts");
		$("#ssheader").removeClass("ssheaderwithproducts");
		$("#photoproductsnexttophoto").fadeOut(200);
		$("#photoproductsnexttophotobg").fadeOut(200);
		placeNav($("#slideshow").attr("curphoto"));
		sizePhoto($("#slideshow").attr("curphoto"));

	}
}



function packageopen(pic,date_id,from_thumb) { 
	fixbackground();
	if(from_thumb) { 
		$("#vinfo").attr("package_from_thumb",from_thumb);
		$("#vinfo").attr("package_thumb_photo",pic);
	}
	$("#buybackground").fadeIn(50,function() { 
		if(isslideshow == true) { 
			stopSlideshow();
		}
		fixbackground();
		window.scrollTo(0,0);
		loading();
		if(!pic) { 
			if($("#vinfo").attr("package_thumb_photo")!="") { 
				pic = $("#vinfo").attr("package_thumb_photo");
			} else { 
				pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
			}
		}
		if(!date_id) { 
			date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
			if(typeof date_id === "undefined") { 
				date_id = $("#vinfo").attr("did");
			}
		}
		sub_id = $("#vinfo").attr("sub_id");
		$("#photoprods").css({"top":50+"px"});
			headerheight = Math.abs($("#ssheader").height()) + 12;

			fixbackground();
			$("#vinfo").attr("view-photo-fixed","1");

			$.get(tempfolder+"/sy-inc/store/store_package_photo.php?pid="+pic+"&date_id="+date_id+"&withphoto=1&sub_id="+sub_id+"&view="+$("#vinfo").attr("view")+"&package_id="+$("#vinfo").attr("package-id")+"&color_id="+$("#filter").attr("color_id")+"&istablet=1", function(data) {
				$("#photoprodsinner").html(data);
				$("#photoprods").slideDown(200, function() { 
					window.scrollTo(0,0);
					setTimeout(packagepriewmini,200);


					$(".packagepreviewphoto").attr('src',$("#photo-"+$("#slideshow").attr("curphoto")).attr("mini"));
					if($("body").width() <= 800) { 
						$("#closebuyphototab").show();
					} else { 
						$("#closebuyphoto").show();
					}
					sizeBuyPhoto();
					loadingdone();

					$('html').click(function() {
					 closebuyphoto();
					 });

			 $('#photoprods').click(function(event){
				 event.stopPropagation();
			 });

				});
			});
	});
}

function packagepriewmini() { 
	$(".packagepreviewphoto").attr('src',$("#photo-"+$("#slideshow").attr("curphoto")).attr("mini"));
}

function packagenexttophoto(pos) { 
	if($("#vinfo").attr("prodplacedefault") == "1" || $("#vinfo").attr("view_package_only") == "1") { 

			pic = $("#photo-"+pos).attr("pkey");
			date_id = $("#photo-"+pos).attr("did");
			sub_id = $("#vinfo").attr("sub_id");
			headerheight = Math.abs($("#ssheader").height()) + 12;

			fixbackground();
			$("#vinfo").attr("view-photo-fixed","1");

			$.get(tempfolder+"/sy-inc/store/store_package_photo.php?pid="+pic+"&date_id="+date_id+"&withphoto=1&sub_id="+sub_id+"&view="+$("#vinfo").attr("view")+"&package_id="+$("#vinfo").attr("package-id")+"&color_id="+$("#filter").attr("color_id")+"&istablet="+istablet, function(data) {
				$("#vinfo").attr("view_package", "1");
				$("#vinfo").attr("viewing_prods", "1");
				$("#photoproductsnexttophoto .inner").html(data);
				// $("#photoprodsinner").empty().append(data);
				setTimeout(packagepriewmini,200);

				if($("#vinfo").attr("prodplace") == "1" || $("#vinfo").attr("view_package_only") == "1") { 
					$(".packagepreviewphoto").attr('src',$("#photo-"+$("#slideshow").attr("curphoto")).attr("mini"));

					if($("body").width() > 800) { 
						sizePhoto($("#slideshow").attr("curphoto"));		

						$("#slideshow").addClass("photowithproducts");
						$("#ssheader").addClass("ssheaderwithproducts");
						$("#photopackagetab").hide();
						$("#singlephotopackagetab").hide();
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});
						$("#vinfo").attr("viewing-store-photo-prod","0");

						if($("#photoproductsnexttophoto").css("display")=="none") { 
							$("#photoproductsnexttophoto").fadeIn(400);
							$("#photoproductsnexttophotobg").fadeIn(400);
							placeNav($("#slideshow").attr("curphoto"));
						}
					}
			}
		});
	} else { 
		$("#slideshow").removeClass("photowithproducts");
		$("#ssheader").removeClass("ssheaderwithproducts");
	}
}
function storeproductnexttophoto(pos,cart_id,pic,date_id) { 
	//if($("#vinfo").attr("prodplacedefault") == "1" || $("#vinfo").attr("view_package_only") == "1") { 

		if(!pic) { 
			pic = $("#photo-"+pos).attr("pkey");
		}
		if(!date_id) { 
			date_id = $("#photo-"+pos).attr("did");
		}

			sub_id = $("#vinfo").attr("sub_id");
			headerheight = Math.abs($("#ssheader").height()) + 12;
			if(!cart_id) { 
				cart_id = $("#vinfo").attr("product-photo-id");
			} else { 
				$("#vinfo").attr("product-photo-id",cart_id)
			}
			if($("body").width() <= lppw || istablet == true) { 
				istablet = 1;
			}
			fixbackground();
			$("#vinfo").attr("view-photo-fixed","1");

			$.get(tempfolder+"/sy-inc/store/store_product_photo.php?pid="+pic+"&date_id="+date_id+"&withphoto=1&sub_id="+sub_id+"&cart_id="+cart_id+"&color_id="+$("#filter").attr("color_id")+"&istablet="+istablet, function(data) {
				$("#vinfo").attr("view_package", "1");

				$("#vinfo").attr("viewing_prods", "1");
				$("#photoproductsnexttophoto .inner").empty().append(data);
				$("#photoprodsinner").empty().append(data);
				setTimeout(packagepriewmini,200);

				if($("#vinfo").attr("prodplace") == "1" || $("#vinfo").attr("view_package_only") == "1") { 
					$(".packagepreviewphoto").attr('src',$("#photo-"+$("#slideshow").attr("curphoto")).attr("mini"));
					$("#vinfo").attr("viewing-store-photo-prod","1");
					setTimeout(packagepriewmini,500);

					if($("body").width() > 800) { 
						$("#slideshow").addClass("photowithproducts");
						$("#ssheader").addClass("ssheaderwithproducts");
						$("#photopackagetab").hide();
						$("#singlephotopackagetab").hide();
						if($("#photoproductsnexttophoto").css('display') == "none") { 
							sscloseright = 0;
						} else { 
							sscloseright = $("#photoproductsnexttophoto").css('width');
						}
						$("#ssClose").css({"top":$("#ssheader").height()+"px","right":sscloseright});

						if($("#photoproductsnexttophoto").css("display")=="none") { 
							$("#photoproductsnexttophoto").fadeIn(400);
							$("#photoproductsnexttophotobg").fadeIn(400);
							placeNav($("#slideshow").attr("curphoto"));
						}
					}
			}
		});
//	} else { 
	//	$("#slideshow").removeClass("photowithproducts");
	//	$("#ssheader").removeClass("ssheaderwithproducts");
//	}
}



function storephotoopen(cart_id,from_thumb,pic,date_id) { 
	fixbackground();
	if(from_thumb) { 
		$("#vinfo").attr("package_from_thumb",from_thumb);
		$("#vinfo").attr("package_thumb_photo",pic);
	}
	$("#buybackground").fadeIn(50,function() { 
		if(isslideshow == true) { 
			stopSlideshow();
		}
		fixbackground();
		window.scrollTo(0,0);
		loading();
		if(!pic) { 
			if($("#vinfo").attr("package_thumb_photo")!="") { 
				pic = $("#vinfo").attr("package_thumb_photo");
			} else { 
				pic = $("#photo-"+$("#slideshow").attr("curphoto")).attr("pkey");
			}
		}
		if(!date_id) { 
			date_id = $("#photo-"+$("#slideshow").attr("curphoto")).attr("did");
			if(typeof date_id === "undefined") { 
				date_id = $("#vinfo").attr("did");
			}
		}
		sub_id = $("#vinfo").attr("sub_id");
		$("#photoprods").css({"top":50+"px"});
			headerheight = Math.abs($("#ssheader").height()) + 12;

			fixbackground();
			$("#vinfo").attr("view-photo-fixed","1");

			$.get(tempfolder+"/sy-inc/store/store_product_photo.php?pid="+pic+"&date_id="+date_id+"&withphoto=1&sub_id="+sub_id+"&cart_id="+cart_id+"&color_id="+$("#filter").attr("color_id")+"&istablet="+istablet, function(data) {
				$("#photoprodsinner").html(data);
				$("#photoprods").slideDown(200, function() { 
					window.scrollTo(0,0);
					setTimeout(packagepriewmini,200);
					if($("body").width() <= 800) { 
						$("#closebuyphototab").show();
					} else { 
						$("#closebuyphoto").show();
					}
					sizeBuyPhoto();
					loadingdone();

					$('html').click(function() {
					 closebuyphoto();
					 });

			 $('#photoprods').click(function(event){
				 event.stopPropagation();
			 });

				});
			});
	});
}

function getSSCaption(id) { 
	if($("#slideshow").attr("fullscreen") == 1) { 
		add_margin = add_margin_full;
	} else {
		add_margin = add_margin_page;
	}

	if(thumbnails == 1 && id == "photo-1") { 

	} else { 
		add = ($("#"+id).outerWidth() - $("#"+id).width()) / 2;
		var ml = $("#"+id).css("left");
		ml= ml.replace("px", "");
		ml = Math.abs(ml) + (add_margin / 2);
		if($("#slideshow").attr("fullscreen") == 1) { 

			// photobottom = $("#"+id).css("bottom");
			cpos = $("#"+id).position();


			// photobottom= photobottom.replace("px", "");
			photobottom  = "-"+cpos.top;
			// $("#log").show().html(photobottom+" id: "+id+" pos: "+cpos.top);

			photobottom = -Math.abs(photobottom) + Math.abs(add_margin);
			$("#"+id).parent().children().children(".photocaptioncontainer").css({"width":$("#"+id).width()+"px","margin":"auto", "left":ml, "bottom":photobottom, "position":"absolute"});
		} else { 
			$("#"+id).parent().children().children(".photocaptioncontainer").css({"width":$("#"+id).width()+"px","margin":"auto", "left":ml, "bottom":(add_margin / 2) + 4+"px", "position":"absolute"});
		}
		$("#"+id).parent().children().children(".photocaptioncontainer").fadeIn(200);
	}
}

function arrowNavSS() { 
	document.onkeydown = function(evt) {
		evt = evt || window.event;
		if($("#vinfo").attr("disablearrow")!=="1") { 
			switch (evt.keyCode) {
				case 39:
				if($("#ssNextPhoto").css("display")!=="none") { 
					navSlides('next',"1");
				}
					break;
				case 37:
				if($("#ssPrevPhoto").css("display")!=="none") { 
					navSlides('prev',"1");
				}
				break;
				case 27:
				closeFullScreen();
				break;
				case 80:
			//	toggleSpaceBar();
				break;
			}
		}
	};
}
function startSlideshow() { 
	$("#slideshow").attr("sson","1");
	if($("#slideshow").attr("curphoto") == totalphotos) { 
		navSlides('start','0');
	} else { 
		navSlides('next','0');
	}
	$("#ssPlay").hide();
	$("#ssPause").show();
}

function stopSlideshow() { 
	$("#slideshow").attr("sson","0");
	window.clearInterval(SSslideshowtimer);
	$("#ssPlay").show();
	$("#ssPause").hide();
}

function fullScreenThumbScroller() { 
	$("#thumbscroller").removeClass("thumbscrollerpage").addClass("thumbscrollerfullscreen");
	var oScrollbar = $('#scrollbar1');
	oScrollbar.tinyscrollbar({ axis: 'x'});
	oScrollbar.tinyscrollbar_update();

	sw= $("#scrollbar1").width();
	sbl = $("#scrollbar1").position().left;
	cw = $("#overview").width();
	tp = $("#th-"+$("#slideshow").attr("curphoto")).position().left;
	tpo = $("#th-"+$("#slideshow").attr("curphoto")).offset().left;
	place = 0;
	if(tpo > (sw + Math.abs(sbl))) {
		place = tp;
	}
	if(tpo <  Math.abs(sbl)) { 
		if((Math.abs(tp) - (Math.abs(sw))) + $("#th-"+$("#slideshow").attr("curphoto")).width() < 0) { 
			st = 0;
		} else { 
			st = (Math.abs(tp) - (Math.abs(sw))) + $("#th-"+$("#slideshow").attr("curphoto")).width();
		}
		place = st;
	}
	var oScrollbar = $('#scrollbar1');
	oScrollbar.tinyscrollbar({ axis: 'x'});
	oScrollbar.tinyscrollbar_update(place);
	//$("#thumbscroller").show();
}

function closeFullScreenThumbScroller() { 
	$("#thumbscroller").removeClass("thumbscrollerfullscreen").addClass("thumbscrollerpage");
	var oScrollbar = $('#scrollbar1');
	oScrollbar.tinyscrollbar({ axis: 'x'});
	oScrollbar.tinyscrollbar_update();
	place = 0;
	sw= $("#scrollbar1").width();
	sbl = $("#scrollbar1").position().left;
	cw = $("#overview").width();
	tp = $("#th-"+$("#slideshow").attr("curphoto")).position().left;
	tpo = $("#th-"+$("#slideshow").attr("curphoto")).offset().left;
	if(tpo > (sw + Math.abs(sbl))) {
		place = tp;
	}
	if(tpo <  Math.abs(sbl)) { 
		if((Math.abs(tp) - (Math.abs(sw))) + $("#th-"+$("#slideshow").attr("curphoto")).width() < 0) { 
			st = 0;
		} else { 
			st = (Math.abs(tp) - (Math.abs(sw))) + $("#th-"+$("#slideshow").attr("curphoto")).width();
		}
		place = st;
	}
	var oScrollbar = $('#scrollbar1');
	oScrollbar.tinyscrollbar({ axis: 'x'});
	oScrollbar.tinyscrollbar_update(place);
//	$("#thumbscroller").css("visibility", "visible");
	if(thumbnails == 1) { 
		$("#thumbscroller").hide();
	}

}


function closepackagetab() { 
	$("#photopackagetab").hide();
}
function checkforpackages() { 
	if($("#vinfo").attr("has_package") > 0 && $("#vinfo").attr("has_package_one")<=0) { 
		headerheight = Math.abs($("#ssheader").height()) + 12;
		$("#photopackagetab").css({"top":headerheight+"px"});
			if($("body").width() <= lppw || itablet == true) { 

			$("#photopackagetab").fadeIn(100, function() { 
				$("#ssClose").css({"top":$("#ssheader").height()+"px"});
			});
		}
	}
	if($("#vinfo").attr("has_package") > 1){ 
		headerheight = Math.abs($("#ssheader").height()) + 12;
		$("#photopackagetab").css({"top":headerheight+"px"});
			if($("body").width() <= lppw || itablet == true) { 
			$("#photopackagetab").fadeIn(100);
		}
	}
}
function closepackagetabone() { 
	$("#singlephotopackagetab").hide();
}
function checkforpackagesone() { 
	if($("#vinfo").attr("has_package_one") == 1 && $("#vinfo").attr("has_package") == "1") { 
	//	headerheight = Math.abs($("#ssheader").height()) + 12;
	//	$("#singlephotopackagetab").css({"top":headerheight+"px"});
		if($("body").width() <= 800 || $("#vinfo").attr("prodplace")=="0") { 

			$("#singlephotopackagetab").fadeIn(100, function() { 			
				$("#ssClose").css({"top":$("#ssheader").height()+"px"});
			});
		}
		showPackageOne();
	}
}





function slideshowloading() { 
	if($("#slideshow").attr("fullscreen") == 1) { 
		$("#sscontainerloading").css({"width":"100%", "height":"100%", "postion":"fixed" , "top":"0", "left":"0"});

	} else { 
		h = $("#photo-"+$("#slideshow").attr("curphoto")).height();
		w = $("#photo-"+$("#slideshow").attr("curphoto")).width();
		$("#sscontainerloading").css({"width":w+"px", "height":h+"px", "z-index":"2"});
	}
	$("#sscontainerloading").fadeIn(200);
}
function slideshowdoneloading() { 
	$("#sscontainerloading").hide();
}

showthumbsscroller = function() { 
	var nh = $("#scrollbar1").height() + 4;
	$("#thumbscrollerinner").animate({opacity:"1", height:nh+"px", zIndex: "1", overflow: "visible"}, 100);
	$('#thumbscrollerclick').unbind('click', showthumbsscroller); 
	$('#thumbscrollerclick').bind('click', hidethumbsscroller); 
	$("#navarrowthumbs").attr("src","/sy-graphics/icons/nav_down.png");
}

hidethumbsscroller = function() { 
	$("#thumbscrollerinner").animate({opacity:"0", height:"0px", zIndex: "0", overflow: "hidden"}, 100);
	$('#thumbscrollerclick').unbind('click', hidethumbsscroller); 
	$('#thumbscrollerclick').bind('click', showthumbsscroller); 
	$("#navarrowthumbs").attr("src","/sy-graphics/icons/nav_up.png");
}
function playSSAudio(aud,totalplayers,track,type) {
	var audioID = document.getElementById('ssAudio');
       if (audioID.paused)
			$("#ssAudioPlay").hide();
			$("#ssAudioPause").show();
           audioID.play();
       }

 function pauseSSAudio(aud,totalplayers) {
	var audioID = document.getElementById('ssAudio');
           audioID.pause();
			$("#ssAudioPlay").show();
			$("#ssAudioPause").hide();
       }


	function fadeVolOut(newPercent){
		if(document.getElementById('ssAudio')) { 
			if(newPercent > 0){
			document.getElementById('ssAudio').volume = newPercent;
	//		setVolume(newPercent);
			setTimeout('fadeVolOut(' + (newPercent - .05) + ');',50);
			}
		}
	}