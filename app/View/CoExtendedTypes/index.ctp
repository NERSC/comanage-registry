<!--
/**
 * COmanage Registry CO Extended Type Index View
 *
 * Copyright (C) 2012-14 University Corporation for Advanced Internet Development, Inc.
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
 * @copyright     Copyright (C) 2012-14 University Corporation for Advanced Internet Development, Inc.
 * @link          http://www.internet2.edu/comanage COmanage Project
 * @package       registry
 * @since         COmanage Registry v0.6
 * @license       Apache License, Version 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version       $Id$
 */
-->
<?php
  $params = array('title' => $title_for_layout);
  print $this->element("pageTitle", $params);

  // Add breadcrumbs
  print $this->element("coCrumb");
  $this->Html->addCrumb(_txt('ct.co_extended_types.pl'));
  
  // Which attribute are we currently looking at? If not set, we'll default
  // to Identifier.type since that's what was specified in CoExtendedTypesController.
  $attr = 'Identifier.type';
  
  if(isset($this->request->query['attr'])) {
    $attr = Sanitize::html($this->request->query['attr']);
  }
?>
<!-- Selector for which Extended Type to manage -->
<form method="get" action="/registry/co_extended_types/index/co:<?php print $cur_co['Co']['id'] ?>">
  <span class="select-name"><?php print _txt('fd.et.forattr'); ?></span>
  <select name="attr" onchange="this.form.submit();">
    <?php foreach($vv_supported_attrs as $a => $label): ?>
    <option value="<?php print $a; ?>"<?php if($attr == $a) print " selected"; ?>><?php print $label; ?></option>
    <?php endforeach; ?>
  </select>
</form>
<br />

<?php
  if($permissions['add']) {
    print $this->Html->link(_txt('op.add-a',array(_txt('ct.co_extended_types.1'))),
                            array('controller' => 'co_extended_types',
                                  'action' => 'add',
                                  'co' => $cur_co['Co']['id'],
                                  '?' => array(
                                    'attr' => $attr
                                  )),
                            array('class' => 'addbutton')) . '
    ';
    
    print $this->Html->link(_txt('op.restore.types'),
                            array('controller' => 'co_extended_types',
                                  'action' => 'addDefaults',
                                  'co' => $cur_co['Co']['id'],
                                  '?' => array(
                                    // Strictly speaking, we don't need to pass attr as a query string,
                                    // but it's helpful because it will look like Model.field and if
                                    // that's at the end of the URL it will be parsed as a doctype (eg: .json).
                                    'attr' => $attr,
                                  )),
                            array('class' => 'addbutton')) . '
    <br />
    <br />
    ';
  }
?>

<table id="co_extended_types" class="ui-widget">
  <thead>
    <tr class="ui-widget-header">
      <th><?php print $this->Paginator->sort('attribute', _txt('fd.attribute')); ?></th>
      <th><?php print $this->Paginator->sort('name', _txt('fd.name')); ?></th>
      <th><?php print $this->Paginator->sort('display_name', _txt('fd.name.d')); ?></th>
      <th><?php print $this->Paginator->sort('status', _txt('fd.status')); ?></th>
      <th><?php print _txt('fd.actions'); ?></th>
    </tr>
  </thead>
  
  <tbody>
    <?php $i = 0; ?>
    <?php foreach ($co_extended_types as $c): ?>
    <tr class="line<?php print ($i % 2)+1; ?>">
      <td>
        <?php
          if(isset($vv_supported_attrs[ $c['CoExtendedType']['attribute'] ])) {
            print $vv_supported_attrs[ $c['CoExtendedType']['attribute'] ];
          } else {
            print Sanitize::html($c['CoExtendedType']['attribute']);
          }
        ?>
      </td>
      <td>
        <?php
          print $this->Html->link($c['CoExtendedType']['name'],
                                  array('controller' => 'co_extended_types',
                                        'action' => ($permissions['edit'] ? 'edit' : ($permissions['view'] ? 'view' : '')),
                                        $c['CoExtendedType']['id']));
        ?>
      </td>
      <td><?php print Sanitize::html($c['CoExtendedType']['display_name']); ?></td>
      <td><?php print _txt('en.status', null, $c['CoExtendedType']['status']); ?></td>
      <td>
        <?php
          if($permissions['edit']) {
            print $this->Html->link(_txt('op.edit'),
                                    array('controller' => 'co_extended_types',
                                          'action' => 'edit',
                                          $c['CoExtendedType']['id']),
                                    array('class' => 'editbutton')) . "\n";
          }
          
          if($c['CoExtendedType']['attribute'] != 'Name.type'
             || $c['CoExtendedType']['name'] != NameEnum::Official) {
            // NameEnum::Official is required and cannot be deleted (CO-955)
            
            if($permissions['delete']) {
              // We include attr in the request so we know where to redirect to when we're done
              print '<button class="deletebutton" title="' . _txt('op.delete') . '" onclick="javascript:js_confirm_delete(\'' . _jtxt(Sanitize::html($c['CoExtendedType']['name'])) . '\', \'' . $this->Html->url(array('controller' => 'co_extended_types', 'action' => 'delete', $c['CoExtendedType']['id'], '?' => array('attr' => $attr))) . '\')";>' . _txt('op.delete') . '</button>';
            }
          }
        ?>
        <?php ; ?>
      </td>
    </tr>
    <?php $i++; ?>
    <?php endforeach; ?>
  </tbody>
  
  <tfoot>
    <tr class="ui-widget-header">
      <th colspan="5">
        <?php print $this->Paginator->numbers(); ?>
      </th>
    </tr>
  </tfoot>
</table>
