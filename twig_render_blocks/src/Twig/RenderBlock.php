<?php

/**
 * @file
 * Contains \Drupal\twig_blocks\Twig\RenderBlock.
 * @author Kabeer, M <kabeer@4spots.com>
 */

namespace Drupal\twig_render_blocks\Twig;

/**
 * Adds extension to render a menu
 */
class RenderBlock extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'twig_render_block';
  }

  public function getFunctions() {
    return [
      new \Twig_SimpleFunction(
        'twig_render_block',
        [$this, 'render_block'],
        ['is_safe' => ['html']]
      ),
    ];
  }

  /**
   * Provides function to programmatically rendering a block.
   *
   * @param String $block_id
   *   The machine id of the block to render
   */
  public function render_block($block_id) {
    $block_manager = \Drupal::service('plugin.manager.block');
    $config = [];
    $plugin_block = $block_manager->createInstance($block_id, $config);
    $render = $plugin_block->build();
    return render($render);
  }
}
