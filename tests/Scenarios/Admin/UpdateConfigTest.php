<?php 

namespace Tests\Scenarios\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Traits\ClientTrait;

final class UpdateConfigTest extends WebTestCase
{
    use ClientTrait;

    private const URL = 'config';
    private const PAYLOAD = [
        'multipart' => 
            [
                [
                    'name' => 'draw_begins_at_hour',
                    'contents' => '12:00',
                ],
                [
                    'name' => 'draw_begins_at_day_no',
                    'contents' => '1',
                ],
                [
                    'name' => 'draw_begins_at_concrete_day',
                    'contents' => '1',
                ],
                [
                    'name' => 'draw_is_concrete_day_set',
                    'contents' => true,
                ],
                [
                    'name' => 'lottery_ticket_cost',
                    'contents' => 0.1,
                ],
                [
                    'name' => 'lottery_required_tickets_sum',
                    'contents' => 0.5,
                ],
                [
                    'name' => 'fee_basic',
                    'contents' => 2.0,
                ]
            ],
    ];

    public function testUpdateConfigExpectsSuccess()
    {
        $result = self::post(self::URL, self::PAYLOAD);
        $config = json_decode($result->getBody()->getContents())->config;
        $payload = self::PAYLOAD['multipart'];
        $this->assertTrue($config->draw_begins_at_hour ===$payload[0]['contents']);
        $this->assertTrue($config->draw_begins_at_day_no == $payload[1]['contents']);
        $this->assertTrue($config->draw_begins_at_concrete_day == $payload[2]['contents']);
        $this->assertTrue($config->draw_is_concrete_day_set == $payload[3]['contents']);
        $this->assertTrue($config->lottery_ticket_cost == $payload[4]['contents']);
        $this->assertTrue($config->lottery_required_tickets_sum == $payload[5]['contents']);
        $this->assertTrue($config->fee_basic == $payload[6]['contents']);
    }
}