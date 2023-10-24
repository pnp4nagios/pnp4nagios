<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable PSR1.Files.SideEffects
defined('SYSPATH') or die('No direct access allowed.');
// phpcs:enable PSR1.Files.SideEffects

/**
 * Ajax controller.
 *
 * @package    PNP4Nagios
 * @author     Joerg Linge
 * @license    GPL
 */
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps


class Ajax_Controller extends System_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Disable auto-rendering
        $this->auto_render = false;
    }

    public function index()
    {
        url::redirect("start", 302);
    }

    public function search()
    {
        $query     = pnp::clean($this->input->get('term'));
        $result    = array();
        if (strlen($query) >= 1) {
            $hosts = $this->data->getHosts();
            foreach ($hosts as $host) {
                if (preg_match("/$query/i", $host['name'])) {
                    array_push($result, $host['name']);
                }
            }
            echo json_encode($result);
        }
    }

    public function remove($what)
    {
        if ($what == 'timerange') {
            $this->session->delete('start');
            $this->session->delete('end');
            $this->session->set('timerange-reset', 1);
        }
    }

    public function filter($what)
    {
        $received_token = $_POST['csrf_token'];
        $token = Security::token();

        if (!Security::check($received_token, $token)) {
            echo "CSRF Token invalid";
            return false;
        }

        if ($what == 'set-sfilter') {
            $this->session->set('sfilter', htmlspecialchars($_POST['sfilter']));
        } elseif ($what == 'set-spfilter') {
            $this->session->set('spfilter', htmlspecialchars($_POST['spfilter']));
        } elseif ($what == 'set-pfilter') {
            $this->session->set('pfilter', htmlspecialchars($_POST['pfilter']));
        }
    }

    public function basket($action = false)
    {
        // Disable auto-rendering
        $this->auto_render = false;
        $host     = false;
        $service  = false;
        $basket   = array();

        if ($action == "list") {
            $basket = $this->session->get("basket");
            if (is_array($basket) && (!empty($basket))) {
                foreach ($basket as $item) {
                    printf(
                        "<li class=\"ui-state-default %s\" id=\"%s\"><a title=\"%s\" id=\"%s\"><img width=12px height=12px src=\"%smedia/images/remove.png\"></a>%s</li>\n",
                        "basket_action_remove",
                        $item,
                        $item,
                        Kohana::lang('common.basket-remove', $item),
                        url::base(),
                        pnp::shorten($item)
                    );
                }
            }
        } elseif ($action == "add") {
            $received_token = $_POST['csrf_token'];
            $token = Security::token();

            if (!Security::check($received_token, $token)) {
                echo "CSRF Token invalid";
                return false;
            }

            $item = htmlspecialchars($_POST['item']);
            $basket = $this->session->get("basket");
            if (!is_array($basket)) {
                $basket = [];
                $basket[] = "$item";
            } else {
                if (!in_array($item, $basket)) {
                    $basket[] = $item;
                }
            }
            $this->session->set("basket", $basket);
            foreach ($basket as $item) {
                printf(
                    "<li class=\"ui-state-default %s\" id=\"%s\"><a title=\"%s\" id=\"%s\"><img width=12px height=12px src=\"%smedia/images/remove.png\"></a>%s</li>\n",
                    "basket_action_remove",
                    $item,
                    $item,
                    Kohana::lang('common.basket-remove', $item),
                    url::base(),
                    pnp::shorten($item)
                );
            }
        } elseif ($action == "sort") {
            $received_token = $_POST['csrf_token'];
            $token = Security::token();

            if (!Security::check($received_token, $token)) {
                echo "CSRF Token invalid";
                return false;
            }

            $item = htmlspecialchars($_POST['item']);
            $basket = explode(',', $items);
            array_pop($basket);
            $this->session->set("basket", $basket);
            foreach ($basket as $item) {
                printf(
                    "<li class=\"ui-state-default %s\" id=\"%s\"><a title=\"%s\" id=\"%s\"><img width=12px height=12px src=\"%smedia/images/remove.png\"></a>%s</li>\n",
                    "basket_action_remove",
                    $item,
                    $item,
                    Kohana::lang('common.basket-remove', $item),
                    url::base(),
                    pnp::shorten($item)
                );
            }
        } elseif ($action == "remove") {
            $received_token = $_POST['csrf_token'];
            $token = Security::token();

            if (!Security::check($received_token, $token)) {
                echo "CSRF Token invalid";
                return false;
            }

            $basket = $this->session->get("basket");
            $item_to_remove = htmlspecialchars($_POST['item']);
            $new_basket = array();
            foreach ($basket as $item) {
                if ($item ==  $item_to_remove) {
                    continue;
                }
                $new_basket[] = $item;
            }
            $basket = $new_basket;
            $this->session->set("basket", $basket);
            foreach ($basket as $item) {
                printf(
                    "<li class=\"ui-state-default %s\" id=\"%s\"><a title=\"%s\" id=\"%s\"><img width=12px height=12px src=\"%smedia/images/remove.png\"></a>%s</li>\n",
                    "basket_action_remove",
                    $item,
                    $item,
                    Kohana::lang('common.basket-remove', $item),
                    url::base(),
                    pnp::shorten($item)
                );
            }
        } elseif ($action == "clear") {
            $this->session->delete("basket");
        } else {
            echo "Action $action not known";
        }
        $basket = $this->session->get("basket");
        if (is_array($basket) && empty($basket)) {
            echo Kohana::lang('common.basket-empty');
        } else {
            echo "<div align=\"center\" class=\"p2\">\n";
            echo "<button id=\"show\">" . Kohana::lang('common.basket-show') . "</button>\n";
            echo "<button id=\"clear\">" . Kohana::lang('common.basket-clear') . "</button>\n";
            echo "</div>\n";
        }
    }
}
