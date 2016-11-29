<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Text.php 10/11/2013 11:35:22 scp@orilla $
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
 * @class TeamSpeak3_Viewer_Html
 * @brief Renders nodes used in HTML-based TeamSpeak 3 viewers.
 */
class TeamSpeak3_Viewer_Html implements TeamSpeak3_Viewer_Interface
{
  /**
   * A pre-defined pattern used to display a node in a TeamSpeak 3 viewer.
   *
   * @var string
   */
  protected $pattern = "<table id='%0' class='%1' summary='%2'><tr class='%3'><td class='%4'>%5</td><td class='%6' title='%7'>%8 %9</td><td class='%10'>%11%12</td></tr></table>\n";

  /**
   * The TeamSpeak3_Node_Abstract object which is currently processed.
   *
   * @var TeamSpeak3_Node_Abstract
   */
  protected $currObj = null;

  /**
   * An array filled with siblingsfor the  TeamSpeak3_Node_Abstract object which is currently
   * processed.
   *
   * @var array
   */
  protected $currSib = null;

  /**
   * An internal counter indicating the number of fetched TeamSpeak3_Node_Abstract objects.
   *
   * @var integer
   */
  protected $currNum = 0;

  /**
   * The relative URI path where the images used by the viewer can be found.
   *
   * @var string
   */
  protected $iconpath = null;

  /**
   * The relative URI path where the country flag icons used by the viewer can be found.
   *
   * @var string
   */
  protected $flagpath = null;

  /**
   * The relative path of the file transter client script on the server.
   *
   * @var string
   */
  protected $ftclient = null;

  /**
   * Stores an array of local icon IDs.
   *
   * @var array
   */
  protected $cachedIcons = array(100, 200, 300, 400, 500, 600);

  /**
   * Stores an array of remote icon IDs.
   *
   * @var array
   */
  protected $remoteIcons = array();

  /**
   * The TeamSpeak3_Viewer_Html constructor.
   *
   * @param  string $iconpath
   * @param  string $flagpath
   * @param  string $ftclient
   * @param  string $pattern
   * @return void
   */
  public function __construct($iconpath = "images/viewer/", $flagpath = null, $ftclient = null, $pattern = null)
  {
    $this->iconpath = $iconpath;
    $this->flagpath = $flagpath;
    $this->ftclient = $ftclient;

    if($pattern)
    {
      $this->pattern = $pattern;
    }
  }

  /**
   * Returns the code needed to display a node in a TeamSpeak 3 viewer.
   *
   * @param  TeamSpeak3_Node_Abstract $node
   * @param  array $siblings
   * @return string
   */
  public function fetchObject(TeamSpeak3_Node_Abstract $node, array $siblings = array())
  {
    $this->currObj = $node;
    $this->currSib = $siblings;

    $args = array(
      $this->getContainerIdent(),
      $this->getContainerClass(),
      $this->getContainerSummary(),
      $this->getRowClass(),
      $this->getPrefixClass(),
      $this->getPrefix(),
      $this->getCorpusClass(),
      $this->getCorpusTitle(),
      $this->getCorpusIcon(),
      $this->getCorpusName(),
      $this->getSuffixClass(),
      $this->getSuffixIcon(),
      $this->getSuffixFlag(),
    );

    return TeamSpeak3_Helper_String::factory($this->pattern)->arg($args);
  }

  /**
   * Returns a unique identifier for the current node which can be used as a HTML id
   * property.
   *
   * @return string
   */
  protected function getContainerIdent()
  {
    return $this->currObj->getUniqueId();
  }

  /**
   * Returns a dynamic string for the current container element which can be used as
   * a HTML class property.
   *
   * @return string
   */
  protected function getContainerClass()
  {
    return "ts3_viewer " . $this->currObj->getClass(null);
  }
  
  /**
   * Returns the ID of the current node which will be used as a summary element for
   * the container element.
   *
   * @return integer
   */
  protected function getContainerSummary()
  {
    return $this->currObj->getId();
  }

