<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2010 Bharat Mediratta
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA.
 */
class piwik_theme {
  static function page_bottom($theme) {
    $piwik_url = module::get_var("piwik", "installation_url");
    $site_id = module::get_var("piwik", "site_id");
    if (!$piwik_url || !$site_id) {
      return;
    }

    $piwik_code = '
    <!-- Piwik -->
    <script type="text/javascript">
      var pkBaseURL = (("https:" == document.location.protocol) ? "https://'.$piwik_url.'/" : "http://'.$piwik_url.'/");
      document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
    </script><script type="text/javascript">
      try {
        var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", '.$site_id.');
        piwikTracker.trackPageView();
        piwikTracker.enableLinkTracking();
      } catch( err ) {}
    </script><noscript><p><img src="http://'.$piwik_url.'/piwik.php?idsite='.$site_id.'" style="border:0" alt="" /></p></noscript>
    <!-- End Piwik Tag -->

    ';

    return $piwik_code;
  }
}
