<?php

function getCountry($ct, $st_remote_host) {

   if (preg_match("/.com/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.net/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.org/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.edu/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.us/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.mil/i", $ct)) {
      $ctlist['USA']++;
      }
      elseif (preg_match("/.ca/i", $ct)) {
      $ctlist['Canada']++;
      }
      elseif (preg_match("/.uk/i", $ct)) {
      $ctlist['UK']++;
      }
      elseif (preg_match("/.de/i", $ct)) {
      $ctlist['Germany']++;
      }
      elseif (preg_match("/.nl/i", $ct)) {
      $ctlist['Netherlands']++;
      }
      elseif (preg_match("/.fr/i", $ct)) {
      $ctlist['France']++;
      }
      elseif (preg_match("/.jp/i", $ct)) {
      $ctlist['Japan']++;
      }

      elseif (preg_match("/.ch/i", $ct)) {
      $ctlist['Switzerland']++;
      }
      elseif (preg_match("/.cr/i", $ct)) {
      $ctlist['Costa Rica']++;
      }
      elseif (preg_match("/.my/i", $ct)) {
      $ctlist['Malaysia']++;
      }
      elseif (preg_match("/.pl/i", $ct)) {
      $ctlist['Poland']++;
      }
      elseif (preg_match("/.pk/i", $ct)) {
      $ctlist['Pakistan']++;
      }

      elseif (preg_match("/.no/i", $ct)) {
      $ctlist['Norway']++;
      }
      elseif (preg_match("/.yu/i", $ct)) {
      $ctlist['Yugoslavia']++;
      }
      elseif (preg_match("/.ma/i", $ct)) {
      $ctlist['Morocco']++;
      }
      elseif (preg_match("/.mx/i", $ct)) {
      $ctlist['Mexico']++;
      }
      elseif (preg_match("/.gov/i", $ct)) {
      $ctlist['USA Gov.']++;
      }
      elseif (preg_match("/.tr/i", $ct)) {
      $ctlist['Turkey']++;
      }
      elseif (preg_match("/.gr/i", $ct)) {
      $ctlist['Greece']++;
      }
      elseif (preg_match("/.be/i", $ct)) {
      $ctlist['Belgium']++;
      }
      elseif (preg_match("/.cz/i", $ct)) {
      $ctlist['Czech Republic']++;
      }
      elseif (preg_match("/.sk/i", $ct)) {
      $ctlist['Slovakia']++;
      }

      elseif (preg_match("/.lt/i", $ct)) {
      $ctlist['Lithuania']++;
      }
      elseif (preg_match("/.ar/i", $ct)) {
      $ctlist['Argentina']++;
      }
      elseif (preg_match("/.at/i", $ct)) {
      $ctlist['Austria']++;
      }
      elseif (preg_match("/.us/i", $ct)) {
      $ctlist['USA']++;
      }

      elseif (preg_match("/.bm/i", $ct)) {
      $ctlist['Bermuda']++;
      }
      elseif (preg_match("/.nz/i", $ct)) {
      $ctlist['New Zealand']++;
      }
      elseif (preg_match("/.hu/i", $ct)) {
      $ctlist['Hungary']++;
      }
      elseif (preg_match("/.fi/i", $ct)) {
      $ctlist['Finland']++;
      }
      elseif (preg_match("/.gb/i", $ct)) {
      $ctlist['Great Britain']++;
      }
      elseif (preg_match("/.br/i", $ct)) {
      $ctlist['Brazil']++;
      }
      elseif (preg_match("/.za/i", $ct)) {
      $ctlist['South Africa']++;
      }

      elseif (preg_match("/.au/i", $ct)) {
      $ctlist['Australia']++;
      }
      elseif (preg_match("/.it/i", $ct)) {
      $ctlist['Italy']++;
      }
      elseif (preg_match("/.vi/i", $ct)) {
      $ctlist['U.S. Virgin Islands']++;
      }
      elseif (preg_match("/.es/i", $ct)) {
      $ctlist['Spain']++;
      }
      elseif (preg_match("/.dk/i", $ct)) {
      $ctlist['Denmark']++;
      }

      elseif (preg_match("/.se/i", $ct)) {
      $ctlist['Sweden']++;
      }
      elseif (preg_match("/.th/i", $ct)) {
      $ctlist['Thailand']++;
      }
      elseif (preg_match("/.id/i", $ct)) {
      $ctlist['Indonesia']++;
      }
      elseif (preg_match("/.ie/i", $ct)) {
      $ctlist['Ireland']++;
      }
      elseif (preg_match("/.il/i", $ct)) {
      $ctlist['Israel']++;
      }
      elseif (preg_match("/.in/i", $ct)) {
      $ctlist['India']++;
      }
      elseif (preg_match("/.jm/i", $ct)) {
      $ctlist['Jamaica']++;
      }
      elseif (preg_match("/.is/i", $ct)) {
      $ctlist['Iceland']++;
      }
      elseif (preg_match("/.iq/i", $ct)) {
      $ctlist['Iraq']++;
      }
	  elseif (preg_match("/.ir/i", $ct)) {
      $ctlist['Iran']++;
      }

  	  elseif (preg_match("/.ru/i", $ct)) {
      $ctlist['Russia']++;
      }
	  elseif (preg_match("/.pr/i", $ct)) {
      $ctlist['Puerto Rico']++;
      }
	  elseif (preg_match("/.pt/i", $ct)) {
      $ctlist['Portugal']++;
      }
	  elseif (preg_match("/.sa/i", $ct)) {
      $ctlist['Saudi Arabia']++;
      }
	  elseif (preg_match("/.sg/i", $ct)) {
      $ctlist['Singapore']++;
      }
	  elseif (preg_match("/.kw/i", $ct)) {
      $ctlist['Kuwait']++;
      }
	  elseif (preg_match("/.sc/i", $ct)) {
      $ctlist['Seychelles']++;
      }
	  elseif (preg_match("/.ph/i", $ct)) {
      $ctlist['Philippines']++;
      }
	  elseif (preg_match("/.ee/i", $ct)) {
      $ctlist['Estonia']++;
      }
	  elseif (preg_match("/.hr/i", $ct)) {
      $ctlist['Croatia/Hrvatska']++;
      }

	  else {
		if(is_numeric($ct) == true) {

		$ctlist[$st_remote_host]++;

		} else {
			$ctlist['Other']++;
		}
	}
	
	arsort($ctlist, SORT_NUMERIC); 
	foreach($ctlist AS $oc => $cc) {
		if($cc > 0) {
			return $oc;
		}
	}

}




