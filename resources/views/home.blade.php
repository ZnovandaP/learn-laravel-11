<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
  <title>Home</title>
</head>

<body>
  <x-layout>
     <x-slot name="header">
      <h1 class="mt-3">Home</h1>
    </x-slot>
    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Laboriosam voluptas minima libero excepturi ducimus natus in nemo ipsa porro provident repellendus incidunt dolor cum nulla molestias voluptatum obcaecati, maiores consequatur!
    
    Culpa, magni. Vero maxime alias consequuntur officia repellendus excepturi aliquid voluptates aut sapiente cupiditate, porro incidunt eos, culpa id dolor voluptate, dolore eius eum ut minus facilis voluptatum omnis. Amet!

    Possimus recusandae dolores harum commodi velit qui repellat non ratione voluptates, pariatur illo nulla dolorem iusto rem laudantium ab sint quae magnam voluptatem alias dolore officia. Quibusdam sit vitae consequuntur!
    {{ request()->is('/') }}
  </x-layout>
</body>

</html>
