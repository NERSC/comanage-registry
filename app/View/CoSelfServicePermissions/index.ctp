<!--
/**
 * COmanage Registry CO Self Service Permissions Index View
 *
 * Copyright (C) 2014 University Corporation for Advanced Internet Development, Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright     Copyright (C) 2014 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.9
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */-->
<?php
  $params = array('title' => $title_for_layout);
  print $this->element("pageTitle", $params);

  // Add breadcrumbs
  print $this->element("coCrumb");
  $this->Html->addCrumb(_txt('ct.co_self_service_permissions.pl'));

  // Add buttons to sidebar
  $sidebarButtons = $this->get('sidebarButtons');

  if($permissions['add']) {
    $sidebarButtons[] = array(
      'icon'    => 'circle-plus',
      'title'   => _txt('op.add-a',array(_txt('ct.co_self_service_permissions.1'))),
      'url'     => array(
        'controller' => 'co_self_service_permissions', 
        'action' => 'add', 
        'co' => $cur_co['Co']['id']
      )
    );
  }
  
  $this->set('sidebarButtons', $sidebarButtons);
?>

<div class="ui-state-highlight ui-corner-all co-info-topbox">
  <p>
    <span class="ui-icon ui-icon-info co-info"></span>
    <strong><?php print _txt('fd.ssp.default'); ?></strong>
  </p>
</div>
<br />

<table id="cous" class="ui-widget">
  <thead>
    <tr class="ui-widget-header">
      <th><?php print $this->Paginator->sort('model', _txt('fd.model')); ?></th>
      <th><?php print $this->Paginator->sort('type', _txt('fd.type')); ?></th>
      <th><?php print $this->Paginator->sort('permission', _txt('fd.perm')); ?></th>
      <th><?php print _txt('fd.actions'); ?></th>
    </tr>
  </thead>
  
  <tbody>
    <?php $i = 0; ?>
    <?php foreach ($co_self_service_permissions as $c): ?>
    <tr class="line<?php print ($i % 2)+1; ?>">
      <td>
        <?php
          print $this->Html->link($c['CoSelfServicePermission']['model'],
                                  array('controller' => 'co_self_service_permissions',
                                        'action' => ($permissions['edit'] ? 'edit' : ($permissions['view'] ? 'view' : '')),
                                        $c['CoSelfServicePermission']['id']));
        ?>
      </td>
      <td>
        <?php
          if(!empty($c['CoSelfServicePermission']['type'])) {
            print $types[ $c['CoSelfServicePermission']['model'] ][ $c['CoSelfServicePermission']['type'] ];
          }
        ?>
      </td>
      <td>
        <?php
          if(!empty($c['CoSelfServicePermission']['permission'])) {
            print _txt('en.permission', null, $c['CoSelfServicePermission']['permission']);
          }
        ?>
      </td>
      <td>
        <?php
          if($permissions['edit'])
            print $this->Html->link(_txt('op.edit'),
                                    array('controller' => 'co_self_service_permissions',
                                          'action' => 'edit',
                                          $c['CoSelfServicePermission']['id']),
                                    array('class' => 'editbutton')) . "\n";
            
          if($permissions['delete'])
            print '<button class="deletebutton" title="' . _txt('op.delete') . '" onclick="javascript:js_confirm_delete(\'' . _jtxt(Sanitize::html($c['CoSelfServicePermission']['model'])) . '\', \'' . $this->Html->url(array('controller' => 'co_self_service_permissions', 'action' => 'delete', $c['CoSelfServicePermission']['id'])) . '\')";>' . _txt('op.delete') . '</button>';
        ?>
        <?php ; ?>
      </td>
    </tr>
    <?php $i++; ?>
    <?php endforeach; ?>
  </tbody>
  
  <tfoot>
    <tr class="ui-widget-header">
      <th colspan="4">
        <?php print $this->Paginator->numbers(); ?>
      </th>
    </tr>
  </tfoot>
</table>
