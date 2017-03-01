<?php
header('Content-Type: application/json');
include('Helper.php');
$world = $_POST['world'];
$level = $_POST['level'];
$mapPath = './files/' . $_POST['name'];
$sources = [];
switch ($world) {
    case 1:
        $sources = [
            's1' => 'sprites/Background - World 1.png',
            's2' => 'sprites/Enemy - World 1 - beetle.png',
            's3' => 'sprites/Enemy - World 1 - compost.png',
            's4' => 'sprites/Enemy - World 1 - frogger.png',
            's5' => 'sprites/Enemy - World 1 - lizman.png',
            's6' => 'sprites/Enemy - World 1 - rockman.png',
            's7' => 'sprites/Enemy - World 1 - thumper.png',
            's8' => 'sprites/Items - World 1.png',
            's9' => 'sprites/Misc - nodes.png'
        ];
        break;
    case 2:
        $sources = [
            's1' => 'sprites/Background - World 2.png',
            's2' => 'sprites/Enemy - World 2 - beast.png',
            's3' => 'sprites/Enemy - World 2 - blob.png',
            's4' => 'sprites/Enemy - World 2 - dhturret.png',
            's5' => 'sprites/Enemy - World 2 - dustdevl.png',
            's6' => 'sprites/Enemy - World 2 - fire_man.png',
            's7' => 'sprites/Enemy - World 2 - guard.png',
            's8' => 'sprites/Enemy - World 2 - kangaroo.png',
            's9' => 'sprites/Enemy - World 2 - sewer.png',
            's10' => 'sprites/Enemy - World 2 - steamjet.png',
            's11' => 'sprites/Enemy - World 2 - thumper.png',
            's12' => 'sprites/Items - World 2.png',
            's13' => 'sprites/Misc - nodes.png'
        ];
        break;
    case 3:
        $sources = [
            's1' => 'sprites/Background - World 3.png',
            's2' => 'sprites/Enemy - World 3 - cocoon.png',
            's3' => 'sprites/Enemy - World 3 - hand.png',
            's4' => 'sprites/Enemy - World 3 - lobber.png',
            's5' => 'sprites/Enemy - World 3 - mace.png',
            's6' => 'sprites/Enemy - World 3 - misslink.png',
            's7' => 'sprites/Enemy - World 3 - slug.png',
            's8' => 'sprites/Enemy - World 3 - spider.png',
            's9' => 'sprites/Enemy - World 3 - stonewat.png',
            's10' => 'sprites/Items - World 3.png',
            's11' => 'sprites/Misc - nodes.png'
        ];
        break;
    case 4:
        $sources = [
            's1' => 'sprites/Background - World 4a.png',
            's2' => 'sprites/Background - World 4b.png',
            's3' => 'sprites/Background - World 4c.png',
            's4' => 'sprites/Enemy - World 4 - barongun.png',
            's5' => 'sprites/Enemy - World 4 - emplacmt.png',
            's6' => 'sprites/Enemy - World 4 - emplgun.png',
            's7' => 'sprites/Enemy - World 4 - engyball.png',
            's8' => 'sprites/Enemy - World 4 - gyro_big.png',
            's9' => 'sprites/Enemy - World 4 - halftrak.png',
            's10' => 'sprites/Enemy - World 4 - mechanic.png',
            's11' => 'sprites/Enemy - World 4 - rat.png',
            's12' => 'sprites/Enemy - World 4 - revdome.png',
            's13' => 'sprites/Enemy - World 4 - robot.png',
            's14' => 'sprites/Enemy - World 4 - thebaron.png',
            's15' => 'sprites/Items - World 4.png',
            's16' => 'sprites/Misc - nodes.png'
        ];
        break;
}

$map = Helper::parseMap($mapPath);
$sprites = Helper::parseSprite(__DIR__.DIRECTORY_SEPARATOR, $sources);

echo json_encode([
    'info' => [
        'world' => $world,
        'level' => $level,
        'width' => imagesx(imagecreatefrompng($mapPath))
    ],
    'sources' => $sources,
    'map' => Helper::compare($map, $sprites),
]);