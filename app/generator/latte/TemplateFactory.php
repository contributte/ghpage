<?php

namespace Generator\Latte;

use Latte\Engine;
use Nette\Application\UI;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory as NTemplateFactory;

final class TemplateFactory extends NTemplateFactory
{

    /**
     * @param UI\Control $control
     */
    public function createTemplate(UI\Control $control = NULL)
    {
        $template = parent::createTemplate($control);

        return $template;
    }

}
