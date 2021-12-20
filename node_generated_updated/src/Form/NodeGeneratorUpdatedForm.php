<?php

namespace Drupal\node_generated_updated\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class NodeGeneratorUpdatedForm.
 */
class NodeGeneratorUpdatedForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'node_generator_updated_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $content_types = \Drupal\node\Entity\NodeType::loadMultiple();
    $options = [];
    foreach($content_types as $content_type){
      $options[$content_type->id()] = $content_type->label();
    }
    $form['content_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Content Types'),
      '#options' => $options,
    ];
    $form['no_of_nodes'] = [
      '#type' => 'number',
      '#title' => $this->t('Enter the number of nodes'),
    ];
    $form['node_title'] = [
      '#type' => 'textfield',
      '#title' => 'Node Title'
    ];
    $form['node_body'] = [
      '#type' => 'textarea',
      '#title' => 'Node Body'
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if($form_state->getValue('no_of_nodes') < 0) {
      $form_state->setErrorByName('no_of_nodes', t('Please enter value greater than 0'));
    }elseif ($form_state->getValue('no_of_nodes') > 5) {
      $form_state->setErrorByName('no_of_nodes', t('Please enter value smaller than 5'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    $n = $form_state->getValue('no_of_nodes');
    $values = array(
      'nid' => NULL,
      'type' => $form_state->getValue('content_type'),
      'title' => $form_state->getValue('node_title'),
      'body' => $form_state->getValue('node_body'),
      'uid' => 1,
      'status' => TRUE,
    );
    for($i=0; $i<$n; $i++){
      $node = entity_create('node', $values);
      $node->save();
    }
    drupal_set_message(t('Nodes generated successfully'));
  }

}
