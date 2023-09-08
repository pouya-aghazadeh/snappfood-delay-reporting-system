<?php

namespace App\BusinessLayer\Entities;

use App\BaseClasses\BusinessEntity;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Medoo\Medoo;

class DelayReport extends BusinessEntity
{

    public function Create($orderId)
    {

        // checking the validity of order
        $order = $this->db->get('orders', '*', [
            'id' => $orderId
        ]);
        if (empty($order))
            throw new \Error('order not found', 404);

        // checking details of order > for those having trip
        if (!empty($order['trip_id'])) {
            $trip = $this->db->get('trips', '*', [
                'id' => $order['trip_id']
            ]);

            // checking the misinformation of recent db transactions
            if (empty($trip))
                throw new \Exception('invalid trip id registered', 500);


            // if order has in progress trip
            if (in_array($trip['status'], ['ASSIGNED', 'AT_VENDOR', 'PICKED'])) {
                $client = new Client();
                $request = new Request('GET', 'https://run.mocky.io/v3/122c2796-5df4-461c-ab75-87c1192b17f7');
                $res = $client->sendAsync($request)->wait()->getBody();
                $res = json_decode($res);
                if (empty($res->data->eta))
                    throw new \Error('error in getting delay duration', 500);
                return [
                    'success' => true,
                    'message' => $res->data->eta . ' minutes later order will be delivered'
                ];
            }
        }

        // checking time pass of delivery
        $datetime_of_delivery = strtotime($order['created_at'] . ' + ' . $order['delivery_time'] . ' minute');
        if ($datetime_of_delivery > time())
            throw new \Error('delivery time does not passed', 202);

        // checking existence of recent submission
        $existence_check = $this->db->get('delay_reports', '*', [
            'order_id' => $orderId,
            'OR' => [
                'AND #1' => [
                    'tracker_id[!]' => null,
                    'approx_delay_amount' => null
                ],
                'AND #2' => [
                    'tracker_id' => null,
                    'approx_delay_amount[!]' => null
                ],
            ],
            'ORDER' => [
                'id' => 'DESC'
            ]
        ]);
        if (!empty($existence_check))
            throw new \Error('delay report created before with id : ' . $existence_check['id'], 400);


        // submission of report
        $this->db->insert('delay_reports', [
            'order_id' => $orderId,
        ]);
        return [
            'success' => true,
            'message' => 'delay report submitted with id : ' . $this->db->id()
        ];
    }

    public function Track($trackerId)
    {
        // checking the validity of tracker
        if (!$this->db->has('trackers', [
            'id' => $trackerId
        ])) {
            throw new \Error('tracker id is invalid', 400);
        }

        // checking the existence of in progress tracking for tracker
        $active_tracking = $this->db->get('delay_reports', '*', [
            'tracker_id' => $trackerId,
            'approx_delay_amount' => null
        ]);
        if (!empty($active_tracking)) {
            throw new \Error('you have in progress tracking with id : ' . $active_tracking['id'], 400);
        }

        // checking existence of report ready for track
        $untracked_report = $this->db->get("delay_reports", '*', [
            "tracker_id" => null,
            "approx_delay_amount" => null
        ]);
        if (empty($untracked_report))
            throw new \Error('there is no untracked report', 404);

        // setting the tracker
        $this->db->update('delay_reports', [
            'tracker_id' => $trackerId,
            'tracked_at' => date('Y-m-d H:i:s')
        ], [
            'id' => $untracked_report['id']
        ]);
        return [
            'success' => true,
            'message' => 'now you\'re tracking report with id : '.$untracked_report['id']
        ];
    }

    public function ListByVendor($vendorId)
    {
        // checking the validity of vendor
        if (!$this->db->has('vendors', [
            'id' => $vendorId
        ])) {
            throw new \Error('vendor id is invalid', 400);
        }

        // query for fetching statistics of last week
        return $this->db->select('orders(o)',[
            '[><]delay_reports(dr)' => ['o.id' => 'order_id'],
        ],[
            'date' => Medoo::raw('DATE(dr.created_at)'),
            'delay' => Medoo::raw('SUM(dr.approx_delay_amount)')
        ],[
            'dr.created_at[>]' => date('Y-m-d H:i:s',strtotime('-7 day')),
            'GROUP' => Medoo::raw('DATE(dr.created_at)'),
            'ORDER' => [
                'dr.created_at' => 'DESC'
            ]
        ]);
    }
}