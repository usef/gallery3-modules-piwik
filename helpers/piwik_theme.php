<?php defined("SYSPATH") or die("No direct script access.");
/**
 * Gallery - a web based photo album viewer and editor
 * Copyright (C) 2000-2010 Bharat Mediratta
 *
 * Gallery3 Piwik Module
 * Copyright (C) 2010 Yusef Maali
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
    $trackingCode = module::get_var("piwik", "tracking_code");

    if(empty($trackingCode)) {
      $siteId      = module::get_var("piwik", "site_id");
      $trackingUrl = module::get_var("piwik", "installation_url");

      /* Piwik tracking code needs URLs without the http header */
      if (substr($trackingUrl, 0, 4) == "http")
        $trackingUrl = substr($trackingUrl, strpos($trackingUrl, "://") + 3);

      if (empty($trackingUrl) || empty($siteId))
        return;

      $trackingCode = '
      <!-- Piwik code inserted by Piwik Analytics Gallery3 plugin by Yusef Maali -->
      <script type="text/javascript">
        var pkBaseURL = (("https:" == document.location.protocol) ? "https://'.$trackingUrl.'/" : "http://'.$trackingUrl.'/");
        document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
      </script><script type="text/javascript">
        try {
          var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", '.$siteId.');
          piwikTracker.trackPageView();
          piwikTracker.enableLinkTracking();
        } catch( err ) {}
      </script><noscript><p><img src="http://'.$trackingUrl.'/piwik.php?idsite='.$siteId.'" style="border:0" alt="" /></p></noscript>
      <!-- End Piwik Tag -->

      ';  
    }

    return $trackingCode;
  }
}
