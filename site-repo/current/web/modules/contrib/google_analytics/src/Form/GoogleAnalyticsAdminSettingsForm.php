<?php

namespace Drupal\google_analytics\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\google_analytics\GoogleAnalitycsInterface;

/**
 * Configure Google_Analytics settings for this site.
 */
class GoogleAnalyticsAdminSettingsForm extends ConfigFormBase {

  /**
   * The manages modules.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The constructor method.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current user.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The manages modules.
   */
  public function __construct(ConfigFactoryInterface $config_factory, AccountInterface $currentUser, ModuleHandlerInterface $moduleHandler) {
    parent::__construct($config_factory);
    $this->currentUser = $currentUser;
    $this->moduleHandler = $moduleHandler;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'google_analytics_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['google_analytics.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('google_analytics.settings');

    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General settings'),
      '#open' => TRUE,
    ];

    $form['general']['google_analytics_account'] = [
      '#default_value' => $config->get('account'),
      '#description' => $this->t('This ID is unique to each site you want to track separately, and is in the form of UA-xxxxxxx-yy or G-xxxxxxxxx. To get a Web Property ID, <a href=":analytics">register your site with Google Analytics</a>, or if you already have registered your site, go to your Google Analytics Settings page to see the ID next to every site profile. <a href=":webpropertyid">Find more information in the documentation</a>.', [':analytics' => 'https://marketingplatform.google.com/about/analytics/', ':webpropertyid' => Url::fromUri('https://developers.google.com/analytics/resources/concepts/gaConceptsAccounts', ['fragment' => 'webProperty'])->toString()]),
      '#maxlength' => 20,
      '#placeholder' => 'UA-',
      '#required' => TRUE,
      '#size' => 20,
      '#title' => $this->t('Web Property ID'),
      '#type' => 'textfield',
    ];

    $form['general']['google_analytics_premium'] = [
      '#default_value' => $config->get('premium'),
      '#description' => $this->t('If you are a Google Analytics Premium customer, you can use up to 200 instead of 20 custom dimensions and metrics.'),
      '#title' => $this->t('Premium account'),
      '#type' => 'checkbox',
    ];

    // Visibility settings.
    $form['tracking_scope'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Tracking scope'),
      '#attached' => [
        'library' => [
          'google_analytics/google_analytics.admin',
        ],
      ],
    ];

    $form['tracking']['domain_tracking'] = [
      '#type' => 'details',
      '#title' => $this->t('Domains'),
      '#group' => 'tracking_scope',
    ];

    global $cookie_domain;
    $multiple_sub_domains = [];
    foreach (['www', 'app', 'shop'] as $subdomain) {
      if (count(explode('.', $cookie_domain)) > 2 && !is_numeric(str_replace('.', '', $cookie_domain))) {
        $multiple_sub_domains[] = $subdomain . $cookie_domain;
      }
      // IP addresses or localhost.
      else {
        $multiple_sub_domains[] = $subdomain . '.example.com';
      }
    }

    $multiple_toplevel_domains = [];
    foreach (['.com', '.net', '.org'] as $tldomain) {
      $host = $_SERVER['HTTP_HOST'];
      $domain = substr($host, 0, strrpos($host, '.'));
      if (count(explode('.', $host)) > 2 && !is_numeric(str_replace('.', '', $host))) {
        $multiple_toplevel_domains[] = $domain . $tldomain;
      }
      // IP addresses or localhost.
      else {
        $multiple_toplevel_domains[] = 'www.example' . $tldomain;
      }
    }

    $form['tracking']['domain_tracking']['google_analytics_domain_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('What are you tracking?'),
      '#options' => [
        0 => $this->t('A single domain (default)'),
        1 => $this->t('One domain with multiple subdomains'),
        2 => $this->t('Multiple top-level domains'),
      ],
      0 => [
        '#description' => $this->t('Domain: @domain', ['@domain' => $_SERVER['HTTP_HOST']]),
      ],
      1 => [
        '#description' => $this->t('Examples: @domains', ['@domains' => implode(', ', $multiple_sub_domains)]),
      ],
      2 => [
        '#description' => $this->t('Examples: @domains', ['@domains' => implode(', ', $multiple_toplevel_domains)]),
      ],
      '#default_value' => $config->get('domain_mode'),
    ];
    $form['tracking']['domain_tracking']['google_analytics_cross_domains'] = [
      '#title' => $this->t('List of top-level domains'),
      '#type' => 'textarea',
      '#default_value' => $config->get('cross_domains'),
      '#description' => $this->t('If you selected "Multiple top-level domains" above, enter all related top-level domains. Add one domain per line. By default, the data in your reports only includes the path and name of the page, and not the domain name. For more information see section <em>Show separate domain names</em> in <a href=":url">Tracking Multiple Domains</a>.', [':url' => 'https://support.google.com/analytics/answer/1034342']),
      '#states' => [
        'enabled' => [
          ':input[name="google_analytics_domain_mode"]' => ['value' => '2'],
        ],
        'required' => [
          ':input[name="google_analytics_domain_mode"]' => ['value' => '2'],
        ],
      ],
    ];

    // Page specific visibility configurations.
    $account = $this->currentUser;
    $php_access = $account->hasPermission('use PHP for google analytics tracking visibility');
    $visibility_request_path_pages = $config->get('visibility.request_path_pages');

