<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Update.php 10/11/2013 11:35:21 scp@orilla $
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   TeamSpeak3
 * @version   1.1.23
 * @author    Sven 'ScP' Paulsen
 * @copyright Copyright (c) 2010 by Planet TeamSpeak. All rights reserved.
 */

/**
 * @class TeamSpeak3_Adapter_Update
 * @brief Provides methods to query the latest TeamSpeak 3 build numbers from the master server.
 */
class TeamSpeak3_Adapter_Update extends TeamSpeak3_Adapter_Abstract
{
  /**
   * The IPv4 address or FQDN of the TeamSpeak Systems update server.
   *
   * @var string
   */
  protected $default_host = "update.teamspeak.com";

  /**
   * The UDP port number of the TeamSpeak Systems update server.
   *
   * @var integer
   */
  protected $default_port = 17384;

  /**
   * Stores an array containing the latest build numbers (integer timestamps).
   *
   * @var array
   */
  protected $build_datetimes = null;

  /**
   * Stores an array containing the latest version strings.
   *
   * @var array
   */
  protected $version_strings = null;

  /**
   * Connects the TeamSpeak3_Transport_Abstract object and performs initial actions on the remote
   * server.
   *
   * @throws TeamSpeak3_Adapter_Update_Exception
   * @return void
   */
  public function syn()
  {
    if(!isset($this->options["host"]) || empty($this->options["host"])) $this->options["host"] = $this->default_host;
    if(!isset($this->options["port"]) || empty($this->options["port"])) $this->options["port"] = $this->default_port;

    $this->initTransport($this->options, "TeamSpeak3_Transport_UDP");
    $this->transport->setAdapter($this);

    TeamSpeak3_Helper_Profiler::init(spl_object_hash($this));

    $this->getTransport()->send(TeamSpeak3_Helper_String::fromHex(33));

    if(!preg_match_all("/,?(\d+)#([0-9a-zA-Z\._-]+),?/", $this->getTransport()->read(96), $matches) || !isset($matches[1]) || !isset($matches[2]))
    {
      throw new TeamSpeak3_Adapter_Update_Exception("invalid reply from the server");
    }

    $this->build_datetimes = $matches[1];
    $this->version_strings = $matches[2];

    TeamSpeak3_Helper_Signal::getInstance()->emit("updateConnected", $this);
  }

  /**
   * The TeamSpeak3_Adapter_Update destructor.
   *
   * @return void
   */
  public function __destruct()
  {
    if($this->getTransport() instanceof TeamSpeak3_Transport_Abstract && $this->getTransport()->isConnected())
    {
      $this->getTransport()->disconnect();
    }
  }

  /**
   * Returns the current build number for a specified update channel. Note that since version
   * 3.0.0, the build number represents an integer timestamp. $channel must be set to one of
   * the following values:
   *
   * - stable
   * - beta
   * - alpha
   * - server
   *
   * @param  string  $channel
   * @throws TeamSpeak3_Adapter_Update_Exception
   * @return integer
   */
  public function getRev($channel = "stable")
  {
    switch($channel)
    {
      case "stable":
        return isset($this->build_datetimes[0]) ? $this->build_datetimes[0] : null;

      case "beta":
        return isset($this->build_datetimes[1]) ? $this->build_datetimes[1] : null;

      case "alpha":
        return isset($this->build_datetimes[2]) ? $this->build_datetimes[2] : null;

      case "server":
        return isset($this->build_datetimes[3]) ? $this->build_datetimes[3] : null;

      default:
        throw new TeamSpeak3_Adapter_Update_Exception("invalid update channel identifier (" . $channel . ")");
    }
  }

  /**
   * Returns the current version string for a specified update channel. $channel must be set to
   * one of the following values:
   *
   * - stable
   * - beta
   * - alpha
   * - server
   *
   * @param  string  $channel
   * @throws TeamSpeak3_Adapter_Update_Exception
   * @return integer
   */
  public function getVersion($channel = "stable")
  {
    switch($channel)
    {
      case "stable":
        return isset($this->version_strings[0]) ? $this->version_strings[0] : null;

      case "beta":
        return isset($this->version_strings[1]) ? $this->version_strings[1] : null;

      case "alpha":
        return isset($this->version_strings[2]) ? $this->version_strings[2] : null;

      case "server":
        return isset($this->version_strings[3]) ? $this->version_strings[3] : null;

      default:
        throw new TeamSpeak3_Adapter_Update_Exception("invalid update channel identifier (" . $channel . ")");
    }
  }

  /**
   * Alias for getRev() using the 'stable' update channel.
   *
   * @param  string  $channel
   * @return integer
   */
  public function getClientRev()
  {
    return $this->getRev("stable");
  }

  /**
   * Alias for getRev() using the 'server' update channel.
   *
   * @param  string  $channel
   * @return integer
   */
  public function getServerRev()
  {
    return $this->getRev("server");
  }

  /**
   * Alias for getVersion() using the 'stable' update channel.
   *
   * @param  string  $channel
   * @return integer
   */
  public function getClientVersion()
  {
    return $this->getVersion("stable");
  }

  /**
   * Alias for getVersion() using the 'server' update channel.
   *
   * @param  string  $channel
   * @return integer
   */
  public function getServerVersion()
  {
    return $this->getVersion("server");
  }
}
