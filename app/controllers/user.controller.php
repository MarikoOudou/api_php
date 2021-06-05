<?php

    $status_code = Constant::$CODE_GOOD;
    $status_message = Constant::$RESULT_GOOD_MESSAGE;
    $has_error = Constant::$RESULT_GOOD_REQUEST;
    $items = null;
    $item = null;
    $add_items = true;
    $add_item = false;

    switch ($action) {
        case Constant::$ACTION_CONNEXION:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATA])) {
                $data_user = $data[Constant::$NODE_DATA];

                //var_dump($data_user); die();

                $error = false;
                $code = "";
                $msg = "";

                $args = array();
                foreach ($data_user as $k => $v) {
                    array_push($args, $k);
                }
                if (!in_array(User::$USER_NODE_LOGIN, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_LOGIN . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }
                if (!in_array(User::$USER_NODE_PWD, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_PWD . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }

                if (!$error) {
                    if($item = User::connexion($pdo, $data_user[User::$USER_NODE_LOGIN], $data_user[User::$USER_NODE_PWD])){
                        $add_items = false;
                        $add_item = true;
                    } else {
                        $error = true;
                        $code = Constant::$CODE_BAD;
                        $msg = "Email et/ou mot de passe incorrect.";
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
                $status_message = Constant::$NODE_DATA . " non fourni.";
                $has_error = Constant::$RESULT_BAD_REQUEST;
                $add_items = false;
            }
            break;
        case Constant::$ACTION_RESET_PASSWORD:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATA])) {
                $data_user = $data[Constant::$NODE_DATA];

                $error = false;
                $code = "";
                $msg = "";

                $args = array();
                foreach ($data_user as $k => $v) {
                    array_push($args, $k);
                }
                if (!in_array(User::$USER_NODE_EMAIL, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_EMAIL . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }
                if (!in_array(User::$USER_NODE_PWD, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_PWD . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }
                if (!in_array(User::$USER_NODE_NEW_MDP, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_NEW_MDP . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }

                if (!$error) {
                    if($item = User::connexion($pdo, $data_user[User::$USER_NODE_EMAIL], $data_user[User::$USER_NODE_PWD])){
                        $data_user[User::$USER_NODE_PWD] = sha1($data_user[User::$USER_NODE_NEW_MDP]);
                        $user = new User($pdo);
                        $item = (array) $item;
                        $item[User::$USER_NODE_PWD] = sha1($data_user[User::$USER_NODE_NEW_MDP]);
                        $user->update($item);
                        $add_items = false;
                        $add_item = true;
                    } else {
                        $error = true;
                        $code = Constant::$CODE_BAD;
                        $msg = "Email et/ou mot de passe incorrect.";
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
                $status_message = Constant::$NODE_DATA . " non fourni.";
                $has_error = Constant::$RESULT_BAD_REQUEST;
                $add_items = false;
            }
            break;
        case Constant::$ACTION_GET:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATA])) {
                $data_user = $data[Constant::$NODE_DATA];
                $user = new User($pdo);

                try {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;
                    $items = $user->getByCriteria($data_user);
                    $items = $items[Constant::$NODE_DATAS];
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
                $datas_user = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $users = array();

                foreach ($datas_user as $i => $user) {
                    $args = array();
                    foreach ($user as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(User::$USER_NODE_LOGIN, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = User::$USER_NODE_LOGIN . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(User::$USER_NODE_EMAIL, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = User::$USER_NODE_EMAIL . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(User::$USER_NODE_PWD, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = User::$USER_NODE_PWD . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                    if (!in_array(User::$USER_NODE_FULLNAME, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = User::$USER_NODE_FULLNAME . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    foreach ($datas_user as $i => $user) {
                        try {
                            $data_user[User::$USER_NODE_LOGIN] = $user[User::$USER_NODE_LOGIN];
                            $_user = new User($pdo);
                            $_users = $_user->getByCriteria($data_user, 0, PDO::FETCH_ASSOC);
                            if ($_users["count"] > 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = User::$USER_NODE_LOGIN . " déja utilisé.";
                                $_users = null;
                                break;
                            } else {
                                $du[User::$USER_NODE_EMAIL] = $user[User::$USER_NODE_EMAIL];
                                $_u = new User($pdo);
                                $_us = $_u->getByCriteria($du, 0, PDO::FETCH_ASSOC);
                                if ($_us["count"] > 0) {
                                    $error = true;
                                    $code = Constant::$ARGS_INVALID;
                                    $msg = User::$USER_NODE_EMAIL . " déja utilisé.";
                                    $_us = null;
                                    break;
                                } else {
                                    $_user = new User($pdo);
                                    $user[User::$USER_NODE_PWD] = sha1($user[User::$USER_NODE_PWD]);
                                    date_default_timezone_set('UTC');
                                    $user[User::$USER_NODE_DATE_CREATION] = date("Y-m-d");
                                    $user[User::$USER_NODE_ID] = $_user->insert($user);
                                    array_push($users, $user);
                                }
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
                    $items = $users;
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
                $datas_user = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $users = array();
                $args = array();

                foreach ($datas_user as $i => $user) {
                    foreach ($user as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(User::$USER_NODE_ID, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = User::$USER_NODE_ID . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    $users = array();
                    foreach ($datas_user as $i => $user) {
                        try {
                            $data_user[User::$USER_NODE_ID] = $user[User::$USER_NODE_ID];
                            $_user = new User($pdo);
                            $_users = $_user->getByCriteria($data_user, 0, PDO::FETCH_ASSOC);
                            if ($_users["count"] == 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = User::$USER_NODE_ID . " introuvable.";
                                $_users = null;
                                break;
                            }
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }

                        try {
                            $_user = new User($pdo);
                            if (in_array(User::$USER_NODE_FILEBASE64, $args)) {
                                try {
                                    $user[User::$USER_NODE_AVATAR] = AVATARROOT . $user[User::$USER_NODE_ID] . '.jpg';
                                    file_put_contents($user[User::$USER_NODE_AVATAR], base64_decode($user[User::$USER_NODE_FILEBASE64]));

                                    unset($user[User::$USER_NODE_FILEBASE64]);
                                    $_user->update($user);
                                    $_user->updateImg($user);
                                    array_push($users, $user);
                                } catch (Exception $e) {
                                    $error = true;
                                    $code = $e->getCode();
                                    $msg = $e->getMessage();
                                }
                            } else {
                                $_user->update($user);
                                array_push($users, $user);
                            }
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
                    $items = $users;
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
        case Constant::$ACTION_GET_FORGOTTEN_PASSWORD_CODE:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATA])) {
                $data_user = $data[Constant::$NODE_DATA];

                $error = false;
                $code = "";
                $msg = "";
                $users = array();

                $args = array();
                foreach ($data_user as $k => $v) {
                    array_push($args, $k);
                }
                if (!in_array(User::$USER_NODE_EMAIL, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_EMAIL . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }

                if (!$error) {
                    $users = array();
                    try {
                        $data_user[User::$USER_NODE_EMAIL] = $user[User::$USER_NODE_EMAIL];
                        $_user = new User($pdo);
                        $_users = $_user->getByCriteria($data_user, 0, PDO::FETCH_ASSOC);
                        if ($_users["count"] != 1) {
                            $error = true;
                            $code = Constant::$ARGS_INVALID;
                            $msg = User::$USER_NODE_EMAIL . " introuvable.";
                            $_users = null;
                        }
                    } catch (Exception $e) {
                        $error = true;
                        $code = $e->getCode();
                        $msg = $e->getMessage();
                    }

                    try {
                        $validation_code = generateRandomString(8);

                        $__user = $_users[Constant::$NODE_DATAS][0];
                        $__user = (array) $__user;
                        $__user[User::$USER_NODE_VALIDATION_CODE] = $validation_code;

                        $_user = new User($pdo);
                        $_user->update($__user);

                        $message = 'Salut ' . ucwords($__user[User::$USER_NODE_PRENOM]) . ' ' . ucwords($__user[User::$USER_NODE_NOM]) .',<br><br>

                                    Merci de confirmer la mise à jour de votre mot de passe avec le code suivant:<br><br>
                                    Code de validation: <b>'.$validation_code.'</b>';
                        sendMail($data_user[User::$USER_NODE_EMAIL], Constant::$FORGOTTEN_PASSWORD_CODE, $message);
                    } catch (Exception $e) {
                        $error = true;
                        $code = $e->getCode();
                        $msg = Constant::$UNKNOW_EMAIL;
                    }
                }

                if (!$error) {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;
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
        case Constant::$ACTION_VALIDATE_FORGOTTEN_PASSWORD_CODE:
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data[Constant::$NODE_DATA])) {
                $data_user = $data[Constant::$NODE_DATA];

                $error = false;
                $code = "";
                $msg = "";
                $users = array();

                $args = array();
                foreach ($data_user as $k => $v) {
                    array_push($args, $k);
                }
                if (!in_array(User::$USER_NODE_PWD, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_PWD . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }
                if (!in_array(User::$USER_NODE_VALIDATION_CODE, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_VALIDATION_CODE . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }
                if (!in_array(User::$USER_NODE_EMAIL, $args)) {
                    $code = Constant::$CODE_BAD;
                    $msg = User::$USER_NODE_EMAIL . " non fourni.";
                    $has_error = Constant::$RESULT_BAD_REQUEST;
                    $error = true;
                }

                if (!$error) {
                    $users = array();
                    try {
                        $data_user[User::$USER_NODE_EMAIL] = $user[User::$USER_NODE_EMAIL];
                        $_user = new User($pdo);
                        $_users = $_user->getByCriteria($data_user, 0, PDO::FETCH_ASSOC);
                        if ($_users["count"] != 1) {
                            $error = true;
                            $code = Constant::$ARGS_INVALID;
                            $msg = User::$USER_NODE_EMAIL . " introuvable.";
                            $_users = null;
                        } else {
                            $__user = $_users[Constant::$NODE_DATAS][0];
                            $__user = (array) $__user;

                            if ($__user[User::$USER_NODE_VALIDATION_CODE] != $data_user[User::$USER_NODE_VALIDATION_CODE]) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = Constant::$INVALID_CODE;
                                $_users = null;
                            }
                        }
                    } catch (Exception $e) {
                        $error = true;
                        $code = $e->getCode();
                        $msg = $e->getMessage();
                        break;
                    }

                    try {
                        $__user = $_users[Constant::$NODE_DATAS][0];
                        $__user = (array) $__user;
                        $__user[User::$USER_NODE_PWD] = sha1($data_user[User::$USER_NODE_PWD]);
                        $__user[User::$USER_NODE_VALIDATION_CODE] = "";

                        $_user = new User($pdo);
                        $_user->update($__user);
                    } catch (Exception $e) {
                        $error = true;
                        $code = $e->getCode();
                        $msg = Constant::$UNKNOW_EMAIL;
                        break;
                    }
                }

                if (!$error) {
                    $status_code = Constant::$CODE_GOOD;
                    $status_message = Constant::$RESULT_GOOD_MESSAGE;
                    $has_error = Constant::$RESULT_GOOD_REQUEST;
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
                $datas_user = $data[Constant::$NODE_DATAS];

                $error = false;
                $code = "";
                $msg = "";
                $users = array();

                foreach ($datas_user as $i => $user) {
                    $args = array();
                    foreach ($user as $k => $v) {
                        array_push($args, $k);
                    }
                    if (!in_array(User::$USER_NODE_ID, $args)) {
                        $code = Constant::$CODE_BAD;
                        $msg = User::$USER_NODE_ID . " non fourni.";
                        $has_error = Constant::$RESULT_BAD_REQUEST;
                        $error = true;
                        break;
                    }
                }

                if (!$error) {
                    foreach ($datas_user as $i => $user) {
                        try {
                            $data_user[User::$USER_NODE_ID] = $user[User::$USER_NODE_ID];
                            $_user = new User($pdo);
                            $_users = $_user->getByCriteria($data_user, 0, PDO::FETCH_ASSOC);
                            if ($_users["count"] == 0) {
                                $error = true;
                                $code = Constant::$ARGS_INVALID;
                                $msg = User::$USER_NODE_ID . " introuvable.";
                                $_users = null;
                                break;
                            }
                        } catch (Exception $e) {
                            $error = true;
                            $code = $e->getCode();
                            $msg = $e->getMessage();
                            break;
                        }

                        try {
                            $_user = new User($pdo);
                            $_user->delete($user[User::$USER_NODE_ID]);
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

    apiResponse($status_code, $status_message, $has_error, $items, $item, $add_items, $add_item);

?>