<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Tests\TestCase;

class DriverControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /** @test */
    public function it_can_display_driver_list()
    {
        $firebase = Firebase::createFirestore();

        // Assuming you have driver data in your Firebase collection, fetch them
        $driverDocuments = $firebase->collection('drivers')->documents();

        $response = $this->get(route('admin.drivers.index'));

        $response->assertStatus(200); // Expected response status
        $response->assertViewHas('drivers'); // Assert that the view receives a drivers collection

        // Assert that the number of drivers in the view matches the number of documents in Firebase collection
        $driversInView = $response->viewData('drivers');
        $this->assertCount(count($driverDocuments), $driversInView);
    }
}
