<?php
namespace Drupal\custom_report_pdf\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the Example module.
 */
class ReportPdfController extends ControllerBase {

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function reportPdf() {
    return [
      '#markup' => 'Hello, world',
    ];
  }

}