<?php 
				//+++++++++++++   OPEN SIGN   ++++++++++++++
				$query = "SELECT * FROM ng_opencl ORDER BY id DESC";
	
				$contentset = mysql_query($query, $mysamconnec);
				testquery($contentset);
				
				while($content = mysql_fetch_array($contentset)){
			
					if ($content['open'] == 1) {
						//echo "<h2><img src=\"img/openbig.gif\"></h2>";
					}
					else {
						//echo "<h2><img src=\"img/closedbig.gif\"></h2>";
					}
				}
			?>