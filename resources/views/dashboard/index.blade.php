<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/js/app.js'])


  <title>{{ $title }}</title>
</head>

<body>
  <x-layout>
    <x-slot name="header">
      <h1 class="mt-3">{{ $title }}</h1>

      @if (session('success'))
        <div class="alert alert-success mb-0 mt-3" role="alert">
          {{ session('success') }}
        </div>
      @endif
    </x-slot>

    <div class="d-flex justify-content-end align-items-center mt-3 flex-wrap gap-3">
      <div class="dropdown">
        <button class="btn btn-warning dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Filter By Category
        </button>
        <ul class="dropdown-menu border-warning">
          @forelse ($categories as $category)
            <li>
              <a class="dropdown-item {{ request('category') == $category->slug ? 'bg-warning' : '' }}"
                href="{{ route('dashboard.index', ['category' => $category->slug]) }}">
                {{ $category->name }}</a>
            </li>
          @empty
            <li class="dropdown-item">Empty Category</li>
          @endforelse
        </ul>
      </div>

      <div class="dropdown">
        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          Filter By Author
        </button>
        <ul class="dropdown-menu border-success">
          @forelse ($users as $user)
            <li>
              <a class="dropdown-item {{ request('author') == $user->username ? 'bg-success text-light' : '' }}"
                href="{{ route('dashboard.index', ['author' => $user->username]) }}">
                {{ $user->name }}</a>
            </li>
          @empty
            <li class="dropdown-item">Empty Author</li>
          @endforelse
        </ul>
      </div>

      <form style="height:fit-content">
          @if (request('category'))
            <input type="search" name="category" value="{{ request('category') }}" hidden>
          @endif
          @if (request('author'))
            <input type="search" name="author" value="{{ request('author') }}" hidden>
          @endif
          <div class="input-group">
            <input type="search" class="form-control border-primary" autocomplete="off"
            placeholder="Search by title..." aria-label="Search by title" name="title"
            aria-describedby="button-addon2">
            <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
          </div>
      </form>

    </div>

    <section class="mt-3 overflow-x-auto">
      <table class="table-hover table-bordered table" style="min-width: 1200px">
        <thead>
          <tr>
            <th scope class="bg-primary text-light text-center">No.</th>
            <th scope class="bg-primary text-light text-center">Title</th>
            <th scope class="bg-primary text-light text-center">Author</th>
            <th scope class="bg-primary text-light text-center">Category</th>
            <th scope class="bg-primary text-light text-center">Body (content)</th>
            <th scope class="bg-primary text-light text-center">Created At</th>
            <th scope class="bg-primary text-light text-center">Updated At</th>
            <th scope class="bg-primary text-light text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($posts as $key => $post)
            <tr>
              <th scope="row">{{ $posts->firstItem() + $key }}</th>
              <td>{{ $post->title }}</td>
              <td>{{ $post->author->name }}</td>
              <td><span class="badge text-bg-warning">{{ $post->category->name }}</span></td>
              <td>{{ Str::limit($post->body, 120) }}</td>
              <td>{{ $post->created_at }}</td>
              <td>{{ $post->updated_at }}</td>
              <td class="d-flex gap-2">
                <a href="{{ route('post.edit', $post->slug) }}" class="btn btn-primary" title="Edit post">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <a href="{{ route('post.show', $post->slug) }}" class="btn btn-info" title="Preview/show post">
                  <i class="bi bi-search text-white"></i>
                </a>
                <button class="btn btn-danger" title="Delete post" onClick="injectPostIdToModal('{{ $post->id }}')"
                  data-bs-toggle="modal" data-bs-target="#modal-permission">
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
          @empty
            <tr class="table-danger">
              <td colspan="8">Data Post is Empty</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </section>

    <div class="mb-4">
      {{ $posts->links() }}
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal-permission" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete Post Permission</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h3 class="text-center">Apakah anda yakin ingin menghapus postingan blog ini?</h3>
          </div>
          <div class="modal-footer">
            <form action="{{ route('post.destroy') }}" method="POST" enctype="multipart/form-data">
              @method('DELETE')
              @csrf

              <input type="hidden" name="post_id" id="delete-id">


              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" type="submit" class="btn btn-danger">Delete</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    @push('scripts')
      <script>
        const deleteIdField = document.getElementById('delete-id');

        function injectPostIdToModal(id) {
          deleteIdField.value = id;

          console.log(id);

        }
      </script>
    @endpush

    <a href="{{ route('post.create') }}" class="btn btn-primary floating-btn position-fixed rounded-circle"
      title="Create/add new post">
      <i class="bi bi-plus fs-2"></i>
    </a>
  </x-layout>
</body>

</html>
