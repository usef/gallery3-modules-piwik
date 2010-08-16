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
class Piwik_Api_Model {

  /* Authentication token */
  protected $m_tokenAuth;
  
  /* After the constructor has been called, it contains also the basic REST connection string */
  protected $m_trackerUrl;

  /*
   * A list of site tracked by Piwik.
   * The list is cached inside the constructor
   *
   * [1] => Array
   *   (
   *     [idsite] => 1
   *     [name] => piwik.org
   *     [main_url] => http://piwik.org
   *     [ts_created] => 2010-01-14 01:57:14
   *     [timezone] => UTC+2
   *     [currency] => EUR
   *     [excluded_ips] => 
   *     [excluded_parameters] => 
   *     [feedburnerName] => 
   *   )
   */
  protected $m_aSites;



  public function __construct() {
    $this->m_trackerUrl = module::get_var("piwik", "installation_url", null);
    $this->m_tokenAuth  = module::get_var("piwik", "token_auth", null);

    /* Make encrypted http connections */
    //TODO reactivate this!!
    //$this->m_trackerUrl = substr_replace($this->m_trackerUrl, "https", 0, 4);

    /* Build basic REST connection string */
    if (substr($this->m_trackerUrl, -1) != "/")
      $this->m_trackerUrl .= "/";
    $this->m_trackerUrl   .= "index.php?module=API&format=php&token_auth=".$this->m_tokenAuth;

    /* Populate the sites list. This is cached once, here. */
    $this->m_aSites = array();
    $aIdList = $this->getAllSiteId();
    foreach($aIdList as $id)
      $this->m_aSites[$id] = $this->getSiteFromId($id);
  }


  /*
   * Returns an array with the list of SiteId tracked by Piwik
   */
  public function getAllSiteId() {
    $url = $this->m_trackerUrl . "&method=SitesManager.getAllSitesId";
    $data = $this->_fetchData($url);

    $reorderedData = array();
    foreach($data as $id)
      $reorderedData[] = $id[0];

    return $reorderedData;
  }

  /*
   * Returns an array with the list of SiteId tracked by Piwik
   */
  public function getSiteFromId($siteId) {
    $url = $this->m_trackerUrl . "&method=SitesManager.getSiteFromId&idSite=".$siteId;
    $data = $this->_fetchData($url);

    return $data[0];
  }

  /*
   * Returns the $m_aSites member field
   */
  public function getSites() {
    return $this->m_aSites;
  }




  /*
   * ##### Have care of this function!! #####
   */
  private function _fetchData($restUrl) {
    //TODO timeouted connections?
    $fetchedData = file_get_contents($restUrl);
    return unserialize($fetchedData);
  }

}
