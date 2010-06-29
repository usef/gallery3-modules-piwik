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
class Admin_Piwik_Controller extends Admin_Controller {
  public function index() {
    $view = new Admin_View("admin.html");
    $view->page_title = t("Piwik settings");
    $view->content = new View("admin_piwik.html");
    $view->content->form = $this->_get_admin_form();
    print $view;
  }

  public function save() {
    access::verify_csrf();
    $form = $this->_get_admin_form();

    try {
      $form->validate();
    }
    catch(Exception $e){
      message::error(t("Invalid settings"));
      url::redirect("admin/piwik");
    }
    module::set_var("piwik", "installation_url", $form->piwik_settings->installation_url->value);
    module::set_var("piwik", "site_id", $form->piwik_settings->site_id->value);
    message::success(t("Piwik settings updated"));
    url::redirect("admin/piwik");
  }

  private function _get_admin_form() {
    $form = new Forge("admin/piwik/save", "", "post", array("id" => "g-piwik-admin-form"));
    $piwik_settings = $form->group("piwik_settings")->label(t("Settings"));
    $piwik_settings->input("installation_url")->label(t('Piwik Installation Url (ie. www.your-piwik-installation.com)'))->rules("required|valid_url")->value(module::get_var("piwik", "installation_url"));
    $piwik_settings->input("site_id")->label(t('Site Id'))->rules("required|valid_digit")->value(module::get_var("piwik", "site_id"));
    $piwik_settings->submit("submit")->value(t("Save"));

    return $form;
  }
}
