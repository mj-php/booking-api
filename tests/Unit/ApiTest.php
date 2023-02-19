<?php

namespace Tests\Unit;

use App\Models\Element;
use App\Models\Reservation;
use App\Models\User;
use Database\Factories\ReservationFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Check that unauthorized user cannot get token.
     *
     * @return void
     */
    public function testUnauthorized(): void
    {
        $response = $this->get('api/getToken');

        $response->assertStatus(401);
    }

    /**
     * Check that authorized user can get token.
     *
     * @return string
     */
    public function testGetToken(): string
    {
        $response = $this->actingAs($this->user)->get('api/getToken');

        $response->assertStatus(200);

        return $response->decodeResponseJson()['token'];
    }

    /**
     * @depends testGetToken
     */
    public function testGetReservationList($token)
    {
        $element = Element::factory()->create();

        Reservation::factory()->count(2)->create(
            [
                'user_id' => $this->user->id,
                'element_id' => $element->id
            ]
        );

        $allReservations = Reservation::with('element')->get()->toArray();

        $reservationsResponse = $this->actingAs($this->user)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('api/reservations/list');

        $allApiReservations = $reservationsResponse->decodeResponseJson()['data'];

        $this->assertEquals($allReservations,$allApiReservations);
    }
}
