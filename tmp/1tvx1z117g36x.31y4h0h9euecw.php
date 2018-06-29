<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $LANG38; ?></title>
<link rel="stylesheet" href="<?php echo $CONSTRUCTR_BASE_URL; ?>/CONSTRUCTR-CMS/ASSETS/css/constructr_css_merged.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<?php echo $this->render($NAVIGATION,$this->mime,get_defined_vars()); ?>
    <div class="row">
        <div class="col s12">
            <?php if (count($PAGES) != 0): ?>
                
                    <p><strong><?php echo count($PAGES); ?> <?php echo $LANG39; ?></strong>&#160;|&#160;<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/mr-cleaner"><?php echo $LANG368; ?></a>&#160;|&#160;<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/new" class="waves-effect waves-light"><?php echo $LANG40; ?> +</a></p>
                    <?php if ($MRCLEANER  =='success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG367; ?></div>
                    <?php endif; ?>
                    <?php if ($EDIT  =='success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG41; ?></div>
                    <?php endif; ?>
                    <?php if ($EDIT  == 'no-success'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG42; ?></div>
                    <?php endif; ?>
                    <?php if ($EDIT  == 'no-success-visibility-homepage'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG43; ?></div>
                    <?php endif; ?>
                    <?php if ($EDIT  == 'no-success-url'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG44; ?></div>
                    <?php endif; ?>
                    <?php if ($EDIT  == 'no-success-cnstrctr'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG45; ?></div>
                    <?php endif; ?>
                    <?php if ($NEW  == 'success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG46; ?></div>
                    <?php endif; ?>
                    <?php if ($NEW  == 'no-success'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG47; ?></div>
                    <?php endif; ?>
                    <?php if ($NEW  == 'no-success-url'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG48; ?></div>
                    <?php endif; ?>
                    <?php if ($NEW  == 'no-success-cnstrctr'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG49; ?></div>
                    <?php endif; ?>
                    <?php if ($DELETE  == 'success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG50; ?></div>
                    <?php endif; ?>
                    <?php if ($DELETE  == 'no-success'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG51; ?></div>
                    <?php endif; ?>
                    <?php if ($DELETE  == 'no-success-content-available'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG52; ?></div>
                    <?php endif; ?>
                    <?php if ($DELETE  == 'no-success-is-homepage'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG53; ?></div>
                    <?php endif; ?>
                    <?php if ($MOVE  == 'success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG54; ?></div>
                    <?php endif; ?>
                    <?php if ($MOVE  == 'no-success'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG55; ?></div>
                    <?php endif; ?>
                    <?php if ($MOVE  == 'no-success-homepage'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG56; ?></div>
                    <?php endif; ?>
                    <table class="bordered hoverable" onmouseout="javascript:$('.tooltipped').tooltip('remove');">
                        <thead>
                            <tr>
                            <th data-field="Name"><?php echo $LANG57; ?></th>
                            <th data-field="Name"><?php echo $LANG58; ?></th>
                            <th data-field="URL"><?php echo $LANG59; ?></th>
                            <th data-field="Template"><?php echo $LANG60; ?></th>
                            <th data-field="Aktionen" class="center-align" style="width:250px;"><?php echo $LANG61; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($PAGES?:array()) as $PAGE): ?>
                            <tr id="page_id_<?php echo $PAGE['constructr_pages_id']; ?>">
                                <td class="tooltipped pages" data-position="right" data-delay="25" data-tooltip="<?php echo $LANG62; ?>''ID <?php echo $PAGE['constructr_pages_id']; ?>''<?php echo $LANG63; ?>" id="page_id_@@@<?php echo $PAGE['constructr_pages_id']; ?>">
                                    <?php if ($PAGE['constructr_pages_level']  > 1): ?>
                                        
                                            <?php echo $levelIndicator($PAGE['constructr_pages_level']); ?>

                                        
                                    <?php endif; ?>
                                    <a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/content/<?php echo $PAGE['constructr_pages_id']; ?>" data-position="right" data-delay="25" data-tooltip="<?php echo $LANG64; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG65; ?>" class="tooltipped"><small><?php echo html_entity_decode($PAGE['constructr_pages_name']); ?></small></a>
                                </td>
                            	<td><small><?php echo $PAGE['constructr_pages_id']; ?></small></td>
                                <td><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/<?php echo $PAGE['constructr_pages_url']; ?>" target="_blank" data-position="top" data-delay="25" data-tooltip="<?php echo $LANG66; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''" class="tooltipped"><small><?php echo $CONSTRUCTR_BASE_URL; ?>/<?php echo $PAGE['constructr_pages_url']; ?></small></a>
                                </td>
                                <td><small><?php echo $PAGE['constructr_pages_template']; ?></small></td>
                                <td class="center-align"><nobr>
									<?php if ($PAGE['constructr_pages_order']  == 1): ?>
										
											&#160;&#160;&#160;&#160;&#160;&#160;&#160;
										
										<?php else: ?>
		                                	&#160;
		                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/move-up/<?php echo $PAGE['constructr_pages_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG67; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG68; ?>" class="tooltipped"><i class="material-icons">arrow_drop_up</i></a>
										
									<?php endif; ?>
									<?php if ($PAGE['constructr_pages_order']  ==  count($PAGES)): ?>
										
											&#160;&#160;&#160;&#160;&#160;&#160;&#160;
										
										<?php else: ?>
											<?php if ($PAGE['constructr_pages_order']  == 1): ?>
												
													&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;
												
												<?php else: ?>
				                                	&#160;
				                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/move-down/<?php echo $PAGE['constructr_pages_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG62; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG70; ?>" class="tooltipped"><i class="material-icons">arrow_drop_down</i></a>
												
											<?php endif; ?>
										
									<?php endif; ?>
									&#160;
									<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/content/<?php echo $PAGE['constructr_pages_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG71; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG72; ?>" class="tooltipped"><i class="material-icons">format_align_justify</i></a>
                                	&#160;
                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/edit/<?php echo $PAGE['constructr_pages_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG73; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG74; ?>" class="tooltipped"><i class="material-icons">mode_edit</i></a>
                                	&#160;
									<?php if ($PAGE['constructr_pages_nav_visible']  == 1): ?>
										
											<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/visibility/off/<?php echo $PAGE['constructr_pages_id']; ?>/<?php echo $PAGE['constructr_pages_order']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG77; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG78; ?>" class="tooltipped"><i class="material-icons">visibility</i></a>
										
										<?php else: ?>
		                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/visibility/on/<?php echo $PAGE['constructr_pages_id']; ?>/<?php echo $PAGE['constructr_pages_order']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG75; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG76; ?>" class="tooltipped"><i class="material-icons">visibility_off</i></a>
										
									<?php endif; ?>
									&#160;
                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/<?php echo $PAGE['constructr_pages_url']; ?>" target="_blank" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG79; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''" class="tooltipped"><i class="material-icons">open_in_new</i></a>
                                	&#160;
                                	<?php if ($PAGE['constructr_pages_order']  == 1): ?>
                                	
                                		&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;
                                	
                                	<?php else: ?>
	                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/delete/<?php echo $PAGE['constructr_pages_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG80; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG81; ?>" class="deleter tooltipped"><i class="material-icons">delete</i></a>
	                                	&#160;
	                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/deletewithcontent/<?php echo $PAGE['constructr_pages_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG82; ?>''<?php echo html_entity_decode($PAGE['constructr_pages_name']); ?>''<?php echo $LANG83; ?>" class="deleterC tooltipped"><i class="material-icons">cancel</i></a>
                                	
                                	<?php endif; ?>
                                </nobr></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                
                <?php else: ?>
                    <p><strong><?php echo count($PAGES); ?> <?php echo $LANG39; ?></strong>&#160;|&#160;<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/mr-cleaner"><?php echo $LANG368; ?></a>&#160;|&#160;<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/new" class="waves-effect waves-light"><?php echo $LANG40; ?> +</a></p>
                
            <?php endif; ?>
        </div>
    </div>
	<script src="<?php echo $CONSTRUCTR_BASE_URL; ?>/CONSTRUCTR-CMS/ASSETS/js/constructr_js_merged.min.js"></script>
    <script>
        $(function(){
        	$(".button-collapse").sideNav();
            $('.pages').dblclick(function(){
				var INFO=$(this).attr('id');
				INFO=INFO.split('@@@');
            	var PAGE_ID=INFO[1];
            	window.location='<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement/edit/'+PAGE_ID;
            });
            $('.deleterC').click(function(e) {
				e.preventDefault();
	            var U=$(this).attr('href');
	            vex.dialog.buttons.YES.text='<?php echo $LANGG0; ?>';
	            vex.dialog.buttons.NO.text='<?php echo $LANGG1; ?>';
	            vex.dialog.confirm({
	                className:'vex-theme-wireframe',
	                message:'<h5><?php echo $LANG88; ?>:</h5><?php echo $LANG86; ?>',
	                callback:function(value){
	                    if(value==true){
	                        $(location).attr('href',U);
	                    } else {
	                        return false;
	                    }
	                }
	            });
            });
            $('.deleter').click(function(e){
				e.preventDefault();
	            var U=$(this).attr('href');
	            vex.dialog.buttons.YES.text='<?php echo $LANGG0; ?>';
	            vex.dialog.buttons.NO.text='<?php echo $LANGG1; ?>';
	            vex.dialog.confirm({
	                className:'vex-theme-wireframe',
	                message:'<h5><?php echo $LANG88; ?>:</h5><?php echo $LANG87; ?>',
	                callback:function(value){
	                    if(value==true){
	                        $(location).attr('href',U);
	                    } else {
	                        return false;
	                    }
	                }
	            });
            });
            function autoBlinder(){
                $('.card-panel').fadeOut();
            }
            setInterval(autoBlinder,4500);
        });
    </script>
</body>
</html>
