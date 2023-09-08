<?php

namespace App\ServiceLayer\Controllers;

use App\BaseClasses\Controller;

class DelayReport extends Controller {

    public function Register($request, $response, $args)
    {
        $DelayReportEntity = new \App\BusinessLayer\Entities\DelayReport($this->db);
        $data = $DelayReportEntity->Create($args['order-id']);
        $response->getBody()->write(json_encode($data,JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function Track($request, $response, $args)
    {
        $DelayReportEntity = new \App\BusinessLayer\Entities\DelayReport($this->db);
        $data = $DelayReportEntity->Track($args['tracker-id']);
        $response->getBody()->write(json_encode($data,JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    public function ListByVendorId($request, $response, $args) {
        $DelayReportEntity = new \App\BusinessLayer\Entities\DelayReport($this->db);
        $data = $DelayReportEntity->ListByVendor($args['vendor-id']);

        $response->getBody()->write(json_encode($data,JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}