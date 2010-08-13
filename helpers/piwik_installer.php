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
class piwik_installer_Core {
  static function install() {
    module::set_var("piwik", "installation_url", "www.your-piwik-installation.com");
    module::set_var("piwik", "site_id", 0);
    module::set_version("piwik", 1);
  }

  static function uninstall() {
    module::delete("piwik");
    module::clear_var("piwik", "installation_url");
    module::clear_var("piwik", "site_id");
  }
}
