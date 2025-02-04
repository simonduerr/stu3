<?php

declare(strict_types=1);

namespace Stu\Module\Communication\Action\CreateKnPlot;

use Stu\Lib\Request\CustomControllerHelperTrait;

final class CreateKnPlotRequest implements CreateKnPlotRequestInterface
{
    use CustomControllerHelperTrait;

    public function getText(): string
    {
        return tidyString(strip_tags(
            $this->queryParameter('description')->string()->trim()->required()
        ));
    }

    public function getTitle(): string
    {
        return tidyString(strip_tags(
            $this->queryParameter('title')->string()->trim()->required()
        ));
    }
}