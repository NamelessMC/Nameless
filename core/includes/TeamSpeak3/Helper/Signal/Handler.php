<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Handler.php 10/11/2013 11:35:21 scp@orilla $
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
 * @class TeamSpeak3_Helper_Signal_Handler
 * @brief Helper class providing handler functions for signals.
 */
class TeamSpeak3_Helper_Signal_Handler
{
  /**
   * Stores the name of the subscribed signal.
   *
   * @var string
   */
  protected $signal = null;

  /**
   * Stores the callback function for the subscribed signal.
   *
   * @var mixed
   */
  protected $callback = null;

  /**
   * The TeamSpeak3_Helper_Signal_Handler constructor.
   *
   * @param  string $signal
   * @param  mixed  $callback
   * @throws TeamSpeak3_Helper_Signal_Exception
   * @return TeamSpeak3_Helper_Signal_Handler
   */
  public function __construct($signal, $callback)
  {
    $this->signal = (string) $signal;

    if(!is_callable($callback))
    {
      throw new TeamSpeak3_Helper_Signal_Exception("invalid callback specified for signal '" . $signal . "'");
    }

    $this->callback = $callback;
  }

  /**
   * Invoke the signal handler.
   *
   * @param  array $args
   * @return mixed
   */
  public function call(array $args = array())
  {
    return call_user_func_array($this->callback, $args);
  }
}
