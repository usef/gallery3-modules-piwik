<?php defined("SYSPATH") or die("No direct script access.") ?>  

<div class="g-block">
  <h1> <?= t("Piwik settings") ?> </h1>
  
  <div class="g-block-content">
    <!-- g-admin-comments-menu is not a correct name for this item! -->
<!--
    <div id="g-admin-comments-menu" class="ui-helper-clearfix">
    <php //$menu->render(); ?>
    </div>
-->
    <?= $form ?>
  </div>
</div>
