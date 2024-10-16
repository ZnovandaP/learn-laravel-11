<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])

  <title>Blog</title>
</head>

<body>
  <x-layout>
    <x-slot name="header">
      <h1 class="mt-3">Blog Posts</h1>
      </h1>
    </x-slot>

    <form>
      <div class="d-flex justify-content-center my-3">
        <input type="search" class="search-blog__title form-control border-primary" autocomplete="off"
          placeholder="Search by title..." aria-label="Search by title" name="title" aria-describedby="button-addon2">
        @if (request('category'))
          <input type="search" name="category" value="{{ request('category') }}" hidden>
        @endif
        @if (request('author'))
          <input type="search" name="author" value="{{ request('author') }}" hidden>
        @endif
        <button class="btn btn-primary" type="submit" id="button-addon2">Search</button>
      </div>
    </form>

    <div class="mt-5">
      {{ $posts->links() }}
    </div>

    <div class="row mt-lg-4 row-gap-4 mt-3">
      @forelse ($posts as $post)
        <div class="col-sm-12 col-lg-6 col-xxl-4">
          <div class="card">
            <div class="card-header">
              <p class="mb-0">
                Penulis:
                <a href="{{ "blog?author={$post->author->username}" }}">
                  {{ $post->author->name }}
                </a>
              </p>
              {{-- ! $post->created_at->diffForHumans() // 12 minutes ago --}}
              <p class="mb-0 mt-1">
                Kategori:
                <a href="blog?category={{ $post->category->slug }}">
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
      @empty
        <div class="col-12 mt-4 text-center">
          <h2>
            Postingan dengan judul "{{ request('title') }}" tidak ditemukan!
          </h2>
          <a href="/blog">&laquo; silahkan kembali kehalaman blog</a>
        </div>
      @endforelse

      <div class="mb-4">
        {{ $posts->links() }}
      </div>
    </div>
  </x-layout>
</body>

</html>
