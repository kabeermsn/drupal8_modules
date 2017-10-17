<?php

namespace Drupal\fourspots_content_ordering\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NodeOrderForm.
 * @author Kabeer. M <kabeer@4spots.com>
 */
class NodeOrderForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'fourspots_content_ordering.nodeorder',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'node_order_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $nodeType = NULL) {
    // $config = $this->config('fourspots_content_ordering.nodeorder');
    $form['nodeTable'] = array(
      '#type' => 'table',
      '#header' => array(t('title'), t('Node Id'), t('Weight')),
      '#empty' => t('There are no items yet'),
      '#tabledrag' => array(
        array(
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'nodetable-order-weight',
          ),
        ),
      );


    $query = \Drupal::entityQuery('node')
    ->condition('type', $nodeType)
    ->condition('status', 1)
    ->sort('field_content_weight' , 'ASC');
    $result = $query->execute();
    $count = count($result);
    $nodeObjects = \Drupal::entityManager()->getStorage('node')->loadMultiple($result);

    $weight = 0;
    foreach ($nodeObjects as $key => $node) {
      $id = $node->nid->value;
      $form['nodeTable'][$id]['#attributes']['class'][] = 'draggable';
      $weight = ($node->field_content_weight->value)? $weight:$weight++;
      $form['nodeTable'][$id]['#weight'] = $weight;

      $form['nodeTable'][$id]['label'] = array(
        '#plain_text' => $node->title->value,
        );

      $form['nodeTable'][$id]['id'] = array(
        '#plain_text' => $id,
        );


      $form['nodeTable'][$id]['weight'] = array(
        '#type' => 'weight',
        '#title' => t('Weight for @title', array('@title' => 'Node Order')),
        '#title_display' => 'invisible',
        '#default_value' => $weight,
        '#delta' => $count,
        '#attributes' => array('class' => array('nodetable-order-weight')),
        );
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $nodeOrderTable = $form_state->getValue('nodeTable');
    $order = 1;
    foreach ($nodeOrderTable as $nid => $weight) {
      $node = \Drupal::entityManager()->getStorage('node')->load($nid);
      $node->field_content_weight->value = $order;
      $order++;
      $node->save();
    }
    drupal_set_message('Content Ordered Succesfully');
    return;
  }

}
