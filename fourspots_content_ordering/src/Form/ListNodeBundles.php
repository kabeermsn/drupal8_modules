<?php

namespace Drupal\fourspots_content_ordering\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ListNodeBundles.
 * @author Kabeer. M <kabeer@4spots.com>
 */
class ListNodeBundles extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'fourspots_content_ordering.listnodebundles',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'list_node_bundles';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('fourspots_content_ordering.listnodebundles');

    $nodeTypeObjects = \Drupal::entityTypeManager()
    ->getStorage('node_type')
    ->loadMultiple();

    $nodeTypes = array();
    $defaultNodeType = '';
    foreach ($nodeTypeObjects as $key => $value) {
      $bundleFields = array_keys(\Drupal::entityManager()->getFieldDefinitions('node', $key));
      if(in_array('field_content_weight', $bundleFields)) {
        $defaultNodeType = ($defaultNodeType == '')? $key:$defaultNodeType;
        $nodeTypes[$key] = $value->get('name');
      }
    }

    $form['type'] = array(
    '#title' => t('Content Type'),
    '#type' => 'select',
    '#default_value' => $defaultNodeType,
    '#options' => $nodeTypes,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nodeType = $form_state->getValue('type');
    $form_state->setRedirect('fourspots_content_ordering.node_order_form', array('nodeType' => $nodeType));
    return;
  }

}
