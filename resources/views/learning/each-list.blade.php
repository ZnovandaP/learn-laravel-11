@once
  <style>
    .list-item {
      color: blue;
      font-weight: bold;
    }
  </style>
@endonce

{{-- menggunakan directive @@each --}}
<ul>
  <li class="list-item">{{ $hobby }}</li>
</ul>