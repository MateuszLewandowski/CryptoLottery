<?php 

namespace Tests\Scenarios\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Traits\ClientTrait;

final class GetConfigTest extends WebTestCase
{
    use ClientTrait;

    private const URL = 'config';

    public function testGetConfigExpectsSuccess()
    {
        $result = self::get(self::URL);
        $this->assertTrue($result->getStatusCode() === Response::HTTP_OK);
        $config = json_decode($result->getBody()->getContents())->config;
        $this->assertStringContainsString(':', $config->draw_begins_at_hour);
        $this->assertTrue($config->draw_begins_at_day_no > 0 && $config->draw_begins_at_day_no < 8);
        $this->assertTrue($config->draw_begins_at_concrete_day > 0 && $config->draw_begins_at_concrete_day < 8);
        
        $this->assertTrue(gettype($config->draw_is_concrete_day_set) === 'boolean');

        $this->assertIsFloat((float) $config->lottery_ticket_cost);
        $this->assertTrue($config->lottery_ticket_cost > 0.0);

        $this->assertIsFloat((float) $config->lottery_required_tickets_sum);
        $this->assertTrue($config->lottery_required_tickets_sum > 0.0);

        $this->assertIsFloat((float) $config->fee_basic);
        $this->assertTrue($config->fee_basic > 0.0);
    }
}