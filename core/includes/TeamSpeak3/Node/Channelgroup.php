<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Channelgroup.php 10/11/2013 11:35:21 scp@orilla $
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
 * @class TeamSpeak3_Node_Channelgroup
 * @brief Class describing a TeamSpeak 3 channel group and all it's parameters.
 */
class TeamSpeak3_Node_Channelgroup extends TeamSpeak3_Node_Abstract
{
  /**
   * The TeamSpeak3_Node_Channelgroup constructor.
   *
   * @param  TeamSpeak3_Node_Server $server
   * @param  array  $info
   * @param  string $index
   * @throws TeamSpeak3_Adapter_ServerQuery_Exception
   * @return TeamSpeak3_Node_Channelgroup
   */
  public function __construct(TeamSpeak3_Node_Server $server, array $info, $index = "cgid")
  {
    $this->parent = $server;
    $this->nodeInfo = $info;

    if(!array_key_exists($index, $this->nodeInfo))
    {
      throw new TeamSpeak3_Adapter_ServerQuery_Exception("invalid groupID", 0xA00);
    }

    $this->nodeId = $this->nodeInfo[$index];
  }

  /**
   * Renames the channel group specified.
   *
   * @param  string $name
   * @return void
   */
  public function rename($name)
  {
    return $this->getParent()->channelGroupRename($this->getId(), $name);
  }

  /**
   * Deletes the channel group. If $force is set to TRUE, the channel group will be
   * deleted even if there are clients within.
   *
   * @param  boolean $force
   * @return void
   */
  public function delete($force = FALSE)
  {
    $this->getParent()->channelGroupDelete($this->getId(), $force);

    unset($this);
  }

  /**
   * Creates a copy of the channel group and returns the new groups ID.
   *
   * @param  string  $name
   * @param  integer $tcgid
   * @param  integer $type
   * @return integer
   */
  public function copy($name = null, $tcgid = 0, $type = TeamSpeak3::GROUP_DBTYPE_REGULAR)
  {
    return $this->getParent()->channelGroupCopy($this->getId(), $name, $tcgid, $type);
  }

  /**
   * Returns a list of permissions assigned to the channel group.
   *
   * @param  boolean $permsid
   * @return array
   */
  public function permList($permsid = FALSE)
  {
    return $this->getParent()->channelGroupPermList($this->getId(), $permsid);
  }

  /**
   * Adds a set of specified permissions to the channel group. Multiple permissions
   * can be added by providing the two parameters of each permission in separate arrays.
   *
   * @param  integer $permid
   * @param  integer $permvalue
   * @return void
   */
  public function permAssign($permid, $permvalue)
  {
    return $this->getParent()->channelGroupPermAssign($this->getId(), $permid, $permvalue);
  }

  /**
   * Alias for permAssign().
   *
   * @deprecated
   */
  public function permAssignByName($permname, $permvalue)
  {
    return $this->permAssign($permname, $permvalue);
  }

  /**
   * Removes a set of specified permissions from the channel group. Multiple
   * permissions can be removed at once.
   *
   * @param  integer $permid
   * @return void
   */
  public function permRemove($permid)
  {
    return $this->getParent()->channelGroupPermRemove($this->getId(), $permid);
  }

  /**
   * Alias for permAssign().
   *
   * @deprecated
   */
  public function permRemoveByName($permname)
  {
    return $this->permRemove($permname);
  }

  /**
   * Returns a list of clients assigned to the server group specified.
   *
   * @return array
   */
  public function clientList()
  {
    return $this->getParent()->channelGroupClientList($this->getId());
  }

  /**
   * Alias for privilegeKeyCreate().
   *
   * @deprecated
   */
  public function tokenCreate($cid, $description = null, $customset = null)
  {
    return $this->privilegeKeyCreate($cid, $description, $customset);
  }

  /**
   * Creates a new privilege key (token) for the channel group and returns the key.
   *
   * @param  integer $cid
   * @param  string  $description
   * @param  string  $customset
   * @return TeamSpeak3_Helper_String
   */
  public function privilegeKeyCreate($cid, $description = null, $customset = null)
  {
    return $this->getParent()->privilegeKeyCreate(TeamSpeak3::TOKEN_CHANNELGROUP, $this->getId(), $cid, $description, $customset);
  }

  /**
   * Sends a text message to all clients residing in the channel group on the virtual server.
   *
   * @param  string $msg
   * @return void
   */
  public function message($msg)
  {
    foreach($this as $client)
    {
      try
      {
        $this->execute("sendtextmessage", array("msg" => $msg, "target" => $client, "targetmode" => TeamSpeak3::TEXTMSG_CLIENT));
      }
      catch(TeamSpeak3_Adapter_ServerQuery_Exception $e)
      {
        /* ERROR_client_invalid_id */
        if($e->getCode() != 0x0200) throw $e;
      }
    }
  }

  /**
   * Downloads and returns the channel groups icon file content.
   *
   * @return TeamSpeak3_Helper_String
   */
  public function iconDownload()
  {
    if($this->iconIsLocal("iconid") || $this["iconid"] == 0) return;

    $download = $this->getParent()->transferInitDownload(rand(0x0000, 0xFFFF), 0, $this->iconGetName("iconid"));
    $transfer = TeamSpeak3::factory("filetransfer://" . $download["host"] . ":" . $download["port"]);

    return $transfer->download($download["ftkey"], $download["size"]);
  }

  /**
   * @ignore
   */
  protected function fetchNodeList()
  {
    $this->nodeList = array();

    foreach($this->getParent()->clientList() as $client)
    {
      if($client["client_channel_group_id"] == $this->getId())
      {
        $this->nodeList[] = $client;
      }
    }
  }

  /**
   * Returns a unique identifier for the node which can be used as a HTML property.
   *
   * @return string
   */
  public function getUniqueId()
  {
    return $this->getParent()->getUniqueId() . "_cg" . $this->getId();
  }

  /**
   * Returns the name of a possible icon to display the node object.
   *
   * @return string
   */
  public function getIcon()
  {
    return "group_channel";
  }

  /**
   * Returns a symbol representing the node.
   *
   * @return string
   */
  public function getSymbol()
  {
    return "%";
  }

  /**
   * Returns a string representation of this node.
   *
   * @return string
   */
  public function __toString()
  {
    return (string) $this["name"];
  }
}

