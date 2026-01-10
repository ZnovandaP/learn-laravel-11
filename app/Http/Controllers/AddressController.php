<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressCreateRequest;
use App\Http\Requests\AddressUpdateRequest;
use App\Service\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
  private AddressService $addressService;
  public function __construct(AddressService $addressService)
  {
    $this->addressService = $addressService;
  }

  public function createAddress(int $contactId, AddressCreateRequest $addressCreateRequest)
  {
    $data = $addressCreateRequest->validated();

    return $this->addressService->createAddress($contactId, $data);
  }

  public function getAddresses(int $contactId)
  {
    return $this->addressService->getAddresses($contactId);
  }

  public function getAddress(int $contactId, int $addressId)
  {
    return $this->addressService->getAddress($contactId, $addressId);
  }

  public function updateAddress(int $contactId, int $addressId, AddressUpdateRequest $addressUpdateRequest)
  {
    $data = $addressUpdateRequest->validated();

    return $this->addressService->updateAddress($contactId, $addressId, $data);
  }

  public function deleteAddress(int $contactId, int $addressId)
  {
    return $this->addressService->deleteAddress($contactId, $addressId);
  }

}