  /**
   * Returns a dynamic string for the current row element which can be used as a HTML
   * class property.
   *
   * @return string
   */
  protected function getRowClass()
  {
    return ++$this->currNum%2 ? "row1" : "row2";
  }

  /**
   * Returns a string for the current prefix element which can be used as a HTML class
   * property.
   *
   * @return string
   */
  protected function getPrefixClass()
  {
    return "prefix " . $this->currObj->getClass(null);
  }

  /**
   * Returns the HTML img tags to display the prefix of the current node.
   *
   * @return string
   */
  protected function getPrefix()
  {
    $prefix = "";

    if(count($this->currSib))
    {
      $last = array_pop($this->currSib);

      foreach($this->currSib as $sibling)
      {
        $prefix .=  ($sibling) ? $this->getImage("tree_line.gif") : $this->getImage("tree_blank.png");
      }

      $prefix .= ($last) ? $this->getImage("tree_end.gif") : $this->getImage("tree_mid.gif");
    }

    return $prefix;
  }

  /**
   * Returns a string for the current corpus element which can be used as a HTML class
   * property. If the current node is a channel spacer the class string will contain
   * additional class names to allow further customization of the content via CSS.
   *
   * @return string
   */
  protected function getCorpusClass()
  {
    $extras = "";

    if($this->currObj instanceof TeamSpeak3_Node_Channel && $this->currObj->isSpacer())
    {
      switch($this->currObj->spacerGetType())
      {
        case (string) TeamSpeak3::SPACER_SOLIDLINE:
          $extras .= " solidline";
          break;

        case (string) TeamSpeak3::SPACER_DASHLINE:
          $extras .= " dashline";
          break;

        case (string) TeamSpeak3::SPACER_DASHDOTLINE:
          $extras .= " dashdotline";
          break;

        case (string) TeamSpeak3::SPACER_DASHDOTDOTLINE:
          $extras .= " dashdotdotline";
          break;

        case (string) TeamSpeak3::SPACER_DOTLINE:
          $extras .= " dotline";
          break;
      }

      switch($this->currObj->spacerGetAlign())
      {
        case TeamSpeak3::SPACER_ALIGN_CENTER:
          $extras .= " center";
          break;

        case TeamSpeak3::SPACER_ALIGN_RIGHT:
          $extras .= " right";
          break;

        case TeamSpeak3::SPACER_ALIGN_LEFT:
          $extras .= " left";
          break;
      }
    }

    return "corpus " . $this->currObj->getClass(null) . $extras;
  }

  /**
   * Returns the HTML img tags which can be used to display the various icons for a
   * TeamSpeak_Node_Abstract object.
   *
   * @return string
   */
  protected function getCorpusTitle()
  {
    if($this->currObj instanceof TeamSpeak3_Node_Server)
    {
      return "ID: " . $this->currObj->getId() . " | Clients: " . $this->currObj->clientCount() . "/" . $this->currObj["virtualserver_maxclients"] . " | Uptime: " . TeamSpeak3_Helper_Convert::seconds($this->currObj["virtualserver_uptime"]);
    }
    elseif($this->currObj instanceof TeamSpeak3_Node_Channel && !$this->currObj->isSpacer())
    {
      return "ID: " . $this->currObj->getId() . " | Codec: " . TeamSpeak3_Helper_Convert::codec($this->currObj["channel_codec"]) . " | Quality: " . $this->currObj["channel_codec_quality"];
    }
    elseif($this->currObj instanceof TeamSpeak3_Node_Client)
    {
      return "ID: " . $this->currObj->getId() . " | Version: " . TeamSpeak3_Helper_Convert::versionShort($this->currObj["client_version"]) . " | Platform: " . $this->currObj["client_platform"];
    }
    elseif($this->currObj instanceof TeamSpeak3_Node_Servergroup || $this->currObj instanceof TeamSpeak3_Node_Channelgroup)
    {
      return "ID: " . $this->currObj->getId() . " | Type: " . TeamSpeak3_Helper_Convert::groupType($this->currObj["type"]) . " (" . ($this->currObj["savedb"] ? "Permanent" : "Temporary") . ")";
    }
  }

