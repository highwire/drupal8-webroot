<?php

namespace Drupal\xhprof\Controller;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\xhprof\ProfilerInterface;
use Drupal\xhprof\XHProfLib\Report\ReportConstants;
use Drupal\xhprof\XHProfLib\Report\ReportEngine;
use Drupal\xhprof\XHProfLib\Report\ReportInterface;
use Drupal\xhprof\XHProfLib\Run;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Displays profiling results.
 */
class XHProfController extends ControllerBase {

  /**
   * The profiler.
   *
   * @var \Drupal\xhprof\ProfilerInterface
   */
  private $profiler;

  /**
   * The report engine.
   *
   * @var \Drupal\xhprof\XHProfLib\Report\ReportEngine
   */
  private $reportEngine;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('xhprof.profiler'),
      $container->get('xhprof.report_engine')
    );
  }

  /**
   * @param \Drupal\xhprof\ProfilerInterface $profiler
   * @param \Drupal\xhprof\XHProfLib\Report\ReportEngine $reportEngine
   */
  public function __construct(ProfilerInterface $profiler, ReportEngine $reportEngine) {
    $this->profiler = $profiler;
    $this->reportEngine = $reportEngine;
  }

  /**
   * Returns list of runs.
   *
   * @return array
   *   A render array.
   */
  public function runsAction() {
    $runs = $run = $this->profiler->getStorage()->getRuns();

    // Table attributes
    $attributes = array('id' => 'xhprof-runs-table');

    // Table header
    $header = array();
    $header[] = array('data' => t('View'));
    $header[] = array('data' => t('File size'));
    $header[] = array('data' => t('Path'), 'field' => 'path');
    $header[] = array('data' => t('Date'), 'field' => 'date', 'sort' => 'desc');

    // Table rows
    $rows = array();
    foreach ($runs as $run) {
      $row = array();
      $row[] = $this->l($run['run_id'], new Url('xhprof.run', array('run' => $run['run_id'])));
      $row[] = format_size($run['size']);
      $row[] = isset($run['path']) ? $run['path'] : '';
      $row[] = format_date($run['date'], 'small');
      $rows[] = $row;
    }

    $build['table'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#attributes' => $attributes
    );

    return $build;
  }

  /**
   * Renders the run.
   *
   * @param \Drupal\xhprof\XHProfLib\Run $run
   *   The run.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return array
   *   A render array.
   */
  public function runAction(Run $run, Request $request) {
    $length = $request->get('length', 100);
    $sort = $request->get('sort', 'wt');

    $report = $this->reportEngine->getReport(NULL, NULL, $run, NULL, NULL, $sort, NULL, NULL);

    $build['#title'] = $this->t('XHProf view report for %id', array('%id' => $run->getId()));

    $descriptions = ReportConstants::getDescriptions();

    $build['summary'] = array(
      'title' => array(
        '#type' => 'inline_template',
        '#template' => '<h3>Summary</h3>',
      ),
      'table' => array(
        '#theme' => 'table',
        '#header' => array(),
        '#rows' => $this->getSummaryRows($report, $descriptions),
      )
    );

    $build['length'] = array(
      '#type' => 'inline_template',
      '#template' => ($length == -1) ? '<h3>Displaying all functions, sorted by {{ sort }}.</h3>' : '<h3>Displaying top {{ length }} functions, sorted by {{ sort }}. [{{ all }}]</h3>',
      '#context' => array(
        'length' => $length,
        'all' => $this->l($this->t('show all'), new Url('xhprof.run', array(
          'run' => $run->getId(),
          'length' => -1
        ))),
        'sort' => Xss::filter($descriptions[$sort], array()),
      ),
    );

    $build['table'] = array(
      '#theme' => 'table',
      '#header' => $this->getRunHeader($report, $descriptions, $run->getId()),
      '#rows' => $this->getRunRows($run, $report, $length),
      '#attributes' => array('class' => array('responsive')),
      '#attached' => array(
        'library' => array(
          'xhprof/xhprof',
        ),
      ),
    );

    return $build;
  }

  /**
   * Renders diff of two runs.
   *
   * @param \Drupal\xhprof\XHProfLib\Run $run1
   *   The first run.
   * @param \Drupal\xhprof\XHProfLib\Run $run2
   *   The second run.
   *
   * @return array
   *   A render array.
   */
  public function diffAction(Run $run1, Run $run2) {
    return ['#markup' => 'Not working yet'];
  }

  /**
   * @param \Drupal\xhprof\XHProfLib\Run $run
   * @param $symbol
   *
   * @return string
   */
  public function symbolAction(Run $run, $symbol, Request $request) {
    $sort = $request->query->get('sort', 'wt');

    $globalReport = $this->reportEngine->getReport(NULL, NULL, $run, NULL, NULL, $sort, NULL, NULL);
    $report = $this->reportEngine->getReport(NULL, NULL, $run, NULL, [$symbol], $sort, NULL, NULL);

    $build['#title'] = $this->t('XHProf view report for %id', ['%id' => $run->getId()]);

    $descriptions = ReportConstants::getDescriptions();

    $build['title'] = [
      '#type' => 'inline_template',
      '#template' => '<strong>Parent/Child report for ' . $symbol . '</strong>',
    ];

    $build['table'] = [
      '#theme' => 'table',
      '#header' => $this->getRunHeader($report, $descriptions, $run->getId()),
      '#rows' => $this->getRunRows($run, $report, -1, $globalReport, $symbol),
      '#attributes' => ['class' => ['responsive']],
      '#attached' => [
        'library' => [
          'xhprof/xhprof',
        ],
      ],
    ];

    return $build;
  }

  /**
   * @param string $class
   *
   * @return string
   */
  private function abbrClass($class) {
    $parts = explode('\\', $class);
    $short = array_pop($parts);

    if (strlen($short) >= 40) {
      $short = substr($short, 0, 30) . " … " . substr($short, -5);
    }

    return new FormattableMarkup('<abbr title="@class">@short</abbr>', [
      '@class' => $class,
      '@short' => $short
    ]);
  }

  /**
   * @param \Drupal\xhprof\XHProfLib\Report\ReportInterface $report
   * @param array $descriptions
   *
   * @return array
   */
  private function getRunHeader(ReportInterface $report, $descriptions, $run_id) {
    $headers = array('fn', 'ct', 'ct_perc');

    $metrics = $report->getMetrics();

    foreach ($metrics as $metric) {
      $headers[] = $metric;
      $headers[] = $metric . '_perc';
      $headers[] = 'excl_' . $metric;
      $headers[] = 'excl_' . $metric . '_perc';
    }

    $sortable = ReportConstants::getSortableColumns();
    foreach ($headers as &$header) {
      if (isset($sortable[$header])) {
        $header = [
          'data' => Link::createFromRoute($descriptions[$header], 'xhprof.run', ['run' => $run_id], [
            'query' => [
              'sort' => $header,
            ],
          ])->toRenderable(),
        ];
      }
      else {
        $header = new FormattableMarkup($descriptions[$header], []);
      }
    }

    return $headers;
  }

  /**
   * @param \Drupal\xhprof\XHProfLib\Run $run
   * @param \Drupal\xhprof\XHProfLib\Report\ReportInterface $report
   * @param int $length
   *
   * @return array
   */
  private function getRunRows(Run $run, ReportInterface $report, $length, ReportInterface $globalReport = NULL, $symbol = NULL) {
    $rows = [];

    $runId = $run->getId();
    $symbols = $report->getSymbols($length);

    if ($symbol) {
      $globalSymbols = $globalReport->getSymbols(-1);

      // Add the current function in the table.
      $this->getCurrentFunctionRows($globalSymbols[$symbol], $rows);

      // Add parent functions in the table.
      $runSymbols = $run->getSymbols(-1);
      $parents = [];
      $children = [];
      foreach ($runSymbols as $value) {
        if (($value->getChild() == $symbol) && ($parent = $value->getParent())) {
          $parents[$parent] = $globalSymbols[$parent];
        }
        elseif (($value->getParent() == $symbol) && ($child = $value->getChild())) {
          $children[$child] = $value;
        }
      }
      $this->getParentFunctionsRows($parents, $runId, $rows);

      $rows[] = [
        [
          'data' => new FormattableMarkup('<strong>@value</strong>', [
            '@value' => 'Child functions',
          ]),
          'colspan' => 11,
        ],
      ];
    }

    foreach ($symbols as $value) {
      // If its a symbol table, display only the children in the list.
      if (!$symbol || !empty($children[$value[0]])) {
        $text = $value[0];
        $url = Url::fromRoute('xhprof.symbol', [
          'run' => $runId,
          'symbol' => $value[0],
        ]);

        $value[0] = Link::fromTextAndUrl($text, $url)->toString();

        $rows[] = $value;
      }
    }

    return $rows;
  }

  private function getCurrentFunctionRows($symbol, &$rows) {
    $rows[] = [
      [
        'data' => new FormattableMarkup('<strong>@value</strong>', [
          '@value' => 'Current Function',
        ]),
        'colspan' => 11,
      ],
    ];

    $symbol[0] = Link::fromTextAndUrl($symbol[0], Url::fromRoute('<current>'));
    $rows[] = $symbol;

    return $rows;
  }

  private function getParentFunctionsRows($parents, $runId, &$rows) {
    if (!empty($parents)) {
      $rows[] = [
        [
          'data' => new FormattableMarkup('<strong>@value</strong>', [
            '@value' => count($parents) == 1 ? 'Parent Function' : 'Parent functions',
          ]),
          'colspan' => 11,
        ],
      ];
      foreach ($parents as $parent) {
        $parent[0] = Link::fromTextAndUrl($parent[0], Url::fromRoute('xhprof.symbol', [
          'run' => $runId,
          'symbol' => $parent[0],
        ]));

        $rows[] = $parent;
      }
    }

    return $rows;
  }

  /**
   * @param \Drupal\xhprof\XHProfLib\Report\ReportInterface $report
   * @param array $descriptions
   *
   * @return array
   */
  private function getSummaryRows(ReportInterface $report, $descriptions) {
    $summaryRows = [];
    $possibileMetrics = $report->getPossibleMetrics();
    foreach ($report->getSummary() as $metric => $value) {
      $key = 'Total ' . Xss::filter($descriptions[$metric], []);
      $unit = isset($possibileMetrics[$metric]) ? $possibileMetrics[$metric][1] : '';

      $value = new FormattableMarkup('@value @unit', [
        '@value' => $value,
        '@unit' => $unit,
      ]);

      $summaryRows[] = [$key, $value];
    }

    return $summaryRows;
  }

}
