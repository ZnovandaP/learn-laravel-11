<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])

  <title>{{ $post['title'] }}</title>
</head>

<body>
  <x-layout>
    <x-slot name="header">
      <h1 class="mt-3 text-center">{{ $post['title'] }}</h1>
      <h5 class="text-muted fw-normal fst-italic text-center">
        <a href="/author/{{ $post->author->username }}/posts">
          Posted: {{ $post->author->name }}
        </a>
        <span>{{ " - {$post->created_at->format('d F Y')}" }}</span>
      </h5>
      <span class="d-block mx-auto badge fs-6 text-bg-warning mt-3" style="width: fit-content">{{ $post->category->name }}</span>
    </x-slot>

    <article class="mt-3">
      {{ $post['body'] }}
    </article>

    <a href="/blog" class="d-block mt-2">&laquo; Back to blog posts</a>
  </x-layout>
</body>

</html>
