<?php
/**
 * Hawk Components module for Craft CMS 3.x
 *
 * A plugin that controls the components.
 *
 * @link      hawk.ca
 * @copyright Copyright (c) 2019 HAWK
 */

namespace modules\components\controllers;

use craft\helpers\UrlHelper;
use modules\components\Components;

use Craft;
use craft\base\Field;
use craft\fields\PlainText;
use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;
use craft\models\FieldGroup;
use craft\web\Controller;
use craft\web\View;
use modules\components\models\Component as ComponentModel;
use modules\components\services\ComponentService;

use yii\web\NotFoundHttpException;

/**
 * Base Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your module’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    HAWK
 * @package   Components
 * @since     1.0.0
 */
class BaseController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our module's index action URL,
     * e.g.: actions/components/base
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->renderTemplate(
            'components/index',
            [
                'components' => Components::getInstance()
                    ->components
                    ->getComponents(),
                'pluginSettings' => [
                    "compFolders" => [
                        "_atoms",
                        "_molecules",
                        "_organisms",
                        "_embeds",
                        "_views"
                    ],
                    // The different buttons to resize the iFrame.
                    // It's up to you to motifie this sizes
                    "mqButtons" => [
                        "min" => "320px",
                        "xs" => "400px",
                        "s" => "600px",
                        "m" => "800px",
                        "l" => "1000px",
                        "max" => "1440px",
                        "fluid" => "100vw"
                    ],
                    // The different component statusses for your components
                    // Create your own or start with the following
                    "compStatus" => [
                        0 => ["name" => "wip", "color" => "#FF9800"],
                        1 => ["name" => "review", "color" => "#369BF4"],
                        2 => ["name" => "done", "color" => "#4CAF50"],
                        3 => ["name" => "discarded", "color" => "#F44336"],
                    ],
                    'pages' => [
        'typography' => [
            'label' => 'Typography',
            'options' => [
                'glyphsLower' => [
                    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                    'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                ],
                'glyphsUpper' => [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                    'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                ],
                'glyphsLigatures' => [
                    'ﬀ', 'ﬁ', 'ﬂ', 'ﬃ', 'ﬄ'
                ],
                'glyphsPunctuation' => [
                    '!', '"', '#', '%', '&', '\'', '(', ')', '*', ',', '.', '/', ':', ';',
                    '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '˜', '¡', '¦',
                    '§', '¨', '©', 'ª', '«', '¬', '®', '¯', '²', '³', '´', '¶', '·', '¹',
                    'º', '»', '¿', '‐', '–', '—', '―', '‘', '’', '‚', '“', '”', '„', '•',
                    '™',
                ],
                'glyphsCurrency' => [
                    '$', '¢', '£', '€', '¥', '฿'
                ],
                'headline' => 'The quick brown fox jumps over a Dog. Zwei Boxkämpfer jagen Eva durch Sylt portez ce vieux Whiskey blond.',
                'textSimple' => '
                  <p>
                    Shooting highlining in front of the supermoo*n was one of the most difficu*lt shots I’ve *chased to date. It is a completely hybrid process between shooting an action sport in the midst of a very technical landscape photograph.
                  </p>',
                'textFull' => '
                  <p>
                    Shooting <del>highlining</del> in front of the <strong>supermoo*n</strong> was one of the most difficu*lt shots I’ve *chased to date. It is a completely hybrid process between shooting an action sport in the midst of a very technical <mark>landscape photograph</mark>. It started as a simple challenge to myself and I <a href="#">quickly became</a> immersed in the process of figuring out how to shoot it.
                  </p>
                  <ul>
                    <li>Shooting highlining in front of the supermoo</li>
                    <li>One of the most difficu*lt shots I’ve *chased to date.</li>
                    <li>I’ve *chased to date.
                      <ul>
                        <li>Shooting highlining in front of the supermoo</li>
                        <li>One of the most difficu*lt shots I’ve *chased to date.</li>
                        <li>I’ve *chased to date.</li>
                      </ul>
                    </li>
                  </ul>
                  <p>
                    <em>Shooting highlining in front of the supermoo*n was one of the most difficu*lt shots I’ve *chased to date. It is a completely hybrid process between shooting an action sport in the midst of a very technical landscape photograph.</em>
                  </p>
                  <p>
                    <strong>Shooting highlining in front of the supermoo*n was one of the most difficu*lt shots I’ve *chased to date. It is a completely hybrid process between shooting an action sport in the midst of a very technical landscape photograph.</strong>
                  </p>
                  <ol>
                    <li>Shooting highlining in front of the supermoo</li>
                    <li>One of the most difficu*lt shots I’ve *chased to date.</li>
                    <li>I’ve *chased to date.
                      <ol>
                        <li>Shooting highlining in front of the supermoo</li>
                        <li>One of the most difficu*lt shots I’ve *chased to date.</li>
                        <li>I’ve *chased to date.</li>
                      </ol>
                    </li>
                  </ol>
                  <p>
                    Shooting highlining in front of the supermoo*n was one of the most difficu*lt shots I’ve *chased to date. It is a completely hybrid process between shooting an action sport in the midst of a very technical landscape photograph.
                  </p>'
            ]
        ]
    ]
                ],
                'patternlibBaseUrl' => UrlHelper::siteUrl(),
                'assetsUrl' => Craft::$app->assetManager->getPublishedUrl('@modules/components'),
            ]
        );
    }

    /**
     * @param string $component
     * @param string $varient
     * @return string
     */
    public function actionRender(string $component, string $type, string $variant)
    {
        if (!$component || !$variant) {
            return 'Please specific a component or variant';
        }

        // Get component details
        /* @var $component ComponentModel */
        $component = Components::getInstance()
            ->components
            ->getComponent($component, $type);

        $variant = $component
            ->getVariant($variant);

        $template = Components::getInstance()
            ->components
            ->getComponentTemplate($component);

        // Render component
        $oldMode = Craft::$app
            ->view
            ->getTemplateMode();

        Craft::$app
            ->view
            ->setTemplateMode(View::TEMPLATE_MODE_SITE);

        $html = Craft::$app
            ->view
            ->renderTemplate(
            'components/render',
            [
                'component' => '@components' . DIRECTORY_SEPARATOR . $template,
                'templateOptions' => $variant
            ]
        );

        Craft::$app
            ->view
            ->setTemplateMode($oldMode);

        return $html;
    }
}
