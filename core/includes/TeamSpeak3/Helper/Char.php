<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Char.php 10/11/2013 11:35:21 scp@orilla $
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
 * @class TeamSpeak3_Helper_Char
 * @brief Helper class for char handling.
 */
class TeamSpeak3_Helper_Char
{
  /**
   * Stores the original character.
   *
   * @var string
   */
  protected $char = null;

  /**
   * The TeamSpeak3_Helper_Char constructor.
   *
   * @param  string $var
   * @throws TeamSpeak3_Helper_Exception
   * @return TeamSpeak3_Helper_Char
   */
  public function __construct($char)
  {
    if(strlen($char) != 1)
    {
      throw new TeamSpeak3_Helper_Exception("char parameter may not contain more or less than one character");
    }

    $this->char = strval($char);
  }

  /**
   * Returns true if the character is a letter.
   *
   * @return boolean
   */
  public function isLetter()
  {
    return ctype_alpha($this->char);
  }

  /**
   * Returns true if the character is a decimal digit.
   *
   * @return boolean
   */
  public function isDigit()
  {
    return ctype_digit($this->char);
  }

  /**
   * Returns true if the character is a space.
   *
   * @return boolean
   */
  public function isSpace()
  {
    return ctype_space($this->char);
  }

  /**
   * Returns true if the character is a mark.
   *
   * @return boolean
   */
  public function isMark()
  {
    return ctype_punct($this->char);
  }

  /**
   * Returns true if the character is a control character (i.e. "\t").
   *
   * @return boolean
   */
  public function isControl()
  {
    return ctype_cntrl($this->char);
  }

  /**
   * Returns true if the character is a printable character.
   *
   * @return boolean
   */
  public function isPrintable()
  {
    return ctype_print($this->char);
  }

  /**
   * Returns true if the character is the Unicode character 0x0000 ("\0").
   *
   * @return boolean
   */
  public function isNull()
  {
    return ($this->char === "\0") ? TRUE : FALSE;
  }

  /**
   * Returns true if the character is an uppercase letter.
   *
   * @return boolean
   */
  public function isUpper()
  {
    return ($this->char === strtoupper($this->char)) ? TRUE : FALSE;
  }

  /**
   * Returns true if the character is a lowercase letter.
   *
   * @return boolean
   */
  public function isLower()
  {
    return ($this->char === strtolower($this->char)) ? TRUE : FALSE;
  }

  /**
   * Returns the uppercase equivalent if the character is lowercase.
   *
   * @return TeamSpeak3_Helper_Char
   */
  public function toUpper()
  {
    return ($this->isUpper()) ? $this : new self(strtoupper($this));
  }

  /**
   * Returns the lowercase equivalent if the character is uppercase.
   *
   * @return TeamSpeak3_Helper_Char
   */
  public function toLower()
  {
    return ($this->isLower()) ? $this : new self(strtolower($this));
  }

  /**
   * Returns the ascii value of the character.
   *
   * @return integer
   */
  public function toAscii()
  {
    return ord($this->char);
  }

  /**
   * Returns the Unicode value of the character.
   *
   * @return integer
   */
  public function toUnicode()
  {
    $h = ord($this->char{0});

    if($h <= 0x7F)
    {
      return $h;
    }
    else if($h < 0xC2)
    {
      return FALSE;
    }
    else if($h <= 0xDF)
    {
      return ($h & 0x1F) << 6 | (ord($this->char{1}) & 0x3F);
    }
    else if($h <= 0xEF)
    {
      return ($h & 0x0F) << 12 | (ord($this->char{1}) & 0x3F) << 6 | (ord($this->char{2}) & 0x3F);
    }
    else if($h <= 0xF4)
    {
      return ($h & 0x0F) << 18 | (ord($this->char{1}) & 0x3F) << 12 | (ord($this->char{2}) & 0x3F) << 6 | (ord($this->char{3}) & 0x3F);
    }
    else
    {
      return FALSE;
    }
  }

  /**
   * Returns the hexadecimal value of the char.
   *
   * @return string
   */
  public function toHex()
  {
    return strtoupper(dechex($this->toAscii()));
  }

  /**
   * Returns the TeamSpeak3_Helper_Char based on a given hex value.
   *
   * @param  string $hex
   * @throws TeamSpeak3_Helper_Exception
   * @return TeamSpeak3_Helper_Char
   */
  public static function fromHex($hex)
  {
    if(strlen($hex) != 2)
    {
      throw new TeamSpeak3_Helper_Exception("given parameter '" . $hex . "' is not a valid hexadecimal number");
    }

    return new self(chr(hexdec($hex)));
  }

  /**
   * Returns the character as a standard string.
   *
   * @return string
   */
  public function toString()
  {
    return $this->char;
  }

  /**
   * Returns the integer value of the character.
   *
   * @return integer
   */
  public function toInt()
  {
    return intval($this->char);
  }

  /**
   * Returns the character as a standard string.
   *
   * @return string
   */
  public function __toString()
  {
    return $this->char;
  }
}
