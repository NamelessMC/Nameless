<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: Servergroup.php 10/11/2013 11:35:21 scp@orilla $
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
 * @class TeamSpeak3_Node_Servergroup
 * @brief Class describing a TeamSpeak 3 server group and all it's parameters.
 */
class TeamSpeak3_Node_Servergroup extends TeamSpeak3_Node_Abstract
{
  /**
   * The TeamSpeak3_Node_Servergroup constructor.
   *
   * @param  TeamSpeak3_Node_Server $server
   * @param  array  $info
   * @param  string $index
   * @throws TeamSpeak3_Node_Exception
   * @return TeamSpeak3_Node_Servergroup
   */
  public function __construct(TeamSpeak3_Node_Server $server, array $info, $index = "sgid")
  {
    $this->parent = $server;
    $this->nodeInfo = $info;

    if(!array_key_exists($index, $this->nodeInfo))
    {
      throw new TeamSpeak3_Node_Exception("invalid groupID", 0xA00);
    }

    $this->nodeId = $this->nodeInfo[$index];
  }

  /**
   * Renames the server group specified.
   *
   * @param  string  $name
   * @return void
   */
  public function rename($name)
  {
    return $this->getParent()->serverGroupRename($this->getId(), $name);
  }

  /**
   * Deletes the server group. If $force is set to 1, the server group will be
   * deleted even if there are clients within.
   *
   * @param  boolean $force
   * @return void
   */
  public function delete($force = FALSE)
  {
    $this->getParent()->serverGroupDelete($this->getId(), $force);

    unset($this);
  }

  /**
   * Creates a copy of the server group and returns the new groups ID.
   *
   * @param  string  $name
   * @param  integer $tsgid
   * @param  integer $type
   * @return integer
   */
  public function copy($name = null, $tsgid = 0, $type = TeamSpeak3::GROUP_DBTYPE_REGULAR)
  {
    return $this->getParent()->serverGroupCopy($this->getId(), $name, $tsgid, $type);
  }

  /**
   * Returns a list of permissions assigned to the server group.
   *
   * @param  boolean $permsid
   * @return array
   */
  public function permList($permsid = FALSE)
  {
    return $this->getParent()->serverGroupPermList($this->getId(), $permsid);
  }

  /**
   * Adds a set of specified permissions to the server group. Multiple permissions
   * can be added by providing the four parameters of each permission in separate arrays.
   *
   * @param  integer $permid
   * @param  integer $permvalue
   * @param  integer $permnegated
   * @param  integer $permskip
   * @return void
   */
  public function permAssign($permid, $permvalue, $permnegated = FALSE, $permskip = FALSE)
  {
    return $this->getParent()->serverGroupPermAssign($this->getId(), $permid, $permvalue, $permnegated, $permskip);
  }

  /**
   * Alias for permAssign().
   *
   * @deprecated
   */
  public function permAssignByName($permname, $permvalue, $permnegated = FALSE, $permskip = FALSE)
  {
    return $this->permAssign($permname, $permvalue, $permnegated, $permskip);
  }

  /**
   * Removes a set of specified permissions from the server group. Multiple
   * permissions can be removed at once.
   *
   * @param  integer $permid
   * @return void
   */
  public function permRemove($permid)
  {
    return $this->getParent()->serverGroupPermRemove($this->getId(), $permid);
  }

  /**
   * Alias for permRemove().
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
    return $this->getParent()->serverGroupClientList($this->getId());
  }

  /**
   * Adds a client to the server group specified. Please note that a client cannot be
   * added to default groups or template groups.
   *
   * @param  integer $cldbid
   * @return void
   */
  public function clientAdd($cldbid)
  {
    return $this->getParent()->serverGroupClientAdd($this->getId(), $cldbid);
  }

  /**
   * Removes a client from the server group.
   *
   * @param  integer $cldbid
   * @return void
   */
  public function clientDel($cldbid)
  {
    return $this->getParent()->serverGroupClientDel($this->getId(), $cldbid);
  }

  /**
   * Alias for privilegeKeyCreate().
   *
   * @deprecated
   */
  public function tokenCreate($description = null, $customset = null)
  {
    return $this->privilegeKeyCreate($description, $customset);
  }

  /**
   * Creates a new privilege key (token) for the server group and returns the key.
   *
   * @param  string  $description
   * @param  string  $customset
   * @return TeamSpeak3_Helper_String
   */
  public function privilegeKeyCreate($description = null, $customset = null)
  {
    return $this->getParent()->privilegeKeyCreate(TeamSpeak3::TOKEN_SERVERGROUP, $this->getId(), 0, $description, $customset);
  }

  /**
   * Sends a text message to all clients residing in the server group on the virtual server.
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
   * Downloads and returns the server groups icon file content.
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
      if(in_array($this->getId(), explode(",", $client["client_servergroups"])))
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
    return $this->getParent()->getUniqueId() . "_sg" . $this->getId();
  }

  /**
   * Returns the name of a possible icon to display the node object.
   *
   * @return string
   */
  public function getIcon()
  {
    return "group_server";
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

