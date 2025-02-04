<?php

declare(strict_types=1);

namespace Stu\Module\Starmap\View\ShowSystemEditField;

use Stu\Lib\Request\CustomControllerHelperTrait;

final class ShowSystemEditFieldRequest implements ShowSystemEditFieldRequestInterface
{
    use CustomControllerHelperTrait;

    public function getFieldId(): int
    {
        return $this->queryParameter('field')->int()->required();
    }
}