  /**
   * Returns a HTML img tag which can be used to display the status icon for a
   * TeamSpeak_Node_Abstract object.
   *
   * @return string
   */
  protected function getCorpusIcon()
  {
    if($this->currObj instanceof TeamSpeak3_Node_Channel && $this->currObj->isSpacer()) return;

    return $this->getImage($this->currObj->getIcon() . ".png");
  }

  /**
   * Returns a string for the current corpus element which contains the display name
   * for the current TeamSpeak_Node_Abstract object.
   *
   * @return string
   */
  protected function getCorpusName()
  {
    if($this->currObj instanceof TeamSpeak3_Node_Channel && $this->currObj->isSpacer())
    {
      if($this->currObj->spacerGetType() != TeamSpeak3::SPACER_CUSTOM) return;

      $string = $this->currObj["channel_name"]->section("]", 1, 99);

      if($this->currObj->spacerGetAlign() == TeamSpeak3::SPACER_ALIGN_REPEAT)
      {
        $string->resize(30, $string);
      }

      return htmlspecialchars($string);
    }

    if($this->currObj instanceof TeamSpeak3_Node_Client)
    {
      $before = array();
      $behind = array();

      foreach($this->currObj->memberOf() as $group)
      {
        if($group->getProperty("namemode") == TeamSpeak3::GROUP_NAMEMODE_BEFORE)
        {
          $before[] = "[" . htmlspecialchars($group["name"]) . "]";
        }
        elseif($group->getProperty("namemode") == TeamSpeak3::GROUP_NAMEMODE_BEHIND)
        {
          $behind[] = "[" . htmlspecialchars($group["name"]) . "]";
        }
      }

      return implode("", $before) . " " . htmlspecialchars($this->currObj) . " " . implode("", $behind);
    }

    return htmlspecialchars($this->currObj);
  }

  /**
   * Returns a string for the current suffix element which can be used as a HTML
   * class property.
   *
   * @return string
   */
  protected function getSuffixClass()
  {
    return "suffix " . $this->currObj->getClass(null);
  }

  /**
   * Returns the HTML img tags which can be used to display the various icons for a
   * TeamSpeak_Node_Abstract object.
   *
   * @return string
   */
  protected function getSuffixIcon()
  {
    if($this->currObj instanceof TeamSpeak3_Node_Server)
    {
      return $this->getSuffixIconServer();
    }
    elseif($this->currObj instanceof TeamSpeak3_Node_Channel)
    {
      return $this->getSuffixIconChannel();
    }
    elseif($this->currObj instanceof TeamSpeak3_Node_Client)
    {
      return $this->getSuffixIconClient();
    }
  }

  /**
   * Returns the HTML img tags which can be used to display the various icons for a
   * TeamSpeak_Node_Server object.
   *
   * @return string
   */
  protected function getSuffixIconServer()
  {
    $html = "";

    if($this->currObj["virtualserver_icon_id"])
    {
      if(!$this->currObj->iconIsLocal("virtualserver_icon_id") && $this->ftclient)
      {
        if(!isset($this->cacheIcon[$this->currObj["virtualserver_icon_id"]]))
        {
          $download = $this->currObj->transferInitDownload(rand(0x0000, 0xFFFF), 0, $this->currObj->iconGetName("virtualserver_icon_id"));

          if($this->ftclient == "data:image")
          {
            $download = TeamSpeak3::factory("filetransfer://" . $download["host"] . ":" . $download["port"])->download($download["ftkey"], $download["size"]);
          }

          $this->cacheIcon[$this->currObj["virtualserver_icon_id"]] = $download;
        }
        else
        {
          $download = $this->cacheIcon[$this->currObj["virtualserver_icon_id"]];
        }

        if($this->ftclient == "data:image")
        {
          $html .= $this->getImage("data:" . TeamSpeak3_Helper_Convert::imageMimeType($download) . ";base64," . base64_encode($download), "Server Icon", null, FALSE);
        }
        else
        {
          $html .= $this->getImage($this->ftclient . "?ftdata=" . base64_encode(serialize($download)), "Server Icon", null, FALSE);
        }
      }
      elseif(in_array($this->currObj["virtualserver_icon_id"], $this->cachedIcons))
      {
        $html .= $this->getImage("group_icon_" . $this->currObj["virtualserver_icon_id"] . ".png", "Server Icon");
      }
    }

    return $html;
  }

