<?php

declare(strict_types=1);

namespace Stu\Module\Communication\Action\AddKnPost;

use Stu\Lib\Request\CustomControllerHelperTrait;

final class AddKnPostRequest implements AddKnPostRequestInterface
{
    use CustomControllerHelperTrait;

    public function getPostMark(): int
    {
        return $this->queryParameter('markposting')->int()->defaultsTo(0);
    }

    public function getPlotId(): int
    {
        return $this->queryParameter('plotid')->int()->defaultsTo(0);
    }

    public function getText(): string
    {
        return tidyString(strip_tags(
            $this->queryParameter('text')->string()->trim()->required()
        ));
    }

    public function getTitle(): string
    {
        return tidyString(strip_tags(
            $this->queryParameter('title')->string()->trim()->required()
        ));
    }
}