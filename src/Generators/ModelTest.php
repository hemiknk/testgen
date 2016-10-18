<?php

namespace Testgen\Generators;

use Faker\Factory;
use Testgen\Template;
use Testgen\View;

/**
 * Class ModelTest
 *
 * @package Testgen\Generators
 */
class ModelTest extends AbstractTest
{
    /**
     * Generate test actions
     *
     * @return string
     */
    protected function generateActions()
    {
        $data = '';
        foreach ($this->settings['actions'] as $key => $action) {
            $data = $this->getData($this->settings['actions']);
        }
        $tableName = strtolower($this->name);

        $template = (new View(Template::getModel()))
            ->place('name', $this->name)
            ->place('data', $data)
            ->place('tableName', $tableName)
            ->produce();
        return $template;
    }

    /**
     * Return fake data string
     *
     * @param $actions
     * @return string
     */
    protected function getData($actions)
    {
        $result = [];
        foreach ($actions as $action) {
            $typeField = explode(' ', trim($action));
            if ($value = $this->getValue($typeField[0])) {
                $result[str_replace('$', '', $typeField[1])] = $value;
            }
        }
        $result = json_encode($result, true);
        $result = str_replace(":", ' => ', $result);
        $result = str_replace(['{', '}'], '', $result);
        return str_replace(',"', ',' . PHP_EOL . str_repeat(' ', 12) . '"', $result);
    }

    /**
     * Return required data type
     *
     * @param $type
     * @return bool|int|string
     */
    protected function getValue($type)
    {
        $faker = Factory::create();
        switch ($type) {
            case 'string' :
                return $faker->name;
                break;
            case 'integer' :
                return $faker->randomDigit;
                break;
            default :
                return false;
        }
    }

}