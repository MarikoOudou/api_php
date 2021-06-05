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
                $data_member = $data[Constant::$NODE_DATA];
                $member = new Member($pdo);

                $members = array();

                try {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;
                    $items = $items[Constant::$NODE_DATAS];
                    $count = $items["count"];
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
                $datas_member = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $members = array();

                foreach ($datas_member as $i => $member) {
                    $args = array();
                    foreach ($member as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(Member::$MEMBER_NODE_NAME, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_NAME . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Member::$MEMBER_NODE_FORENAME, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_FORENAME . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Member::$MEMBER_NODE_MAIL, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_MAIL . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Member::$MEMBER_NODE_PHONE, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_PHONE . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Member::$MEMBER_NODE_COMPANY, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_COMPANY . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Member::$MEMBER_NODE_COUNTRY, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_COUNTRY . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(Member::$MEMBER_NODE_ACCEPTED, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_ACCEPTED . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    foreach ($datas_member as $i => $member) {
                        try {
                            $data_member[Member::$MEMBER_NODE_MAIL] = $member[Member::$MEMBER_NODE_MAIL];
                            $_member = new Member($pdo);
                            $_members = $_member->getByCriteria($data_member, 0, PDO::FETCH_ASSOC);
                            if ($_members["count"] > 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = Member::$MEMBER_NODE_MAIL . " déja utilisé.";
                                $_members = null;
                                break;
                            } else {
                                $_member = new Member($pdo);
                                date_default_timezone_set('UTC');
                                $member[Member::$MEMBER_NODE_DATE_CREATION] = date("Y-m-d hh:mm");
                                $member[Member::$MEMBER_NODE_ID] = $_member->insert($member);
                                array_push($members, $member);
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
                    $items = $members;
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
                $datas_member = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $members = array();
                $args = array();

                foreach ($datas_member as $i => $member) {
                    foreach ($member as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(Member::$MEMBER_NODE_ID, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_ID . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    $members = array();
                    foreach ($datas_member as $i => $member) {
                        try {
                            $data_member[Member::$MEMBER_NODE_ID] = $member[Member::$MEMBER_NODE_ID];
                            $_member = new Member($pdo);
                            $_members = $_member->getByCriteria($data_member, 0, PDO::FETCH_ASSOC);
                            if ($_members["count"] == 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = Member::$MEMBER_NODE_ID . " introuvable.";
                                $_members = null;
                                break;
                            }
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }

                        try {
                            $_member = new Member($pdo);
                            $_member->update($member);
                            array_push($members, $member);
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
                    $items = $members;
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
                $datas_member = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $members = array();

                foreach ($datas_member as $i => $member) {
                    $args = array();
                    foreach ($member as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(Member::$MEMBER_NODE_ID, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = Member::$MEMBER_NODE_ID . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    foreach ($datas_member as $i => $member) {
                        try {
                            $data_member[Member::$MEMBER_NODE_ID] = $member[Member::$MEMBER_NODE_ID];
                            $_member = new Member($pdo);
                            $_members = $_member->getByCriteria($data_member, 0, PDO::FETCH_ASSOC);
                            if ($_members["count"] == 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = Member::$MEMBER_NODE_ID . " introuvable.";
                                $_members = null;
                                break;
                            }
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }

                        try {
                            $_member = new Member($pdo);
                            $_member->delete($member[Member::$MEMBER_NODE_ID]);
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