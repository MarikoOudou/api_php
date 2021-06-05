<?php

    $status = [
        Constant::$NODE_STATUS_CODE => Constant::$CODE_BAD,
        Constant::$NODE_STATUS_MESSAGE => Constant::$RESULT_BAD_MESSAGE
    ];

    $response = [
        Constant::$NODE_STATUS => $status,
        Constant::$NODE_HAS_ERROR => Constant::$RESULT_BAD_REQUEST,
        Constant::$NODE_ITEM => []
    ];

    header('Content-type: application/json');
    echo json_encode($response);

?>