    $form['tracking']['page_visibility_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Pages'),
      '#group' => 'tracking_scope',
    ];

    if ($config->get('visibility.request_path_mode') == 2 && !$php_access) {
      $form['tracking']['page_visibility_settings'] = [];
      $form['tracking']['page_visibility_settings']['google_analytics_visibility_request_path_mode'] = ['#type' => 'value', '#value' => 2];
      $form['tracking']['page_visibility_settings']['google_analytics_visibility_request_path_pages'] = ['#type' => 'value', '#value' => $visibility_request_path_pages];
    }
    else {
      $options = [
        $this->t('Every page except the listed pages'),
        $this->t('The listed pages only'),
      ];
      $description = $this->t("Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are %blog for the blog page and %blog-wildcard for every personal blog. %front is the front page.", ['%blog' => '/blog', '%blog-wildcard' => '/blog/*', '%front' => '<front>']);

      if ($this->moduleHandler->moduleExists('php') && $php_access) {
        $options[] = $this->t('Pages on which this PHP code returns <code>TRUE</code> (not supported in Drupal 9, experts only)');
        $title = $this->t('Pages or PHP code');
        $description .= ' ' . $this->t('If the PHP option is chosen, enter PHP code between %php. Note that executing incorrect PHP code can break your Drupal site.', ['%php' => '<?php ?>']);
      }
      else {
        $title = $this->t('Pages');
      }
      $form['tracking']['page_visibility_settings']['google_analytics_visibility_request_path_mode'] = [
        '#type' => 'radios',
        '#title' => $this->t('Add tracking to specific pages'),
        '#options' => $options,
        '#default_value' => $config->get('visibility.request_path_mode'),
      ];
      $form['tracking']['page_visibility_settings']['google_analytics_visibility_request_path_pages'] = [
        '#type' => 'textarea',
        '#title' => $title,
        '#title_display' => 'invisible',
        '#default_value' => !empty($visibility_request_path_pages) ? $visibility_request_path_pages : '',
        '#description' => $description,
        '#rows' => 10,
      ];
    }

    // Render the role overview.
    $visibility_user_role_roles = $config->get('visibility.user_role_roles');

    $form['tracking']['role_visibility_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Roles'),
      '#group' => 'tracking_scope',
    ];

    $form['tracking']['role_visibility_settings']['google_analytics_visibility_user_role_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Add tracking for specific roles'),
      '#options' => [
        $this->t('Add to the selected roles only'),
        $this->t('Add to every role except the selected ones'),
      ],
      '#default_value' => $config->get('visibility.user_role_mode'),
    ];
    $form['tracking']['role_visibility_settings']['google_analytics_visibility_user_role_roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Roles'),
      '#default_value' => !empty($visibility_user_role_roles) ? $visibility_user_role_roles : [],
      '#options' => array_map('\Drupal\Component\Utility\Html::escape', user_role_names()),
      '#description' => $this->t('If none of the roles are selected, all users will be tracked. If a user has any of the roles checked, that user will be tracked (or excluded, depending on the setting above).'),
    ];

    // Standard tracking configurations.
    $visibility_user_account_mode = $config->get('visibility.user_account_mode');

    $form['tracking']['user_visibility_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Users'),
      '#group' => 'tracking_scope',
    ];
    $t_permission = ['%permission' => $this->t('Opt-in or out of tracking')];
    $form['tracking']['user_visibility_settings']['google_analytics_visibility_user_account_mode'] = [
      '#type' => 'radios',
      '#title' => $this->t('Allow users to customize tracking on their account page'),
      '#options' => [
        0 => $this->t('No customization allowed'),
        1 => $this->t('Tracking on by default, users with %permission permission can opt out', $t_permission),
        2 => $this->t('Tracking off by default, users with %permission permission can opt in', $t_permission),
      ],
      '#default_value' => !empty($visibility_user_account_mode) ? $visibility_user_account_mode : 0,
    ];
    $form['tracking']['user_visibility_settings']['google_analytics_trackuserid'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track User ID'),
      '#default_value' => $config->get('track.userid'),
      '#description' => $this->t('User ID enables the analysis of groups of sessions, across devices, using a unique, persistent, and non-personally identifiable ID string representing a user. <a href=":url">Learn more about the benefits of using User ID</a>.', [':url' => 'https://support.google.com/analytics/answer/3123663']),
    ];

    // Link specific configurations.
    $form['tracking']['linktracking'] = [
      '#type' => 'details',
      '#title' => $this->t('Links and downloads'),
      '#group' => 'tracking_scope',
    ];
    $form['tracking']['linktracking']['google_analytics_trackoutbound'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track clicks on outbound links'),
      '#default_value' => $config->get('track.outbound'),
    ];
    $form['tracking']['linktracking']['google_analytics_trackmailto'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track clicks on mailto links'),
      '#default_value' => $config->get('track.mailto'),
    ];
    $form['tracking']['linktracking']['google_analytics_trackfiles'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track downloads (clicks on file links) for the following extensions'),
      '#default_value' => $config->get('track.files'),
    ];
    $form['tracking']['linktracking']['google_analytics_trackfiles_extensions'] = [
      '#title' => $this->t('List of download file extensions'),
      '#title_display' => 'invisible',
      '#type' => 'textfield',
      '#default_value' => $config->get('track.files_extensions'),
      '#description' => $this->t('A file extension list separated by the | character that will be tracked as download when clicked. Regular expressions are supported. For example: @extensions', ['@extensions' => GoogleAnalitycsInterface::GOOGLE_ANALYTICS_TRACKFILES_EXTENSIONS]),
      '#maxlength' => 500,
      '#states' => [
        'enabled' => [
          ':input[name="google_analytics_trackfiles"]' => ['checked' => TRUE],
        ],
        // Note: Form required marker is not visible as title is invisible.
        'required' => [
          ':input[name="google_analytics_trackfiles"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $colorbox_dependencies = '<div class="admin-requirements">';
    $colorbox_dependencies .= $this->t('Requires: @module-list', ['@module-list' => ($this->moduleHandler->moduleExists('colorbox') ? $this->t('@module (<span class="admin-enabled">enabled</span>)', ['@module' => 'Colorbox']) : $this->t('@module (<span class="admin-missing">disabled</span>)', ['@module' => 'Colorbox']))]);
    $colorbox_dependencies .= '</div>';

    $form['tracking']['linktracking']['google_analytics_trackcolorbox'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track content in colorbox modal dialogs'),
      '#description' => $this->t('Enable to track the content shown in colorbox modal windows.') . $colorbox_dependencies,
      '#default_value' => $config->get('track.colorbox'),
      '#disabled' => ($this->moduleHandler->moduleExists('colorbox') ? FALSE : TRUE),
    ];

    $form['tracking']['linktracking']['google_analytics_tracklinkid'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track enhanced link attribution'),
      '#default_value' => $config->get('track.linkid'),
      '#description' => $this->t('Enhanced Link Attribution improves the accuracy of your In-Page Analytics report by automatically differentiating between multiple links to the same URL on a single page by using link element IDs. <a href=":url">Enable enhanced link attribution</a> in the Admin UI of your Google Analytics account.', [':url' => 'https://support.google.com/analytics/answer/2558867']),
    ];
    $form['tracking']['linktracking']['google_analytics_trackurlfragments'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track changing URL fragments as pageviews'),
      '#default_value' => $config->get('track.urlfragments'),
      '#description' => $this->t('By default, the URL reported to Google Analytics will not include the "fragment identifier" (i.e. the portion of the URL beginning with a hash sign), and hash changes by themselves will not cause new pageviews to be reported. Checking this box will cause hash changes to be reported as pageviews (in modern browsers) and all pageview URLs to include the fragment where applicable.'),
    ];

    // Message specific configurations.
    $form['tracking']['messagetracking'] = [
      '#type' => 'details',
      '#title' => $this->t('Messages'),
      '#group' => 'tracking_scope',
    ];
    $track_messages = $config->get('track.messages');
    $form['tracking']['messagetracking']['google_analytics_trackmessages'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Track messages of type'),
      '#default_value' => !empty($track_messages) ? $track_messages : [],
      '#description' => $this->t('This will track the selected message types shown to users. Tracking of form validation errors may help you identifying usability issues in your site. For each visit (user session), a maximum of approximately 500 combined GATC requests (both events and page views) can be tracked. Every message is tracked as one individual event. Note that - as the number of events in a session approaches the limit - additional events might not be tracked. Messages from excluded pages cannot be tracked.'),
      '#options' => [
        'status' => $this->t('Status message'),
        'warning' => $this->t('Warning message'),
        'error' => $this->t('Error message'),
      ],
    ];

    $form['tracking']['search_and_advertising'] = [
      '#type' => 'details',
      '#title' => $this->t('Search and Advertising'),
      '#group' => 'tracking_scope',
    ];

    $site_search_dependencies = '<div class="admin-requirements">';
    $site_search_dependencies .= $this->t('Requires: @module-list', ['@module-list' => ($this->moduleHandler->moduleExists('search') ? $this->t('@module (<span class="admin-enabled">enabled</span>)', ['@module' => 'Search']) : $this->t('@module (<span class="admin-missing">disabled</span>)', ['@module' => 'Search']))]);
    $site_search_dependencies .= '</div>';

    $form['tracking']['search_and_advertising']['google_analytics_site_search'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track internal search'),
      '#description' => $this->t('If checked, internal search keywords are tracked. You must configure your Google account to use the internal query parameter <strong>search</strong>. For more information see <a href=":url">Setting Up Site Search for a Profile</a>.', [':url' => 'https://support.google.com/analytics/answer/1012264']) . $site_search_dependencies,
      '#default_value' => $config->get('track.site_search'),
      '#disabled' => ($this->moduleHandler->moduleExists('search') ? FALSE : TRUE),
    ];
    $form['tracking']['search_and_advertising']['google_analytics_trackadsense'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track AdSense ads'),
      '#description' => $this->t('If checked, your AdSense ads will be tracked in your Google Analytics account.'),
      '#default_value' => $config->get('track.adsense'),
    ];
    $form['tracking']['search_and_advertising']['google_analytics_trackdisplayfeatures'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Track display features'),
      '#description' => $this->t('The display features plugin can be used to enable Display Advertising Features in Google Analytics, such as Remarketing, Demographics and Interest Reporting, and more. <a href=":displayfeatures">Learn more about Display Advertising Features in Google Analytics</a>. If you choose this option you will need to <a href=":privacy">update your privacy policy</a>.', [':displayfeatures' => 'https://support.google.com/analytics/answer/3450482', ':privacy' => 'https://support.google.com/analytics/answer/2700409']),
      '#default_value' => $config->get('track.displayfeatures'),
    ];

    // Privacy specific configurations.
    $form['tracking']['privacy'] = [
      '#type' => 'details',
      '#title' => $this->t('Privacy'),
      '#group' => 'tracking_scope',
    ];
    $form['tracking']['privacy']['google_analytics_tracker_anonymizeip'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Anonymize visitors IP address'),
      '#description' => $this->t('Tell Google Analytics to anonymize the information sent by the tracker objects by removing the last octet of the IP address prior to its storage. Note that this will slightly reduce the accuracy of geographic reporting. In some countries it is not allowed to collect personally identifying information for privacy reasons and this setting may help you to comply with the local laws.'),
      '#default_value' => $config->get('privacy.anonymizeip'),
    ];

    // Custom Events.
    $form['google_analytics_custom_events'] = [
      '#description' => $this->t('Custom event tracking for Google Analytics. See <a href=":custom_var_documentation">GA events documentation</a> here.', [':custom_var_documentation' => 'https://developers.google.com/analytics/devguides/collection/analyticsjs/events']),
      '#title' => $this->t('Custom Events'),
      '#tree' => TRUE,
      '#type' => 'details',
    ];

    $form['google_analytics_custom_events']['container'] = [
      '#type' => 'container',
      '#prefix' => '<div id="config-wrapper">',
      '#suffix' => '</div>',
      '#open' => TRUE,
    ];

    $google_analytics_custom_events = $config->get('custom.events');

    $form['google_analytics_custom_events']['container']['standardized_tracking'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable standardized tracking'),
      '#description' => $this->t('This will allow for elements with the correct class and data attributes to be tracked based on set attributes. Class: "ga-custom-event" DataAttributes: "data-ga-category", "data-ga-action", "data-ga-label", "data-ga-value".'),
      '#default_value' => $google_analytics_custom_events['container']['standardized_tracking'] ?? 0,
    ];

    $form['google_analytics_custom_events']['container']['draggable_table'] = [
      '#type' => 'table',
      '#header' => [t('Custom Event'), t('Remove'), t('Weight')],
      '#tree' => TRUE,
      // TableDrag: Each array value is a list of callback arguments for
      // drupal_add_tabledrag(). The #id of the table is automatically prepended;
      // if there is none, an HTML ID is auto-generated.
      '#tabledrag' => [
        [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'mytable-order-weight',
        ],
      ],
    ];

    $config_count = $form_state->get('config_count');

    if (is_null($config_count)) {
      $config_count = 0;
      if (!empty($google_analytics_custom_events['container'])) {
        $config_count = count($google_analytics_custom_events['container']['draggable_table']);
      }
      $form_state->set('config_count', $config_count );
    }

    $removed_configs = $form_state->get('removed_configs');

    if (is_null($removed_configs)) {
      $form_state->set('removed_configs', []);
      $removed_configs = [];
    }

    for ($i = 0; $i < $config_count; $i++) {
      if (in_array($i, $removed_configs)) {
        $form['google_analytics_custom_events']['container']['draggable_table'][$i]['#attributes']['style'] = 'display: none;';
      }

      $tc = $google_analytics_custom_events['container']['draggable_table'][$i]['ga_event_settings'];

      $form['google_analytics_custom_events']['container']['draggable_table'][$i]['#attributes']['class'][] = 'draggable';
      $form['google_analytics_custom_events']['container']['draggable_table'][$i]['ga_event_settings'] = [
        '#type' => 'container',
        '#prefix' => '<div id="field-group-wrapper-' . $i . '">',
        '#suffix' => '</div>',
        '#attributes' => ['class' => ['container-block']],
      ];
      $form['google_analytics_custom_events']['container']['draggable_table'][$i]['ga_event_settings']['label'] = [
        '#type' => 'textfield',
        '#title' => t('Event Name'),
        '#default_value' => $tc['label'],
      ];
      $form['google_analytics_custom_events']['container']['draggable_table'][$i]['ga_event_settings']['selector'] = [
        '#type' => 'textfield',
        '#title' => t('Selector'),
        '#default_value' => $tc['selector'],
      ];

      // Create form elments for the required ga() fields.
      $event_fields = [
        'event_category' => 'Event Category',
        'event_action' => 'Event Action',
        'event_label' => 'Event Label',
        'event_value' => 'Event Value',
      ];
      foreach ($event_fields as $key => $field) {
        $form['google_analytics_custom_events']['container']['draggable_table'][$i]['ga_event_settings'][$key] = [
          '#type' => 'textfield',
          '#title' => t($field),
          '#default_value' => $tc[$key],
          '#element_validate' => [[get_class($this), 'tokenElementValidate']],
          '#token_types' => ['node'],
        ];
        if (\Drupal::moduleHandler()->moduleExists('token')) {
          $form['google_analytics_custom_events']['container']['draggable_table'][$i]['ga_event_settings'][$key]['#element_validate'][] = 'token_element_validate';
        }
      }

      $form['google_analytics_custom_events']['container']['draggable_table'][$i]['remove'] = [
        '#type' => 'submit',
        '#value' => 'Remove',
        '#name' => 'remove-' . $i,
        '#submit' => [[$this, 'removeConfigSubmit']],
        '#limit_validation_errors' => [],
        '#ajax' => [
          'callback' => '::addConfigCallback',
          'wrapper' => 'config-wrapper',
        ],
      ];

      $form['google_analytics_custom_events']['container']['draggable_table'][$i]['weight'] = [
        '#type' => 'weight',
        '#title' => t('Weight for @title', ['@title' => 'markup display item configuration']),
        '#title_display' => 'invisible',
        '#default_value' => 1,
        // Classify the weight element for #tabledrag.
        '#attributes' => ['class' => ['mytable-order-weight']],
      ];
    }
    $form['google_analytics_custom_events']['google_analytics_custom_events_description'] = [
      '#type' => 'item',
      '#description' => $this->t('Tokens supported for "Event Category|Action|Label|Value". You may also use %target.href for the clicked item href.'),
    ];

    $form['google_analytics_custom_events']['actions']['add_config'] = [
      '#type' => 'submit',
      '#value' => 'Add Event',
      '#limit_validation_errors' => [],
      '#submit' => [[$this, 'addConfigSubmit']],
      '#ajax' => [
        'callback' => '::addConfigCallback',
        'wrapper' => 'config-wrapper',
      ],
    ];

    if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['google_analytics_custom_events']['google_analytics_custom_events_token_tree'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => ['node'],
      ];
    }

    // Custom Dimensions.
    $form['google_analytics_custom_dimension'] = [
      '#description' => $this->t('You can set values for Google Analytics <a href=":custom_var_documentation">Custom Dimensions</a> here. You must have already configured your custom dimensions in the <a href=":setup_documentation">Google Analytics Management Interface</a>. You may use tokens. Global and user tokens are always available; on node pages, node tokens are also available. A dimension <em>value</em> is allowed to have a maximum length of 150 bytes. Expect longer values to get trimmed.', [':custom_var_documentation' => 'https://developers.google.com/analytics/devguides/collection/analyticsjs/custom-dims-mets', ':setup_documentation' => 'https://support.google.com/analytics/answer/2709829']),
      '#title' => $this->t('Custom dimensions'),
      '#tree' => TRUE,
      '#type' => 'details',
    ];

    $form['google_analytics_custom_dimension']['indexes'] = [
      '#type' => 'table',
      '#header' => [
        ['data' => $this->t('Index')],
        ['data' => $this->t('Name')],
        ['data' => $this->t('Value')],
      ],
    ];

    $google_analytics_custom_dimension = $config->get('custom.dimension');

    // Standard Google Analytics accounts support up to 20 custom dimensions,
    // premium accounts support up to 200 custom dimensions.
    $limit = ($config->get('premium')) ? 200 : 20;
    for ($i = 1; $i <= $limit; $i++) {
      $form['google_analytics_custom_dimension']['indexes'][$i]['index'] = [
        '#default_value' => $i,
        '#description' => $this->t('Index number'),
        '#disabled' => TRUE,
        '#size' => ($limit == 200) ? 3 : 2,
        '#title' => $this->t('Custom dimension index #@index', ['@index' => $i]),
        '#title_display' => 'invisible',
        '#type' => 'textfield',
      ];
      $form['google_analytics_custom_dimension']['indexes'][$i]['name'] = [
        '#default_value' => isset($google_analytics_custom_dimension[$i]['name']) ? $google_analytics_custom_dimension[$i]['name'] : '',
        '#description' => $this->t('The custom dimension name.'),
        '#maxlength' => 255,
        '#title' => $this->t('Custom dimension name #@index', ['@index' => $i]),
        '#title_display' => 'invisible',
        '#type' => 'textfield',
      ];
      $form['google_analytics_custom_dimension']['indexes'][$i]['value'] = [
        '#default_value' => isset($google_analytics_custom_dimension[$i]['value']) ? $google_analytics_custom_dimension[$i]['value'] : '',
        '#description' => $this->t('The custom dimension value.'),
        '#maxlength' => 255,
        '#title' => $this->t('Custom dimension value #@index', ['@index' => $i]),
        '#title_display' => 'invisible',
        '#type' => 'textfield',
        '#element_validate' => [[get_class($this), 'tokenElementValidate']],
        '#token_types' => ['node'],
      ];
      if ($this->moduleHandler->moduleExists('token')) {
        $form['google_analytics_custom_dimension']['indexes'][$i]['value']['#element_validate'][] = 'token_element_validate';
      }
    }

    $form['google_analytics_custom_dimension']['google_analytics_description'] = [
      '#type' => 'item',
      '#description' => $this->t('You can supplement Google Analytics\' basic IP address tracking of visitors by segmenting users based on custom dimensions. Section 7 of the <a href=":ga_tos">Google Analytics terms of service</a> requires that You will not (and will not allow any third party to) use the Service to track, collect or upload any data that personally identifies an individual (such as a name, userid, email address or billing information), or other data which can be reasonably linked to such information by Google. You will have and abide by an appropriate Privacy Policy and will comply with all applicable laws and regulations relating to the collection of information from Visitors. You must post a Privacy Policy and that Privacy Policy must provide notice of Your use of cookies that are used to collect traffic data, and You must not circumvent any privacy features (e.g., an opt-out) that are part of the Service.', [':ga_tos' => 'https://www.google.com/analytics/terms/gb.html']),
    ];
    if ($this->moduleHandler->moduleExists('token')) {
      $form['google_analytics_custom_dimension']['google_analytics_token_tree'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => ['node'],
      ];
    }

    // Custom Metrics.
    $form['google_analytics_custom_metric'] = [
      '#description' => $this->t('You can add Google Analytics <a href=":custom_var_documentation">Custom Metrics</a> here. You must have already configured your custom metrics in the <a href=":setup_documentation">Google Analytics Management Interface</a>. You may use tokens. Global and user tokens are always available; on node pages, node tokens are also available.', [':custom_var_documentation' => 'https://developers.google.com/analytics/devguides/collection/analyticsjs/custom-dims-mets', ':setup_documentation' => 'https://support.google.com/analytics/answer/2709829']),
      '#title' => $this->t('Custom metrics'),
      '#tree' => TRUE,
      '#type' => 'details',
    ];

    $form['google_analytics_custom_metric']['indexes'] = [
      '#type' => 'table',
      '#header' => [
        ['data' => $this->t('Index')],
        ['data' => $this->t('Name')],
        ['data' => $this->t('Value')],
      ],
    ];

    $google_analytics_custom_metric = $config->get('custom.metric');

    // Standard Google Analytics accounts support up to 20 custom metrics,
    // premium accounts support up to 200 custom metrics.
    for ($i = 1; $i <= $limit; $i++) {
      $form['google_analytics_custom_metric']['indexes'][$i]['index'] = [
        '#default_value' => $i,
        '#description' => $this->t('Index number'),
        '#disabled' => TRUE,
        '#size' => ($limit == 200) ? 3 : 2,
        '#title' => $this->t('Custom metric index #@index', ['@index' => $i]),
        '#title_display' => 'invisible',
        '#type' => 'textfield',
      ];
      $form['google_analytics_custom_metric']['indexes'][$i]['name'] = [
        '#default_value' => isset($google_analytics_custom_metric[$i]['name']) ? $google_analytics_custom_metric[$i]['name'] : '',
        '#description' => $this->t('The custom metric name.'),
        '#maxlength' => 255,
        '#title' => $this->t('Custom metric name #@index', ['@index' => $i]),
        '#title_display' => 'invisible',
        '#type' => 'textfield',
      ];
      $form['google_analytics_custom_metric']['indexes'][$i]['value'] = [
        '#default_value' => isset($google_analytics_custom_metric[$i]['value']) ? $google_analytics_custom_metric[$i]['value'] : '',
        '#description' => $this->t('The custom metric value.'),
        '#maxlength' => 255,
        '#title' => $this->t('Custom metric value #@index', ['@index' => $i]),
        '#title_display' => 'invisible',
        '#type' => 'textfield',
        '#element_validate' => [[get_class($this), 'tokenElementValidate']],
        '#token_types' => ['node'],
      ];
      if ($this->moduleHandler->moduleExists('token')) {
        $form['google_analytics_custom_metric']['indexes'][$i]['value']['#element_validate'][] = 'token_element_validate';
      }
    }

    $form['google_analytics_custom_metric']['google_analytics_description'] = [
      '#type' => 'item',
      '#description' => $this->t('You can supplement Google Analytics\' basic IP address tracking of visitors by segmenting users based on custom metrics. Section 7 of the <a href=":ga_tos">Google Analytics terms of service</a> requires that You will not (and will not allow any third party to) use the Service to track, collect or upload any data that personally identifies an individual (such as a name, userid, email address or billing information), or other data which can be reasonably linked to such information by Google. You will have and abide by an appropriate Privacy Policy and will comply with all applicable laws and regulations relating to the collection of information from Visitors. You must post a Privacy Policy and that Privacy Policy must provide notice of Your use of cookies that are used to collect traffic data, and You must not circumvent any privacy features (e.g., an opt-out) that are part of the Service.', [':ga_tos' => 'https://www.google.com/analytics/terms/gb.html']),
    ];
    if ($this->moduleHandler->moduleExists('token')) {
      $form['google_analytics_custom_metric']['google_analytics_token_tree'] = [
        '#theme' => 'token_tree_link',
        '#token_types' => ['node'],
      ];
    }

    // Advanced feature configurations.
    $form['advanced'] = [
      '#type' => 'details',
      '#title' => $this->t('Advanced settings'),
      '#open' => FALSE,
    ];

    $form['advanced']['google_analytics_cache'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Locally cache tracking code file'),
      '#description' => $this->t("If checked, the tracking code file is retrieved from Google Analytics and cached locally. It is updated daily from Google's servers to ensure updates to tracking code are reflected in the local copy. Do not activate this until after Google Analytics has confirmed that site tracking is working!"),
      '#default_value' => $config->get('cache'),
    ];

    // Allow for tracking of the originating node when viewing translation sets.
    if ($this->moduleHandler->moduleExists('content_translation')) {
      $form['advanced']['google_analytics_translation_set'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Track translation sets as one unit'),
        '#description' => $this->t('When a node is part of a translation set, record statistics for the originating node instead. This allows for a translation set to be treated as a single unit.'),
        '#default_value' => $config->get('translation_set'),
      ];
    }

    $user_access_add_js_snippets = !$this->currentUser()->hasPermission('add JS snippets for google analytics');
    $user_access_add_js_snippets_permission_warning = $user_access_add_js_snippets ? ' <em>' . $this->t('This field has been disabled because you do not have sufficient permissions to edit it.') . '</em>' : '';
    $form['advanced']['codesnippet'] = [
      '#type' => 'details',
      '#title' => $this->t('Custom JavaScript code'),
      '#open' => TRUE,
      '#description' => $this->t('You can add custom Google Analytics <a href=":snippets">code snippets</a> here. These will be added every time tracking is in effect. Before you add your custom code, you should read the <a href=":ga_concepts_overview">Google Analytics Tracking Code - Functional Overview</a> and the <a href=":ga_js_api">Google Analytics Tracking API</a> documentation. <strong>Do not include the &lt;script&gt; tags</strong>, and always end your code with a semicolon (;).', [':snippets' => 'https://drupal.org/node/248699', ':ga_concepts_overview' => 'https://developers.google.com/analytics/resources/concepts/gaConceptsTrackingOverview', ':ga_js_api' => 'https://developers.google.com/analytics/devguides/collection/analyticsjs/method-reference']),
    ];
    $form['advanced']['codesnippet']['google_analytics_codesnippet_create'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Parameters'),
      '#default_value' => $this->getNameValueString($config->get('codesnippet.create')),
      '#rows' => 5,
      '#description' => $this->t('Enter one value per line, in the format name|value. Settings in this textarea will be added to <code>gtag("config", "UA-XXXX-Y", {"name":"value"});</code>. For more information, read <a href=":url">documentation</a> in the gtag.js reference.', [':url' => 'https://developers.google.com/analytics/devguides/collection/gtagjs/']),
      '#element_validate' => [[get_class($this), 'validateParameterValues']],
    ];
    $form['advanced']['codesnippet']['google_analytics_codesnippet_before'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Code snippet (before)'),
      '#default_value' => $config->get('codesnippet.before'),
      '#disabled' => $user_access_add_js_snippets,
      '#rows' => 5,
      '#description' => $this->t('Code in this textarea will be added <strong>before</strong> <code>gtag("config", "UA-XXXX-Y");</code>.') . $user_access_add_js_snippets_permission_warning,
    ];
    $form['advanced']['codesnippet']['google_analytics_codesnippet_after'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Code snippet (after)'),
      '#default_value' => $config->get('codesnippet.after'),
      '#disabled' => $user_access_add_js_snippets,
      '#rows' => 5,
      '#description' => $this->t('Code in this textarea will be added <strong>after</strong> <code>gtag("config", "UA-XXXX-Y");</code>. This is useful if you\'d like to track a site in two accounts.') . $user_access_add_js_snippets_permission_warning,
    ];

    $form['advanced']['google_analytics_debug'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable debugging'),
      '#description' => $this->t('If checked, the Google Universal Analytics debugging script will be loaded. You should not enable your production site to use this version of the JavaScript. The analytics_debug.js script is larger than the analytics.js tracking code and it is not typically cached. Using it in your production site will slow down your site for all of your users. Again, this is only for your own testing purposes. Debug messages are printed to the <code>window.console</code> object.'),
      '#default_value' => $config->get('debug'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    // Trim custom dimensions and metrics.
    foreach ($form_state->getValue(['google_analytics_custom_dimension', 'indexes']) as $dimension) {
      $form_state->setValue(['google_analytics_custom_dimension', 'indexes', $dimension['index'], 'value'], trim($dimension['value']));
      // Remove empty values from the array.
      if (!mb_strlen($form_state->getValue(['google_analytics_custom_dimension', 'indexes', $dimension['index'], 'value']))) {
        $form_state->unsetValue(['google_analytics_custom_dimension', 'indexes', $dimension['index']]);
      }
    }
    $form_state->setValue('google_analytics_custom_dimension', $form_state->getValue(['google_analytics_custom_dimension', 'indexes']));

    foreach ($form_state->getValue(['google_analytics_custom_metric', 'indexes']) as $metric) {
      $form_state->setValue(['google_analytics_custom_metric', 'indexes', $metric['index'], 'value'], trim($metric['value']));
      // Remove empty values from the array.
      if (!mb_strlen($form_state->getValue(['google_analytics_custom_metric', 'indexes', $metric['index'], 'value']))) {
        $form_state->unsetValue(['google_analytics_custom_metric', 'indexes', $metric['index']]);
      }
    }
    $form_state->setValue('google_analytics_custom_metric', $form_state->getValue(['google_analytics_custom_metric', 'indexes']));

    // Trim some text values.
    $form_state->setValue('google_analytics_account', trim($form_state->getValue('google_analytics_account')));
    $form_state->setValue('google_analytics_visibility_request_path_pages', trim($form_state->getValue('google_analytics_visibility_request_path_pages')));
    $form_state->setValue('google_analytics_cross_domains', trim($form_state->getValue('google_analytics_cross_domains')));
    $form_state->setValue('google_analytics_codesnippet_before', trim($form_state->getValue('google_analytics_codesnippet_before')));
    $form_state->setValue('google_analytics_codesnippet_after', trim($form_state->getValue('google_analytics_codesnippet_after')));
    $form_state->setValue('google_analytics_visibility_user_role_roles', array_filter($form_state->getValue('google_analytics_visibility_user_role_roles')));
    $form_state->setValue('google_analytics_trackmessages', array_filter($form_state->getValue('google_analytics_trackmessages')));

    // Replace all type of dashes (n-dash, m-dash, minus) with normal dashes.
    $form_state->setValue('google_analytics_account', str_replace(['–', '—', '−'], '-', $form_state->getValue('google_analytics_account')));

    if (!preg_match('/^(?:UA-\d+-\d+|G-\w+)$/', $form_state->getValue('google_analytics_account'))) {
      $form_state->setErrorByName('google_analytics_account', $this->t('A valid Google Analytics Web Property ID is case sensitive and formatted like UA-xxxxxxx-yy or G-xxxxxxxxxx.'));
    }

    // If multiple top-level domains has been selected, a domain names list is
    // required.
    if ($form_state->getValue('google_analytics_domain_mode') == 2 && $form_state->isValueEmpty('google_analytics_cross_domains')) {
      $form_state->setErrorByName('google_analytics_cross_domains', $this->t('A list of top-level domains is required if <em>Multiple top-level domains</em> has been selected.'));
    }
    // Clear the domain list if cross domains are disabled.
    if ($form_state->getValue('google_analytics_domain_mode') != 2) {
      $form_state->setValue('google_analytics_cross_domains', '');
    }

    // Verify that every path is prefixed with a slash, but don't check PHP
    // code snippets and do not check for slashes if no paths configured.
    if ($form_state->getValue('google_analytics_visibility_request_path_mode') != 2 && !empty($form_state->getValue('google_analytics_visibility_request_path_pages'))) {
      $pages = preg_split('/(\r\n?|\n)/', $form_state->getValue('google_analytics_visibility_request_path_pages'));
      foreach ($pages as $page) {
        if (strpos($page, '/') !== 0 && $page !== '<front>') {
          $form_state->setErrorByName('google_analytics_visibility_request_path_pages', $this->t('Path "@page" not prefixed with slash.', ['@page' => $page]));
          // Drupal forms show one error only.
          break;
        }
      }
    }

    // Disallow empty list of download file extensions.
    if ($form_state->getValue('google_analytics_trackfiles') && $form_state->isValueEmpty('google_analytics_trackfiles_extensions')) {
      $form_state->setErrorByName('google_analytics_trackfiles_extensions', $this->t('List of download file extensions cannot empty.'));
    }
    // Clear obsolete local cache if cache has been disabled.
    if ($form_state->isValueEmpty('google_analytics_cache') && $form['advanced']['google_analytics_cache']['#default_value']) {
      google_analytics_clear_js_cache();
    }

    // This is for the Newbie's who cannot read a text area description.
    if (stristr($form_state->getValue('google_analytics_codesnippet_before'), 'google-analytics.com/analytics.js')) {
      $form_state->setErrorByName('google_analytics_codesnippet_before', $this->t('Do not add the tracker code provided by Google into the javascript code snippets! This module already builds the tracker code based on your Google Analytics account number and settings.'));
    }
    if (stristr($form_state->getValue('google_analytics_codesnippet_after'), 'google-analytics.com/analytics.js')) {
      $form_state->setErrorByName('google_analytics_codesnippet_after', $this->t('Do not add the tracker code provided by Google into the javascript code snippets! This module already builds the tracker code based on your Google Analytics account number and settings.'));
    }
    if (preg_match('/(.*)<\/?script(.*)>(.*)/i', $form_state->getValue('google_analytics_codesnippet_before'))) {
      $form_state->setErrorByName('google_analytics_codesnippet_before', $this->t('Do not include the &lt;script&gt; tags in the javascript code snippets.'));
    }
    if (preg_match('/(.*)<\/?script(.*)>(.*)/i', $form_state->getValue('google_analytics_codesnippet_after'))) {
      $form_state->setErrorByName('google_analytics_codesnippet_after', $this->t('Do not include the &lt;script&gt; tags in the javascript code snippets.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $custom_event_configs = $this->getConfigurations($form_state);
    $config = $this->config('google_analytics.settings');
    $config
      ->set('account', $form_state->getValue('google_analytics_account'))
      ->set('premium', $form_state->getValue('google_analytics_premium'))
      ->set('cross_domains', $form_state->getValue('google_analytics_cross_domains'))
      ->set('codesnippet.create', $form_state->getValue('google_analytics_codesnippet_create'))
      ->set('codesnippet.before', $form_state->getValue('google_analytics_codesnippet_before'))
      ->set('codesnippet.after', $form_state->getValue('google_analytics_codesnippet_after'))
      ->set('custom.events', $custom_event_configs)
      ->set('custom.dimension', $form_state->getValue('google_analytics_custom_dimension'))
      ->set('custom.metric', $form_state->getValue('google_analytics_custom_metric'))
      ->set('domain_mode', $form_state->getValue('google_analytics_domain_mode'))
      ->set('track.files', $form_state->getValue('google_analytics_trackfiles'))
      ->set('track.files_extensions', $form_state->getValue('google_analytics_trackfiles_extensions'))
      ->set('track.colorbox', $form_state->getValue('google_analytics_trackcolorbox'))
      ->set('track.linkid', $form_state->getValue('google_analytics_tracklinkid'))
      ->set('track.urlfragments', $form_state->getValue('google_analytics_trackurlfragments'))
      ->set('track.userid', $form_state->getValue('google_analytics_trackuserid'))
      ->set('track.mailto', $form_state->getValue('google_analytics_trackmailto'))
      ->set('track.messages', $form_state->getValue('google_analytics_trackmessages'))
      ->set('track.outbound', $form_state->getValue('google_analytics_trackoutbound'))
      ->set('track.site_search', $form_state->getValue('google_analytics_site_search'))
      ->set('track.adsense', $form_state->getValue('google_analytics_trackadsense'))
      ->set('track.displayfeatures', $form_state->getValue('google_analytics_trackdisplayfeatures'))
      ->set('privacy.anonymizeip', $form_state->getValue('google_analytics_tracker_anonymizeip'))
      ->set('cache', $form_state->getValue('google_analytics_cache'))
      ->set('debug', $form_state->getValue('google_analytics_debug'))
      ->set('visibility.request_path_mode', $form_state->getValue('google_analytics_visibility_request_path_mode'))
      ->set('visibility.request_path_pages', $form_state->getValue('google_analytics_visibility_request_path_pages'))
      ->set('visibility.user_role_mode', $form_state->getValue('google_analytics_visibility_user_role_mode'))
      ->set('visibility.user_role_roles', $form_state->getValue('google_analytics_visibility_user_role_roles'))
      ->set('visibility.user_account_mode', $form_state->getValue('google_analytics_visibility_user_account_mode'))
      ->save();

    if ($form_state->hasValue('google_analytics_translation_set')) {
      $config->set('translation_set', $form_state->getValue('google_analytics_translation_set'))->save();
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * Add config submit ajax callback.
   */
  public function addConfigSubmit(array &$form, FormStateInterface $form_state) {
    if ($config_count = $form_state->get('config_count')) {
      $config_count++;
      $form_state->set('config_count', $config_count);
    }
    else {
      $form_state->set('config_count', 1);
    }
    $form_state->setRebuild(TRUE);
  }

  /**
   * Callback for both add and remove ajax-enabled buttons.
   */
  public function addConfigCallback(array &$form) {
    return $form['google_analytics_custom_events']['container'];
  }

  /**
   * Remove config submit ajax callback.
   */
  public function removeConfigSubmit(array &$form, FormStateInterface $form_state) {
    $element = $form_state->getTriggeringElement();
    $removed_configs = $form_state->get('removed_configs');

    if (!in_array($element['#parents'][3], $removed_configs)) {
      $removed_configs[] = $element['#parents'][3];
      $form_state->set('removed_configs', $removed_configs);
    }

    $form_state->setRebuild(TRUE);
  }

  /**
   * Helper function for getting the array of configured items
   */
  private function getConfigurations(FormStateInterface $form_state) {
    $ga_custom_events = $form_state->getValue('google_analytics_custom_events');
    $configs = $ga_custom_events['container']['draggable_table'];
    if (empty($configs)) {
      return [];
    }
    $removed_items = $form_state->get('removed_configs');
    foreach ($configs as $key => $config) {
      if (in_array($key, $removed_items)) {
        unset($ga_custom_events['container']['draggable_table'][$key]);
        array_values($ga_custom_events['container']['draggable_table']);
      }
    }
    return $ga_custom_events;
  }

  /**
   * Validate a form element that should have tokens in it.
   *
   * For example:
   * @code
   * $form['my_node_text_element'] = [
   *   '#type' => 'textfield',
   *   '#title' => $this->t('Some text to token-ize that has a node context.'),
   *   '#default_value' => 'The title of this node is [node:title].',
   *   '#element_validate' => [[get_class($this), 'tokenElementValidate']],
   * ];
   * @endcode
   */
  public static function tokenElementValidate(&$element, FormStateInterface $form_state) {
    $value = isset($element['#value']) ? $element['#value'] : $element['#default_value'];

    if (!mb_strlen($value)) {
      // Empty value needs no further validation since the element should depend
      // on using the '#required' FAPI property.
      return $element;
    }

    $tokens = \Drupal::token()->scan($value);
    $invalid_tokens = static::getForbiddenTokens($tokens);
    if ($invalid_tokens) {
      $form_state->setError($element, t('The %element-title is using the following forbidden tokens with personal identifying information: @invalid-tokens.', ['%element-title' => $element['#title'], '@invalid-tokens' => implode(', ', $invalid_tokens)]));
    }

    return $element;
  }

  /**
   * Get an array of all forbidden tokens.
   *
   * @param array|string $value
   *   An array of token values.
   *
   * @return array
   *   A unique array of invalid tokens.
   */
  protected static function getForbiddenTokens($value) {
    $invalid_tokens = [];
    $value_tokens = is_string($value) ? \Drupal::token()->scan($value) : $value;

    foreach ($value_tokens as $tokens) {
      if (array_filter($tokens, 'static::containsForbiddenToken')) {
        $invalid_tokens = array_merge($invalid_tokens, array_values($tokens));
      }
    }

    return array_unique($invalid_tokens);
  }

  /**
   * Validate if string contains forbidden tokens not allowed by privacy rules.
   *
   * @param string $token_string
   *   A string with one or more tokens to be validated.
   *
   * @return bool
   *   TRUE if blacklisted token has been found, otherwise FALSE.
   */
  protected static function containsForbiddenToken($token_string) {
    // List of strings in tokens with personal identifying information not
    // allowed for privacy reasons. See section 8.1 of the Google Analytics
    // terms of use for more detailed information.
    //
    // This list can never ever be complete. For this reason it tries to use a
    // regex and may kill a few other valid tokens, but it's the only way to
    // protect users as much as possible from admins with illegal ideas.
    //
    // User tokens are not prefixed with colon to catch 'current-user' and
    // 'user'.
    //
    // TODO: If someone have better ideas, share them, please!
    $token_blacklist = [
      ':account-name]',
      ':author]',
      ':author:edit-url]',
      ':author:url]',
      ':author:path]',
      ':current-user]',
      ':current-user:original]',
      ':display-name]',
      ':fid]',
      ':mail]',
      ':name]',
      ':uid]',
      ':one-time-login-url]',
      ':owner]',
      ':owner:cancel-url]',
      ':owner:edit-url]',
      ':owner:url]',
      ':owner:path]',
      'user:cancel-url]',
      'user:edit-url]',
      'user:url]',
      'user:path]',
      'user:picture]',
      // addressfield_tokens.module
      ':first-name]',
      ':last-name]',
      ':name-line]',
      ':mc-address]',
      ':thoroughfare]',
      ':premise]',
      // realname.module
      ':name-raw]',
      // token.module
      ':ip-address]',
    ];

    return preg_match('/' . implode('|', array_map('preg_quote', $token_blacklist)) . '/i', $token_string);
  }

  /**
   * The #element_validate callback for parameters.
   *
   * @param array $element
   *   An associative array containing the properties and children of the
   *   generic form element.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The $form_state array for the form this element belongs to.
   *
   * @see form_process_pattern()
   */
  public static function validateParameterValues(array $element, FormStateInterface $form_state) {
    $values = static::extractParameterValues($element['#value']);

    if (!is_array($values)) {
      $form_state->setError($element, t('The %element-title field contains invalid input.', ['%element-title' => $element['#title']]));
    }
    else {
      // Check that name and value are valid for the field type.
      foreach ($values as $name => $value) {
        if ($error = static::validateParameterName($name)) {
          $form_state->setError($element, $error);
          break;
        }
        if ($error = static::validateParameterValue($value)) {
          $form_state->setError($element, $error);
          break;
        }
      }

      $form_state->setValueForElement($element, $values);
    }
  }

  /**
   * Extracts the values array from the element.
   *
   * @param string $string
   *   The raw string to extract values from.
   *
   * @return array|null
   *   The array of extracted key/value pairs, or NULL if the string is invalid.
   *
   * @see \Drupal\options\Plugin\Field\FieldType\ListTextItem::allowedValuesString()
   */
  protected static function extractParameterValues($string) {
    $values = [];

    $list = explode("\n", $string);
    $list = array_map('trim', $list);
    $list = array_filter($list, 'strlen');

    foreach ($list as $text) {
      // Check for an explicit key.
      $matches = [];
      if (preg_match('/(.*)\|(.*)/', $text, $matches)) {
        // Trim key and value to avoid unwanted spaces issues.
        $name = trim($matches[1]);
        $value = trim($matches[2]);
      }
      else {
        return NULL;
      }

      $values[$name] = $value;
    }

    return self::convertFormValueDataTypes($values);
  }

  /**
   * Checks whether a parameter name is valid.
   *
   * @param string $name
   *   The option value entered by the user.
   *
   * @return string|null
   *   The error message if the specified value is invalid, NULL otherwise.
   */
  protected static function validateParameterName($name) {
    // List of supported field names:
    // https://developers.google.com/analytics/devguides/collection/analyticsjs/field-reference#create
    $allowed_parameters = [
      'client_id',
      'currency',
      'country',
      'cookie_name',
      'cookie_domain',
      'cookie_expires',
      'optimize_id',
      'sample_rate',
      'send_page_view',
      'site_speed_sample_rate',
      'use_amp_client_id',
    ];

    if ($name == 'allow_ad_personalization_signals') {
      return t('Parameter name %name is disallowed. Please configure <em>Track display features</em> under <em>Tracking scope > Search and Advertising</em>.', ['%name' => $name]);
    }
    if ($name == 'anonymize_ip') {
      return t('Parameter name %name is disallowed. Please configure <em>Anonymize visitors IP address</em> under <em>Tracking scope > Privacy</em>.', ['%name' => $name]);
    }
    if ($name == 'link_attribution') {
      return t('Parameter name %name is disallowed. Please configure <em>Track enhanced link attribution</em> under <em>Tracking scope > Links and downloads</em>.', ['%name' => $name]);
    }
    if ($name == 'linker') {
      return t('Parameter name %name is disallowed. Please configure <em>Multiple top-level domains</em> under <em>Tracking scope > Domains</em> to enable cross domain tracking.', ['%name' => $name]);
    }
    if ($name == 'user_id') {
      return t('Parameter name %name is disallowed. Please configure <em>Track User ID</em> under <em>Tracking scope > Users</em>.', ['%name' => $name]);
    }
    if (!in_array($name, $allowed_parameters)) {
      return t('Parameter name %name is unknown. Parameters are case sensitive. Please see <a href=":url">documentation</a> for supported parameters.', ['%name' => $name, ':url' => 'https://developers.google.com/analytics/devguides/collection/gtagjs/']);
    }
    return NULL;
  }

  /**
   * Checks whether a candidate value is valid.
   *
   * @param string|bool $value
   *   The option value entered by the user.
   *
   * @return string|null
   *   The error message if the specified value is invalid, NULL otherwise.
   */
  protected static function validateParameterValue($value) {
    if (!is_bool($value) && !mb_strlen($value)) {
      return t('A parameter requires a value.');
    }
    if (mb_strlen($value) > 255) {
      return t('The value of a parameter must be a string at most 255 characters long.');
    }
    return NULL;
  }

  /**
   * Generates a string representation of an array.
   *
   * This string format is suitable for edition in a textarea.
   *
   * @param array $values
   *   An array of values, where array keys are values and array values are
   *   labels.
   *
   * @return string
   *   The string representation of the $values array:
   *    - Values are separated by a carriage return.
   *    - Each value is in the format "name|value" or "value".
   */
  protected function getNameValueString(array $values) {
    $lines = [];
    foreach ($values as $name => $value) {
      // Convert data types.
      if (is_bool($value)) {
        $value = ($value) ? 'true' : 'false';
      }

      $lines[] = "$name|$value";
    }
    return implode("\n", $lines);
  }

  /**
   * Prepare form data types for Json conversion.
   *
   * @param array $values
   *   Array of values.
   *
   * @return array
   *   Value with casted data type.
   */
  protected static function convertFormValueDataTypes(array $values) {

    foreach ($values as $name => $value) {
      // Convert data types.
      $match = mb_strtolower($value);
      if ($match == 'true') {
        $value = TRUE;
      }
      elseif ($match == 'false') {
        $value = FALSE;
      }

      // Convert other known fields.
      switch ($name) {
        case 'sample_rate':
          // Float types.
          settype($value, 'float');
          break;

        case 'cookie_expires':
          // Integer types.
          settype($value, 'integer');
          break;
      }

      $values[$name] = $value;
    }

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      // Load the service required to construct this class.
      $container->get('config.factory'),
      $container->get('current_user'),
      $container->get('module_handler')
    );
  }

}
