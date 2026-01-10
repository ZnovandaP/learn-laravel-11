<?php
namespace App\Service;

use App\Http\Resources\AddressCollection;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use App\Models\Contact;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class AddressService
{
  public function createAddress(int $contactId, array $data)
  {
    try {
      return DB::transaction(function () use ($contactId, $data) {
        $contact = Contact::where('id', $contactId)->first();

        if (!$contact) {
          throw new HttpResponseException(response()->json([
            'message' => 'Contact not found'
          ], 404));
        }

        $address = Address::create([
          'street' => $data['street'],
          'city' => $data['city'],
          'province' => $data['province'],
          'postal_code' => $data['postal_code'],
          'country' => $data['country'],
          'contact_id' => $contact->id,
        ]);

        return (new AddressResource($address))->response()->setStatusCode(201);
      });
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getAddresses(int $contactId)
  {
    $contact = Contact::where('id', $contactId)->first();

    if (!$contact) {
      throw new HttpResponseException(response()->json([
        'message' => 'Contact not found'
      ], 404));
    }

    $addresses = Address::where('contact_id', $contact->id)->get();

    return new AddressCollection($addresses);
  }

  public function getAddress(int $contactId, int $addressId)
  {
    $contact = Contact::where('id', $contactId)->first();

    if (!$contact) {
      throw new HttpResponseException(response()->json([
        'message' => 'Contact not found'
      ], 404));
    }

    $address = Address::where('id', $addressId)->where('contact_id', $contact->id)->first();

    if (!$address) {
      throw new HttpResponseException(response()->json([
        'message' => 'Address not found'
      ], 404));
    }

    return new AddressResource($address);
  }

  public function updateAddress(int $contactId, int $addressId, array $data)
  {
    try {
      return DB::transaction(function () use ($contactId, $addressId, $data) {
        $contact = Contact::where('id', $contactId)->first();

        if (!$contact) {
          throw new HttpResponseException(response()->json([
            'message' => 'Contact not found'
          ], 404));
        }

        $address = Address::where('id', $addressId)->where('contact_id', $contact->id)->first();

        if (!$address) {
          throw new HttpResponseException(response()->json([
            'message' => 'Address not found'
          ], 404));
        }

        $address->update($data);

        return new AddressResource($address);
      });
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function deleteAddress(int $contactId, int $addressId)
  {
    try {
      return DB::transaction(function () use ($contactId, $addressId) {
        $contact = Contact::where('id', $contactId)->first();

        if (!$contact) {
          throw new HttpResponseException(response()->json([
            'message' => 'Contact not found'
          ], 404));
        }

        $address = Address::where('id', $addressId)->where('contact_id', $contact->id)->first();

        if (!$address) {
          throw new HttpResponseException(response()->json([
            'message' => 'Address not found'
          ], 404));
        }

        $address->delete();

        return response(['data' => true], 200);
      });
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}