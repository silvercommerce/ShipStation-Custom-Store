<?php

namespace SilverCommerce\ShipStationCS;

use DateTime;
use LogicException;
use SilverStripe\ORM\DataList;
use SilverStripe\Control\Director;
use SilverStripe\Core\Environment;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Config\Config;
use SilverStripe\Control\HTTPRequest;
use SilverCommerce\OrdersAdmin\Model\Invoice;
use SilverStripe\Security\BasicAuth;
use SilverStripe\Security\PermissionProvider;

class EndpointController extends Controller implements PermissionProvider
{
    const PARAM_START_DATE = 'start_date';

    const PARAM_END_DATE = 'end_date';

    const AUTH_CODE = 'SHIPSTATION_CS_AUTH';

    const AUTH_REALM = 'ShipStation Custom Store';

    private static $url_segment = "shipstationcs";

    private static $allowed_actions = [
        'orders',
        'ship'
    ];

    private static $url_handlers = [
        'orders/$Key' => 'orders',
        'ship/$Key' => 'status',
    ];

    private static $order_filters = [
        'Status' => 'paid'
    ];

    public function init()
    {
        parent::init();

        $request = $this->getRequest();
        $realm = self::AUTH_REALM;
        $code = self::AUTH_CODE;
        BasicAuth::requireLogin($request, $realm, $code);

        return;
    }

    public function validURLKey(): bool
    {
        $request = $this->getRequest();
        $url_key = (string)$request->param('Key');
        $env_key = (string)self::getEndpointKey();

        $compare = strcmp($url_key, $env_key);

        if ($compare === 0) {
            return true;
        }

        return false;
    }

    public static function getEndpointKey(): string
    {
        return (string)Environment::getEnv('SHIPSTATION_ENDPOINT_KEY');
    }

    public function Link($action = null): string
    {
        $key = self::getEndpointKey();
        $segment = Config::inst()->get(
            self::class,
            'url_segment'
        );

        return Controller::join_links(
            $key,
            $segment,
            $action
        );
    }

    public function AbsoluteLink($action = null): string
    {
        $link = $this->Link($action);
        return Director::absoluteURL($link);
    }

    protected function getDateFilter(): array
    {
        $request = $this->getRequest();
        $date_filter = [];
        $start_param = $request->getVar(self::PARAM_START_DATE);
        $end_param = $request->getVar(self::PARAM_END_DATE);

        if (empty($start_param) || empty($end_param)) {
            return $date_filter;
        }

        $start_date = new DateTime($start_param);
        $end_date = new DateTime($end_param);
        
        return [
            "StartDate:LessThanOrEqual" => $end_date->format("Y-m-d"),
            "StartDate:GreaterThanOrEqual" =>  $start_date->format("Y-m-d")
        ];
    }

    public function getValidOrders(): DataList
    {
        $filters = Config::inst()->get(
            self::class,
            'order_filters'
        );

        if (!is_array($filters)) {
            throw new LogicException('Order filters must be an array of ORM filters');
        }

        $date_filter = $this->getDateFilter();
        $filters = array_merge($filters, $date_filter);

        return Invoice::get()->filter($filters);
    }

    public function getPaginatedOrders(int $length = 50): CSPaginatedList
    {
        $request = $this->getRequest();
        $orders = $this->getValidOrders();
        $list = CSPaginatedList::create($orders, $request);
        $list->setPageLength($length);

        return $list;
    }

    public function orders(HTTPRequest $request): string
    {
        $valid = $this->validURLKey();

        if (!$valid) {
            return $this->httpError(404);                                                           
        }

        return $this->render();
    }

    public function ship(HTTPRequest $request): string
    {
        $valid = $this->validURLKey();

        if (!$valid) {
            return $this->httpError(404);
        }

        return $this->render();
    }

    public function providePermissions()
    {
        return [
            self::AUTH_CODE => [
                'name' => _t(static::class . '.PermissionName', 'ShipStation Custom Store'),
                'help' => _t(static::class . '.PermissionHelp', 'Allow ShipStation access to the custom store feed'),
                'category' => 'ShipStation',
                'sort' => 0
            ]
        ];
    }
}
