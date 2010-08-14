<?php defined("SYSPATH") or die("No direct script access.") ?>  

<div class="g-block">
<?php
if($mode == piwik::basic_mode)
  echo "Plis, go away, nothing interesting for you here";
elseif($mode == piwik::advanced_mode)
  echo "Ook, plis wait some time for the graph!";
?> 
</div>
