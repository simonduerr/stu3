<?php

declare(strict_types=1);

namespace Stu\Module\Starmap\View\ShowOverall;

use AccessViolation;
use Stu\Module\Control\GameControllerInterface;
use Stu\Module\Control\ViewControllerInterface;
use Stu\Orm\Repository\MapBorderTypeRepositoryInterface;
use Stu\Orm\Repository\MapFieldTypeRepositoryInterface;
use Stu\Orm\Repository\MapRepositoryInterface;

final class ShowOverall implements ViewControllerInterface
{
    public const VIEW_IDENTIFIER = 'SHOW_OVERALL';

    private $mapBorderTypeRepository;

    private $mapFieldTypeRepository;

    private $mapRepository;

    public function __construct(
        MapBorderTypeRepositoryInterface $mapBorderTypeRepository,
        MapFieldTypeRepositoryInterface $mapFieldTypeRepository,
        MapRepositoryInterface $mapRepository
    ) {
        $this->mapBorderTypeRepository = $mapBorderTypeRepository;
        $this->mapFieldTypeRepository = $mapFieldTypeRepository;
        $this->mapRepository = $mapRepository;
    }

    public function handle(GameControllerInterface $game): void
    {
        if (!$game->isAdmin()) {
            throw new AccessViolation();
        }
        $types = [];
        $img = imagecreatetruecolor(MAP_MAX_X * 15, MAP_MAX_Y * 15);

        // mapfields
        $startY = 1;
        $cury = 0;
        $curx = 0;

        foreach ($this->mapRepository->getAllOrdered() as $data) {
            if ($startY != $data->getCy()) {
                $startY = $data->getCy();
                $curx = 0;
                $cury += 15;
            }
            $borderType = $data->getMapBorderType();
            if ($borderType !== null) {
                $border = imagecreatetruecolor(15, 15);
                $var = '#' . $borderType->getColor();
                $arr = sscanf($var, '#%2x%2x%2x');
                $col = imagecolorallocate($border, $arr[0], $arr[1], $arr[2]);
                imagefill($border, 0, 0, $col);
                imagecopy($img, $border, $curx, $cury, 0, 0, 15, 15);
                $curx += 15;
                continue;
            }
                $types[$data->getFieldId()] = imagecreatefromgif(APP_PATH . 'src/assets/map/' . $data->getFieldType()->getType(). '.gif');
            imagecopyresized($img, $types[$data->getFieldId()], $curx, $cury, 0, 0, 15, 15, 30, 30);
            $curx += 15;
        }
        header("Content-type: image/png");
        imagepng($img);
        imagedestroy($img);
        exit;
    }
}