  /**
   * Returns the HTML img tags which can be used to display the various icons for a
   * TeamSpeak_Node_Channel object.
   *
   * @return string
   */
  protected function getSuffixIconChannel()
  {
    if($this->currObj instanceof TeamSpeak3_Node_Channel && $this->currObj->isSpacer()) return;

    $html = "";

    if($this->currObj["channel_flag_default"])
    {
      $html .= $this->getImage("channel_flag_default.png", "Default Channel");
    }

    if($this->currObj["channel_flag_password"])
    {
      $html .= $this->getImage("channel_flag_password.png", "Password-protected");
    }

    if($this->currObj["channel_codec"] == TeamSpeak3::CODEC_CELT_MONO || $this->currObj["channel_codec"] == TeamSpeak3::CODEC_OPUS_MUSIC)
    {
      $html .= $this->getImage("channel_flag_music.png", "Music Codec");
    }

    if($this->currObj["channel_needed_talk_power"])
    {
      $html .= $this->getImage("channel_flag_moderated.png", "Moderated");
    }

    if($this->currObj["channel_icon_id"])
    {
      if(!$this->currObj->iconIsLocal("channel_icon_id") && $this->ftclient)
      {
        if(!isset($this->cacheIcon[$this->currObj["channel_icon_id"]]))
        {
          $download = $this->currObj->getParent()->transferInitDownload(rand(0x0000, 0xFFFF), 0, $this->currObj->iconGetName("channel_icon_id"));

          if($this->ftclient == "data:image")
          {
            $download = TeamSpeak3::factory("filetransfer://" . $download["host"] . ":" . $download["port"])->download($download["ftkey"], $download["size"]);
          }

          $this->cacheIcon[$this->currObj["channel_icon_id"]] = $download;
        }
        else
        {
          $download = $this->cacheIcon[$this->currObj["channel_icon_id"]];
        }

        if($this->ftclient == "data:image")
        {
          $html .= $this->getImage("data:" . TeamSpeak3_Helper_Convert::imageMimeType($download) . ";base64," . base64_encode($download), "Channel Icon", null, FALSE);
        }
        else
        {
          $html .= $this->getImage($this->ftclient . "?ftdata=" . base64_encode(serialize($download)), "Channel Icon", null, FALSE);
        }
      }
      elseif(in_array($this->currObj["channel_icon_id"], $this->cachedIcons))
      {
        $html .= $this->getImage("group_icon_" . $this->currObj["channel_icon_id"] . ".png", "Channel Icon");
      }
    }

    return $html;
  }

