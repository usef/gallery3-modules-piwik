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

  /*
   * If called without parameters, checks for the enabled working mode
   * and redirect to the appropriate page
   */
  public function index($target = null) {
    if(is_null($target)) {
      if (module::get_var("piwik", "enabled_mode") == piwik::advanced_mode)
        url::redirect("admin/piwik/advanced_settings");
      else
        url::redirect("admin/piwik/basic_settings");
    }

    if($target == piwik::advanced_mode)
      url::redirect("admin/piwik/advanced_settings");
    else
      url::redirect("admin/piwik/basic_settings");
  }

  /*
   * Shows the basic settings page
   */
  public function basic_settings() {
    $view = new Admin_View("admin.html");
    $view->page_title = t("Piwik settings");
    $view->content = new View("admin_piwik.html");
    $view->content->menu = $this->_get_admin_menu();
    $view->content->form = $this->_get_admin_basic_form();
    print $view;
  }
  /*
   * Shows the advanced settings page
   */
  public function advanced_settings() {
    $view = new Admin_View("admin.html");
    $view->page_title = t("Piwik settings");
    $view->content = new View("admin_piwik.html");
    $view->content->menu = $this->_get_admin_menu();
    $view->content->form = $this->_get_admin_advanced_form();
    print $view;
  }


  /*
   * This is the destination of form submissions.
   * It validates and saves the data
   *
   * $form_type contains the form type (basic, advanced, ...)
   */
  public function save_settings($form_type) {
    access::verify_csrf();
    
    $form = null;
    if ($form_type == piwik::basic_mode)
      $form = $this->_get_admin_basic_form();
    elseif ($form_type == piwik::advanced_mode)
      $form = $this->_get_admin_advanced_form();
    else {
      message::error(t("Invalid settings"));
      url::redirect("admin/piwik");
    }

    /* When validating, the Forge library will load the submitted form value */
    $form->validate();

    /* Piwik tracking code needs URLs without the http header */
    $trackingUrl = $form->piwik_settings->installation_url->value;
    if (substr($trackingUrl, 0, 4) == "http")
      $trackingUrl = substr($trackingUrl, strpos($trackingUrl, "://") + 3);
    module::set_var("piwik", "installation_url", $trackingUrl);


    if ($form_type == piwik::basic_mode) {
      module::set_var("piwik", "site_id", $form->piwik_settings->site_id->value);
      module::set_var("piwik", "enabled_mode", piwik::basic_mode);
    }
    elseif ($form_type == piwik::advanced_mode) {
      module::set_var("piwik", "token_auth", $form->piwik_settings->token_auth->value);
      module::set_var("piwik", "enabled_mode", piwik::advanced_mode);
    }

    message::success(t("Piwik settings updated"));
    url::redirect("admin/piwik");
  }


  /*
   * Creates the navigation menu inside the admin page
   */
  private function _get_admin_menu() {
    $menu = Menu::factory("root");
    $menu->append(
      Menu::factory("link")
        ->id("basic_settings")
        ->label(t("Basic Settings"))
        ->url(url::site("admin/piwik/index/".piwik::basic_mode))
      );
    $menu->append(
      Menu::factory("link")
        ->id("advanced_settings")
        ->label(t("Advanced Settings"))
        ->url(url::site("admin/piwik/index/".piwik::advanced_mode))
      );

    return $menu;
  }

  /*
   * Creates the basic settings form
   */
  private function _get_admin_basic_form() {
    $form = new Forge("admin/piwik/save_settings/".piwik::basic_mode, "", "post", array("id" => "g-piwik-admin-form"));
    $piwik_settings = $form->group("piwik_settings")->label(t("Basic Tracking Settings"));
    $piwik_settings
       ->input("installation_url")
       ->label(t('Piwik Installation Url'))
       ->rules("required")
       ->value(module::get_var("piwik", "installation_url"));
    $piwik_settings
       ->input("site_id")
       ->label(t('Site Id'))
       ->rules("required|valid_digit")
       ->value(module::get_var("piwik", "site_id"));
    $piwik_settings
       ->submit("submit")
       ->value(t("Save"));

    return $form;
  }

  /*
   * Create the advanced settings form
   */
  private function _get_admin_advanced_form() {
    $form = new Forge("admin/piwik/save_settings/".piwik::advanced_mode, "", "post", array("id" => "g-piwik-admin-form"));
    $piwik_settings = $form->group("piwik_settings")->label(t("Advanced Piwik Settings"));
    $piwik_settings
       ->input("installation_url")
       ->label(t('Piwik Installation Url'))
       ->rules("required")
       ->value(module::get_var("piwik", "installation_url"));
    $piwik_settings
       ->input("token_auth")
       ->label(t('Authentication Token'))
       ->rules("required")
       ->value(module::get_var("piwik", "token_auth"));
    $piwik_settings
       ->submit("submit")
       ->value(t("Save"));

    return $form;
  }
}
