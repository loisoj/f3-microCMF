<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{@ CONSTRUCTR_PAGE_TITLE @}}</title>
        <meta name="keywords" content="{{@ CONSTRUCTR_PAGE_KEYWORDS @}}">
        <meta name="description" content="{{@ CONSTRUCTR_PAGE_DESCRIPTION @}}">
        <style>

        	{{@ PAGE_CSS @}}

	    	/*
	
	    	 Copy the following CSS into your site specific css to be compressed by ConstructrCMS
	
	    	 */

			/*COPY START*/

            html, body{
            	margin:0 0 0 0;
            	padding:0 0 0 0;
            }
            #logo img{
            	display:block;
            	margin-left:auto;
            	margin-right:auto;
            	margin-top:150px;
            	margin-bottom:150px;
            }
            #content{
            	margin-top:100px;
            	margin-bottom:100px;
            	max-width:1200px;
            	display:block;
            	margin-left:auto;
            	margin-right:auto;
            	font-family:'Helvetica Neue', Arial, Verdana, sans-serif;
            	font-size: 1.3em;
            	font-weight:200;
            }
            #footer{
            	margin-top:100px;
            	margin-bottom:100px;
            	max-width:1200px;
            	display:block;
            	margin-left:auto;
            	margin-right:auto;
            	font-family:'Helvetica Neue', Arial, Verdana, sans-serif;
            	font-size: 0.8em;
            	font-weight:200;
            	text-align:center;
            }
           /*FEEL FREE TO ADD YOUR PERSONAL CSS-CODE*/

           /*COPY END - after this you can remove the above CSS code*/

        </style>
    </head>
    <body>

		<div id="logo">
			<img src="{{@ CONSTRUCTR_BASE_URL @}}/THEMES/ASSETS/phaziz.png" alt="phaziz" />
		</div>

		<!--/*FEEL FREE TO ADD YOUR PERSONAL HTML-CODE AND MORE CONSTRUCTRCMS SMART TAGS*/-->

		<div id="content">
			{{@ PAGE_CONTENT_HTML @}}

			<?php
			
				$rss = new DOMDocument();
				
				/*UPDATE THE RSS-FEED URL HERE*/
				$rss->load('https://___YOUR___DOMAIN___.org/news/feed/');
				/*UPDATE THE RSS-FEED URL HERE*/

				$feed=[];
			
				foreach ($rss->getElementsByTagName('item') as $node)
				{
					$item=
					[ 
						'item' =>$node->getElementsByTagName('item')->item(0)->nodeValue,
						'category' =>$node->getElementsByTagName('category')->item(0)->nodeValue,
						'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
						'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
						'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
						'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
					];
					array_push($feed, $item);
				}
			
				$limit = 15;
			
				for($x=0;$x<$limit;$x++)
				{
					if($feed[$x]['title']!=''){
						$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
						$link = $feed[$x]['link'];
						$description = $feed[$x]['desc'];
						$date = date('l F d, Y', strtotime($feed[$x]['date']));
						echo '<div>';
						echo '<p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br />';
						echo '<small><em>'.$date.'</em></small></p>';
						echo '<p>'.$description.'</p>';
						echo '</div>';
					}
				}
			
			?>

		</div>
		
		<div id="footer">
			Proudly built with <a href="http://constructr-cms.org" target="_blank">ConstructrCMS</a> - A new Content Management System by <a href="http://phaziz.com" target="_blank">phaziz.com</a>! 
		</div>

        <script src="{{@ CONSTRUCTR_BASE_URL @}}/CONSTRUCTR-CMS/ASSETS/jquery/jquery-2.1.4.min.js"></script>
        <script>

        	$(function(){
        		{{@ PAGE_JS @}};
        	});
        </script>

    </body>
</html>