<?php

namespace Drupal\user_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormInterface;

/**
 * Provides a 'punch' block.
 *
 * @Block(
 *   id = "punch_block",
 *   admin_label = @Translation("Punch block"),
 *   category = @Translation("Punch block")
 * )
 */
class PunchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\user_module\Form\PunchForm');

    return $form;
   }
}
