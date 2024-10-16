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
    </x-slot>

    <form action="{{ route('post.update', $post->slug) }}" class="mt-5" method="POST" enctype="multipart/form-data">
      @method('PUT')
      @csrf

      <div class="input-group marker">
        <span class="input-group-text border-secondary" id="basic-addon1">Title Blog</span>
        <input type="text" class="form-control border-secondary" placeholder="title" aria-label="title"
          name="title" aria-describedby="basic-addon1" value="{{ $post->title }}">
      </div>
      @error('title')
        <P class="text-danger my-2">{{ $message }}</P>
      @enderror

      <div class="d-flex my-4 gap-4">

        <div class="w-100">
          <div class="input-group">
            <label class="input-group-text border-secondary" for="category">Category</label>
            <select class="form-select border-secondary" aria-label="Select category" id="category" name="category">
              <option selected value="{{ $post->category->id }}">{{ $post->category->name }}</option>
              @forelse ($categories as $category)
                @if ($post->category->id != $category->id)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endif
              @empty
                <option selected>No category</option>
              @endforelse
            </select>
          </div>
          @error('category')
            <P class="text-danger my-2">{{ $message }}</P>
          @enderror
        </div>

        <div class="w-100">
          <div class="input-group">
            <label class="input-group-text border-secondary" for="author">Author</label>
            <select class="form-select border-secondary" aria-label="Select author" id="author" name="author" data-live-search="true">
              <option selected value="{{ $post->author->id }}">{{ $post->author->name }}</option>
              @forelse ($users as $user)
                @if ($post->author->id != $user->id)
                  <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endif
              @empty
                <option selected>No Author</option>
              @endforelse
            </select>
          </div>
          @error('author')
            <P class="text-danger my-2">{{ $message }}</P>
          @enderror
        </div>

      </div>

      <div class="input-group mt-2">
        <span class="input-group-text bg-light-subtle border-secondary">Body Blog</span>
        <textarea style="min-height: 180px; max-height: 300px" class="form-control border-secondary" aria-label="With textarea"
          name="body">
          {{ $post->body }}
        </textarea>
      </div>

      <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="{{ route('dashboard.index') }}" class="btn btn-danger">Cancel</a>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </x-layout>
</body>

</html>
