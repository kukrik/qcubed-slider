<?php
    use QCubed\Query\QQ;

    require_once('../../../../../qcubed.inc.php');

    header('Content-Type: application/json');

    $slides = Sliders::queryArray(
        QQ::Equal(QQN::sliders()->Status, 1),
        QQ::Clause(QQ::orderBy(QQN::sliders()->Order))
    );

    $arr = [];
    foreach ($slides as $slide) {
        $arr[] = [
            'groupId' => $slide->GroupId,
            'url' => $slide->Url,
            'title' => $slide->Title,
            'path' => $slide->Path,
            'extension' => $slide->Extension,
            'width' => $slide->Width,
            'height' => $slide->Height,
            'top' => $slide->Top,
        ];
    }
    echo json_encode($arr);
    exit;

