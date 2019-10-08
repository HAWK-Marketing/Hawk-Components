<?php
/**
 * Hawk Components module for Craft CMS 3.x
 *
 * A plugin that controls the components.
 *
 * @link      hawk.ca
 * @copyright Copyright (c) 2019 HAWK
 */

namespace modules\components\services;

use modules\components\Components;

use Craft;
use craft\base\Component;
use modules\components\models\Component as ComponentModel;

/**
 * Component Service
 *
 * All of your module’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other modules can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    HAWK
 * @package   Components
 * @since     1.0.0
 */
class ComponentService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function can literally be anything you want, and you can have as many service
     * functions as you want
     *
     * From any other plugin/module file, call it like this:
     *
     *     HawkComponentsModule::$instance->import->exampleService()
     *
     * @return mixed
     */
    public function getComponent(string $component, string $type) : ComponentModel
    {
        $config = json_decode(
            file_get_contents(__DIR__ . '/../../../../components/_atoms/cheese/component.json')
        );

        $component = new ComponentModel;

        $component->name = $config->name;
        $component->path = $config->path;
        $component->status = (bool) $config->status;
        $component->type = $config->type;
        $component->key = $config->key;
        $component->visible = (bool) $config->visible;
        $component->description = $config->description;
        $component->fields = (array) $config->field;
        $component->variants = (array) $config->variants;

        return $component;
    }


    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function getComponents() : array
    {
        $path = new \DirectoryIterator(
            $this->getComponentsPath()
        );

        $atoms = [];
        $molecules = [];
        $widgets = [];
        $views = [];
        $templates = [];

        foreach ($path as $file) {
            if ($file->isDot()) continue;

            if ($file->getFilename() === '_atoms') {
                $atoms = $this->getComponentsInDirectory($file->getRealPath());
            }

            if ($file->getFilename() === '_molecules') {
                $molecules = $this->getComponentsInDirectory($file->getRealPath());
            }

            if ($file->getFilename() === '_widgets') {
                $widgets = $this->getComponentsInDirectory($file->getRealPath());
            }

            if ($file->getFilename() === '_views') {
                $views = $this->getComponentsInDirectory($file->getRealPath());
            }

            if ($file->getFilename() === '_templates') {
                $templates = $this->getComponentsInDirectory($file->getRealPath());
            }
        }

        return [
            'atoms' => $atoms,
            'molecules' => $molecules,
            'widgets' => $widgets,
            'views' => $views,
            'templates' => $templates
        ];
    }

    public function getComponentsInDirectory($directory) : array
    {
        $path = new \DirectoryIterator(
            $directory
        );

        $component = [];
        foreach ($path as $file) {
            if ($file->isDot()) continue;

            if (file_exists($file->getRealPath() . DIRECTORY_SEPARATOR . 'component.json')) {
                $config = json_decode(
                    file_get_contents($file->getRealPath() . DIRECTORY_SEPARATOR . 'component.json')
                );

                // Remove field
                unset($config->field);

                $component[$file->getFilename()] = $config;
            }
        }

        return $component;
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function getComponentsPath() : string
    {
        return Craft::$app->path->getConfigPath() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'components';
    }

    /**
     * @param ComponentModel $component
     * @return string
     */
    public function getComponentTemplate(ComponentModel $component) : string
    {
        return $component->path  . DIRECTORY_SEPARATOR . '_' . $component->key . '.twig';
    }
}
