<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])

  <title>{{ "{$user->name}: Posts" }}</title>
</head>

<body>
  <x-layout>
    <x-slot name="header">
      <h1 class="mt-3">{{ "$count Blog Posts By {$user->name}" }}</h1>
      </h1>
    </x-slot>

    <div class="row mt-lg-4 row-gap-4 mt-3">
      @foreach ($user->posts as $post)
        <div class="col-sm-12 col-lg-6 col-xxl-4">
          <div class="card">
            <div class="card-header">
              <p class="mb-0">
                Penulis: {{ $post->author->name }}
              </p>
              {{-- ! $post->created_at->diffForHumans() // 12 minutes ago --}}
              <p class="mb-0 mt-1">
                Kategori:
                <a href="/category/{{ $post->category->slug }}/posts">
                  <span class="badge text-bg-warning">
                    {{ $post->category->name }}
                  </span>
                </a>
              </p>
              <p class="mb-0 mt-1">Diunggah pada: {{ $post->created_at->diffForHumans() }}</p>
            </div>
            <div class="card-body">
              <h5 class="card-title">{{ $post->title }}</h5>
              <p class="card-text">{{ Str::limit($post->body, 120) }}</p>
              <a href="{{ "post/$post->slug" }}" class="btn btn-primary w-100">Read More...</a>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </x-layout>
</body>

</html>
