<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ContactRestApiTest extends TestCase
{
  protected function setUp(): void
  {
    parent::setUp();
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

  public function testCreateContactSuccess(): void
  {
    $this->seed([UserTestSeeder::class]);
    $token = $this->authUser();
    $response = $this->post('/api/contacts/', [
      'firstname' => 'John',
      'lastname' => 'Doe',
      'email' => 'tets@example.com',
      'phone' => '123-456-7890',
    ], [
      'Authorization' => $token,
    ]);
    $response->assertStatus(201);
    $response->assertJsonStructure([
      'data' => [
        'id',
        'firstname',
        'lastname',
        'email',
        'phone'
      ]
    ]);
  }

  public function testCreateContactUnauthorized(): void
  {
    $response = $this->post('/api/contacts/', [
      'firstname' => 'John',
      'lastname' => 'Doe',
      'email' => 'tets@example.com',
      'phone' => '123-456-7890',
    ]);

    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing'
    ]);
  }

  public function testCreateContactValidationError(): void
  {
    $this->seed([UserTestSeeder::class]);
    $token = $this->authUser();
    $response = $this->post('/api/contacts/', [
      'lastname' => 'Doe',
      'email' => 'tets@example.com',
      'phone' => '123-456-7890',
    ], [
      'Authorization' => $token,
    ]);
    $response->assertStatus(400);
    $response->assertJson([
      'errors' => [
        'firstname' => [
          'The firstname field is required.'
        ]
      ]
    ]);
  }

  public function testGetContactsSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    $response = $this->get('/api/contacts?page=1&size=5', [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        '*' => [
          'id',
          'firstname',
          'lastname',
          'email',
          'phone',
          'created_at',
          'updated_at'
        ]
      ],
      'links' => [
        'first',
        'last',
        'prev',
        'next'
      ],
      'meta' => [
        'current_page',
        'from',
        'last_page',
        'links',
        'path',
        'per_page',
        'to',
        'total'
      ]
    ]);

    $this->assertEquals(5, count($response->json('data')));
  }

  public function testGetContactSearchByName(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    // search by firstname
    $response = $this->get('/api/contacts?name=FirstName19', [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $data = $response->json('data');
    $this->assertNotEmpty($data);
    $this->assertEquals('FirstName19', $data[0]['firstname']);
    $this->assertEquals(1, count($data));

    // search by lastname
    $response = $this->get('/api/contacts?name=LastName19', [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $data = $response->json('data');
    $this->assertNotEmpty($data);
    $this->assertEquals('LastName19', $data[0]['lastname']);
    $this->assertEquals(1, count($data));
  }

  public function testGetContactSearchByEmail(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    $response = $this->get('/api/contacts?email=contact19@example.com', [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $data = $response->json('data');
    $this->assertNotEmpty($data);
    $this->assertEquals('contact19@example.com', $data[0]['email']);
    $this->assertEquals(1, count($data));
  }

  public function testGetContactSearchByPhone(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    $response = $this->get('/api/contacts?phone=123-456-78919', [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $data = $response->json('data');
    $this->assertNotEmpty($data);
    $this->assertEquals('123-456-78919', $data[0]['phone']);
    $this->assertEquals(1, count($data));
  }

  public function testGetContactsUnauthorized(): void
  {
    $response = $this->get('/api/contacts');
    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing'
    ]);
  }

  public function testGetContactByIdSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    // assuming contact with ID 1 exists
    $contact = Contact::first();
    $response = $this->get("/api/contacts/{$contact->id}", [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        'id',
        'firstname',
        'lastname',
        'email',
        'phone',
        'created_at',
        'updated_at'
      ]
    ]);
  }

  public function testGetContactByIdNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    // assuming contact with ID 9999 does not exist
    $response = $this->get("/api/contacts/9999", [
      'Authorization' => $token,
    ]);
    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Contact not found'
    ]);
  }

  public function testGetContactByIdUnauthorized(): void
  {
    // assuming contact with ID 1 exists
    $response = $this->get("/api/contacts/1");
    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing'
    ]);
  }

  public function testDeleteContactSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    // assuming contact with ID 1 exists
    $contact = Contact::first();
    $response = $this->delete("/api/contacts/{$contact->id}", [], [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $response->assertJson([
      'data' => true
    ]);
  }

  public function testDeleteContactNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    // assuming contact with ID 9999 does not exist
    $response = $this->delete("/api/contacts/9999", [], [
      'Authorization' => $token,
    ]);
    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Contact not found'
    ]);
  }

  public function testDeleteContactUnauthorized(): void
  {
    // assuming contact with ID 1 exists
    $response = $this->delete("/api/contacts/1");
    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing'
    ]);
  }

  public function testUpdateContactSuccess(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    // assuming contact with ID 1 exists
    $contact = Contact::first();
    $oldFirstname = $contact->lastname;
    $response = $this->put("/api/contacts/{$contact->id}", [
      'lastname' => 'Updated lastname',
      'email' => '',
    ], [
      'Authorization' => $token,
    ]);
    $response->assertStatus(200);
    $response->assertJson([
      'data' => [
        'id' => $contact->id,
        'lastname' => 'Updated lastname',
      ]
    ]);
    $this->assertNotEquals($oldFirstname, $response->json('data.lastname'));
  }

  public function testUpdateContactNotFound(): void
  {
    $this->seed([UserTestSeeder::class, ContactSeeder::class]);
    $token = $this->authUser();
    // assuming contact with ID 9999 does not exist
    $response = $this->put("/api/contacts/9999", [
      'lastname' => 'Updated lastname',
    ], [
      'Authorization' => $token,
    ]);
    $response->assertStatus(404);
    $response->assertJson([
      'message' => 'Contact not found'
    ]);
  }

  public function testUpdateContactUnauthorized(): void
  {
    // assuming contact with ID 1 exists
    $response = $this->put("/api/contacts/1", [
      'lastname' => 'Updated lastname'
    ]);
    $response->assertStatus(401);
    $response->assertJson([
      'message' => 'Authorization token is missing'
    ]);
  }
}
