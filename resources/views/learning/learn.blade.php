<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Learning Blade</title>
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>
  @include('components.navbar')

  <h1>Learning Blade</h1>
  <p>This is a simple Blade template example.</p>

  {{-- disable method/directive blade --}}
  <p>Current Path: @{{ request()->path() }}</p>


  @each('learning.each-list', $hobbies, 'hobby')

  {{-- directive inject --}}
  @inject('service', 'App\Demo\HelloService')
  <p>Service: {{ $service->sayHello('Zidane') }}</p>

  @forelse ($hobbies as $hobby)
    {{-- loop variable --}}
    <p>{{$loop->iteration}}. {{ $hobby }}</p>
  @empty
    <p>No hobbies found.</p>
  @endforelse

  {{-- custom directive --}}
  <p>Current Datetime: @datetime(now())</p>
  <br>
  {{$helloService}}
</body>

</html>