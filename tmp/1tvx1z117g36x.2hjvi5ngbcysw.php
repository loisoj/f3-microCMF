<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $LANG136; ?></title>
<link rel="stylesheet" href="<?php echo $CONSTRUCTR_BASE_URL; ?>/CONSTRUCTR-CMS/ASSETS/css/constructr_css_merged.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
img.intense{max-height:50px;max-width:auto;}
img.intense:hover{cursor:zoom-in;}
</style>
</head>
<body>
	<?php echo $this->render($NAVIGATION,$this->mime,get_defined_vars()); ?>
	<form action="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads" method="get" enctype="application/x-www-form-urlencoded">
		<div class="row">
			<div class="input-field col s12">
				<i class="material-icons prefix">search</i>
				<input placeholder="<?php echo $LANG137; ?>" name="needle" id="needle" type="text" value="<?php echo $ORIGIN_NEEDLE; ?>" required="required">
				<?php if ($ORIGIN_NEEDLE  != ''): ?>
					<label><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads"><?php echo $LANG343; ?></a></label>
				<?php endif; ?>
			</div>
		</div>
	</form>
    <div class="row">
        <div class="col s12">
            <p><strong><?php echo $FILES_COUNTR; ?> <?php echo $LANG138; ?></strong>&#160;|&#160;<input type="checkbox" id="pix" checked="checked" /> <label for="pix"><?php echo $LANG139; ?></label>&#160;&#160;&#160;<input type="checkbox" id="files" checked="checked" /> <label for="files"><?php echo $LANG140; ?></label>&#160;|&#160;<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads/new" class="waves-effect waves-light"><?php echo $LANG141; ?> +</a></p>
            <?php if ($NEW  == 'success'): ?>
				<div class="card-panel green darken-3 white-text"><?php echo $LANG142; ?></div>
            <?php endif; ?>
            <?php if ($NEW  == 'no-success'): ?>
				<div class="card-panel red darken-3 white-text"><?php echo $LANG143; ?></div>
            <?php endif; ?>
            <?php if ($DELETE  == 'success'): ?>
				<div class="card-panel green darken-3 white-text"><?php echo $LANG144; ?></div>
            <?php endif; ?>
            <?php if ($DELETE  == 'no-success'): ?>
				<div class="card-panel red darken-3 white-text"><?php echo $LANG145; ?></div>
            <?php endif; ?>
            <?php if ($EDIT  == 'success'): ?>
				<div class="card-panel green darken-3 white-text"><?php echo $LANG352; ?></div>
            <?php endif; ?>
            <?php if ($EDIT  == 'no-success'): ?>
				<div class="card-panel red darken-3 white-text"><?php echo $LANG353; ?></div>
            <?php endif; ?>
		</div>
	</div>
	<?php if ($PAGINATION_FILES): ?>
		
		    <table class="bordered hoverable" onmouseout="javascript:$('.tooltipped').tooltip('remove');">
		        <thead>
		            <tr>
		            <th data-field="Dateiname"><?php echo $LANG146; ?></th>
		            <th data-field="URL"><?php echo $LANG147; ?></th>
		            <th data-field="Aktionen" class="center-align" style="width:125px"><?php echo $LANG148; ?></th>
		            </tr>
		        </thead>
		        <tbody>
		            <?php foreach (($PAGINATION_FILES?:array()) as $PAGINATION_FILE): ?>
		            	<?php if (strrchr($PAGINATION_FILE,'#')  == '#true'): ?>
		            		
		            			<tr class="image_row">
		            		
		            		<?php else: ?>
		            			<tr class="file_row">
		            		
		            	<?php endif; ?>
			            	<?php if (strrchr($PAGINATION_FILE,'#')  == '#true'): ?>
			            		
			            			<td><img class="intense" src="<?php echo $CONSTRUCTR_BASE_URL; ?>/UPLOADS/<?php echo str_replace('#true','',$PAGINATION_FILE); ?>" alt="<?php echo str_replace('#true','',$PAGINATION_FILE); ?>" title="<?php echo str_replace('#true','',$PAGINATION_FILE); ?>"></td>
			            		
			            		<?php else: ?>
			            			<td><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/UPLOADS/<?php echo str_replace('#false','',$PAGINATION_FILE); ?>" target="_blank" class="tooltipped" data-position="right" data-delay="25" data-tooltip="<?php echo $LANG149; ?>"><?php echo str_replace('#false','',$PAGINATION_FILE); ?></a></td>
			            		
			            	<?php endif; ?>
			                <td><small><?php echo $CONSTRUCTR_BASE_URL; ?>/UPLOADS/<?php echo str_replace('#false','',str_replace('#true','',$PAGINATION_FILE)); ?></small></td>
			                <td class="center-align"><nobr>
			                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads/edit/<?php echo $OFFSET; ?>/<?php echo str_replace('#false','',str_replace('#true','',$PAGINATION_FILE)); ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG344; ?>" class="tooltipped"><i class="material-icons">mode_edit</i></a>
			                	&#160;
			                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/UPLOADS/<?php echo str_replace('#false','',str_replace('#true','',$PAGINATION_FILE)); ?>" target="_blank" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG150; ?>" class="tooltipped"><i class="material-icons">open_in_new</i></a>
			                	&#160;
			                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads/delete/<?php echo $OFFSET; ?>/<?php echo str_replace('#false','',str_replace('#true','',$PAGINATION_FILE)); ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG151; ?>" class="deleter tooltipped"><i class="material-icons">delete</i></a>
			                </nobr></td>
			            </tr>
			        <?php endforeach; ?>
		    	</tbody>
			</table>
		
	<?php endif; ?>
	<?php if ($SHOW_PAGINATION  == 'true'): ?>
		
			<div class="row">
				<div class="col s12 center-align">
					<?php echo html_entity_decode($PAGINATION_STRING); ?>

				</div>
			</div>
		
	<?php endif; ?>
	<script src="<?php echo $CONSTRUCTR_BASE_URL; ?>/CONSTRUCTR-CMS/ASSETS/js/constructr_js_merged.min.js"></script>
	<script src="<?php echo $CONSTRUCTR_BASE_URL; ?>/CONSTRUCTR-CMS/ASSETS/intense-images/intense.min.js"></script>
	<script>
		$(function(){
			var PIX=document.querySelectorAll('.intense');
			Intense(PIX);
			$('#needle').select().focus();
			$('.materialboxed').materialbox();
			$(".button-collapse").sideNav();
			$('#pix').bind('click',function(){
				if($(this).prop("checked")==true){
					$('.image_row').show();
				}else{
					$('.image_row').hide();
				}
			});
			$('#files').bind('click',function(){
				if($(this).prop("checked")==true){
					$('.file_row').show();
				} else {
					$('.file_row').hide();
				}
			});
            $('.deleter').click(function(e){
				e.preventDefault();
	            var U=$(this).attr('href');
	            vex.dialog.buttons.YES.text='<?php echo $LANGG0; ?>';
	            vex.dialog.buttons.NO.text='<?php echo $LANGG1; ?>';
	            vex.dialog.confirm({
	                className:'vex-theme-wireframe',
	                message:'<h5><?php echo $LANGG3; ?>:</h5><?php echo $LANG152; ?>',
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
