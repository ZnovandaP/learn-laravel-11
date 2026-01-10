<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpdateRequest;
use App\Models\Contact;
use App\Service\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{

  private ContactService $contactService;
  public function __construct(ContactService $contactService)
  {
    $this->contactService = $contactService;
  }
  public function createContact(ContactCreateRequest $request)
  {
    $data = $request->validated();
    return $this->contactService->createContact($data);
  }

  public function getContacts(Request $request)
  {
    return $this->contactService->getContactsForUser($request);
  }

  public function getContactById(int $contactId)
  {
    return $this->contactService->getContactById($contactId);
  }

  public function updateContact(int $contactId, ContactUpdateRequest $request)
  {
    $data = $request->validated();
    return $this->contactService->updateContact($contactId, $data);
  }

  public function deleteContact(int $contactId)
  {
    return $this->contactService->deleteContact($contactId);
  }
}
