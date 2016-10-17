<?php

namespace Testgen\Generators;

/**
 * Class CestModel
 *
 * @package Testgen\Generators
 */
class CestModel extends CestAction
{
    /**
     * @param $template
     * @return string
     */
    protected function generateActions($template)
    {
        $data = '';
        foreach ($this->settings['actions'] as $key => $action) {
            $data = $this->getData($this->settings['actions']);
        }
        $tableName = strtolower($this->name);
        $template .= <<<EOL
                  
    public function {$this->name}Test(AcceptanceTester \$I)
    {
        \$data = [
            {$data}
        ];
        \$I->dontSeeInDatabase('{$tableName}', \$data);
        \${$tableName} = new {$this->name}();
        \${$tableName}->load(\$data);
        \${$tableName}->save();
        \$I->seeInDatabase('{$tableName}', \$data);
    }

EOL;
        return $template;
    }

    /**
     * @param $actions
     * @return mixed
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
     * @param $type
     * @return bool|int|string
     */
    protected function getValue($type)
    {
        switch ($type) {
            case 'string' :
                return 'qwer';
                break;
            case 'integer' :
                return 7;
                break;
            default :
                return false;
        }
    }

}