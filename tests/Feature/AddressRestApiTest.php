<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use function PHPUnit\Framework\assertNotNull;

class AddressRestApiTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
    DB::delete('delete from addresses');
    DB::delete('delete from contacts');
    DB::delete('delete from user_tests');
  }

  private function authUser()
  {
    $response = $this->post('/api/user-test/login', [
      'username' => 'test',
      'password' => 'Qwerty123!',
    ]);
    $token = $response->json('data.token');
    return $token;
  }

  public function testCreateAddressSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();

    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();

    $response = $this->post("/api/contacts/{$contactByUser->id}/addresses", [
      'street' => 'test street 123',
      'city' => 'test city',
      'province' => 'test province',
      'postal_code' => '123456',
      'country' => 'test country',
    ], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure([
      'data' => [
        'id',
        'street',
        'city',
        'province',
        'postal_code',
        'country',
        'created_at',
        'updated_at',
      ]
    ]);

    assertNotNull(Address::where('contact_id', $contactByUser->id)->first());
  }

  public function testCreateAddressUnauthorized(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $contactByUser = Contact::first();

    $response = $this->post("/api/contacts/{$contactByUser->id}/addresses", [
      'street' => 'test street 123',
      'city' => 'test city',
      'province' => 'test province',
      'postal_code' => '123456',
      'country' => 'test country',
    ]);

    $response->assertStatus(401);
  }

  public function testCreateAddressValidationError(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::first();

    $response = $this->post("/api/contacts/{$contactByUser->id}/addresses", [
      'city' => 'test city',
      'province' => 'test province',
      'postal_code' => '123456',
      'street' => '',
      'country' => 'test country',
    ], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(400);
    $response->assertJson([
      'errors' => [
        'street' => [
          'The street field is required.'
        ]
      ]
    ]);
  }

  public function testGetAddressesSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();

    $response = $this->get("/api/contacts/{$contactByUser->id}/addresses", [
      'Authorization' => $token,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        [
          'id',
          'street',
          'city',
          'province',
          'postal_code',
          'country',
          'created_at',
          'updated_at',
        ]
      ]
    ]);

    $this->assertCount(5, $response->json('data'));
  }

  public function testGetAddressesUnauthorized(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $contactByUser = Contact::first();

    $response = $this->get("/api/contacts/{$contactByUser->id}/addresses");

    $response->assertStatus(401);
  }

  public function testGetAddressesContactNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();

    $response = $this->get("/api/contacts/9999/addresses", [
      'Authorization' => $token,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Contact not found'
    ]);
  }

  public function testGetAddressByIdSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();
    $address = Address::where('contact_id', $contactByUser->id)->first();

    $response = $this->get("/api/contacts/{$contactByUser->id}/addresses/{$address->id}", [
      'Authorization' => $token,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        'id',
        'street',
        'city',
        'province',
        'postal_code',
        'country',
        'created_at',
        'updated_at',
      ]
    ]);

    $this->assertEquals($address->id, $response->json('data.id'));
  }

  public function testGetAddressByIdUnauthorized(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $contactByUser = Contact::first();
    $address = Address::where('contact_id', $contactByUser->id)->first();

    $response = $this->get("/api/contacts/{$contactByUser->id}/addresses/{$address->id}");

    $response->assertStatus(401);
  }

  public function testGetAddressByIdNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();

    $response = $this->get("/api/contacts/{$contactByUser->id}/addresses/9999", [
      'Authorization' => $token,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Address not found'
    ]);
  }

  public function testGetAddressByIdContactNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();

    $response = $this->get("/api/contacts/9999/addresses/1", [
      'Authorization' => $token,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Contact not found'
    ]);
  }

  public function testUpdateAddressSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();
    $address = Address::where('contact_id', $contactByUser->id)->first();

    $response = $this->put("/api/contacts/{$contactByUser->id}/addresses/{$address->id}", [
      'street' => 'updated street 456',
      'city' => 'updated city',
      'province' => 'updated province',
      'postal_code' => '654321',
      'country' => 'updated country',
    ], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        'id',
        'street',
        'city',
        'province',
        'postal_code',
        'country',
        'created_at',
        'updated_at',
      ]
    ]);

    $this->assertEquals('updated street 456', $response->json('data.street'));
    $this->assertEquals('updated city', $response->json('data.city'));
    $this->assertEquals('updated province', $response->json('data.province'));
    $this->assertEquals('654321', $response->json('data.postal_code'));
    $this->assertEquals('updated country', $response->json('data.country'));
  }

  public function testUpdateAddressUnauthorized(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $contactByUser = Contact::first();
    $address = Address::where('contact_id', $contactByUser->id)->first();

    $response = $this->put("/api/contacts/{$contactByUser->id}/addresses/{$address->id}", [
      'street' => 'updated street 456',
      'city' => 'updated city',
      'province' => 'updated province',
      'postal_code' => '654321',
      'country' => 'updated country',
    ]);

    $response->assertStatus(401);
  }

  public function testUpdateAddressValidationError(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();
    $address = Address::where('contact_id', $contactByUser->id)->first();

    $response = $this->put("/api/contacts/{$contactByUser->id}/addresses/{$address->id}", [
      'city' => 'updated city',
      'province' => 'updated province',
      'postal_code' => '654321',
      'country' => 'updated country',
      'street' => '',
    ], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(400);
    $response->assertJson([
      'errors' => [
        'street' => [
          'The street field is required.'
        ]
      ]
    ]);
  }

  public function testUpdateAddressNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();

    $response = $this->put("/api/contacts/{$contactByUser->id}/addresses/9999", [
      'street' => 'updated street 456',
      'city' => 'updated city',
      'province' => 'updated province',
      'postal_code' => '654321',
      'country' => 'updated country',
    ], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Address not found'
    ]);
  }

  public function testUpdateAddressContactNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();

    $response = $this->put("/api/contacts/999999/addresses/1", [
      'street' => 'updated street 456',
      'city' => 'updated city',
      'province' => 'updated province',
      'postal_code' => '654321',
      'country' => 'updated country',
    ], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Contact not found'
    ]);
  }

  public function testDeleteAddressSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();
    $address = Address::where('contact_id', $contactByUser->id)->first();

    $response = $this->delete("/api/contacts/{$contactByUser->id}/addresses/{$address->id}", [], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(200);

    $response->assertJson([
      'data' => true
    ]);

    $this->assertNull(Address::where('id', $address->id)->first());
  }

  public function testDeleteAddressUnauthorized(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $contactByUser = Contact::first();
    $address = Address::where('contact_id', $contactByUser->id)->first();

    $response = $this->delete("/api/contacts/{$contactByUser->id}/addresses/{$address->id}");

    $response->assertStatus(401);
  }

  public function testDeleteAddressNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();
    $contactByUser = Contact::where('user_test_id', Auth::user()->id)->first();

    $response = $this->delete("/api/contacts/{$contactByUser->id}/addresses/9999", [], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Address not found'
    ]);
  }
  public function testDeleteAddressContactNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class, AddressSeeder::class]);
    $token = $this->authUser();

    $response = $this->delete("/api/contacts/9999/addresses/1", [], [
      'Authorization' => $token,
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Contact not found'
    ]);
  }
}


