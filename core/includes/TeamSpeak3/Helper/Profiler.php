<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Profiler.php 10/11/2013 11:35:21 scp@orilla $
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
 * @class TeamSpeak3_Helper_Profiler
 * @brief Helper class for profiler handling.
 */
class TeamSpeak3_Helper_Profiler
{
  /**
   * Stores various timers for code profiling.
   *
   * @var array
   */
  protected static $timers = array();

  /**
   * Inits a timer.
   *
   * @param  string $name
   * @return void
   */
  public static function init($name = "default")
  {
    self::$timers[$name] = new TeamSpeak3_Helper_Profiler_Timer($name);
  }

  /**
   * Starts a timer.
   *
   * @param  string $name
   * @return void
   */
  public static function start($name = "default")
  {
    if(array_key_exists($name, self::$timers))
    {
      self::$timers[$name]->start();
    }
    else
    {
      self::$timers[$name] = new TeamSpeak3_Helper_Profiler_Timer($name);
    }
  }

  /**
   * Stops a timer.
   *
   * @param  string $name
   * @return void
   */
  public static function stop($name = "default")
  {
    if(!array_key_exists($name, self::$timers))
    {
      self::init($name);
    }

    self::$timers[$name]->stop();
  }

  /**
   * Returns a timer.
   *
   * @param  string $name
   * @return TeamSpeak3_Helper_Profiler_Timer
   */
  public static function get($name = "default")
  {
    if(!array_key_exists($name, self::$timers))
    {
      self::init($name);
    }

    return self::$timers[$name];
  }
}
