<div class="navbar-fixed mbt50">
<nav>
<div class="nav-wrapper grey darken-4">
<a href="#" data-activates="mobile" class="button-collapse"><i class="material-icons left">menu</i></a>
<ul class="left hide-on-med-and-down">
<li>&#160;&#160;&#160;</li>
<?php if ($ACT_VIEW  == 'pages'): ?>
<li class="active"><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement"><i class="material-icons left">view_quilt</i><?php echo $LANGN0; ?></a></li>
<?php else: ?><li><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement"><i class="material-icons left">view_quilt</i><?php echo $LANGN0; ?></a></li>
<?php endif; ?>
<?php if ($ACT_VIEW  == 'uploads'): ?>
<li class="active"><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads"><i class="material-icons left">photo</i><?php echo $LANGN1; ?></a></li>
<?php else: ?><li><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads"><i class="material-icons left">photo</i><?php echo $LANGN1; ?></a></li>
<?php endif; ?>
<?php if ($ACT_VIEW  == 'user'): ?>
<li class="active"><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement"><i class="material-icons left">portrait</i><?php echo $LANGN2; ?></a></li>
<?php else: ?><li><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement"><i class="material-icons left">portrait</i><?php echo $LANGN2; ?></a></li>
<?php endif; ?>
</ul>
<ul id="nav-mobile" class="right hide-on-med-and-down">
<li><a class="tooltipped" data-position="left" data-delay="25" data-tooltip="<?php echo $SESSION['username']; ?> <?php echo $LANGN3; ?>" href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/logout"><i class="material-icons">power_settings_new</i></a></li>
<li>&#160;&#160;&#160;</li>
</ul>
<ul class="side-nav" id="mobile">
<?php if ($ACT_VIEW  == 'pages'): ?>
<li class="active"><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement"><i class="material-icons left">view_quilt</i><?php echo $LANGN0; ?></a></li>
<?php else: ?><li><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/pagemanagement"><i class="material-icons left">view_quilt</i><?php echo $LANGN0; ?></a></li>
<?php endif; ?>
<?php if ($ACT_VIEW  == 'uploads'): ?>
<li class="active"><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads"><i class="material-icons left">photo</i><?php echo $LANGN1; ?></a></li>
<?php else: ?><li><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/uploads"><i class="material-icons left">photo</i><?php echo $LANGN1; ?></a></li>
<?php endif; ?>
<?php if ($ACT_VIEW  == 'user'): ?>
<li class="active"><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement"><i class="material-icons left">portrait</i><?php echo $LANGN2; ?></a></li>
<?php else: ?><li><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/usermanagement"><i class="material-icons left">portrait</i><?php echo $LANGN2; ?></a></li>
<?php endif; ?>
<li><a href="<?php echo $CONSTRUCTR_BASE_URL; ?>/constructr/logout"><i class="material-icons left">power_settings_new</i><?php echo $LANGN4; ?></a></li>
</ul>
</div>
</nav>
</div>
