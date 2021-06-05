<?php

    $status_code = Constant::$CODE_GOOD;
    $status_message = Constant::$RESULT_GOOD_MESSAGE;
    $has_error = Constant::$RESULT_GOOD_REQUEST;
    $count = null;
    $items = null;
    $item = null;
    $add_items = true;
    $add_item = false;

    switch ($action) {
        case Constant::$ACTION_GET:
            $data = json_decode(file_get_contents('php://input'), true);
            

            if (isset($data[Constant::$NODE_DATA])) {
                $data_invites = $data[Constant::$NODE_DATA];
                $invites = new Invites($pdo);

                $invitess = array();

                try {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;

                    
                    $items = $invites->getByCriteria($data_invites);

                } catch (Exception $e) {
                    $status_code = $e->getCode();
                    $status_message = $e->getMessage();
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                }
            } else {
                $status_code = Constant::$CODE_BAD;
                $status_message = Constant::$NODE_DATA . " non fourni.";
                $has_error = Constant::$RESULT_BAD_REQUEST;
                $add_items = false;
            }
            break;
        case Constant::$ACTION_CREATE:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATAS])) {
                $datas_invites = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $invitess = array();

                foreach ($datas_invites as $i => $invites) {
                    $args = array();
                    foreach ($invites as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(Invites::$INVITES_NODE_NOM, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_NOM . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Invites::$INVITES_NODE_PRENOMS, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_PRENOMS . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Invites::$INVITES_NODE_CONTACT, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_CONTACT . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Invites::$INVITES_NODE_EMAIL, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_EMAIL . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Invites::$INVITES_NODE_DATE, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_DATE . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Invites::$INVITES_NODE_NOMBREPERSO, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_NOMBREPERSO . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Invites::$INVITES_NODE_PACKAGE, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_PACKAGE . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    foreach ($datas_invites as $i => $invites) {
                        try {
                            $data_invites[Invites::$INVITES_NODE_EMAIL] = $invites[Invites::$INVITES_NODE_EMAIL];
                            $_invites = new Invites($pdo);
                            $_invitess = $_invites->getByCriteria($data_invites, 0, PDO::FETCH_ASSOC);
                            if ($_invitess["count"] > 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = Invites::$INVITES_NODE_EMAIL . " déja utilisé.";
                                $_invitess = null;
                                break;
                            } else {
                                $_invites = new Invites($pdo);
                                date_default_timezone_set('UTC');
                                $invites[Invites::$INVITES_NODE_DATE_CREATION] = date("Y-m-d hh:mm");
                                $invites[Invites::$INVITES_NODE_ID] = $_invites->insert($invites);
                                array_push($invitess, $invites);
                            }
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }
                    }
                }

                if (!$error) {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;
                    $items = $invitess;
                } else {
                    $status_code = $code;
                    $status_message = $msg;
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $add_items = false;
                    // TODO: Supprimer si échec
                }
            } else {
                $status_code = Constant::$CODE_BAD;
                $status_message = Constant::$NODE_DATAS . " non fourni.";
                $has_error = Constant::$RESULT_BAD_REQUEST;
                $add_items = false;
            }
            break;
        case Constant::$ACTION_UPDATE:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATAS])) {
                $datas_invites = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $invitess = array();
                $args = array();

                foreach ($datas_invites as $i => $invites) {
                    foreach ($invites as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(Invites::$INVITES_NODE_ID, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_ID . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    $invitess = array();
                    foreach ($datas_invites as $i => $invites) {
                        try {
                            $data_invites[Invites::$INVITES_NODE_ID] = $invites[Invites::$INVITES_NODE_ID];
                            $_invites = new Invites($pdo);
                            $_invitess = $_invites->getByCriteria($data_invites, 0, PDO::FETCH_ASSOC);
                            if ($_invitess["count"] == 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = Invites::$INVITES_NODE_ID . " introuvable.";
                                $_invitess = null;
                                break;
                            }
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }

                        try {
                            $_invites = new Invites($pdo);
                            $_invites->update($invites);
                            array_push($invitess, $invites);
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                        }
                    }
                }

                if (!$error) {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;
                    $items = $invitess;
                    if (isset($items[Constant::$NODE_DATAS])) $items = $items[Constant::$NODE_DATAS];
                } else {
                    $status_code = $code;
                    $status_message = $msg;
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $add_items = false;
                }
            } else {
                $status_code = Constant::$CODE_BAD;
                $status_message = Constant::$NODE_DATAS . " non fourni.";
                $has_error = Constant::$RESULT_BAD_REQUEST;
                $add_items = false;
            }
            break;
        case Constant::$ACTION_DELETE:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATAS])) {
                $datas_invites = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $invitess = array();

                foreach ($datas_invites as $i => $invites) {
                    $args = array();
                    foreach ($invites as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(Invites::$INVITES_NODE_ID, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Invites::$INVITES_NODE_ID . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    foreach ($datas_invites as $i => $invites) {
                        try {
                            $data_invites[Invites::$INVITES_NODE_ID] = $invites[Invites::$INVITES_NODE_ID];
                            $_invites = new Invites($pdo);
                            $_invitess = $_invites->getByCriteria($data_invites, 0, PDO::FETCH_ASSOC);
                            if ($_invitess["count"] == 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = Invites::$INVITES_NODE_ID . " introuvable.";
                                $_invitess = null;
                                break;
                            }
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }

                        try {
                            $_invites = new Invites($pdo);
                            $_invites->delete($invites[Invites::$INVITES_NODE_ID]);
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }
                    }
                }

                if (!$error) {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;
                    $add_items = false;
                } else {
                    $status_code = $code;
                    $status_message = $msg;
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $add_items = false;
                }
            } else {
                $status_code = Constant::$CODE_BAD;
                $status_message = Constant::$NODE_DATAS . " non fourni.";
                $has_error = Constant::$RESULT_BAD_REQUEST;
                $add_items = false;
            }
            break;
        case Constant::$ACTION_MISSING:
            $status_code = Constant::$CODE_BAD;
            $status_message = Constant::$RESULT_ACTION_MISSING;
            $has_error = Constant::$RESULT_BAD_REQUEST;
            $add_items = false;
            break;
        default:
            $status_code = Constant::$CODE_BAD;
            $status_message = Constant::$RESULT_ACTION_MISSING;
            $has_error = Constant::$RESULT_BAD_REQUEST;
            $add_items = false;
            break;
    }

    apiResponse($status_code, $status_message, $has_error, $items, $item, $add_items, $add_item, $count);

?>