  /**
   * Returns the HTML img tags which can be used to display the various icons for a
   * TeamSpeak_Node_Client object.
   *
   * @return string
   */
  protected function getSuffixIconClient()
  {
    $html = "";

    if($this->currObj["client_is_priority_speaker"])
    {
      $html .= $this->getImage("client_priority.png", "Priority Speaker");
    }

    if($this->currObj["client_is_channel_commander"])
    {
      $html .= $this->getImage("client_cc.png", "Channel Commander");
    }

    if($this->currObj["client_is_talker"])
    {
      $html .= $this->getImage("client_talker.png", "Talk Power granted");
    }
    elseif($cntp = $this->currObj->getParent()->channelGetById($this->currObj["cid"])->channel_needed_talk_power)
    {
      if($cntp > $this->currObj["client_talk_power"])
      {
        $html .= $this->getImage("client_mic_muted.png", "Insufficient Talk Power");
      }
    }

    foreach($this->currObj->memberOf() as $group)
    {
      if(!$group["iconid"]) continue;

      $type = ($group instanceof TeamSpeak3_Node_Servergroup) ? "Server Group" : "Channel Group";

      if(!$group->iconIsLocal("iconid") && $this->ftclient)
      {
        if(!isset($this->cacheIcon[$group["iconid"]]))
        {
          $download = $group->getParent()->transferInitDownload(rand(0x0000, 0xFFFF), 0, $group->iconGetName("iconid"));

          if($this->ftclient == "data:image")
          {
            $download = TeamSpeak3::factory("filetransfer://" . $download["host"] . ":" . $download["port"])->download($download["ftkey"], $download["size"]);
          }

          $this->cacheIcon[$group["iconid"]] = $download;
        }
        else
        {
          $download = $this->cacheIcon[$group["iconid"]];
        }

        if($this->ftclient == "data:image")
        {
          $html .= $this->getImage("data:" . TeamSpeak3_Helper_Convert::imageMimeType($download) . ";base64," . base64_encode($download), $group . " [" . $type . "]", null, FALSE);
        }
        else
        {
          $html .= $this->getImage($this->ftclient . "?ftdata=" . base64_encode(serialize($download)), $group . " [" . $type . "]", null, FALSE);
        }
      }
      elseif(in_array($group["iconid"], $this->cachedIcons))
      {
        $html .= $this->getImage("group_icon_" . $group["iconid"] . ".png", $group . " [" . $type . "]");
      }
    }

    if($this->currObj["client_icon_id"])
    {
      if(!$this->currObj->iconIsLocal("client_icon_id") && $this->ftclient)
      {
        if(!isset($this->cacheIcon[$this->currObj["client_icon_id"]]))
        {
          $download = $this->currObj->getParent()->transferInitDownload(rand(0x0000, 0xFFFF), 0, $this->currObj->iconGetName("client_icon_id"));

          if($this->ftclient == "data:image")
          {
            $download = TeamSpeak3::factory("filetransfer://" . $download["host"] . ":" . $download["port"])->download($download["ftkey"], $download["size"]);
          }

          $this->cacheIcon[$this->currObj["client_icon_id"]] = $download;
        }
        else
        {
          $download = $this->cacheIcon[$this->currObj["client_icon_id"]];
        }

        if($this->ftclient == "data:image")
        {
          $html .= $this->getImage("data:" . TeamSpeak3_Helper_Convert::imageMimeType($download) . ";base64," . base64_encode($download), "Client Icon", null, FALSE);
        }
        else
        {
          $html .= $this->getImage($this->ftclient . "?ftdata=" . base64_encode(serialize($download)), "Client Icon", null, FALSE);
        }
      }
      elseif(in_array($this->currObj["client_icon_id"], $this->cachedIcons))
      {
        $html .= $this->getImage("group_icon_" . $this->currObj["client_icon_id"] . ".png", "Client Icon");
      }
    }

    return $html;
  }

  /**
   * Returns a HTML img tag which can be used to display the country flag for a
   * TeamSpeak_Node_Client object.
   *
   * @return string
   */
  protected function getSuffixFlag()
  {
    if(!$this->currObj instanceof TeamSpeak3_Node_Client) return;

    if($this->flagpath && $this->currObj["client_country"])
    {
      return $this->getImage($this->currObj["client_country"]->toLower() . ".png", $this->currObj["client_country"], null, FALSE, TRUE);
    }
  }

  /**
   * Returns the code to display a custom HTML img tag.
   *
   * @param  string  $name
   * @param  string  $text
   * @param  string  $class
   * @param  boolean $iconpath
   * @param  boolean $flagpath
   * @return string
   */
  protected function getImage($name, $text = "", $class = null, $iconpath = TRUE, $flagpath = FALSE)
  {
    $src = "";

    if($iconpath)
    {
      $src = $this->iconpath;
    }

    if($flagpath)
    {
      $src = $this->flagpath;
    }

    return "<img src='" . $src . $name . "' title='" . $text . "' alt='' align='top' />";
  }
}
