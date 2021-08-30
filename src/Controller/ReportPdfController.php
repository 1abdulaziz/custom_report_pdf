<?php
namespace Drupal\custom_report_pdf\Controller;

use Mpdf\Mpdf;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides route responses for the Example module.
 */
class ReportPdfController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(LanguageManagerInterface $language_manager, EntityTypeManagerInterface $entity_type_manager, MessengerInterface $messenger, AccountInterface $currentUser) {
    $this->languageManager = $language_manager;
    $this->entityTypeManager = $entity_type_manager;
    $this->messenger = $messenger;
    $this->currentUser = $currentUser;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('language_manager'),
      $container->get('entity_type.manager'),
      $container->get('messenger'),
      $container->get('current_user'),

    );
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function reportPdf() {
    \Drupal::service('page_cache_kill_switch')->trigger();
    $language = $this->languageManager->getCurrentLanguage()->getId();
    $html = file_get_contents('https://snip.lndo.site/reportInfo');
    $mpdf = new Mpdf();
    $mpdf->WriteHTML($html);
    $mpdf->Output('reports.pdf', 'I');
    exit;
  }


  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function reportInfo() {
    $module_handler = \Drupal::service('module_handler');
    $module_path = $module_handler->getModule('custom_report_pdf')->getPath();
    $theme = \Drupal::theme()->getActiveTheme();
    \Drupal::service('page_cache_kill_switch')->trigger();

    $data = [
      'items' => "hi",
      'theme_path' => base_path() . $theme->getPath(),
      'module_path' => base_path() . $module_path,
    ];
    $build = [
      '#theme' => 'reportinfo',
      '#data' => $data,
      '#cache' => ['max-age' => 0],
    ];

    $output = \Drupal::service('renderer')->renderRoot($build);
    $response = new Response();
    $response->setContent($output);
    return $response;

  }

}