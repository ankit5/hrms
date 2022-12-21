<?php

namespace Drupal\user_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'Approval' block.
 *
 * @Block(
 *   id = "approval_block",
 *   admin_label = @Translation("Approval block"),
 *   category = @Translation("Approval block")
 * )
 */
class ApprovalBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\user_module\Form\ApprovalForm');
    
    return $form;
   }
}
