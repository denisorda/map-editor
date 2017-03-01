<?php

class Helper
{
    public static function parseMap($path)
    {
        $mapArr = [];
        $map = imagecreatefrompng($path);
        $sizeMapX = imagesx($map);
        $sizeMapY = imagesy($map);
        $y = 0;
        $width = 16;
        $height = 16;
        for ($i = 0; $i < ($sizeMapY / ($height + 1)) - 1; $i++) {
            $x = 0;
            for ($j = 0; $j < ($sizeMapX / ($width + 1)) - 1; $j++) {
                $im = imagecrop($map, ['x' => $x + 1, 'y' => $y + 1, 'width' => $width, 'height' => $height]);
                if ($im !== FALSE) {
                    imagepng($im, './result/map.png');
                    $hash = hash_file('md5', './result/map.png');
                } else {
                    $hash = '-';
                }
                $mapArr[] = $hash;
                $x += 1 + $width;
            }
            $y += 1 + $height;
        }
        return $mapArr;
    }

    public static function parseSprite($path, $sprites)
    {
        $spritesAll = [];
        foreach ($sprites as $key=>$value) {
            $spriteArr = [];
            $sprite = imagecreatefrompng($path . $sprites[$key]);
            $sizeSpriteX = imagesx($sprite);
            $sizeSpriteY = imagesy($sprite);
            $y = 0;
            $width = 16;
            $height = 16;
            for ($i = 0; $i < ($sizeSpriteY / ($height + 1)) - 1; $i++) {
                $x = 0;
                for ($j = 0; $j < ($sizeSpriteX / ($width + 1)) - 1; $j++) {
                    $im = imagecrop($sprite, ['x' => $x + 1, 'y' => $y + 1, 'width' => $width, 'height' => $height]);
                    if ($im !== FALSE) {
                        imagepng($im, './result/sprite.png');
                        $hash = hash_file('md5', './result/sprite.png');
                        $spriteArr[$hash] = ["src" => $key, "row" => $i + 1, "col" => $j + 1];
                    }
                    $x += 1 + $width;
                }
                $y += 1 + $height;
            }
            $spritesAll[$key] = $spriteArr;
        }
        return $spritesAll;

    }

    function getSprite($sprites, $hash){
        foreach ($sprites as $sprite){
            if (!empty($sprite[$hash])) {
                return $sprite[$hash];
            }
        }
        return ["src" => '', "row" => '', "col" => ''];
    }

    public static function compare($map, $sprites)
    {
        $res = [];
        foreach ($map as $hash){
            $res[] = self::getSprite($sprites, $hash);
        }
        return $res;
    }

}