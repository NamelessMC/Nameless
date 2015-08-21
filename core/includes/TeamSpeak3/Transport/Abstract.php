<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Abstract.php 10/11/2013 11:35:22 scp@orilla $
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
 * @class TeamSpeak3_Transport_Abstract
 * @brief Abstract class for connecting to a TeamSpeak 3 Server through different ways of transport.
 */
abstract class TeamSpeak3_Transport_Abstract
{
  /**
   * Stores user-provided configuration settings.
   *
   * @var array
   */
  protected $config = null;

  /**
   * Stores the stream resource of the connection.
   *
   * @var resource
   */
  protected $stream = null;

  /**
   * Stores the TeamSpeak3_Adapter_Abstract object using this transport.
   *
   * @var TeamSpeak3_Adapter_Abstract
   */
  protected $adapter = null;

  /**
   * The TeamSpeak3_Transport_Abstract constructor.
   *
   * @param  array $config
   * @throws TeamSpeak3_Transport_Exception
   * @return TeamSpeak3_Transport_Abstract
   */
  public function __construct(array $config)
  {
    if(!array_key_exists("host", $config))
    {
      throw new TeamSpeak3_Transport_Exception("config must have a key for 'host' which specifies the server host name");
    }

    if(!array_key_exists("port", $config))
    {
      throw new TeamSpeak3_Transport_Exception("config must have a key for 'port' which specifies the server port number");
    }

    if(!array_key_exists("timeout", $config))
    {
      $config["timeout"] = 10;
    }

    if(!array_key_exists("blocking", $config))
    {
      $config["blocking"] = 1;
    }

    $this->config = $config;
  }

  /**
   * Commit pending data.
   *
   * @return array
   */
  public function __sleep()
  {
    return array("config");
  }

  /**
   * Reconnects to the remote server.
   *
   * @return void
   */
  public function __wakeup()
  {
    $this->connect();
  }

  /**
   * The TeamSpeak3_Transport_Abstract destructor.
   *
   * @return void
   */
  public function __destruct()
  {
    if($this->adapter instanceof TeamSpeak3_Adapter_Abstract)
    {
      $this->adapter->__destruct();
    }

    $this->disconnect();
  }

  /**
   * Connects to a remote server.
   *
   * @throws TeamSpeak3_Transport_Exception
   * @return void
   */
  abstract public function connect();

  /**
   * Disconnects from a remote server.
   *
   * @return void
   */
  abstract public function disconnect();

  /**
   * Reads data from the stream.
   *
   * @param  integer $length
   * @throws TeamSpeak3_Transport_Exception
   * @return TeamSpeak3_Helper_String
   */
  abstract public function read($length = 4096);

  /**
   * Writes data to the stream.
   *
   * @param  string $data
   * @return void
   */
  abstract public function send($data);

  /**
   * Returns the underlying stream resource.
   *
   * @return resource
   */
  public function getStream()
  {
    return $this->stream;
  }

  /**
   * Returns the configuration variables in this adapter.
   *
   * @param  string $key
   * @param  mixed  $default
   * @return array
   */
  public function getConfig($key = null, $default = null)
  {
    if($key !== null)
    {
      return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
    }

    return $this->config;
  }

  /**
   * Sets the TeamSpeak3_Adapter_Abstract object using this transport.
   *
   * @param  TeamSpeak3_Adapter_Abstract $adapter
   * @return void
   */
  public function setAdapter(TeamSpeak3_Adapter_Abstract $adapter)
  {
    $this->adapter = $adapter;
  }

  /**
   * Returns the TeamSpeak3_Adapter_Abstract object using this transport.
   *
   * @return TeamSpeak3_Adapter_Abstract
   */
  public function getAdapter()
  {
    return $this->adapter;
  }

  /**
   * Returns the adapter type.
   *
   * @return string
   */
  public function getAdapterType()
  {
    if($this->adapter instanceof TeamSpeak3_Adapter_Abstract)
    {
      $string = TeamSpeak3_Helper_String::factory(get_class($this->adapter));

      return $string->substr($string->findLast("_"))->replace(array("_", " "), "")->toString();
    }

    return "Unknown";
  }

  /**
   * Returns header/meta data from stream pointer.
   *
   * @throws TeamSpeak3_Transport_Exception
   * @return array
   */
  public function getMetaData()
  {
    if($this->stream === null)
    {
      throw new TeamSpeak3_Transport_Exception("unable to retrieve header/meta data from stream pointer");
    }

    return stream_get_meta_data($this->stream);
  }

  /**
   * Returns TRUE if the transport is connected.
   *
   * @return boolean
   */
  public function isConnected()
  {
    return (is_resource($this->stream)) ? TRUE : FALSE;
  }

  /**
   * Blocks a stream until data is available for reading if the stream is connected
   * in non-blocking mode.
   *
   * @param  integer $time
   * @return void
   */
  protected function waitForReadyRead($time = 0)
  {
    if(!$this->isConnected() || $this->config["blocking"]) return;

    do {
      $read = array($this->stream);
      $null = null;

      if($time)
      {
        TeamSpeak3_Helper_Signal::getInstance()->emit(strtolower($this->getAdapterType()) . "WaitTimeout", $time, $this->getAdapter());
      }

      $time = $time+$this->config["timeout"];
    } while(@stream_select($read, $null, $null, $this->config["timeout"]) == 0);
  }
}