function getBrowser($browser) {


		  if (preg_match("/opera/i", $browser)) {
			  $browserlist['Opera']++;
			  }
		 elseif (preg_match("/konqueror/i", $browser)) {
				 $browserlist['Konqueror']++;
				 }
		 elseif (preg_match("/ipad/i", $browser)) {
				 $browserlist['iPad']++;
				 }
		 elseif (preg_match("/chrome/i", $browser)) {
				 $browserlist['Chrome']++;
				 }
		  elseif (preg_match("/msie 3/i", $browser)) {
				 $browserlist['Internet Explorer 3']++;
				 }
		  elseif (preg_match("/msie 4/i", $browser)) {
				 $browserlist['Internet Explorer 4']++;
				 }
		  elseif (preg_match("/msie 5/i", $browser)) {
				 $browserlist['Internet Explorer 5']++;
				 }
		  elseif (preg_match("/msie 6/i", $browser)) {
				 $browserlist['Internet Explorer 6']++;
				 }
		  elseif (preg_match("/msie 7/i", $browser)) {
				 $browserlist['Internet Explorer 7']++;
				 }
		  elseif (preg_match("/msie 8/i", $browser)) {
				 $browserlist['Internet Explorer 8']++;
				 }
		  elseif (preg_match("/msie 9/i", $browser)) {
				 $browserlist['Internet Explorer 9']++;
				 }
		  elseif (preg_match("/msie 10/i", $browser)) {
				 $browserlist['Internet Explorer 10']++;
				 }
		  elseif (preg_match("/rv:11/i", $browser)) {
				 $browserlist['Internet Explorer 11']++;
				 }
		  elseif (preg_match("/msie/i", $browser)) {
				 $browserlist['Internet Explorer ?']++;
				 }
//		  elseif (preg_match("/lynx/i", $browser)) {
//				 $browserlist['Lynx']++;
//				 }
		  elseif (preg_match("/firefox/i", $browser)) {
				 $browserlist['Firefox']++;
				 }
		  elseif (preg_match("/firebird/i", $browser)) {
				 $browserlist['Firebird']++;
				 }
		  elseif (preg_match("/safari/i", $browser)) {
				 $browserlist['Safari']++;
				 }
		  elseif (preg_match("/mozilla\/4/i", $browser)) {
				 $browserlist['Netscape 4']++;
				 }

	  elseif (preg_match("/grub/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/spider/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/msnbot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/networkquality/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/bot.html/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/Gigabot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/scooter/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/InternetSeer.com/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/Teoma/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/SurveyBot/i", $browser)) {
      $browserlist['Spider']++;
	  }
	  elseif (preg_match("/linksmanager/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/BecomeBot/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/slurp/i", $browser)) {
      $browserlist['Spider']++;
	  }

	  elseif (preg_match("/crawler/i", $browser)) {
      $browserlist['Spider']++;
	  }

		  elseif (preg_match("/mozilla\/5/i", $browser)) {
				 $browserlist['Netscape 5/6']++;
				 } else {
					 $browserlist[Other]++;
					 $other .= "$browser, ";
				 }

			arsort($browserlist, SORT_NUMERIC); 
			foreach($browserlist AS $oc => $cc) {
				if($cc > 0) {
					return $oc;
				}
			}

		}
?>