<?php
namespace App\Service;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactService
{

  public array $selected = [
    'id',
    'firstname',
    'lastname',
    'email',
    'phone',
    'created_at',
    'updated_at',
  ];
  public function createContact(array $data)
  {
    try {
      return DB::transaction(function () use ($data) {
        $user = Auth::user();
        $contact = Contact::create([
          'firstname' => $data['firstname'],
          'lastname' => $data['lastname'],
          'email' => $data['email'],
          'phone' => $data['phone'],
          'user_test_id' => $user->id,
        ]);

        return (new ContactResource($contact))->response()->setStatusCode(201);
      });
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getContactsForUser(Request $request)
  {
    $page = $request->input('page', 1);
    $size = $request->input('size', 10);
    $name = strtolower($request->input('name', ''));
    $email = $request->input('email', '');
    $phone = $request->input('phone', '');

    $userId = Auth::user()->id;

    $contacts = Contact::select($this->selected)->where('user_test_id', $userId);
    $contacts->when($name, function ($query, $name) {
      $query->where(function ($q) use ($name) {
        $q->where(DB::raw('LOWER(firstname)'), 'like', '%' . $name . '%')
          ->orWhere(DB::raw('LOWER(lastname)'), 'like', '%' . $name . '%');
      });
    });

    $contacts->when($email, function ($query, $email) {
      $query->where('email', 'like', '%' . $email . '%');
    });

    $contacts->when($phone, function ($query, $phone) {
      $query->where('phone', 'like', '%' . $phone . '%');
    });

    $contacts = $contacts->paginate($size, $this->selected, 'page', $page);
    return ContactResource::collection($contacts);
  }

  public function getContactById(int $contactId)
  {
    $userId = Auth::user()->id;

    $contact = Contact::where('id', $contactId)
      ->where('user_test_id', $userId)->first();

    if (!$contact) {
      throw new HttpResponseException(response()->json([
        'message' => 'Contact not found'
      ], 404));
    }

    return new ContactResource($contact);
  }

  public function deleteContact(int $contactId)
  {
    $userId = Auth::user()->id;

    $contact = Contact::where('id', $contactId)
      ->where('user_test_id', $userId)->first();

    if (!$contact) {
      throw new HttpResponseException(response()->json([
        'message' => 'Contact not found'
      ], 404));
    }

    $contact->delete();

    return response()->json(['data' => true], 200);
  }

  public function updateContact(int $contactId, array $data)
  {
    try {
      return DB::transaction(function () use ($contactId, $data) {
        $userId = Auth::user()->id;

        $contact = Contact::where('id', $contactId)
          ->where('user_test_id', $userId)->first();

        if (!$contact) {
          throw new HttpResponseException(response()->json([
            'message' => 'Contact not found'
          ], 404));
        }

        $contact->update($data);

        return new ContactResource($contact);
      });
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}