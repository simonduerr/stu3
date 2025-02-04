<?php

declare(strict_types=1);

namespace Stu\Module\Communication\Action\EditContactComment;

use Stu\Lib\Request\CustomControllerHelperTrait;

final class EditContactCommentRequest implements EditContactCommentRequestInterface
{
    use CustomControllerHelperTrait;

    public function getContactId(): int
    {
        return $this->queryParameter('edit_contact')->int()->required();
    }

    public function getText(): string
    {
        return tidyString(strip_tags(
            $this->queryParameter(sprintf('comment_%d', $this->getContactId()))->string()->trim()->defaultsToIfEmpty('')
        ));
    }
}