<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $LANG163; ?></title>
<link rel="stylesheet" href="<?php echo $CONSTRUCTR_BASE_URL; ?>/CONSTRUCTR-CMS/ASSETS/css/constructr_css_merged.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
	<?php echo $this->render($NAVIGATION,$this->mime,get_defined_vars()); ?>
    <div class="row">
        <div class="col s12">
            <?php if ($USER_COUNTR != 0): ?>
                
                    <p><strong><?php echo $USER_COUNTR; ?> <?php echo $LANG164; ?></strong>&#160;|&#160;<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/new" class="waves-effect waves-light"><?php echo $LANG165; ?> +</a></p>
                    <?php if ($EDIT  == 'success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG166; ?></div>
                    <?php endif; ?>
                    <?php if ($EDIT  == 'no-success'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG167; ?></div>
                    <?php endif; ?>
                    <?php if ($NEW  == 'success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG168; ?></div>
                    <?php endif; ?>
                    <?php if ($NEW  == 'no-success'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG169; ?></div>
                    <?php endif; ?>
                    <?php if ($DELETE  == 'success'): ?>
						<div class="card-panel green darken-3 white-text"><?php echo $LANG170; ?></div>
                    <?php endif; ?>
                    <?php if ($DELETE  == 'no-success'): ?>
						<div class="card-panel red darken-3 white-text"><?php echo $LANG171; ?></div>
                    <?php endif; ?>
                    <table class="bordered hoverable" onmouseout="javascript:$('.tooltipped').tooltip('remove');">
                        <thead>
                            <tr>
                            <th data-field="Benutzername"><?php echo $LANG172; ?></th>
                            <th data-field="eMail"><?php echo $LANG173; ?></th>
                            <th data-field="Aktionen" class="center-align" style="width:150px"><?php echo $LANG174; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (($USER?:array()) as $USER): ?>
                            <tr>
                                <td class="tooltipped users" data-position="right" data-delay="25" data-tooltip="<?php echo $LANG175; ?>''ID <?php echo $USER['constructr_user_id']; ?>''<?php echo $LANG176; ?>" id="user_id_@@@<?php echo $USER['constructr_user_id']; ?>">
                                    <small><?php echo html_entity_decode($USER['constructr_user_username']); ?></small>
                                </td>
                                <td><small><a class="tooltipped" data-position="top" data-delay="25" data-tooltip="<?php echo $LANG177; ?>" href="mailto:<?php echo $USER['constructr_user_email']; ?>"><?php echo $USER['constructr_user_email']; ?></a></small></td>
                                <td class="center-align">
                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/edit/<?php echo $USER['constructr_user_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG178; ?>''<?php echo html_entity_decode($USER['constructr_user_username']); ?>''<?php echo $LANG179; ?>" class="tooltipped"><i class="material-icons">mode_edit</i></a>
                                	&#160;
                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/edit-rights/<?php echo $USER['constructr_user_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG180; ?>''<?php echo html_entity_decode($USER['constructr_user_username']); ?>''<?php echo $LANG181; ?>" class="tooltipped"><i class="material-icons">security</i></a>
                                	&#160;
									<?php if ($USER['constructr_user_active']  == 1): ?>
										
											<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/deactivate/<?php echo $USER['constructr_user_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG182; ?>''<?php echo html_entity_decode($USER['constructr_user_username']); ?>''<?php echo $LANG183; ?>" class="tooltipped"><i class="material-icons">visibility</i></a>
										
										<?php else: ?>
		                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/activate/<?php echo $USER['constructr_user_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG184; ?>''<?php echo html_entity_decode($USER['constructr_user_username']); ?>''<?php echo $LANG185; ?>" class="tooltipped"><i class="material-icons">visibility_off</i></a>
										
									<?php endif; ?>
                                	&#160;
                                	<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/delete/<?php echo $USER['constructr_user_id']; ?>" data-position="left" data-delay="25" data-tooltip="<?php echo $LANG186; ?>''<?php echo html_entity_decode($USER['constructr_user_username']); ?>''<?php echo $LANG187; ?>" class="deleter tooltipped"><i class="material-icons">delete</i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                
                <?php else: ?>
                    <p><strong><?php echo $USER_COUNTR; ?> <?php echo $LANG164; ?></strong>&#160;|&#160;<a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/new" class="waves-effect waves-light"><?php echo $LANG165; ?> +</a></p>
                
            <?php endif; ?>
        </div>
    </div>
	<script src="<?php echo $CONSTRUCTR_BASE_URL; ?>/CONSTRUCTR-CMS/ASSETS/js/constructr_js_merged.min.js"></script>
    <script>
        $(function(){
            $(".button-collapse").sideNav();
            $('.users').dblclick(function(){
				var INFO=$(this).attr('id');
				INFO=INFO.split('@@@');
            	var USER_ID=INFO[1];
            	window.location='<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement/edit/'+USER_ID;
            });
            $('.deleter').click(function(e) {
				e.preventDefault();
	            var U=$(this).attr('href');
	            vex.dialog.buttons.YES.text='<?php echo $LANGG0; ?>';
	            vex.dialog.buttons.NO.text='<?php echo $LANGG1; ?>';
	            vex.dialog.confirm({
	                className:'vex-theme-wireframe',
	                message:'<h5><?php echo $LANGG3; ?>:</h5><?php echo $LANG189; ?>',
	                callback:function(value){
	                    if(value==true){
	                        $(location).attr('href',U);
	                    } else {
	                        return false;
	                    }
	                }
	            });
            });
            $(document).keyup(function(e){
			    if (e.which===27) javascript:history.back();
			});
            function autoBlinder(){
                $('.card-panel').fadeOut();
            }
            setInterval(autoBlinder,4500);
        });
    </script>
</body>
</html>
