<?php

namespace Drupal\node_generator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Class NodeGeneratorForm.
 */
class NodeGeneratorForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'node_generator_form';
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
    if($form_state->getValue('no_of_nodes') < 2) {
      $form_state->setErrorByName('no_of_nodes', t('Please enter value greater than 2'));
    }elseif ($form_state->getValue('no_of_nodes') > 10) {
      $form_state->setErrorByName('no_of_nodes', t('Please enter value smaller than 10'));
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
      'title' => 'Create node using custom module',
      'uid' => 1,
      'status' => TRUE,
    );
    for($i=0; $i<$n; $i++){
      $node = entity_create('node', $values);
      $node->save();
    }
  }

}
